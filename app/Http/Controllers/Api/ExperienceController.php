<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience;
use App\Models\ImageUpload;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\File;

class ExperienceController extends BaseController
{
    public function index($user)
    {
        $experience = Experience::where('user_id', $user)->get();
        return $this->sendResponse($experience, 'Experience retrieved successfully.');
    }

    public function create(Request $request)
    {
        $olds = Experience::where('user_id', $request->user_id)->get();

        foreach ($olds as $old) {
            if (!is_null($old->company_logo)) {
                if (File::exists($old->company_logo)) {
                    File::delete($old->company_logo);
                }
            }

            $old->delete();
        }
        
        $experience = [];
        
        $input['user_id'] = $request->user_id;
        foreach ($request->company_name as $key => $value) {
            $input['role'] = $request->role[$key] ? $request->role[$key] : null;
            $input['company_name'] = $value;
            $input['joining_date'] = $request->joining_date[$key] ? $request->joining_date[$key] : null;
            $input['end_date'] = $request->end_date[$key] ? $request->end_date[$key] : null;
            $input['is_present'] = $request->is_present[$key] ? $request->is_present[$key] : null;

            if (isset($request->company_logo[$key])) {
                $input['company_logo'] = ImageUpload::upload('uploads/profile/company_logo/', $request->company_logo[$key]);
            }else{
                $input['company_logo'] = null;
            }

            $experience[] = Experience::create($input);
        }

        return $this->sendResponse($experience, 'Experience Create successfully.');
    }
}
