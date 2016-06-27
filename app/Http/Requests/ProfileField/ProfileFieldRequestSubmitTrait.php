<?php

namespace MyBB\Core\Http\Requests\ProfileField;

use MyBB\Core\Database\Models\ProfileField;

trait ProfileFieldRequestSubmitTrait
{
    /**
     * @return array
     */
    public function rules()
    {
        $rules = [];

        foreach ($this->getAllProfileFields() as $profileField) {
            if ($profileField->validation_rules) {
                $rules['profile_fields.' . $profileField->id] = $profileField->validation_rules;
            }
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        $attributes = [];

        foreach ($this->getAllProfileFields() as $profileField) {
            $attributes['profile_fields.' . $profileField->id] = $profileField->name;
        }

        return $attributes;
    }

    /**
     * @return ProfileField[]
     */
    protected function getAllProfileFields()
    {
        return [];
    }
}
