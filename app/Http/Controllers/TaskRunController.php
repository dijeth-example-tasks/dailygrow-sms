<?php

namespace App\Http\Controllers;

use App\Models\TaskRun;
use Illuminate\Http\Request;

class TaskRunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(TaskRun::with('task.segment')->get()->toArray());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskRun $taskRun)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskRun $taskRun)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskRun $taskRun)
    {
        //
    }
}
