<?php
/**
 * Attachment repository contract.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Attachments\Database\Repositories;

use MyBB\Core\Database\Repositories\RepositoryInterface;

interface AttachmentRepositoryInterface extends RepositoryInterface
{
	/**
	 * Add a new download to the total downloads count for an integer.
	 *
	 * @param int $attachmentId The attachment to add a new download to the count for.
	 *
	 * @return int The number of affected rows.
	 */
	public function addDownload($attachmentId);
}
