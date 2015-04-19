<?php

namespace MyBB\Core\Form;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;
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
	 * @var Request
	 */
	protected $request;

	/**
	 * @param ViewFactory       $viewFactory
	 * @param ValidationFactory $validationFactory
	 * @param Request           $request
	 */
	public function __construct(ViewFactory $viewFactory, ValidationFactory $validationFactory, Request $request)
	{
		$this->view = $viewFactory;
		$this->validator = $validationFactory;
		$this->request = $request;
	}

	/**
	 * @param RenderableInterface $renderable
	 *
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
	 *
	 * @return string
	 */
	protected function renderTextarea(RenderableInterface $renderable)
	{
		return $this->view->make('partials.form.field_textarea', [
			'name' => $renderable->getName(),
			'rows' => 6,
			'cols' => 40,
			'value' => $this->getValue($renderable),
			'is_required' => $this->isRequired($renderable),
			'min_length' => $this->getMinLength($renderable),
			'max_length' => $this->getMaxLength($renderable)
		])->render();
	}

	/**
	 * @param RenderableInterface $renderable
	 *
	 * @return string
	 */
	protected function renderInput(RenderableInterface $renderable)
	{
		return $this->view->make('partials.form.field_input', [
			'type' => $renderable->getType(),
			'name' => $renderable->getName(),
			'id' => $this->slugify($renderable->getName()),
			'value' => $this->getValue($renderable),
			'is_required' => $this->isRequired($renderable),
			'min_length' => $this->getMinLength($renderable),
			'max_length' => $this->getMaxLength($renderable)
		])->render();
	}

	/**
	 * @param RenderableInterface $renderable
	 *
	 * @return string
	 */
	protected function renderSelect(RenderableInterface $renderable)
	{
		return $this->view->make('partials.form.field_select', [
			'name' => $renderable->getName(),
			'options' => $renderable->getOptions(),
			'selected' => $this->getValue($renderable)
		])->render();
	}

	/**
	 * @param RenderableInterface $renderable
	 *
	 * @return mixed
	 */
	protected function getValue(RenderableInterface $renderable)
	{
		$dottedNotation = str_replace(['[', ']'], ['.', ''], $renderable->getName());
		if (!is_null($this->request->old($dottedNotation))) {
			return $this->request->old($dottedNotation);
		}

		$value = $renderable->getValue();

		if (!is_null($value)) {
			return $value;
		}

		return '';
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	protected function slugify($string)
	{
		return Str::slug($string);
	}

	/**
	 * @param RenderableInterface $renderable
	 *
	 * @return int
	 */
	protected function getMinLength(RenderableInterface $renderable)
	{
		return (int)$this->extractValueByKeyFromRules('min', $renderable);
	}

	/**
	 * @param RenderableInterface $renderable
	 *
	 * @return int
	 */
	protected function getMaxLength(RenderableInterface $renderable)
	{
		return (int)$this->extractValueByKeyFromRules('max', $renderable);
	}

	/**
	 * @param string              $key
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
	 *
	 * @return bool
	 */
	protected function isRequired(RenderableInterface $renderable)
	{
		return in_array('required', $this->getRules($renderable));
	}

	/**
	 * @param RenderableInterface $renderable
	 *
	 * @return array
	 */
	protected function getRules(RenderableInterface $renderable)
	{
		$rules = $this->getValidator($renderable)->getRules();

		return $rules[$renderable->getName()] ? $rules[$renderable->getName()] : [];
	}

	/**
	 * @param RenderableInterface $renderable
	 *
	 * @return \Illuminate\Validation\Validator
	 */
	protected function getValidator(RenderableInterface $renderable)
	{
		return $this->validator->make([], [$renderable->getName() => $renderable->getValidationRules()]);
	}
}
