<?php

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\ProfileFieldGroup;

interface ProfileFieldGroupRepositoryInterface
{
    /**
     * @param string $slug
     * @return ProfileFieldGroup
     */
    public function getBySlug($slug);
}
