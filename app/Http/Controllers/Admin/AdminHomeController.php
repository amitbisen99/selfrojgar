<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ImageUpload;
use Storage;

class AdminHomeController extends AdminThemeController
{
    /**
     * Write Your Code..
     *
     * @return string
    */
    public function index()
    {
        return view('admin.dashboard.dashboard');
    }

    public function setting()
    {
        $settings = Setting::get();
        return view('admin.dashboard.settings', compact('settings'));
    }

    public function settingUpdate(Request $request)
    {
        $input = $request->all();

        if (isset($input['logo'])) {
            if (is_null($input['logo'])) {
                unset($input['logo']);
            }else{
                $input['logo'] = ImageUpload::upload('uploads/setting/', $request->logo);
            }
        }
        if (isset($input['favicon_icone'])) {
            if (is_null($input['favicon_icone'])) {
                unset($input['favicon_icone']);
            }else{
                $input['favicon_icone'] = ImageUpload::upload('uploads/setting/', $request->favicon_icone);
            }
        }
        unset($input['_token']);
        
        foreach ($input as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            $setting->update(['value' => $value]);
        }

        notificationMsg('success', 'Setting updated sucessfully.');
        return redirect()->back();
    }

    /**
     * Write Your Code..
     *
     * @return string
    */
    public function log()
    {
        $folderName = 'laravel.log';
        $arr = Storage::disk('logs')->files();
        return view('admin.logs.index',compact('arr','folderName'));
    }

    /**
     * Write Your Code..
     *
     * @return string
    */
    public function clearLog()
    {
        $folderName = 'laravel.log';

        if(Storage::disk('logs')->exists($folderName)){
            $result = Storage::disk('logs')->get($folderName);
            shell_exec('cd ..;truncate -s 0 storage/logs/'.$folderName);
        }
        
        return back()->with('success', 'Log Entries Clear successfully');
    }
}
