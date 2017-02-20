<?php
/**
 * Warn user request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Warnings;

use MyBB\Core\Http\Requests\AbstractRequest;
use MyBB\Settings\Store;

class WarnUserRequest extends AbstractRequest
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
            'warningType'             => 'required',
            'must_acknowledge.*'      => 'in:0,1',
            'custom_reason'           => 'required_if:warningType,custom|string',
            'custom_points'           => 'required_if:warningType,custom|integer|between:' . $p_min . ',' . $p_max,
            //todo probably it need dynamic load date format from forum settings
            'custom_expires_at'       => 'date_format:d-m-Y H:i|after:tomorrow',
            'must_acknowledge.custom' => 'required_if:warningType,custom',
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
