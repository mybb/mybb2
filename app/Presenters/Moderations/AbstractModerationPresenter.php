<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters\Moderations;

use Illuminate\Contracts\View\Factory as ViewFactory;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Content\ContentInterface;
use MyBB\Core\Form\RenderableInterface;

abstract class AbstractModerationPresenter extends BasePresenter implements ModerationPresenterInterface
{
	/**
	 * @var ViewFactory
	 */
	protected $viewFactory;

	/**
	 * @param object      $resource
	 * @param ViewFactory $viewFactory
	 */
	public function __construct($resource, ViewFactory $viewFactory)
	{
		parent::__construct($resource);
		$this->viewFactory = $viewFactory;
	}

	/**
	 * @return RenderableInterface[]
	 */
	public function fields()
	{
		return [];
	}

	/**
	 * @return string
	 */
	public function key()
	{
		return $this->getWrappedObject()->getKey();
	}

	/**
	 * @return string
	 */
	public function name()
	{
		return $this->getWrappedObject()->getName();
	}

	/**
	 * @return string
	 */
	abstract protected function getDescriptionView();

	/**
	 * @param array $contentCollection
	 * @param ContentInterface $source
	 * @param ContentInterface $destination
	 *
	 * @return string
	 */
	public function describe(
		array $contentCollection,
		ContentInterface $source = null,
		ContentInterface $destination = null
	) {
		$content = reset($contentCollection);
		$count = count($contentCollection);

		$type = null;
		if ($count > 1) {
			$type = trans('content.type.' . $content->getType() . '.plural');
		} else {
			$type = trans('content.type.' . $content->getType());
		}

		return $this->viewFactory->make($this->getDescriptionView(), [
			'type' => $type,
			'title' => $count > 1 ? null : $content->getTitle(),
			'url' => $content->getUrl(),
			'count' => $count > 1 ? $count : 'a',
			'source_title' => $source ? $source->getTitle() : null,
			'source_url' => $source ? $source->getUrl() : null,
			'destination_title' => $destination ? $destination->getTitle() : null,
			'destination_url' => $destination ? $destination->getUrl() : null,
		]);
	}
}
