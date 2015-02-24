<?php namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;


class UserSettings extends Model {
    protected $fillable = ['date_format', 'time_format', 'timezone', 'dst', 'follow_started_topics', 'follow_replied_topics',
        'show_editor', 'topics_per_page', 'posts_per_page', 'style', 'language', 'notify_on_like', 'notify_on_quote',
        'notify_on_reply', 'notify_on_new_post', 'notify_on_new_comment', 'notify_on_comment_like', 'notify_on_my_comment_like',
        'notify_on_comment_reply', 'notify_on_my_comment_reply', 'notify_on_new_message', 'notify_on_reply_message',
        'notify_on_group_request', 'notify_on_moderation_post', 'notify_on_report', 'notify_on_username_change', 'notification_mails'];

    protected  $guarded = ['user_id'];

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_settings';

    public function getDateFormatAttribute($value)
    {
        if($value == NULL)
        {
            // TODO: Return board default
            return 3;
        }

        return $value;
    }

    public function getDateFormatRawAttribute($value)
    {
         return $this->attributes['date_format'];
    }

    public function getTimeFormatAttribute($value)
    {
        if($value == null)
        {
            // TODO: Return board default
            return 24;
        }

        return $value;
    }

    public function getTimeFormatRawAttribute($value)
    {
        return $this->attributes['time_format'];
    }

    public function getTimezoneAttribute($value)
    {
        if($value == null)
        {
            // TODO: Return board default
            return '0';
        }

        return $value;
    }

    public function getTimezoneRawAttribute($value)
    {
        return $this->attributes['timezone'];
    }

    // TODO: Implement styles
    public function getStyleAttribute($value)
    {
        if($value == null)
        {
            // TODO: Return board default
        }
    }

    public function getStyleRawAttribute($value)
    {
        return $this->attributes['style'];
    }

    public function getLanguageAttribute($value)
    {
        if($value == null)
        {
            // TODO: Return board default
            return 'en';
        }

        return $value;
    }

    public function getLanguageRawAttribute($value)
    {
        return $this->attributes['language'];
    }
}
