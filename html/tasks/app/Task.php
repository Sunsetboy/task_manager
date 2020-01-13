<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
    public $timestamps = false;

    protected $guarded = ['id'];

    /**
     * Get the owner of the task
     */
    public function post()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
