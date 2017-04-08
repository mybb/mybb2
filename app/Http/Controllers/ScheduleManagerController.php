<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers;

class ScheduleManagerController extends AbstractController
{

    /**
     * @return Illuminate/Http/Response
     */
    public function sendFakeImage()
    {
        $image = "R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
        $dateTime = new \DateTime();

        return response(base64_decode($image))
            ->withHeaders([
                'Content-type'  => 'image/gif',
                'Expires'       => 'Sat, 1 Jan 2000 01:00:00 GMT',
                'Last-Modified' => $dateTime->format('D, d M Y H:i:s') . ' GMT',
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma'        => 'no-cache',
            ]);
    }
}
