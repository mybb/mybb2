<?php
/**
 * Attachment type validator interface.
 *
 * An attachment type validator performs any logic required for a given attachment type, such as checking whether an
 * attachment is a valid image, etc.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Attachments;

use MyBB\Core\Attachments\Database\Models\AttachmentType;
use MyBB\Core\Database\Models\User;

interface AttachmentTypeValidator
{
	/**
	 * Validate a given file to ensure it's a valid type.
	 *
	 * @param string $filePath The path to the uploaded file to validate.
	 * @param AttachmentType $type The attachment type mapped to the file.
	 * @param User $uploadingUser The user uploading the attachment.
	 *
	 * @return boolean Whether the file is valid.
	 *
	 * @throws AttachmentTypeValidationException Can be thrown to provide more detail of failure.
	 */
	public function validate($filePath, AttachmentType $type, User $uploadingUser);
}
