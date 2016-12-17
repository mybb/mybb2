<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Settings;

class MemoryCacheSettingsRepository implements SettingsRepositoryInterface
{
    protected $inner;

    protected $isCached;

    protected $cache = [];

    public function __construct(SettingsRepositoryInterface $inner)
    {
        $this->inner = $inner;
    }

    public function all()
    {
        if (! $this->isCached) {
            $this->cache = $this->inner->all();
            $this->isCached = true;
        }

        return $this->cache;
    }

    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        } elseif (! $this->isCached) {
            return array_get($this->all(), $key, $default);
        }

        return $default;
    }

    public function set($key, $value)
    {
        $this->cache[$key] = $value;

        $this->inner->set($key, $value);
    }

    public function delete($key)
    {
        unset($this->cache[$key]);

        $this->inner->delete($key);
    }
}
