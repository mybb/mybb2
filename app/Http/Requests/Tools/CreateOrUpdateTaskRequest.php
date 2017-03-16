<?php
/**
 * Warn user request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Tools;

use MyBB\Core\Http\Requests\AbstractRequest;

class CreateOrUpdateTaskRequest extends AbstractRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'id'             => 'required|integer',
            'name'           => 'required|string',
            'desc'           => 'required|string',
            'namespace'      => 'required|string',
            'time.minutes'   => 'required|string',
            'time.hours'     => 'required|string',
            'time.days'      => 'required|string',
            'time.weekday.*' => 'required|string',
            'time.month.*'   => 'required|string',
            'enabled'        => 'required|bool',
            'logging'        => 'required|bool',
        ];
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
