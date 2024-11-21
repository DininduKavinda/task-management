<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Task extends Model
{
    use Billable;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'is_complete',
        'is_paid',
        'stripe_product_id'
    ];

    public function users()
    {
        $this->belongsTo(User::class);
    }

    protected function cast()
    {
        return [
            'is_complete' => 'bool',
            'ïs_paid' => 'bool'
        ];
    }
}
