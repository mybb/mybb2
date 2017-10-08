<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Contracts\Auth\Guard;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\WordFilter as WordFilterModel;
use MyBB\Core\Form\RenderableInterface;

class WordFilterPresenter extends BasePresenter
{
    /**
     * @var Guard
     */
    protected $guard;

    public function __construct(WordFilterModel $resource, Guard $guard) {
        parent::__construct($resource);
        $this->guard = $guard;
    }
	
	public function find() : string
    {
        return $this->wrappedObject->find;
    }
	
	public function replace() : string
    {
        return $this->wrappedObject->replace;
    }
}
