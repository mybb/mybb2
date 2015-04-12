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
use MyBB\Core\Database\Models\Conversation as ConversationModel;

class Conversation extends BasePresenter
{
	/** @var ConversationModel $wrappedObject */

	/**
	 * @param ConversationModel $resource The conversation being wrapped by this presenter.
	 */
	public function __construct(ConversationModel $resource)
	{
		$this->wrappedObject = $resource;
	}


}
