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
use MyBB\Core\Presenters\WordFilterPresenter;

/**
 * @property int id
 */
class WordFilter extends Model implements HasPresenter
{
    /**
     * @var string
     */
    protected $table = 'parser_badwords';
	
    /**
     * @var array
     */
    protected $casts = [
        'id' => 'int',
		'find' => 'string',
		'replace' => 'string',
    ];
	
	public function getID() : int
    {
        return $this->id;
    }

    public function getPresenterClass() : string
    {
        return WordFilterPresenter::class;
    }
}
