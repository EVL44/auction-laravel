<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class ProductController extends Controller
{
    // getting a product by its id 
    function getProduct($pid) {
        return Product::find($pid);
    }
    public function getRemainingTime($pid)
    {
        $product = Product::findOrFail($pid);
        $expirationTime = $product->expiration_time;

        if ($expirationTime) {
            $now = Carbon::now();
            $expiration = Carbon::parse($expirationTime);

            if ($now < $expiration) {
                $remainingTime = $now->diff($expiration)->format('%J:%H:%I:%S');
                return response()->json(['remaining_time' => $remainingTime]);
            }
        }

        return response()->json(['message' => 'Product has expired'], 400);
    }
    // add product 
    function addProduct(Request $req) {
        $user = auth()->user(); 
        $product = new Product;
        $product->name = $req->input('name');
        $product->description = $req->input('description');
        $product->price = $req->input('price');
        if ($req->hasFile('file')) {
            $product->file_path = $req->file('file')->store('products');
        }
        $product->user_id = $req->input('user_id'); 
        $product->expiration_time = $req->input('expiration_time'); // Set the expiration time
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
    function updateProduct($pid ,Request $req) {

        $product = Product::find($pid);
        if ($req->has('name')) {
            $product->name = $req->input('name');
        }
        if ($req->has('description')) {
            $product->description = $req->input('description');
        }
        if ($req->has('price')) {
            $product->price = $req->input('price');
        }
        if ($req->hasFile('file')) {
            $product->file_path = $req->file('file')->store('products');
        }
        $product->save();
        return $product;

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

    // this function is for search 
    function search($key) {
        return Product::where('name','Like',"%$key%")->get();
    }

    //this function update just the price
    function updatePrice($pid ,Request $req) {

        $product = Product::find($pid);
        if ($req->has('price')) {
            $product->price = $req->input('price');
        }
        $product->save();
        return $product;

    }
}