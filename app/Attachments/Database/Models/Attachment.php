<?php
/**
 * Attachment model.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Attachments\Database\Models;

use Illuminate\Database\Eloquent\Model;
use MyBB\Core\Database\Models\User;

/**
 * @property int id
 * @property int user_id
 * @property int attachment_type_id
 * @property string title
 * @property string description
 * @property string file_name
 * @property string file_path
 * @property int file_size
 * @property string file_hash
 * @property int num_downloads
 * @property \Carbon\Carbon deleted_at
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 */
class Attachment extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'attachments';

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = ['user', 'type'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'user_id' => 'integer',
		'attachment_type_id' => 'integer',
		'file_size' => 'integer',
		'num_downloads' => 'integer',
	];

	/**
	 * An attachment belongs to a single user.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * An attachment belongs to a single attachment type.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function type()
	{
		return $this->belongsTo(AttachmentType::class, 'attachment_type_id');
	}
}
