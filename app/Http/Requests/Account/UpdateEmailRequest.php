<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Account;

use MyBB\Core\Http\Requests\AbstractRequest;

class UpdateEmailRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $redirectRoute = 'account.email';

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required',
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
