<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirm Payment') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10">
        <div class="bg-white shadow-md rounded p-6">
            <h3 class="text-lg font-medium text-gray-900">Confirm Payment for Task</h3>
            <p><strong>Title:</strong> {{ $task->title }}</p>
            <p><strong>Description:</strong> {{ $task->description }}</p>
            <p><strong>Priority:</strong> {{ $task->priority }}</p>
            <p><strong>Due Date:</strong> {{ $task->due_date }}</p>

            <form action="{{ route('tasks.pay.confirm', $task->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success mt-4">Confirm Payment</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary mt-4">Cancel</a>
            </form>
        </div>
    </div>
    <script>
        const stripe = Stripe('pk_test_51MTT8NC5luYyKMWDvOZkOGTqPlSmgpu9bCRtxK4ct2WDjCAeV5jzphjd6sJwDnihXCH5EsA8jHpEUTZU7M6sZ7j200RXLwBBgf');

        // Function to handle payment
        async function payTask(taskId) {
            try {
                // Call the backend API to create a payment intent
                const response = await fetch(`/api/tasks/${taskId}/pay`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.client_secret) {
                    // Use the client secret to confirm payment
                    const {
                        paymentIntent,
                        error
                    } = await stripe.confirmCardPayment(data.client_secret, {
                        payment_method: {
                            card: stripe.elements().create('card'),
                            billing_details: {
                                name: 'Customer Name',
                            },
                        },
                    });

                    if (error) {
                        console.error('Payment failed:', error);
                        alert('Payment failed: ' + error.message);
                    } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                        alert('Payment successful! Task is now marked as paid.');
                        // Optionally refresh the page or update the UI
                    }
                } else {
                    alert('Failed to create payment intent.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while processing the payment.');
            }
        }
    </script>
</x-app-layout>
