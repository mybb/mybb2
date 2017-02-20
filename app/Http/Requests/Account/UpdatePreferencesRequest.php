<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Account;

use MyBB\Core\Http\Requests\AbstractRequest;

class UpdatePreferencesRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'dst'                        => 'required|in:0,1,2',
            'follow_started_topics'      => 'boolean',
            'follow_replies_topics'      => 'boolean',
            'show_editor'                => 'boolean',
            'topics_per_page'            => 'integer|min:5|max:50',
            'posts_per_page'             => 'integer|min:5|max:50',
            'style'                      => '', // exists:styles
            'language'                   => 'required', // test whether exists?
            'message_order'              => 'in:asc,desc',
            'notify_on_like'             => 'boolean',
            'notify_on_quote'            => 'boolean',
            'notify_on_reply'            => 'boolean',
            'notify_on_new_post'         => 'boolean',
            'notify_on_new_comment'      => 'boolean',
            'notify_on_comment_like'     => 'boolean',
            'notify_on_my_comment_like'  => 'boolean',
            'notify_on_comment_reply'    => 'boolean',
            'notify_on_my_comment_reply' => 'boolean',
            'notify_on_new_message'      => 'boolean',
            'notify_on_reply_message'    => 'boolean',
            'notify_on_group_request'    => 'boolean',
            'notify_on_moderation_post'  => 'boolean',
            'notify_on_report'           => 'boolean',
            'notify_on_username_change'  => 'boolean',
            'notification_mails'         => 'required|in:0,1,2',
            'time_format'                => 'required|integer',
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
