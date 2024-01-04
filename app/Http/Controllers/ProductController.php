<?php
  
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Product;
 
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::orderBy('created_at', 'DESC')->get();
  
        return view('products.index', compact('product'));
    }
  
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }
  
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Product::make($request->all(),[
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'title' => 'required',
            'price' => 'required',
            'product_code'=> 'required',
            'description'   => 'required',
        ]);

        $image      = $request->file('image');
        $filename   = date('Y-m-d').$image->getClientOriginalName();
        $path       = 'image/'.$filename;

        Storage::disk('public')->put($path,file_get_contents($image));

        $data = [
            'title'         => $request->title,
            'price'         => $request->price,
            'product_code'  => $request->product_code,
            'description'   => $request->description,
            'image'         => $filename,
        ];

        $title = $request->title;
        $price = $request->price;
        $product_code = $request->product_code;
        $description = $request->description;
        $image = $filename;

        Product::create($data);
 
        return redirect()->route('products')->with('success', 'Product added successfully');
    }
  
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
  
        return view('products.show', compact('product'));
    }
  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
  
        return view('products.edit', compact('product'));
    }
  
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
  
        $product->update($request->all());
  
        return redirect()->route('products')->with('success', 'product updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
  
        $product->delete();
  
        return redirect()->route('products')->with('success', 'product deleted successfully');
    }
}