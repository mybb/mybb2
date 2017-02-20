<?php

namespace MyBB\Core\Form;

class Field implements RenderableInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $elementName;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $validationRules;

    /**
     * @param string $type
     * @param string $elementName
     * @param string $label
     * @param string $description
     */
    public function __construct(string $type, string $elementName, string $label, string $description)
    {
        $this->type = $type;
        $this->elementName = $elementName;
        $this->label = $label;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getOptions() : array
    {
        return $this->options;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getElementName() : string
    {
        return $this->elementName;
    }

    /**
     * @return string
     */
    public function getLabel() : string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue(string $value)
    {
        $this->value = (string)$value;

        return $this;
    }

    /**
     * @return array
     */
    public function getValidationRules() : array
    {
        return $this->validationRules;
    }

    /**
     * @param string $validationRules
     *
     * @return $this
     */
    public function setValidationRules(string $validationRules)
    {
        $this->validationRules = $validationRules;

        return $this;
    }
}
