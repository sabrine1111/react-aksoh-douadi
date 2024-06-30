<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
// {fetch('http://127.0.0.1:8000/api/login', {
//     method: 'POST',
//     headers: {
//         'Content-Type': 'application/json',
//         'Accept': 'application/json'
//     },
//     body: JSON.stringify({
//         email: 'admin@d.d',
//         password: 'admin'
//     })
// })
// .then(response => response.json())
// .then(data => console.log(data))
// .catch(error => console.error('Error:', error));




    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'username'=>'required|string',
            'phone'=>'required|string',
            'email'=>'required|email',
            'password'=>'required|string'
        ]);
        if($validation->fails())
        {
            return response()->json([
                'message'=>$validation->errors(),
            ]);
        }
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password =bcrypt($request->password) ;
        $user->save();

        return response()->json([
            'token'=>"exapmple token for" . $user->username,
        ]);
    }
    public function login(LoginRequest $request)
    {
        if ($request->password == "admin") {
            $request->authenticate();
            $user = auth()->user();
            $token = $request->user()->createToken('authToken')->plainTextToken;
            return response()->json([
                'message'=>'login successfull',
                'user'=>$user,
                'token' =>$token
            ], 200);
        } else {
            return response()->json([
                'message'=>'login failed !',
            ], 401);
        }
    }


    public function favorite()
    {
        $user = auth()->user();
        $favorites = favorite::where('user_id', $user->id)
            ->with(['product' => function ($query) {
                $query->with('product_images');
            }])
            ->get();

        // Adding the first image to the product's image attribute for easier access
        $favorites->each(function($favorite) {
            $favorite->product->image = $favorite->product->product_images->first() ? $favorite->product->product_images->first()->image : null;
        });

        return response()->json([
            'status' => true,
            'favorites' => $favorites,
        ]);
    }

    



    public function removeProductFromFavorite(Request $request)
    {
        $user = auth()->user();
        $favorite = favorite::where('user_id', $user->id)->where('product_id', $request->id)->first();

        if ($favorite == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product already removed',
            ]);
        } else {
            favorite::where('user_id', $user->id)->where('product_id', $request->id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Product removed successfully',
            ]);
        }
    }




}
