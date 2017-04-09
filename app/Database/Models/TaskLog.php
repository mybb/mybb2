<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'task_logs';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
    ];

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    /**
     * Get the task record associated with the log.
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
