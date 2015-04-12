<?php
/**
 * Vote request.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Requests\Poll;


use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Http\Requests\Request;
use MyBB\Core\Presenters\Poll as PollPresenter;

class VoteRequest extends Request
{
    /** @var Guard $guard */
    private $guard;

    /** @var PollPresenter $poll */
    private $poll;

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->addImplicitExtension('votes', function ($attribute, $value, $parameters) {
            if ($this->poll->is_multiple) {

                if (!is_array($value)) {
                    return false;
                }

                foreach ($value as $v) {
                    if (!is_numeric($v) || $v < 1 || $v > $this->poll->num_options()) {
                        return false;
                    }
                }
            } else {
                if (is_array($value)) {
                    return false;
                }

                if (!is_numeric($value) || $value < 1 || $value > $this->poll->num_options()) {
                    return false;
                }
            }

            return true;
        });

        $validator->addImplicitExtension('votes_maxOptions', function ($attribute, $value, $parameters) {
            if ($this->poll->max_options) {
                if (count($value) > $this->poll->max_options) {
                    return false;
                }
            }

            return true;
        });


        return $validator;
    }

    /**
     * @param PollPresenter $poll
     * @param Guard $guard
     */
    public function __construct(PollPresenter $poll, Guard $guard)
    {
        $this->guard = $guard;
        $this->poll = $poll;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = [
            'option' => 'required|votes|votes_maxOptions'
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'option.votes_maxOptions' => trans('errors.poll_very_votes', ['count' => $this->poll->max_options]),
            'option.votes' => trans('errors.poll_invalid_vote')
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        //return $this->guard->check();
        return true; // TODO: In dev return, needs replacing for later...
    }
}
