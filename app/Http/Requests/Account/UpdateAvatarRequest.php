<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Account;

use MyBB\Core\Http\Requests\AbstractRequest;

class UpdateAvatarRequest extends AbstractRequest
{
    /**
     * @var string
     */
    protected $redirectRoute = 'account.avatar';

    /**
     * @return array
     */
    public function rules() : array
    {
        // TODO: validation. Upload size, valid link, valid email
        return [
            'avatar_file' => 'image',
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
