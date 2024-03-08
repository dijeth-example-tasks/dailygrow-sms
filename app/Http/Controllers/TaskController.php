<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Task::with(['segment', 'runs'])->get()->toArray());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $response = $request->json();
        $task = new Task();

        $task->type = $response->get('type');
        $task->time = $response->get('time');
        $task->active = $response->get('active');
        $task->text = $response->get('text');
        $task->name = $response->get('name');
        $task->description = $response->get('description');

        $task->save();

        return response()->json(getApiResponse($task->toArray()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $response = $request->json();

        $task->type = $response->get('type') ?? $task->type;
        $task->time = $response->get('time') ?? $task->time;
        $task->active = $response->get('active') ?? $task->active;
        $task->text = $response->get('text') ?? $task->text;
        $task->name = $response->get('name') ?? $task->name;
        $task->description = $response->get('description') ?? $task->description;

        $task->save();

        return response()->json(getApiResponse($task->toArray()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(getApiResponse($task->toArray()));
    }
}
