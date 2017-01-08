<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Account;

use MyBB\Core\Http\Requests\AbstractRequest;

class UpdatePrivacyRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'showonline'             => 'boolean',
            'receive_messages'       => 'boolean',
            'block_blocked_messages' => 'boolean',
            'hide_blocked_posts'     => 'boolean',
            'only_buddy_messages'    => 'boolean',
            'receive_email'          => 'boolean',
            'dob_privacy'            => 'required|in:0,1,2',
            'dob_visibility'         => 'required|in:0,1,2',
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
