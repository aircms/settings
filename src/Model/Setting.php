<?php

namespace aircms\settings;

use aircms\groupable\Groupable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use Groupable;

    public function get($keyTree)
    {
        return Arr::get(static::cacheData(false), $keyTree);
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

            $groupData = [];
            foreach ($groupItems as $groupItem) {
                $settingModel = static::find($groupItem->id);
                if (!$settingModel) {
                    continue;
                }

                $groupData[$groupItem->linkGroup->name][$settingModel->alias] = $settingModel;
            }

            Cache::set($key, $groupData);

        }

        return $groupData;
    }
}
