<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Foundation\Application;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\ProfileFieldGroup as ProfileFieldGroupModel;

class ProfileFieldGroupPresenter extends BasePresenter
{
    /** @var ProfileFieldGroupModel $wrappedObject */

    /**
     * @var Application
     */
    private $app;

    /**
     * @param ProfileFieldGroupModel $resource The profile field group being wrapped by this presenter.
     * @param Application $app
     */
    public function __construct(ProfileFieldGroupModel $resource, Application $app)
    {
        $this->wrappedObject = $resource;
        $this->app = $app;
    }

    public function fields()
    {
        $profileFields = $this->getWrappedObject()->getProfileFields()->get();
        $profileFields = $profileFields->sortBy('display_order');
        $decorated = [];

        $decorator = $this->app->make('autopresenter');

        foreach ($profileFields as $profileField) {
            $decorated[] = $decorator->decorate($profileField);
        }

        return $decorated;
    }
}
