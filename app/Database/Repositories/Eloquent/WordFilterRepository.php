<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Repositories\Collection;
use MyBB\Core\Database\Models\WordFilter;
use MyBB\Core\Database\Repositories\WordFilterRepositoryInterface;

class WordFilterRepository implements WordFilterRepositoryInterface
{
    /**
     * @var WordFilter
     */
    protected $wordFilter;

    /**
     * @param WordFilter $wordFilter
     */
    public function __construct(WordFilter $wordFilter)
    {
        $this->wordFilter = $wordFilter;
    }

    public function getAll() : \Illuminate\Database\Eloquent\Collection
    {
        return $this->wordFilter->all();
    }
}
