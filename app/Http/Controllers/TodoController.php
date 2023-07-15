<?php

namespace App\Http\Controllers;

use App\Enums\TodoStatusEnum;
use App\Http\Requests\TodoStoreRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $todos = new Todo();

        if(isset($request->keyword)) {
            $todos = $todos->where("description", "like", "%$request->keyword%");
        }

        // status checking
        if(isset($request->finished) && isset($request->unfinished)) {
            $todos = $todos->whereIn('status', [TodoStatusEnum::Unfinished,TodoStatusEnum::Finished]);
        }
        else if (isset($request->finished)) {
            $todos = $todos->whereStatus(TodoStatusEnum::Finished);
        }
        else if (isset($request->unfinished)) {
            $todos = $todos->whereStatus(TodoStatusEnum::Unfinished);
        }

        // ownership checking
        if(isset($request->mytodos)) {
            $todos = $todos->whereUserId(auth()->id());
        }

        $todos = $todos
            ->with('owner')
            ->orderBy("id", "desc")
            // ->orderByRaw("status asc, id desc")
            ->paginate(5);

        return view("todo.index", compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoStoreRequest $request)
    {
        $todo = new Todo();

        $todo->user_id = auth()->id();
        $todo->description = $request->description;
        $todo->save();

        return back()->with([
            'message' => "New todo has been added successfully."
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        if(! isset($request->status)) {
            $status = 0;
        }
        else if ($request->status == 0) {
            $status = 1;
        }

        $todo->status =  $status;
        $todo->save();

        if($todo->status == 1) {
            $message = "Todo #$todo->id has been checked";
        }
        else {
            $message = "Todo #$todo->id has been un checked";
        }

        return back()->with([
            'message' => $message
        ])->withFragment($request->fragment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Todo $todo)
    {
        $isDeleted = $todo->delete();

        if($isDeleted) {
            $message = "Todo has been deleted";
        }
        else {
            $message = "Todo delete is failed";
        }

        return back()->with([
            'message' => $message
        ])->withFragment($request->fragment);
    }
}
