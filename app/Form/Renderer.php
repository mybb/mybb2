<?php

namespace MyBB\Core\Form;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\Str;
use Illuminate\Validation\Factory as ValidationFactory;

class Renderer
{
    /**
     * @var ViewFactory
     */
    protected $view;

    /**
     * @var ValidationFactory
     */
    protected $validator;

    /**
     * @param ViewFactory $viewFactory
     * @param ValidationFactory $validationFactory
     */
    public function __construct(ViewFactory $viewFactory, ValidationFactory $validationFactory)
    {
        $this->view = $viewFactory;
        $this->validator = $validationFactory;
    }

    /**
     * @param RenderableInterface $renderable
     * @return string
     */
    public function render(RenderableInterface $renderable)
    {
        $html = '';

        // label
        $html .= $this->view->make('partials.form.field_label', [
            'for' => $this->slugify($renderable->getLabel()),
            'label' => $renderable->getLabel(),
            'is_required' => $this->isRequired($renderable)
        ])->render();

        // description
        if ($renderable->getDescription()) {
            $html .= $this->view->make('partials.form.field_description', [
                'description' => $renderable->getDescription()
            ])->render();
        }

        switch ($renderable->getType()) {
            case 'text':
            case 'email':
            case 'password':
            case 'url':
            case 'number':
                $html .= $this->renderInput($renderable);
                break;

            case 'select':
                $html .= $this->renderSelect($renderable);
                break;

            case 'textarea':
                $html .= $this->renderTextarea($renderable);
                break;
        }

        return $html;
    }

    /**
     * @param RenderableInterface $renderable
     * @return string
     */
    protected function renderTextarea(RenderableInterface $renderable)
    {
        return $this->view->make('partials.form.field_textarea', [
            'name' => $renderable->getElementName(),
            'rows' => 6,
            'cols' => 40,
            'value' => $renderable->getValue() ? $renderable->getValue() : '',
            'is_required' => $this->isRequired($renderable),
            'min_length' => $this->getMinLength($renderable),
            'max_length' => $this->getMaxLength($renderable)
        ])->render();
    }

    /**
     * @param RenderableInterface $renderable
     * @return string
     */
    protected function renderInput(RenderableInterface $renderable)
    {
        return $this->view->make('partials.form.field_input', [
            'type' => $renderable->getType(),
            'name' => $renderable->getElementName(),
            'id' => $this->slugify($renderable->getElementName()),
            'value' => $renderable->getValue() ? $renderable->getValue() : '',
            'is_required' => $this->isRequired($renderable),
            'min_length' => $this->getMinLength($renderable),
            'max_length' => $this->getMaxLength($renderable)
        ])->render();
    }

    /**
     * @param RenderableInterface $renderable
     * @return string
     */
    protected function renderSelect(RenderableInterface $renderable)
    {
        return $this->view->make('partials.form.field_select', [
            'name' => $renderable->getElementName(),
            'options' => $renderable->getOptions(),
            'selected' => $renderable->getValue()
        ])->render();
    }

    /**
     * @param string $string
     * @return string
     */
    protected function slugify($string)
    {
        return Str::slug($string);
    }

    /**
     * @param RenderableInterface $renderable
     * @return int
     */
    protected function getMinLength(RenderableInterface $renderable)
    {
        return (int) $this->extractValueByKeyFromRules('min', $renderable);
    }

    /**
     * @param RenderableInterface $renderable
     * @return int
     */
    protected function getMaxLength(RenderableInterface $renderable)
    {
        return (int) $this->extractValueByKeyFromRules('max', $renderable);
    }

    /**
     * @param string $key
     * @param RenderableInterface $renderable
     *
     * @return string
     */
    protected function extractValueByKeyFromRules($key, RenderableInterface $renderable)
    {
        $rules = $this->getRules($renderable);

        foreach ($rules as $rule) {
            if (strpos($rule, $key . ':') !== false) {
                $ruleBits = explode(':', $rule);
                return end($ruleBits);
            }
        }
    }

    /**
     * @param RenderableInterface $renderable
     * @return bool
     */
    protected function isRequired(RenderableInterface $renderable)
    {
        return in_array('required', $this->getRules($renderable));
    }

    /**
     * @param RenderableInterface $renderable
     * @return array
     */
    protected function getRules(RenderableInterface $renderable)
    {
        $rules = $this->getValidator($renderable)->getRules();
        return $rules[$renderable->getElementName()] ? $rules[$renderable->getElementName()] : [];
    }

    /**
     * @param RenderableInterface $renderable
     * @return \Illuminate\Validation\Validator
     */
    protected function getValidator(RenderableInterface $renderable)
    {
        return $this->validator->make([], [$renderable->getElementName() => $renderable->getValidationRules()]);
    }
}
