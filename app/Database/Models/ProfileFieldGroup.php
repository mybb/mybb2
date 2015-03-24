<?php

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string id
 */
class ProfileFieldGroup extends Model
{
    const ABOUT_YOU = 'about-you';
    const CONTACT_DETAILS = 'contact-details';

    protected $table = 'profile_field_groups';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = ['id'];

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }
}
