<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Cloudinary\Cloudinary;
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
                $remainingTime = $now->diff($expiration)->format('%A:%M:%J:%H:%I:%S');
                return response()->json(['remaining_time' => $remainingTime]);
            }
        }

        return response()->json(['message' => 'Product has expired'], 400);
    }

    // add product 

    function addProduct(Request $req) {
        $product = new Product;
        $product->name = $req->input('name');
        $product->description = $req->input('description');
        $product->price = $req->input('price');

        if ($req->hasFile('file')) {
            $uploadedFile = $req->file('file');
            $cloudinary = new Cloudinary();

            $result = $cloudinary->uploadApi()->upload($uploadedFile->getRealPath(), [
                'folder' => 'products',
            ]);

            $product->file_path = $result['secure_url']; // Store the Cloudinary URL
        }

        $product->user_id = $req->input('user_id');
        $product->expiration_time = $req->input('expiration_time');
        $product->save();

        return $product;
    }


    //get user product for the list 
    public function getUserProducts($uid) {
        $userProducts = Product::where('user_id', $uid)->get();
        return response()->json($userProducts);
    }

    //get user product for the wishlist 
    public function getUserWishList($uid) {
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
    function updateProduct($pid, Request $req) {
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
        if ($req->has('buyer_id')) {
            $product->buyer_id = $req->input('buyer_id');
        }
        if ($req->hasFile('file')) {
            $uploadedFile = $req->file('file');
            $cloudinary = new Cloudinary();

            $result = $cloudinary->uploadApi()->upload($uploadedFile->getRealPath(), [
                'folder' => 'products',
            ]);

            $product->file_path = $result['secure_url'];
        }

        $product->save();
        return $product;
    }


    //get the best products to show in the home page
    function getTopAuctions() {
        $auctions = Product::all(); 
        $auctions = $auctions->sortByDesc(function($auction) {
            $popularityScore = $auction->views + $auction->bids + $auction->favorites;
            $remainingTimeScore = strtotime($auction->expiration_time) - time();
            $priceScore = $auction->price;
    
            $importanceScore = $popularityScore * 0.5 + $remainingTimeScore * 0.3 + $priceScore * 0.2;
            
            return $importanceScore;
        });

        return response()->json($auctions);
    }

    // this function is for search 
    function search($key) {
        $products = Product::where('name', 'like', "%$key%")->get();
    
        if ($products->isEmpty()) {
            return 0; // Return 0 when no results are found
        } else {
            return $products; // Return products when found
        }
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