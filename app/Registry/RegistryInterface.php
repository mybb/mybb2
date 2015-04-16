<?php

namespace MyBB\Core\Registry;

interface RegistryInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @return mixed
     */
    public function getAll();
}
