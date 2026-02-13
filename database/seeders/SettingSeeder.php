<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
                        [
                            'key' => 'web_title',
                            'value' => 'Flutter API',
                            'type' => 'text',
                            'input_id' => 'web_title',
                            'lable' => 'Website Title',
                        ],
                        [
                            'key' => 'logo',
                            'value' => 'adminTheme/app-assets/images/logo.svg',
                            'type' => 'file',
                            'input_id' => 'web_logo',
                            'lable' => 'Website Logo',
                        ],
                        [
                            'key' => 'favicon_icone',
                            'value' => 'adminTheme/app-assets/images/icon.png',
                            'type' => 'file',
                            'input_id' => 'favicon_icone',
                            'lable' => 'Favicon Icone',
                        ],
                    ];

        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $value['key'])->first();
            if (!is_null($setting)) {
                $setting->update($value);
            }else{
                Setting::create($value);
            }
        }

    }
}
