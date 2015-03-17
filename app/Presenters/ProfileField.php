<?php

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Database\Models\ProfileField as ProfileFieldModel;
use MyBB\Core\Database\Models\ProfileFieldOption;
use MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface;
use MyBB\Core\Form\RenderableInterface;

class ProfileField extends BasePresenter implements RenderableInterface
{
    /**
     * @var Guard
     */
    protected $guard;

    /**
     * @var UserProfileFieldRepositoryInterface
     */
    protected $userProfileFields;

    /**
     * @param ProfileFieldModel $resource
     * @param Guard $guard
     * @param UserProfileFieldRepositoryInterface $userProfileFields
     */
    public function __construct(ProfileFieldModel $resource, Guard $guard, UserProfileFieldRepositoryInterface $userProfileFields)
    {
        parent::__construct($resource);

        $this->guard = $guard;
        $this->userProfileFields = $userProfileFields;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = ProfileFieldOption::getForProfileField($this->getWrappedObject());

        $formattedOptions = [];

        foreach ($options as $option) {
            $formattedOptions[$option->getValue()] = $option->getName();
        }

        return $formattedOptions;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'profile_fields[' . $this->id . ']';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        $userProfileField = $this->userProfileFields->findForProfileField($this->guard->user(), $this->getWrappedObject());

        if ($userProfileField) {
            return $userProfileField->getValue();
        }
    }
}