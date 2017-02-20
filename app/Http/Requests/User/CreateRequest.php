<?php
/**
 * User create request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\User;

use MyBB\Core\Http\Requests\AbstractRequest;

class CreateRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'      => 'required|max:255|unique:users',
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|confirmed|min:6',
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
