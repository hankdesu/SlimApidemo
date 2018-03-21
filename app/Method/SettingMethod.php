<?php

namespace App\Method;

use Dotenv\Dotenv;

/**
*
*/
class SettingMethod
{
    public function settingArr($setting)
    {
        $arr = explode(',', $setting);
        foreach ($arr as $val) {
            $tmp = explode('=', $val);
            $settings[$tmp[0]] = $tmp[1];
        }
        return $settings;
    }
}
