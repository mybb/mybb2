<?php

namespace MyBB\Core\Registry;

interface RegistryInterface
{
    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get($name);

    /**
     * @return mixed
     */
    public function getAll();
}
