<?php

namespace App\Http\Controllers;

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

class ProductController extends Controller
{
    /**
     * index
     *
     * @return void
     */

     public function index(Request $request) : View
{
    // Get the search input, if any
    $search = $request->input('search');

    // Query to get products based on search input or get all products
    $products = Product::when($search, function ($query, $search) {
                    return $query->where('title', 'like', '%' . $search . '%');
                })
                ->latest()
                ->paginate(10);

    // Render the view with the products and the search query
    return view('products.index', compact('products', 'search'));
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
        //validate form
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'stock'         => 'required|numeric'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //create product
        Product::create([
    'image'         => $image->hashName(),
    'title'         => $request->title,
    'description'   => $request->description,
    'stock'         => $request->stock,
    'deadline'      => $request->deadline,
    'status'        => $request->status,
        ]);

        //redirect to index
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
    public function update(Request $request, $id): RedirectResponse
    {
    // Validate form inputs
    $request->validate([
        'image'         => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 'nullable' means image is optional
        'title'         => 'required|min:5',
        'description'   => 'required|min:10',
        'stock'         => 'required|numeric',
        'deadline'      => 'required|date',
        'status'        => 'required|string'
    ]);

    // Get the product by ID
    $product = Product::findOrFail($id);

    // Check if an image was uploaded
    if ($request->hasFile('image')) {

        // Upload new image
        $image = $request->file('image');
        $imageName = $image->hashName();
        $image->storeAs('public/products', $imageName);

        // Delete the old image if it exists
        if ($product->image) {
            Storage::delete('public/products/' . $product->image);
        }

        // Update product with new image
        $product->update([
            'image'         => $imageName,
            'title'         => $request->title,
            'description'   => $request->description,
            'stock'         => $request->stock,
            'deadline'      => $request->deadline,
            'status'        => $request->status,
        ]);

    } else {
        // Update product without changing the image
        $product->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'stock'         => $request->stock,
            'deadline'      => $request->deadline,
            'status'        => $request->status,
        ]);
    }

    // Redirect to index with success message
    return redirect()->route('products.index')->with(['success' => 'Product updated successfully!']);
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