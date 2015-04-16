<?php

namespace MyBB\Core\Presenters\Moderations;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Form\RenderableInterface;
use MyBB\Core\Moderation\Moderations\MovePost;

class MovePostPresenter extends BasePresenter implements RenderableInterface
{
    /**
     * @return MovePost
     */
    public function getWrappedObject()
    {
        return parent::getWrappedObject();
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
    public function icon()
    {
        return $this->getWrappedObject()->getIcon();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'text';
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'The topic ID to move these posts to.';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Move';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Topic ID';
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        return 'integer|exists:topics,id';
    }

    /**
     * @return string
     */
    public function getElementName()
    {
        return 'topic_id';
    }
}
