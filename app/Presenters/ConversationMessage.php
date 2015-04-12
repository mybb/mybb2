<?php
/**
 * Forum presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\ConversationMessage as ConversationMessageModel;

class ConversationMessage extends BasePresenter
{
	/** @var ConversationMessageModel $wrappedObject */

	/**
	 * @param ConversationMessageModel $resource The conversation being wrapped by this presenter.
	 */
	public function __construct(ConversationMessageModel $resource)
	{
		$this->wrappedObject = $resource;
	}


}
