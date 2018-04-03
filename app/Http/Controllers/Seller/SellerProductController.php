<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\User;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products= $seller->products;
        return $this->showAll($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {


        $rules = [
            'name'        => 'required',
            'description' => 'required|min:6',
            'quantity'    => 'required|integer|min:1',
            'image'       => 'required|image',
        ];


        $response = array('response' => '', 'success'=>false);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

                $response['response'] = $validator->messages();
    
            }
            else{

             $data = $request->all();

             $data['status'] = Product::UNAVAILABLE_PRODUCT;
             $data['image']  = $request->image->store('');
             $data['seller_id'] = $seller->id;
             $product = Product::create($data);
            //return response()->json(['data'=> $user], 201);
            return $this->showOne($product, 201);
        }
        return $response;

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
     $rules =[
        'quantity' => 'integer|min:1',
        'status' => 'in:' .Product::AVAILABLE_PRODUCT . ' , ' . Product::UNAVAILABLE_PRODUCT ,
        'image' => 'image', 
     ];

     $this->validate($request ,$rules);

     $this->checkSeller($seller , $product);
     $product->fill($request->all());
        if ($request->has('status')) {
            $product->status = $request->status;
            if ($product->isAvailable() && $product->categories()->count() == 0) {
                return $this->errorResponse('An active product must have at least one category', 409);
            }
        }

        if ($product->isClean()) {
            return $this->errorResponse('You need to specify a different value to update', 422);
        }

        $product->save();
        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);
        $product->delete();
        return $this->showOne($product);
    }

    protected   function checkSeller(Seller $seller , Product $product){
        if ($seller->id != $product->seller_id) {
            throw new HttpException(422,"the specified seller is not actual seller of the product");
            
        }
    }
}
