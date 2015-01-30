<?php
/**
 * Forum repository contract.
 *
 * @version 1.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

interface IForumRepository
{
    /**
     * Get all forums.
     *
     * @return mixed
     */
    public function all();

    /**
     * Get a single forum by ID.
     *
     * @param int $id The ID of the forum.
     *
     * @return mixed
     */
    public function find($id = 0);

    /**
     * Get a single forum by slug (name, sluggified, eg: 'my-first-forum').
     *
     * @param string $slug The slug for the forum.
     *
     * @return mixed
     */
    public function findBySlug($slug = '');
}
