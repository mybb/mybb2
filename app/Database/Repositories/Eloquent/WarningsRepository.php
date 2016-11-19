<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Repositories\WarningsRepositoryInterface;
use MyBB\Core\Database\Models\Warning;
use Illuminate\Contracts\Auth\Guard;

class WarningsRepository implements WarningsRepositoryInterface
{
    /**
     * @var Warning $warning
     */
    protected $warningModel;

    /**
     * @var Guard $guard
     */
    protected $guard;

    /**
     * WarningsRepository constructor.
     * @param Warning $warning
     * @param Guard $guard
     */
    public function __construct(Warning $warning, Guard $guard)
    {
        $this->warningModel = $warning;
        $this->guard = $guard;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $details = [])
    {
        $details = array_merge([
            'issued_by' => $this->guard->user()->id,
        ], $details);

        $warning = $this->warningModel->create($details);
        return $warning;
    }

    /**
     * {@inheritdoc}
     */
    public function findForUser($userId)
    {
        return $this->warningModel->where('user_id', $userId)
            ->with(['issuedBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('expired');
    }

    /**
     * {@inheritdoc}
     */
    public function find($warnId)
    {
        return $this->warningModel->find($warnId);
    }
}
