<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class productController extends Controller
{
    public function Index(){
        $product = Product::all();
        return response([
            'success'=>'true',
            'data'=>$product
        ]);        
    }

    public function show($id){
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404); 
        }

        return response([
            'success'=>'true',
            'data'=>$product
        ]);       
    }

    public function search($name){
        $product = Product::where('name','like',"%$name%");

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404); 
        }

        return response([
            'success'=>'true',
            'data'=>$product
        ]);       
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => $validator->errors(),
            ], 400);
        }

        if($request->price <= 0 ){
            return response()->json(['success' => false,'data' => 'price must be bigger than 0'], 400);
        };

        $product = Product::create($request->all());

        return response()->json(['success' => true,'data' => $product], 201);
    } 

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
        return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'price' => 'sometimes|required|numeric',
        'stock' => 'sometimes|required|integer'
        ]);

        $product->update($request->all());

        return response()->json([
        'success' => true,
        'data' => $product
        ]);
    } 

    public function destroy($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
        return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully'],204);
    }
}

