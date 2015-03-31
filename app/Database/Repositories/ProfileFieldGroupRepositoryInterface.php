<?php

namespace MyBB\Core\Database\Repositories;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\ProfileFieldGroup;

interface ProfileFieldGroupRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll();

    /**
     * @param string $slug
     *
     * @return ProfileFieldGroup
     */
    public function getBySlug($slug);
}
