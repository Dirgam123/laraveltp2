<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Product; 
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class ProductController extends Controller
{
    /**
     * index
     *
     * @return void
     */

public function index(Request $request) : View
{
     $tasksPending = Product::where('deadline', '<=', Carbon::now()->addDays(3))
                            ->where('status', '!=', 'Done')
                            ->get();
    $search = $request->input('search');
$products = Product::all();
    $products = Product::when($search, function ($query, $search) {
                    return $query->where('title', 'like', '%' . $search . '%');
                })
                ->latest()
                ->paginate(10);

                
    return view('products.index', compact('products', 'search', 'tasksPending'));
}

public function newtask($id)
{
    $product = Product::findOrFail($id);

    return view('products.newtask', compact('product'));
}

public function updateDescription(Request $request, $id)
{
    $request->validate([
        'description' => 'required|min:10',
    ]);

    $product = Product::findOrFail($id);
    $product->update([
        'description' => $request->description,
    ]);

    return redirect()->route('products.index')->with(['success' => 'Description updated successfully!']);
}

    /**
     * create
     *
     * @return View
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
public function store(Request $request): RedirectResponse
{
    // Validate form input
    $request->validate([
        'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
        'title'         => 'required|min:5',
        'description'   => 'required|min:10',
        'task_list'     => 'required|string', // Adjusted for task_list
        'deadline'      => 'required|date',
        'status'        => 'required|in:available,Done,Progress,Delayed',
                'title' => 'required',
        'task_list' => 'nullable|string',
    ]);

    $image = $request->file('image');
    $image->storeAs('public/products', $image->hashName());

$taskListArray = explode(',', $request->input('task_list'));

    Product::create([
        'image'         => $image->hashName(),
        'title'         => $request->title,
        'description'   => $request->description,
        'task_list'     => json_encode($request->task_list),
        'deadline'      => $request->deadline,
        'status'        => $request->status,
        'title' => $request->title,
        'task_list' => $taskListArray,
    ]);

    return redirect()->route('products.index')->with(['success' => 'Data Berhasil Disimpan!']);
}
    
    /**
     * show
     *
     * @param  mixed $id
     * @return View
     */
    public function show(string $id): View
    {
        $product = Product::findOrFail($id);

        return view('products.show', compact('product'));
    }
    public function updateTaskList(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'task_list' => 'required|string',
    ]);

    $product->task_list = json_encode(explode(',', $request->task_list));
    $product->save();

    return redirect()->route('products.index')->with('success', 'Task list updated successfully.');
}

    /**
     * edit
     *
     * @param  mixed $id
     * @return View
     */
    public function edit(string $id): View
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }
        
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return RedirectResponse
     */
public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required',
        'deadline' => 'required|date',
        'task_list' => 'nullable|string',
    ]);

    $product = Product::findOrFail($id);

    if ($request->hasFile('image')) {
        if ($product->image && Storage::exists('public/products/' . $product->image)) {
            Storage::delete('public/products/' . $product->image);
        }
        $image = $request->file('image')->store('products', 'public');
        $product->image = $image;
    }

    $taskList = $request->input('task_list');
    $product->task_list = is_string($taskList) ? explode(',', $taskList) : $taskList;

    $product->title = $request->input('title');
    $product->description = $request->input('description');
    $product->status = $request->input('status');
    $product->deadline = Carbon::parse($request->input('deadline'));

    $product->save();

    return redirect()->route('products.index')->with('success', 'Product updated successfully');
}
    
    /**
     * destroy
     *
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        Storage::delete('public/products/'. $product->image);

        $product->delete();

        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}