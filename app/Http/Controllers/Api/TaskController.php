<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\StripeClient;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $stripe;

    public function __construct()
    {
        // Initialize the Stripe client
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }
    public function index(Request $request)
    {

         $priority = $request->input('priority');

        $query = Task::query();

        if ($priority) {
            $query->where('priority', $priority);
        }

        $tasks = $query->oldest('id')->get();

        return response()->json([
            'data' => $tasks,
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

        // Create a new task
        $task = Task::create($validatedData);

        // Create a Stripe product if the priority is "High"
        if ($validatedData['priority'] === 'High') {
            $stripeProduct = $this->stripe->products->create([
                'name' => $validatedData['title'],
                'description' => $validatedData['description'] ?? 'No description',
                'default_price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => 1000, // $10 in cents
                ],
            ]);

            // Attach Stripe product ID to the task
            $task->update(['stripe_product_id' => $stripeProduct->id]);
        }

        return response()->json([
            'task' => $task,
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

        // Update the task
        $task->update($validatedData);

        // Update the Stripe product if the priority is "High"
        if ($task->priority === 'High' && $task->stripe_product_id) {
            $this->stripe->products->update($task->stripe_product_id, [
                'name' => $validatedData['title'],
                'description' => $validatedData['description'] ?? 'No description',
            ]);
        }

        return response()->json([
            'task' => $task,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->stripe_product_id) {
            $this->stripe->products->delete($task->stripe_product_id);
        }

        // Delete the task
        $task->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }

    public function completeMailer(Request $request, $id)
    {
        $task = Task::find($id);
        $update = $task->update([
            'is_complete' => true,
        ]);

        return response()->json(['message', 'Updated successfully'], 200);
    }

    public function completePayment($id)
    {
        $task = Task::find($id);
        $update = $task->update([
            'is_paid' => true,
        ]);
        if ($update) {
        }
        return response()->json(['message', 'Updated successfully'], 200);
    }
}
