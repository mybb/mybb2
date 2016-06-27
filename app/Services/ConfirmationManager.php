<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Services;

use DB;
use Lang;
use Mail;
use MyBB\Core\Database\Models\User;
use URL;

class ConfirmationManager
{

    /**
     * @param string $type The confirmation type. Used to get the language strings
     * @param User $user The user who needs to confirm something
     * @param string $route The named route where the token is checked. Should have one parameter
     * @param null|string $newData The newData which needs to be confirmed (eg new email). Can be null
     * @param array $langData Array of additional language data
     * @param bool $delete Whether or not old tokens for this type/user combination should be deleted
     */
    public static function send($type, User $user, $route, $newData = null, $langData = [], $delete = true)
    {
        // Generate the token - always needed
        $token = str_random();

        // Do we need to delete old confirmations for this user/type combination?
        if ($delete) {
            DB::table('confirmations')->where('type', $type)->where('user_id', $user->id)->delete();
        }

        // Now insert our data set
        DB::table('confirmations')->insert([
            'token'   => $token,
            'type'    => $type,
            'user_id' => $user->id,
            'newData' => $newData,
        ]);

        // Now build the subject/message
        $link = URL::route($route, $token);
        $langData['link'] = $link;

        if (Lang::has("confirmation.{$type}_subject")) {
            $subject = Lang::get("confirmation.{$type}_subject", $langData);
        } else {
            $subject = Lang::get('confirmation.subject', array_merge($langData, ['type' => $type]));
        }

        if (Lang::has("confirmation.{$type}_message")) {
            $message = Lang::get("confirmation.{$type}_message", $langData);
        } else {
            $message = Lang::get('confirmation.message', array_merge($langData, ['type' => $type]));
        }

        // Finally send it
        Mail::raw($message, function ($mail) use ($user, $subject) {

            // TODO: board email
            $mail->from('admin@mybb.com');
            $mail->to($user->email);
            $mail->subject($subject);
        });
    }

    /**
     * @param string $type The confirmation type
     * @param string $token The token which needs to be confirmed
     * @param bool $delete Whether or not the found data should be deleted
     *
     * @return mixed returns the new data passed to send on success or false on failure
     */
    public static function get($type, $token, $delete = true)
    {
        $baseQuery = DB::table('confirmations')->where('type', $type)->where('token', $token);

        // Something is wrong - either we don't have a valid token here or the same token multiple times
        if ($baseQuery->count() != 1) {
            return false;
        }

        $newData = $baseQuery->pluck('newData');

        if ($delete) {
            $baseQuery->delete();
        }

        return $newData;
    }

    /**
     * @param string $type The confirmation type
     * @param User $user The user to test
     *
     * @return bool
     */
    public static function has($type, User $user)
    {
        $count = DB::table('confirmations')->where('type', $type)->where('user_id', $user->id)->count();

        return $count > 0;
    }
}
