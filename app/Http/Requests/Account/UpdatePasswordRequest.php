<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Account;

use MyBB\Core\Http\Requests\AbstractRequest;

class UpdatePasswordRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $redirectRoute = 'account.password';

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'password1' => 'required|min:6',
            'password'  => 'required',
        ];
    }

    /**
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }
}
