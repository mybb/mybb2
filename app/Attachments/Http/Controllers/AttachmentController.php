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

namespace MyBB\Core\Attachments\Http\Controllers;

use MyBB\Core\Attachments\Database\Repositories\AttachmentRepositoryInterface;
use MyBB\Core\Http\Controllers\AbstractController;

class AttachmentController extends AbstractController
{
	/**
	 * @var AttachmentRepositoryInterface $attachmentRepository
	 */
	private $attachmentRepository;

	/**
	 * @param AttachmentRepositoryInterface $attachmentRepo
	 */
	public function __construct(AttachmentRepositoryInterface $attachmentRepo)
	{
		$this->attachmentRepository = $attachmentRepo;
	}

	/**
	 * Get all attachments uploaded by a user.
	 *
	 * @param int $userId The ID of the user to get the attachments for.
	 */
	public function getAllForUser($userId)
	{

	}

	public function getAllForPost($postId)
	{

	}

	public function getAllForTopic($topicId)
	{

	}

	public function getSingle($id)
	{

	}
}