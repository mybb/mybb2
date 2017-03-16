<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tasks';

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
        'namespace',
        'last_run',
        'next_run',
        'enabled',
        'logging',
        'frequency'
    ];

    /**
     * A Task has many logs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(\MyBB\Core\Database\Models\TaskLog::class);
    }
}
