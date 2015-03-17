<?php

namespace MyBB\Core\Form;

class Renderer
{
    /**
     * @param RenderableInterface $renderable
     * @return string
     */
    public function render(RenderableInterface $renderable)
    {
        $html = '';

        $label = '<h3><label for="%s">%s</label></h3>';
        $html .= sprintf($label, $this->slugify($renderable->getLabel()), $renderable->getLabel());


        $description = '<p class="form__description">%s</p>';
        $html .= sprintf($description, $renderable->getDescription());

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
        $textarea = '<textarea name="%s" rows="6" cols="40">%s</textarea>';
        return sprintf($textarea, $renderable->getName(), $renderable->getValue() ? $renderable->getValue() : '');
    }

    /**
     * @param RenderableInterface $renderable
     * @return string
     */
    protected function renderInput(RenderableInterface $renderable)
    {
        $input = '<input type="%s" name="%s" id="%s" value="%s">';
        return sprintf(
            $input,
            $renderable->getType(),
            $renderable->getName(),
            $this->slugify($renderable->getLabel()),
            $renderable->getValue() ? $renderable->getValue() : ''
        );
    }

    /**
     * @param RenderableInterface $renderable
     * @return string
     */
    protected function renderSelect(RenderableInterface $renderable)
    {
        $select = '<select name="%s">%s</select>';
        $option = '<option value="%s">%s</option>';

        $optionsHtml = '';

        foreach ($renderable->getOptions() as $value => $name) {
            $optionsHtml .= sprintf($option, $value, $name);
        }

        return sprintf($select, $renderable->getName(), $optionsHtml);
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