<?php
/**
 * Warn user request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Warnings;

use MyBB\Core\Http\Requests\AbstractRequest;

class WarnUserRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'warningType'             => 'required',
            'must_acknowledge.*'      => 'in:0,1',
            'custom_reason'           => 'required_if:warningType,custom|string',
            'custom_points'           => 'required_if:warningType,custom|integer',
            'custom_expires_at'       => 'date_format:d-m-Y H:i|after:tomorrow',
            //'custom_never'            => 'required_without:custom_expires_at|boolean',
            'must_acknowledge.custom' => 'required_if:warningType,custom',
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
