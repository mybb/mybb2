<?php
/**
 * Create forum request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Forums;

use MyBB\Core\Http\Requests\AbstractRequest;

class CreateForumRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'type'           => 'required|integer',
            'title'          => 'required|string|max:255',
            'slug'           => 'required|string|max:255|unique:forums,slug',
            'parent'         => 'required_if:type,1|integer',
            'order'          => 'required|integer', // todo required if there are forums
            'order_position' => 'required_unless:order,0|integer|in:0,1',
            'link'           => 'url|max:255',
            'open'           => 'integer',
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
