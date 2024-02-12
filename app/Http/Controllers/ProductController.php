<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // getting a product by its id 
    function getProduct($pid) {
        return Product::find($pid);
    }

    // add product 
    function addProduct(Request $req) {
        $user = auth()->user(); 
        $product = new Product;
        $product->name = $req->input('name');
        $product->description = $req->input('description');
        $product->price = $req->input('price');
        $product->file_path = $req->file('file')->store('products');
        $product->user_id = $user->uid; 
        $product->save();

        return $product;
    }

    //get user product for the list 
    public function getUserProducts($uid) {
        $userProducts = Product::where('user_id', $uid)->get();
        return response()->json($userProducts);
    }

    // list not working any more
    function list() {
        $user = auth()->user(); 
        return Product::where('user_id', $user->uid)->get();
    }

    //delete a product
    function deleteProduct($pid) {
        $result = Product::where('pid',$pid)->delete();
        if($result) {
            return ["result"=>"product has been deleted"];
        }
    }

    //update a product not working currently
    function updateProduct(Request $req, $pid) {
        $user = auth()->user(); 
        $product = Product::find($pid);
        
        if (!$product) {
            return response()->json(["error" => "Product not found"], 404);
        }

        if ($product->user_id !== $user->uid) {
            return response()->json(["error" => "Unauthorized"], 401);
        }

        $product->name = $req->input('name');
        $product->description = $req->input('description');
        $product->price = $req->input('price');
    
        if ($req->hasFile('file')) {
            $product->file_path = $req->file('file')->store('products');
        }

        $product->save();

        return response()->json($product);
    }

    // this function is for getting the newest product in the table
    function getNewestProducts() {
        $newestProducts = Product::orderBy('created_at', 'desc')->limit(5)->get();
        return response()->json($newestProducts);
    }

    // this function is for getting the most expensive product in the table
    function getMostExpensiveProducts() {
        $mostExpensiveProducts = Product::orderBy('price', 'desc')->limit(5)->get();
        return response()->json($mostExpensiveProducts);
    }
}
