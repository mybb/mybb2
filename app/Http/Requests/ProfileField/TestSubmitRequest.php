<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\ProfileField;

use MyBB\Core\Database\Models\ProfileField;
use MyBB\Core\Database\Repositories\ProfileFieldRepositoryInterface;
use MyBB\Core\Http\Requests\AbstractRequest;

class TestSubmitRequest extends AbstractRequest
{
    use ProfileFieldRequestSubmitTrait;

    /**
     * @var ProfileFieldRepositoryInterface
     */
    private $profileFieldRepository;

    /**
     * @param ProfileFieldRepositoryInterface $profileFieldRepository
     */
    public function __construct(ProfileFieldRepositoryInterface $profileFieldRepository)
    {
        $this->profileFieldRepository = $profileFieldRepository;
    }

    /**
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * @return ProfileField[]
     */
    protected function getAllProfileFields()
    {
        return [$this->profileFieldRepository->find($this->get('profile_field_id'))];
    }
}
