<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @property int id
 * @property string moderation
 * @property string  destination_content_type
 * @property int   destination_content_id
 * @property string source_content_type
 * @property int  source_content_id
 */
class ModerationLog extends Model implements HasPresenter
{
	/**
	 * @var string
	 */
	protected $table = 'moderation_logs';

	/**
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * @var array
	 */
	protected $casts = [
		'id' => 'int',
		'destination_content_id' => 'int',
		'source_content_id' => 'int'
	];

	/**
	 * @var array
	 */
	protected $dates = ['created_at'];

	/**
	 * Get the presenter class.
	 *
	 * @return string
	 */
	public function getPresenterClass()
	{
		return 'MyBB\Core\Presenters\ModerationLogPresenter';
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subjects()
	{
		return $this->hasMany('MyBB\Core\Database\Models\ModerationLogSubject');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('MyBB\Core\Database\Models\User');
	}
}
