<?php

namespace aircms\settings\Models;

use aircms\groupable\Groupable;
use aircms\groupable\Models\Group;
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

    public function cacheData($useCache = true)
    {
        $key = "group.settings";
        $groupData = Cache::get($key);
        if ($useCache && $groupData) {
            return $groupData;
        }

        if (!$groupData) {
            $groupItems = $this->groupItems();

            $groupInstances = [];
            $groupData = [];
            foreach ($groupItems as $groupItem) {
                $settingModel = static::find($groupItem->groupable_id);
                if (!$settingModel) {
                    continue;
                }

                if (!$groupModel = array_get($groupInstances, $groupItem->group_id)) {
                    $groupInstances[$groupItem->group_id] = $groupModel = Group::find($groupItem->group_id);
                }

                $groupAlias = $groupModel->alias;
                $settingAlias = $settingModel->alias;
                $groupData[$groupAlias][$settingAlias] = $settingModel->id;
            }

            if ($groupData) {
                Cache::set($key, $groupData, 60 * 60);
            }
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
}
