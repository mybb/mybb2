<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @property int id
 */
class ProfileFieldGroup extends Model implements HasPresenter
{
    const ABOUT_YOU = 'about-you';
    const CONTACT_DETAILS = 'contact-details';

    protected $table = 'profile_field_groups';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = ['id'];
    protected $casts = [
        'id' => 'int'
    ];
    protected $with = [
        'getProfileFields'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getProfileFields()
    {
        return $this->hasMany('MyBB\Core\Database\Models\ProfileField', 'profile_field_group_id');
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'MyBB\Core\Presenters\ProfileFieldGroup';
    }
}
