<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
    public function index(){
        $products=Product::where('bakery_id',session('bakery_id'))->get();
        return view('products',['products'=>$products]);
    }



public function store(Request $request){
    $validated=$request->validate([
     'name'=>['required','string','max:255'],
     'category'=>['required','string'], 
     'price'=>['required','numeric'] ,
     'description'=>['nullable','string'],
    ]);

    $data = [
        'name' => $validated['name'],
        'category' => $validated['category'],
        'price' => $validated['price'],
        'description' => $validated['description'],
        'bakery_id' => session('bakery_id'),
        'is_available' => $request->has('is_available'),
    ];

    if ($request->hasFile('image')) {
        $data['image_url'] = $request->file('image')->store('products', 'public');
    }

    $prodect = Product::create($data);

    return redirect('/products');
}

/*=================Update======================*/
    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)
                    ->where('bakery_id', session('bakery_id'))
                    ->firstOrFail();

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'category'    => ['required', 'string'],
            'price'       => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'name'         => $validated['name'],
            'category'     => $validated['category'],
            'price'        => $validated['price'],
            'description'  => $validated['description'],
            'is_available' => $request->has('is_available'),
        ];

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect('/products');
    }
/*================================Update======================*/
/*=================================Edit======================*/

     public function edit($id){
        $product=Product::where('id',$id)->where('bakery_id',session('bakery_id'))->firstOrfail();
        return view('edit-product',['product'=>$product]);
    }

/*==============================Edit======================*/
/*===============================Delete======================*/
      public function destroy($id){
         $product = Product::where('id', $id)
                     ->where('bakery_id', session('bakery_id'))
                     ->firstOrFail();

         $product->delete();

         return redirect('/products');
    }
/*================================Delete======================*/
}