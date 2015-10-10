<?php
/**
 * Attachment repository using Eloquent ORM.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Attachments\Database\Repositories\Eloquent;

use MyBB\Core\Attachments\Database\Models\Attachment;
use MyBB\Core\Attachments\Database\Repositories\AttachmentRepositoryInterface;

class AttachmentRepsoitory implements AttachmentRepositoryInterface
{
	/**
	 * @var Attachment $model
	 */
	private $model;

	/**
	 * @param Attachment $model
	 */
	public function __construct(Attachment $model)
	{
		$this->model = $model;
	}

	/**
	 * Add a new download to the total downloads count for an integer.
	 *
	 * @param int $attachmentId The attachment to add a new download to the count for.
	 *
	 * @return int The number of affected rows.
	 */
	public function addDownload($attachmentId)
	{
		return $this->model->newQuery()->where('id', $attachmentId)->increment('num_downloads');
	}

	/**
	 * @param int $id
	 *
	 * @return Attachment
	 */
	public function find($id)
	{
		return $this->model->findOrFail($id);
	}
}
