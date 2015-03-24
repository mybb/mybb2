<?php

namespace MyBB\Core\Presenters;

use Illuminate\Support\Facades\App;
use McCool\LaravelAutoPresenter\BasePresenter;

class ProfileFieldGroup extends BasePresenter
{
    public function fields()
    {
        $profileFields = $this->getWrappedObject()->getProfileFields()->get();
        $profileFields = $profileFields->sortBy('display_order');
        $decorated = [];

        $decorator = App::make('autopresenter');
        foreach ($profileFields as $profileField) {
            $decorated[] = $decorator->decorate($profileField);
        }

        return $decorated;
    }
}
