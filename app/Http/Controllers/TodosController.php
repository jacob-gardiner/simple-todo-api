<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoCollection;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Exception;
use Illuminate\Http\JsonResponse;

class TodosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(new TodoCollection(Todo::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTodoRequest $request
     * @return JsonResponse
     */
    public function store(StoreTodoRequest $request)
    {
        $todo = Todo::create([
            'title' => $request->title
        ]);

        return response()->json(new TodoResource($todo));
    }

    /**
     * Display the specified resource.
     *
     * @param Todo $todo
     * @return JsonResponse
     */
    public function show(Todo $todo)
    {
        return response()->json(new TodoResource($todo));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTodoRequest $request
     * @param Todo              $todo
     * @return JsonResponse
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        $todo->update([
            'title' => $request->title,
            'completed_at' => $request->completed ? now()->toDateTimeString() : null,
        ]);

        return response()->json(new TodoResource($todo));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Todo $todo
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json();
    }
}
