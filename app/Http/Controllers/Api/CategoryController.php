<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
//        return auth()->user()->Category;

//        return Category::all();

//        $categories = Category::all();

//        $categories= auth()->user()->Category->load('tasks');
        $categories= auth()->user()->Category()->with('tasks')->paginate(10);

        return CategoryResource::collection($categories);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function store(Request $request)
    {
        $validatedData = $request->validate([
           'title'=>'required'

        ]);

        return auth()->user()->Category()->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        if (auth::user() != $category->user_id){

            return response()->json(['message'=>'You Dont this Resource'],401);
        }
        $category->load('tasks');

        return new CategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
//        return $request->all();

        if (auth()->id() != $category->user_id){

            return response()->json(['message' => 'You dont\'t own this resource'],401);

        }
        $validatedData = $request->validate([

            'title'=>'required',

        ]);

        if ($category->update($request->all())) {
            return response()->json(['message'=>'updated']);
        }else{

            return response()->json(['message' =>'error'],500);
    }
    }

    public function destroy(Category $category)
    {

        if (auth()->id() != $category->user_id){

            return response()->json(['message' => 'You dont\'t own this resource'],401);

    }
        if ($category->delete()){
            return response()->json(['message'=>'Resources deleted']);
        }

        return response()->json(['message' => 'Error , try again layer'],500);
    }

    public function restore($categoryId){



        $category = Category::onlyTrashed()->findOrFail($categoryId);


        if (auth()->id() != $category->user_id){

            return response()->json(['message' => 'You dont\'t own this resource'],401);

        }
        if ($category->restore()){

            return ['message' => 'Restored resource successfully'];

        }

        return response()->json(['message' => 'You have an error '],500);
    }

    public function forceDelete($categoryId){

        $category = Category::withTrashed()->findOrFail($categoryId);

        if (auth()->id() != $category->user_id){

            return response()->json(['message' => 'You dont\'t own this resource'],401);

        }

        if ($category->forceDelete()){

            return response()->json(['message'=>'Resources deleted']);

        }

        return response()->json(['message'=>'Error , try again later' ] ,500);
}
}
