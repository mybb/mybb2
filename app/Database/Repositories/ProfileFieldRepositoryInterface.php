<?php

namespace MyBB\Core\Database\Repositories;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileField;

interface ProfileFieldRepositoryInterface
{
    /**
     * @param array $data
     * @return ProfileField
     */
    public function create(array $data);

    /**
     * @param int $id
     * @return ProfileField
     */
    public function find($id);

    /**
     * @return Collection
     */
    public function getAll();
}