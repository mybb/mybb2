<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileFieldGroup;

interface ProfileFieldGroupRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * @param string $slug
     *
     * @return ProfileFieldGroup
     */
    public function getBySlug(string $slug) : ProfileFieldGroup;

    /**
     * @return array
     */
    public function getAllForSelectElement() : array;

    /**
     * @param array $data
     *
     * @return ProfileFieldGroup
     */
    public function create(array $data) : ProfileFieldGroup;
}
