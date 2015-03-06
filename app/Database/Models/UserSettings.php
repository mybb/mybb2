<?php namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;


class UserSettings extends Model
{
	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'date_format',
		'time_format',
		'timezone',
		'dst',
		'follow_started_topics',
		'follow_replied_topics',
		'show_editor',
		'topics_per_page',
		'posts_per_page',
		'style',
		'language',
		'notify_on_like',
		'notify_on_quote',
		'notify_on_reply',
		'notify_on_new_post',
		'notify_on_new_comment',
		'notify_on_comment_like',
		'notify_on_my_comment_like',
		'notify_on_comment_reply',
		'notify_on_my_comment_reply',
		'notify_on_new_message',
		'notify_on_reply_message',
		'notify_on_group_request',
		'notify_on_moderation_post',
		'notify_on_report',
		'notify_on_username_change',
		'notification_mails',
		'showonline',
		'receive_messages',
		'block_blocked_messages',
		'hide_blocked_posts',
		'only_buddy_messages',
		'receive_email',
		'dob_privacy',
		'dob_visibility',
	];
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['user_id'];
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'user_id';
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_settings';

	/**
	 * @param $value
	 *
	 * @return int
	 */
	public function getDateFormatAttribute($value)
	{
		if($value == null)
		{
			// TODO: Return board default
			return 3;
		}

		return $value;
	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	public function getDateFormatRawAttribute($value)
	{
		return $this->attributes['date_format'];
	}

	/**
	 * @param $value
	 *
	 * @return int
	 */
	public function getTimeFormatAttribute($value)
	{
		if($value == null)
		{
			// TODO: Return board default
			return 24;
		}

		return $value;
	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	public function getTimeFormatRawAttribute($value)
	{
		return $this->attributes['time_format'];
	}

	/**
	 * @param $value
	 *
	 * @return string
	 */
	public function getTimezoneAttribute($value)
	{
		if($value == null)
		{
			// TODO: Return board default
			return '0';
		}

		return $value;
	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	public function getTimezoneRawAttribute($value)
	{
		return $this->attributes['timezone'];
	}

	// TODO: Implement styles
	/**
	 * @param $value
	 */
	public function getStyleAttribute($value)
	{
		if($value == null)
		{
			// TODO: Return board default
		}
	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	public function getStyleRawAttribute($value)
	{
		return $this->attributes['style'];
	}

	/**
	 * @param $value
	 *
	 * @return string
	 */
	public function getLanguageAttribute($value)
	{
		if($value == null)
		{
			// TODO: Return board default
			return 'en';
		}

		return $value;
	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	public function getLanguageRawAttribute($value)
	{
		return $this->attributes['language'];
	}
}
