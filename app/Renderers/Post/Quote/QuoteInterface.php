<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Renderers\Post\Quote;

use MyBB\Core\Database\Models\Post;

interface QuoteInterface
{
	/**
	 * @param Post $post
	 *
	 * @return string
	 */
	public function renderFromPost(Post $post);
}
