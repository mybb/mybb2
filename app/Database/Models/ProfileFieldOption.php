<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property string name
 * @property string value
 */
class ProfileFieldOption extends Model
{
    protected $table = 'profile_field_options';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = ['id'];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param ProfileField $profileField
     *
     * @return Collection
     */
    public static function getForProfileField(ProfileField $profileField)
    {
        return static::where('profile_field_id', $profileField->id)->get();
    }
}
