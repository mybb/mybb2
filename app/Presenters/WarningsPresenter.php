<?php
/**
 * Forum presenter class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Warning as WarningsModel;

class WarningsPresenter extends BasePresenter
{
    /** @var WarningsModel $wrappedObject */

    /**
     * @param WarningsModel $resource The conversation being wrapped by this presenter.
     */
    public function __construct(WarningsModel $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @return UserPresenter
     */
    public function issued()
    {
        if ($this->wrappedObject->author instanceof UserPresenter) {
            return $this->wrappedObject->author;
        }

        return app()->make(\MyBB\Core\Presenters\UserPresenter::class, [$this->wrappedObject->author]);
    }

    /**
     * @return UserPresenter
     */
    public function revoked_By()
    {
        if ($this->wrappedObject->author instanceof UserPresenter) {
            return $this->wrappedObject->author;
        }

        return app()->make(\MyBB\Core\Presenters\UserPresenter::class, [$this->wrappedObject->author]);
    }
}
