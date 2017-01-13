<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Account;

use MyBB\Core\Http\Requests\AbstractRequest;

class CropAvatarRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'w'  => 'required|integer',
            'h'  => 'required|integer',
            'x'  => 'required|integer',
            'x2' => 'required|integer',
            'y'  => 'required|integer',
            'y2' => 'required|integer',
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
