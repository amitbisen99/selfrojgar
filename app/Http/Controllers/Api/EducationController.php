<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Education;

class EducationController extends BaseController
{
    public function index($user)
    {
        $education = Education::where('user_id', $user)->get();
        return $this->sendResponse($education, 'Education retrieved successfully.');
    }

    public function create(Request $request)
    {
        Education::where('user_id', $request->user_id)->delete();

        $education = [];
        
        $input['user_id'] = $request->user_id;
        foreach ($request->education as $key => $value) {
            $input['education'] = $value;
            $input['university'] = $request->university[$key] ? $request->university[$key] : null;
            $input['joining_date'] = $request->joining_date[$key] ? $request->joining_date[$key] : null;
            $input['end_date'] = $request->end_date[$key] ? $request->end_date[$key] : null;
            $input['is_onGoing'] = $request->is_onGoing[$key] ? $request->is_onGoing[$key] : null;

            $education[] = Education::create($input);
        }

        return $this->sendResponse($education, 'Education Create successfully.');
    }
}
