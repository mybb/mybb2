<?php

namespace MyBB\Core\Form;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeConstructed()
    {
        $field = new Field('foo', 'bar', 'bar', 'bar');
        static::assertInstanceOf('MyBB\Core\Form\Field', $field);
    }

    public function testGettersReturnWhatTheyShould()
    {
        $field = new Field('type', 'element', 'label', 'description');

        $expectations = [
            'getType'        => 'type',
            'getElementName' => 'element',
            'getLabel'       => 'label',
            'getDescription' => 'description',
        ];

        foreach ($expectations as $method => $value) {
            static::assertEquals($field->{$method}(), $value);
        }
    }

    public function testSettersAndGettersDoWhatTheyShould()
    {
        $field = new Field('type', 'element', 'label', 'description');
        $field->setOptions(['options' => 'options']);
        $field->setValidationRules('rules');
        $field->setValue('foo');

        $expectations = [
            'getOptions'         => ['options' => 'options'],
            'getValidationRules' => 'rules',
            'getValue'           => 'foo',
        ];

        foreach ($expectations as $method => $value) {
            static::assertEquals($field->{$method}(), $value);
        }
    }
}
