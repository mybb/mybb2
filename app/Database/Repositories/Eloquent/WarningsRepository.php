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
    public function create(array $details = []) : Warning
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
    public function findForUser(int $userId) : Warning
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
    public function find(int $warnId) : Warning
    {
        return $this->warningModel->find($warnId);
    }

    /**
     * {@inheritdoc}
     */
    public function revoke(Warning $warning, string $reason) : Warning
    {
        return $warning->update([
            'revoked_at'    => date("Y-m-d H:i:s"),
            'expires_at'    => date("Y-m-d H:i:s"),
            'revoked_by'    => $this->guard->user()->id,
            'revoke_reason' => $reason,
            'expired'       => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Warning $warning, array $details = [])
    {
        return $warning->update($details);
    }

    /**
     * {@inheritdoc}
     */
    public function lastAckWarn(int $userId) : Warning
    {
        return $this->warningModel->where('user_id', $userId)
            ->where('must_acknowledge', 1)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function ackWarnCount(int $userId) : int
    {
        return $this->warningModel->where('user_id', $userId)
            ->where('must_acknowledge', 1)
            ->count();
    }
}
