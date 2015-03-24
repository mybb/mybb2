<?php

namespace MyBB\Core\Form;

use Illuminate\Contracts\View\Factory;

class Renderer
{
    protected $view;

    public function __construct(Factory $factory)
    {
        $this->view = $factory;
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
            'label' => $renderable->getLabel()
        ]);

        // description
        if ($renderable->getDescription()) {
            $html .= $this->view->make('partials.form.field_description', [
                'description' => $renderable->getDescription()
            ]);
        }

        switch ($renderable->getType()) {
            case 'text':
            case 'email':
            case 'password':
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
            'name' => $renderable->getName(),
            'rows' => 6,
            'cols' => 40,
            'value' => $renderable->getValue() ? $renderable->getValue() : ''
        ]);
    }

    /**
     * @param RenderableInterface $renderable
     * @return string
     */
    protected function renderInput(RenderableInterface $renderable)
    {
        return $this->view->make('partials.form.field_input', [
            'type' => $renderable->getType(),
            'name' => $renderable->getName(),
            'id' => $this->slugify($renderable->getName()),
            'value' => $renderable->getValue() ? $renderable->getValue() : ''
        ]);
    }

    /**
     * @param RenderableInterface $renderable
     * @return string
     */
    protected function renderSelect(RenderableInterface $renderable)
    {
        return $this->view->make('partials.form.field_select', [
            'name' => $renderable->getName(),
            'options' => $renderable->getOptions(),
            'selected' => $renderable->getValue()
        ]);
    }

    /**
     * @param string $string
     * @return string
     */
    protected function slugify($string)
    {
        $string = strtolower($string);
        $stringBits = explode(' ', $string);
        return implode('_', $stringBits);
    }
}
