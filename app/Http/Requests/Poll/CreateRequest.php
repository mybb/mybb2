<?php
/**
 * Create request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Poll;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Http\Requests\AbstractRequest;

class CreateRequest extends AbstractRequest
{
    /**
     * @var Guard
     */
    private $guard;

    /**
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        $rules = [
            'question'    => 'required',
            'option'      => ['required', 'array', 'option'],
            'is_multiple' => 'boolean',
            'is_public'   => 'boolean',
            'is_closed'   => 'boolean',
            'maxoptions'  => 'required_with:is_multiple|integer|min:0',
            'endAt'       => 'date',
        ];

        return $rules;
    }

    /**
     * get the options of the poll
     *
     * @return array
     */
    public function options() : array
    {
        $input = $this->input('option');
        $options = [];
        foreach ($input as $option) {
            if ($option) {
                $options[] = [
                    'option' => $option,
                    'votes'  => 0,
                ];
            }
        }

        return $options;
    }

    /**
     * @return bool
     */
    public function authorize() : bool
    {
        //return $this->guard->check();
        return true; // TODO: In dev return, needs replacing for later...
    }

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->addImplicitExtension('option', function ($attribute, $value, $parameters) {
            foreach ($value as $option) {
                if (!is_scalar($option)) {
                    return false;
                }
            }

            return true;
        });


        return $validator;
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'option.option' => trans('errors.poll_invalid_options'),
        ];
    }
}
