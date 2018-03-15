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
Route::resource('buyers', 'Buyer\BuyerController',['only'=>['index','show']]);
Route::resource('buyers.transaction', 'Buyer\BuyerTransactionController',['only'=>['index']]);
Route::resource('buyers.products', 'Buyer\BuyerProductController',['only'=>['index']]);
Route::resource('buyers.sellers', 'Buyer\BuyerSellerController',['only'=>['index']]);
Route::resource('buyers.categories', 'Buyer\BuyerCategoryController',['only'=>['index']]);



// Category
Route::resource('categories', 'Category\CategoryController',['except' => ['create','edit']]);

//Sellers
Route::resource('sellers' , 'Seller\SellerController',['only' => ['index', 'show']]);

//products
Route::resource('products' , 'Product\ProductController',['only' => ['index', 'show']]);

//transaction
Route::resource('transactions' , 'Transaction\TransactionController',['only' => ['index', 'show']]);
Route::resource('transactions.categories' , 'Transaction\TransactionCategoryController',['only' => ['index']]);
Route::resource('transactions.sellers' , 'Transaction\TransactionSellerController',['only' => ['index']]);


//users
Route::resource('users' , 'User\UserController',['except' => ['create', 'edit']]);
