<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Buyer 
Route::apiResource('buyers', 'Buyer\BuyerController',['only'=>['index','show']]);


// Category
Route::apiResource('categories', 'Category\CategoryController',['except' => ['create','edit']]);

//Sellers
Route::apiResource('sellers' , 'Seller\SellerController',['only' => ['index', 'show']]);

//products
Route::apiResource('products' , 'Product\ProductController',['only' => ['index', 'show']]);

//transaction
Route::apiResource('transactions' , 'Transaction\TransactionController',['only' => ['index', 'show']]);

//users
Route::apiResource('users' , 'Transaction\TransactionController',['except' => ['create', 'edit']]);
