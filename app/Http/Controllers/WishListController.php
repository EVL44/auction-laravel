<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishList;

class WishListController extends Controller
{
    function addToWishList(Request $req) {
        $wish = new WishList;
        $wish->user_id =  $req->input('user_id');
        $wish->product_id =  $req->input('product_id');
        $wish->save();

        return $wish;
    }

    function getWishList($uid) {
        $userWishlist = WishList::where('user_id', $uid)->pluck('product_id')->toArray();
        return $userWishlist;
    }
    
    function removeFromWishList($uid, $pid) {
        $deleted = WishList::where('user_id', $uid)->where('product_id', $pid)->delete();
        return response()->json(['message' => 'Product removed from wish list']);
    }
}
