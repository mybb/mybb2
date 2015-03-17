<?php

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileField;
use Mybb\Core\Database\Repositories\ProfileFieldRepositoryInterface;

class ProfileFieldRepository implements ProfileFieldRepositoryInterface
{
    /**
     * @param array $data
     * @return ProfileField
     */
    public function create(array $data)
    {
        return ProfileField::create($data);
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        return ProfileField::all();
    }

    /**
     * @param int $id
     * @return ProfileField
     */
    public function find($id)
    {
        return ProfileField::find($id);
    }
}
