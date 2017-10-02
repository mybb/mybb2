<?php
/**
 * Forum model class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Models;

use Illuminate\Database\Eloquent\Model;
use McCool\LaravelAutoPresenter\HasPresenter;
use MyBB\Core\Content\ContentInterface;
use MyBB\Core\Moderation\Moderations\{
    CloseableInterface, StickableInterface
};
use MyBB\Core\Permissions\Interfaces\InheritPermissionInterface;
use MyBB\Core\Permissions\Traits\InheritPermissionableTrait;
use MyBB\Core\Presenters\ForumPresenter;
use MyBB\Core\Database\Collections\TreeCollection;

/**
 * @property int id
 */
class Forum extends Model implements HasPresenter, InheritPermissionInterface, CloseableInterface, StickableInterface, ContentInterface
{
    use InheritPermissionableTrait;

    // @codingStandardsIgnoreStart
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // @codingStandardsIgnoreEnd

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'forums';
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'int',
    ];

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass() : string
    {
        return ForumPresenter::class;
    }

    /**
     * @return Forum
     */
    public function getParent() : Forum
    {
        if ($this->parent_id === null) {
            return null;
        }
        return $this->find($this->parent_id);
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed $id
     * @param  array $columns
     *
     * @return \Illuminate\Support\Collection|static|null
     */
    public static function find($id, array $columns = ['*'])
    {
        return static::query()->find($id, $columns);
    }

    /**
     * A forum contains many topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(\MyBB\Core\Database\Models\Topic::class);
    }

    /**
     * A forum contains many posts, through its topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function posts()
    {
        return $this->hasManyThrough(\MyBB\Core\Database\Models\Post::class, \MyBB\Core\Database\Models\Topic::class);
    }

    /**
     * A forum has a single last post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastPost()
    {
        return $this->hasOne(\MyBB\Core\Database\Models\Post::class, 'id', 'last_post_id');
    }

    /**
     * A forum has a single last post author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastPostAuthor()
    {
        return $this->hasOne(\MyBB\Core\Database\Models\User::class, 'id', 'last_post_user_id');
    }

    /**
     * Parent relation (self-referential) 1-1.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(get_class($this), 'parent_id');
    }

    /**
     * Children relation (self-referential) 1-N.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(get_class($this), 'parent_id')
            ->orderBy('left_id');
    }

    /**
     * {@inheritdoc}
     */
    public function newCollection(array $models = [])
    {
        return new TreeCollection($models);
    }

    /**
     * @return bool|int
     */
    public function close()
    {
        return $this->update(['closed' => 1]);
    }

    /**
     * @return bool|int
     */
    public function open()
    {
        return $this->update(['closed' => 0]);
    }

    /**
     * @return bool|int
     */
    public function stick()
    {
        return $this->update(['sticky' => 1]);
    }

    /**
     * @return bool|int
     */
    public function unstick()
    {
        return $this->update(['sticky' => 0]);
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return 'forum';
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return route('forums.show', ['id' => $this->id, 'slug' => $this->slug]);
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }
}
