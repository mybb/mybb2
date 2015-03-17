<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string value
 */
class UserProfileField extends Model
{
    protected $table = 'user_profile_fields';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getProfileField()
    {
        return $this->belongsTo('MyBB\Core\Database\Models\ProfileField', 'profile_field_id', null, 'profileField');
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}