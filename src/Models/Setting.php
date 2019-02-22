<?php

namespace aircms\settings\Models;

use aircms\groupable\Groupable;
use aircms\groupable\Models\GroupItems;
use aircms\settings\Exceptions\SettingItemNotExistException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use Groupable;

    public $timestamps = false;

    public function get($keyTree, $default = null)
    {
        $modelID = Arr::get(static::cacheData(false), $keyTree);
        if (!$modelID) {
            return $default;
        }

        return static::find($modelID);
    }

    public function cacheData($useCache = false)
    {
        $key = "group.settings";
        $groupData = Cache::get($key);
        if ($useCache && $groupData) {
            return $groupData;
        }

        /** @var GroupItems[] $groupItems */
        $groupItems = $this->groupItems();

        $groupData = [];
        foreach ($groupItems as $groupItem) {
            $settingModel = $groupItem->groupableItem;
            if (!$settingModel) {
                $groupItem->delete();
                continue;
            }

            $groupAlias = $groupItem->getGroupAlias();
            $settingAlias = $settingModel->alias;

            array_set($groupData, "$groupAlias.$settingAlias", $settingModel->id);
        }

        if ($groupData) {
            Cache::set($key, $groupData, 60 * 60);
        }

        return $groupData;
    }

    public function set($keyTree, $value)
    {
        $model = $this->get($keyTree);
        if (!$model) {
            throw new SettingItemNotExistException();
        }

        $model->value = $value;
        $model->save();

        return $model;
    }

    public function getPreSettingDataAttribute()
    {
        return json_decode($this->pre_setting, true);
    }
}
