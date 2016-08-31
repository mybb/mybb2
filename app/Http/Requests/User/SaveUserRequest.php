<?php
/**
 * Edit user request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\User;

use MyBB\Core\Http\Requests\AbstractRequest;

class SaveUserRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        $id = $this->input('user_id');
        
        return [
            'name'      => 'required|max:255|unique:users,name,'.$id,
            'email'     => 'required|email|max:255|unique:users,email,'.$id,
            'password'  => 'confirmed|min:6',
            'usertitle' => 'string',
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
