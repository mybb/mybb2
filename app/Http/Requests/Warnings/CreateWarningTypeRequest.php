<?php
/**
 * Warning type create request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Warnings;

use MyBB\Core\Http\Requests\AbstractRequest;

class CreateWarningTypeRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'reason'           => 'required|string',
            'points'           => 'required|integer',
            'multiple'         => 'integer',
            'type'             => 'required|in:hour,day,week,month,never',
            'must_acknowledge' => 'required|in:0,1,2',
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
