<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    //  returns the task view with all tasks
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tasks = $this->fetchAllTasks();
            return response()->json(['data' => $tasks]);
        }

        return view('tasks');
    }

    // fetch all tasks
    public function fetchAllTasks()
    {
        $allTasks = Task::all();
        return $allTasks;
    }

    // update the task status
    public function updateTaskStatus($id, Request $request)
    {
        $task = Task::find($id);

        if ($task) {

            $currentStatus = $task->status;

            if ($currentStatus=="Pending") {
                $task->status = "In-progress";
            }else if($currentStatus=="In-progress"){
                $task->status = "Completed";
            }

            $task->save();

            return response()->json([
                'status' => 'success',
                'new_status' => $task->status,
            ]);

        
        }

        return response()->json(['status' => 'error', 'message' => 'Task not found'], 404);
    }

    // save a new task in the database
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'due_date' => 'required|date|after_or_equal:today',
            ]);

            Task::create($validated);

            $output = [
                'success' => true,
                'msg' => __("tasks.added_success"),
            ];

            return response()->json($output);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->getMessageBag(),
            ], 422);
        }
    }
}
