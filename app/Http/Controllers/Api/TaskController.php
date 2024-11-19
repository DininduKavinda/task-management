<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::orderBy('priority')->get();

        return response()->json([
            'tasks' => $tasks,
        ]);
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
    public function store(TaskRequest $request)
    {
        //
        $validatedData = $request->validated();

        $create_task = Task::create($validatedData);

        $stripe = new \Stripe\StripeClient('sk_test_51MTT8NC5luYyKMWDxPt9lzHPXD6GrPBsllDplrWQywfeteA7WIZmvKp2pQ2GjeO83joY8Q8n9e0DNio2Omzw1RkW00F2VHQyCs');

        if ($validatedData['priority'] == 'High') {
            $stripe->products->create([
                'name' => $validatedData['title'],
                'default_price_data[currency]' => 'eur',
                'default_price_data[unit_amount]' =>  '10'

            ]);
        };
        return response()->json([
            'task' => $create_task,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
        return response()->json([
            'task' => $task
        ]);
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
    public function update(TaskRequest $request, Task $task)
    {
        //
        $validatedData = $request->validated();

        $task_update = $task->update($validatedData);

        return response()->json([
            'task' => $task_update,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $delete = $task->delete();

        response()->json(['message', 'Deleted successfully'], 200);
    }

    public function completeMailer(Request $request, $id)
    {
        $task = Task::find($id);
        $update = $task->update([
            'is_complete' => true,
        ]);


        return response()->json(['message', 'Updated successfully'], 200);
    }

    public function completePayment(Request $request, $id)
    {
        $task = Task::find($id);
        $update = $task->update([
            'is_paid' => true,
        ]);
        if($update) {
            
        }
        return response()->json(['message', 'Updated successfully'], 200);
    }
}
