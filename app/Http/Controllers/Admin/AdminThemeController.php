<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class AdminThemeController extends Controller
{
    /**
     * Write Your Code..
     *
     * @return string
    */
    public function __construct()
    {
        view()->share('adminTheme', 'adminTheme.default');
        $settings = Setting::pluck('value','key');
        $newSetting = [];

        foreach ($settings as $key => $value) {
            $newSetting[$key] = $value;
        }
        view()->share('webSetting',$newSetting);
    }
}
