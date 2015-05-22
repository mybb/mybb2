<?php
/**
 * Tree collection class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TreeCollection extends Collection
{
	/**
	 * Fill `parent` and `children` relationships for every node in collection.
	 *
	 * This will overwrite any previously set relations.
	 *
	 * @return $this
	 */
	public function linkNodes()
	{
		if ($this->isEmpty()) {
			return $this;
		}
		$groupedChildren = $this->groupBy('parent_id');
		/** @var Model $node */
		foreach ($this->items as $node) {
			if (!isset($node->parent)) {
				$node->setRelation('parent', null);
			}
			$children = $groupedChildren->get($node->getKey(), []);
			/** @var Model $child */
			foreach ($children as $child) {
				$child->setRelation('parent', $node);
			}
			$node->setRelation('children', Collection::make($children));
		}

		return $this;
	}

	/**
	 * Build tree from node list. Each item will have set children relation.
	 *
	 * @return Collection
	 */
	public function toTree()
	{
		$this->linkNodes();
		return $this;
	}
}
