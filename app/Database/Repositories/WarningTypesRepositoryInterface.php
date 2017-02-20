<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\WarningType;

interface WarningTypesRepositoryInterface
{

    /**
     * Create new warning type
     *
     * @param array $details
     * @return WarningType
     */
    public function create(array $details = []) : WarningType;

    /**
     * Return all warning types
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all();

    /**
     * Find a single warning type by id
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Delete single warning type by id
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * Edit single warning type
     *
     * @param WarningType $warningType
     * @param array $warningTypeDetails
     * @return mixed
     */
    public function edit(WarningType $warningType, array $warningTypeDetails = []);
}
