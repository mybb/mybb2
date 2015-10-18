<?php
/**
 * Attachment type validator for image files.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Attachments\AttachmentTypeValidators;

use MyBB\Core\Attachments\AttachmentTypeValidationException;
use MyBB\Core\Attachments\AttachmentTypeValidator;
use MyBB\Core\Attachments\Database\Models\AttachmentType;
use MyBB\Core\Database\Models\User;

class ImageTypeValidator implements AttachmentTypeValidator
{
	/**
	 * Validate a given file to ensure it's a valid type.
	 *
	 * This validator checks if a file is a valid image using `exif_imagetype`.
	 *
	 * @param string $filePath The path to the uploaded file to validate.
	 * @param AttachmentType $type The attachment type mapped to the file.
	 * @param User $uploadingUser The user uploading the attachment.
	 *
	 * @return bool Whether the file is valid.
	 *
	 * @throws AttachmentTypeValidationException Can be thrown to provide more detail of failure.
	 */
	public function validate($filePath, AttachmentType $type, User $uploadingUser)
	{
		$imageType = exif_imagetype($filePath);

		if ($imageType === false) {
			throw new AttachmentTypeValidationException(trans('attachments.not_a_valid_image_file'));
		}

		// TODO: Any more validation?

		return true;
	}
}
