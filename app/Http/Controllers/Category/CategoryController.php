<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Event;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return $this->showAll($categories);
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required'
        ];


        $response = array('response' => '', 'success'=>false);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
                $response['response'] = $validator->messages();

        }else{

            $data = $request->all();
            $category = Category::create($data);
            return $this->showOne($category, 201);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->fill($request->all());

        if ($category->isClean()) {
            return $this->errorResponse('You need to specify any different vlaue to update', 422);


        }
                    $category->save();
            return $this->showOne($category);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showOne($category);
    }
}
