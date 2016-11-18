<?php
/**
 * Search repository contract.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

interface SearchRepositoryInterface
{

    /**
     * Find a single search log by token.
     *
     * @param string $token The token of the search log to find.
     *
     * @return mixed
     */
    public function find($token);

    /**
     * Create a new searchlog
     *
     * @param array $details Details about the searchlog.
     *
     * @return mixed
     */
    public function create(array $details = []);
}
