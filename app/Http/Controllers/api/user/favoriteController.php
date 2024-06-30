<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class favoriteController extends Controller
{public function addToFavorite(Request $request)
    {
        
        // Log the entire request to see what is being received
        Log::info('Request Data: ', $request->all());
    
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated'
            ]);
        }
    
        // Debugging line
        Log::info('Product ID: ' . $request->product_id);
    
        $product = Product::find($request->product_id);
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
                'product_id' => $request->product_id // Added for debugging
            ]);
        }
    
        Favorite::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $request->id
            ],
            [
                'user_id' => $user->id,
                'product_id' => $request->id
            ]
        );
    
        return response()->json([
            'status' => true,
            'message' => $product->title . ' added to your favorite'
        ]);
    }
    
}
