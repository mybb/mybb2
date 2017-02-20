<?php
/**
 * Warning type create request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Warnings;

use MyBB\Core\Http\Requests\AbstractRequest;
use MyBB\Settings\Store;

class CreateWarningTypeRequest extends AbstractRequest
{
    /**
     * @var Store
     */
    protected $settings;

    /**
     * CreateWarningTypeRequest constructor.
     * @param Store $settings
     */
    public function __construct(Store $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        $p_max = $this->settings->get('warnings.max_points');
        $p_min = $this->settings->get('warnings.allow_zero');
        if ($p_min) {
            $p_min = 0;
        } else {
            $p_min = 1;
        }

        return [
            'reason'           => 'required|string',
            'points'           => 'required|integer|between:' . $p_min . ',' . $p_max,
            'multiple'         => 'integer',
            'type'             => 'required|in:hour,day,week,month,never',
            'must_acknowledge' => 'required|in:0,1,2',
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
