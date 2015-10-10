<?php
/**
 * Attachment type model.
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

/**
 * @property int id
 * @property string name
 * @property array mime_types
 * @property array file_extensions
 * @property int max_file_size
 * @property string validation_class
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 */
class AttachmentType extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'attachment_types';

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
	protected $with = ['attachments'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'mime_types' => 'array',
		'file_extensions' => 'array',
	];

	/**
	 * An attachment belongs to a single user.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function attachments()
	{
		return $this->hasMany(Attachment::class);
	}
}
