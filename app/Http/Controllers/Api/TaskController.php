<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TasksResource;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:api');
    }


    public function index()
    {
//        $tasks= auth()->user()->tasks->load('category');
        $tasks= auth()->user()->tasks()->with('category')->paginate(10);
        return TasksResource::collection($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'title' => 'required',
            'category_id' => 'required',
            'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:' . date('Y-m-d'),
        ]);

        $category = Category::findOrFail($validatedData['category_id']);


        if (auth()->id() != $category->user_id) {
            return response()->json(['message' => 'You Dont own category_id: ' . $category->id], 401);

        }
        $request['user_id'] = auth()->id();

//        return auth()->user()->Category()->create($request->all());

        $task = $category->tasks()->create($request->all());


        if ($task) {

            return $task;
        }

        return response()->json(['message' => 'Error, try again'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if (auth() ->id() != $task->user_id){
            return response()->json(['message'=>'You dont own  this resource'],401);
        }
        $task->load('category');

        return new TasksResource($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([

            'title' => 'required',
            'category_id' => 'required',
            'due_date' => 'required|date|date_format:Y-m-d'
        ]);

        $category = Category::findOrFail($validatedData['category_id']);


        if (auth()->id() != $category->user_id || auth()->id() != $task->user_id) {
            return response()->json(['message' => 'You Dont have permissions , check that you own the task and category'], 401);

        }
        $request['user_id'] = auth()->id();

//        return auth()->user()->Category()->create($request->all());

        $updated = $task->update($request->all());


        if ($updated) {

            return response()->json(['message' => 'Update successfully']);
        }

        return response()->json(['message' => 'Error, try again'], 500);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (auth()->id() != $task->user_id) {

            return response()->json(['message' => 'You Dont have permissions '], 401);
        }
        if ($task->delete()) {

            return response()->json(['message' => 'Deleted successfully']);
        }

        return response()->json(['message' => 'Error , try again later'], 500);
    }

    public function restore($taskId)
    {

        $task = Task::withTrashed()->findOrFail($taskId);

        if (auth()->id() != $task->user_id) {

            return response()->json(['message' => 'You Dont have permissions '], 401);
        }
        if ($task->restore()) {

            return response()->json(['message' => 'You Dont have permissions'], 500);
        }


        return response()->json(['message' => 'Error, try again  later  '], 401);
    }
        public function forceDelete($taskId)
        {


            $task = Task::withTrashed()->findOrFail($taskId);

            if (auth()->id() != $task->user_id) {

                return response()->json(['message' => 'You Dont have permissions '], 401);
            }
            if ($task->forceDelete()) {

                return response()->json(['message' => 'You Dont have permissions'], 500);
            }


            return response()->json(['message' => 'Error, try again  later '], 401);
        }


}
