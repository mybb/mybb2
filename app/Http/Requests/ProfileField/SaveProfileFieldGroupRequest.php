<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\ProfileField;

use MyBB\Core\Http\Requests\AbstractRequest;

class SaveProfileFieldGroupRequest extends AbstractRequest
{
    /**
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|alpha_dash',
            'description' => 'required|string|max:255',
        ];
    }
}
