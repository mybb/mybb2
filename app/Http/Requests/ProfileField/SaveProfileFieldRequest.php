<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\ProfileField;

use MyBB\Core\Http\Requests\AbstractRequest;

class SaveProfileFieldRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'required|string|max:255',
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
