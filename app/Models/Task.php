<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'is_complete',
        'is_paid',
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
