<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Collections;

use Illuminate\Database\Eloquent\Collection;

class TreeCollection extends Collection
{

    /**
     * Sort and fill `children` relationships for every node in collection.
     *
     * @return Collection
     */
    public function toTree()
    {
        $dict = $this->getDictionary();

        uasort($dict, function ($a, $b) {
            return ($a->left_id >= $b->left_id) ? 1 : -1;
        });
        return new Collection($this->hierarchical($dict));
    }

    /**
     * Fill `children` relationships for every node in collection.
     *
     * @param array $result
     * @return array
     */
    protected function hierarchical(array $result) : array
    {
        foreach ($result as $key => $node) {
            $node->setRelation('children', new Collection);
        }

        $nestedKeys = [];
        foreach ($result as $key => $node) {
            $parentKey = $node->parent_id;
            if (!is_null($parentKey) && array_key_exists($parentKey, $result)) {
                $result[$parentKey]->children[] = $node;
                $nestedKeys[] = $node->getKey();
            }
        }

        foreach ($nestedKeys as $key) {
            unset($result[$key]);
        }
        return $result;
    }
}
