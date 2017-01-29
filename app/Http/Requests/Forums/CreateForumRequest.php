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
        $return = [
            'type'           => 'required|integer',
            'title'          => 'required|string|max:255',
            //'slug'           => 'required|string|max:255|unique:forums,slug',
            'parent'         => 'required_if:type,1|integer',
            //todo add order rule
            'link'           => 'url|max:255',
            'open'           => 'integer',
        ];
        // TODO temporary solution, probably need to create separately request for edit.
        if ($this->input('id')) {
            $return['slug'] = 'required|string|max:255|unique:forums,slug,'.$this->input('id');
        } else {
            $return['slug'] = 'required|string|max:255|unique:forums,slug';
        }
        return $return;
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
