<?php

namespace App\Http\Controllers;

use App\Models\Notification;
//import model product
use App\Models\Product; 

//import return type View
use Illuminate\View\View;

//import return type redirectResponse
use Illuminate\Http\Request;

//import Http Request
use Illuminate\Http\RedirectResponse;

//import Facades Storage
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
    // Get the search input, if any
    $search = $request->input('search');
$products = Product::all();
    // Query to get products based on search input or get all products
    $products = Product::when($search, function ($query, $search) {
                    return $query->where('title', 'like', '%' . $search . '%');
                })
                ->latest()
                ->paginate(10);

                
    // Render the view with the products and the search query
    return view('products.index', compact('products', 'search', 'tasksPending'));
}

public function newtask($id)
{
    // Retrieve the product by ID
    $product = Product::findOrFail($id);

    // Render the 'newtask' view with the product data
    return view('products.newtask', compact('product'));
}

public function updateDescription(Request $request, $id)
{
    // Validate the description
    $request->validate([
        'description' => 'required|min:10',
    ]);

    // Retrieve and update the product's description
    $product = Product::findOrFail($id);
    $product->update([
        'description' => $request->description,
    ]);

    // Redirect to products.index with success message
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

    // Upload the image
    $image = $request->file('image');
    $image->storeAs('public/products', $image->hashName());

    // Convert task_list to JSON format if it’s a comma-separated string
$taskListArray = explode(',', $request->input('task_list'));

    // Create product
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

    // Redirect to index
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
        //get product by ID
        $product = Product::findOrFail($id);

        //render view with product
        return view('products.show', compact('product'));
    }
    public function updateTaskList(Request $request, $id)
{
    $product = Product::findOrFail($id);

    // Validate task list (optional step depending on your requirements)
    $request->validate([
        'task_list' => 'required|string',
    ]);

    // Update the task list as a JSON array
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
        //get product by ID
        $product = Product::findOrFail($id);

        //render view with product
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

    // Handle image upload if a new image is provided
    if ($request->hasFile('image')) {
        if ($product->image && Storage::exists('public/products/' . $product->image)) {
            Storage::delete('public/products/' . $product->image);
        }
        $image = $request->file('image')->store('products', 'public');
        $product->image = $image;
    }

    // Process task_list as array or string
    $taskList = $request->input('task_list');
    $product->task_list = is_string($taskList) ? explode(',', $taskList) : $taskList;

    // Update other fields
    $product->title = $request->input('title');
    $product->description = $request->input('description');
    $product->status = $request->input('status');
    $product->deadline = Carbon::parse($request->input('deadline'));

    // Save the product
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
        //get product by ID
        $product = Product::findOrFail($id);

        //delete image
        Storage::delete('public/products/'. $product->image);

        //delete product
        $product->delete();

        //redirect to index
        return redirect()->route('products.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}