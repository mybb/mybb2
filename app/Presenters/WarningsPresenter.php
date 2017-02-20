<?php
/**
 * Warnings presenter class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Warning as WarningsModel;
use MyBB\Core\Warnings\WarningsManager;

class WarningsPresenter extends BasePresenter
{
    /**
     * @var WarningsManager
     */
    protected $warningsManager;

    /** @var WarningsModel $wrappedObject */

    /**
     * @param WarningsModel $resource The conversation being wrapped by this presenter.
     * @param WarningsManager $warningsManager
     */
    public function __construct(WarningsModel $resource, WarningsManager $warningsManager)
    {
        parent::__construct($resource);
        $this->warningsManager = $warningsManager;
    }

    /**
     * @return UserPresenter
     */
    public function issued_by()
    {
        if ($this->wrappedObject->issuedBy instanceof UserPresenter) {
            return $this->wrappedObject->issuedBy;
        }

        return app()->make(\MyBB\Core\Presenters\UserPresenter::class, [$this->wrappedObject->issuedBy]);
    }

    /**
     * @return UserPresenter
     */
    public function revoked_by()
    {
        if ($this->wrappedObject->revokedBy instanceof UserPresenter) {
            return $this->wrappedObject->revokedBy;
        }

        return app()->make(\MyBB\Core\Presenters\UserPresenter::class, [$this->wrappedObject->revokedBy]);
    }

    /**
     * @return string|null
     */
    public function revoked_at()
    {
        return $this->wrappedObject->revoked_at;
    }

    /**
     * @return string|null
     */
    public function expires_at()
    {
        return $this->wrappedObject->revoked_at;
    }

    /**
     * @return string
     */
    public function formatSnapshot() : string
    {
        $warningContent = $this->warningsManager->getWarningContentClass($this->wrappedObject->content_type);
        $snapshot = $warningContent->getWarningPreviewView($this->wrappedObject->snapshot);
        return $snapshot;
    }
}
