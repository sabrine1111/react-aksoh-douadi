<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function add(Request $request)
    {
        if($request->user()->role == "admin"){
        $validate = Validator::make($request->all(),[
            'name'=>'required|string',
            'subtitle'=>'required|string',
            'description'=>'required|string',
            'price'=>'required|integer',
            'image'=>'required|mimes:png,jpeg'
        ]);
        if($validate->fails())
        {
            return response()->json(['errors'=>$validate->errors()],422);
        }
        $imagename = uniqid() . time() . '.'. $request->image->getClientOriginalExtension();
        
        $product = new Product();
        $product->name = $request->name;
        $product->subtitle = $request->subtitle;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->image = $imagename;
        $product->save();
        $request->image->move(resource_path('image'),$imagename);
        return response()->json([
            'product'=>$product
        ]);
    }
    else{
        return response()->json([
            'error'=>'you dont have the permission to do that'
        ]);
    }



    }

    
}
