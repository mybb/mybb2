<?php
/**
 * Created by PhpStorm.
 * User: euan
 * Date: 29/03/15
 * Time: 16:58
 */

namespace MyBB\Core\Http\Requests\Post;


use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Http\Requests\Request;

class LikePostRequest extends Request
{
    protected $rules = [
        'post_id' => 'required|integer|exists:posts,id',
    ];

    /**
     * Get the validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

    public function authorize(Guard $guard)
    {
        if (!$guard->check()) {
            return false;
        }

        // TODO: Check user permissions here...

        return true;
    }
}
