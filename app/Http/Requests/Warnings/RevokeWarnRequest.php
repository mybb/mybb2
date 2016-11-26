<?php
/**
 * Revoke warn request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Warnings;

use MyBB\Core\Http\Requests\AbstractRequest;

class RevokeWarnRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'reason' => 'required|string',
            'id'     => 'required|integer|exists:warnings,id',
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
