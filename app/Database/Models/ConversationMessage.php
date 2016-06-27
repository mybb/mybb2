<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;

/**
 * @property int id
 * @property int conversation_id
 * @property int author_id
 * @property string message
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Conversation conversation
 * @property User author
 */
class ConversationMessage extends Model implements HasPresenter
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'conversation_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'message_parsed',
        'author_id',
        'created_at',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'author',
    ];

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'MyBB\Core\Presenters\ConversationMessagePresenter';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo('MyBB\\Core\\Database\\Models\\Conversation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('MyBB\\Core\\Database\\Models\\User', 'author_id');
    }
}
