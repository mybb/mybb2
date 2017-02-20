<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters\Moderations;

use MyBB\Core\Content\ContentInterface;

abstract class AbstractReversibleModerationPresenter extends AbstractModerationPresenter implements
    ReversibleModerationPresenterInterface
{
    /**
     * @return string
     */
    public function reverseName() : string
    {
        return $this->getWrappedObject()->getReverseName();
    }

    /**
     * @param array $contentCollection
     * @param ContentInterface $source
     * @param ContentInterface $destination
     *
     * @return string
     */
    public function reverseDescribe(
        array $contentCollection,
        ContentInterface $source = null,
        ContentInterface $destination = null
    ) : string {
        $content = reset($contentCollection);
        $count = count($contentCollection);

        $type = null;
        if ($count > 1) {
            $type = trans('content.type.' . $content->getType() . '.plural');
        } else {
            $type = trans('content.type.' . $content->getType());
        }

        return $this->viewFactory->make($this->getReverseDescriptionView(), [
            'type'              => $type,
            'title'             => $count > 1 ? null : $content->getTitle(),
            'url'               => $content->getUrl(),
            'count'             => $count > 1 ? $count : 'a',
            'source_title'      => $source ? $source->getTitle() : null,
            'source_url'        => $source ? $source->getUrl() : null,
            'destination_title' => $destination ? $destination->getTitle() : null,
            'destination_url'   => $destination ? $destination->getUrl() : null,
        ]);
    }

    /**
     * @return string
     */
    abstract protected function getReverseDescriptionView() : string;
}
