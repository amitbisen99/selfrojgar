<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\Job;
use App\Models\JobApply;
use App\Models\City;
use App\Models\State;
use App\Models\Country;

class JobApplyController extends BaseController
{
    public function jobApply(Request $request)
    {
        $input = $request->all();
     
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'job_id' => 'required',
        ]);
     
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        $Job = Job::find($input['job_id']);
        if (!is_null($Job)) {
            $jobApply = JobApply::where('user_id', $input['user_id'])->where('job_id', $input['job_id'])->first();
            if (is_null($jobApply)) {
                $jobApply = JobApply::create($input);

                $name = $jobApply->user ? $jobApply->user->name : '';
                $title = 'Job Apply by '.$name;

                createNotification($title, 'JobApply', $jobApply->id, 'jobApply');

                return $this->sendResponse([], 'Job applied successfully.');
            }else{
                return $this->sendError('you already applied on this job.', []);
            }
        }else{
            return $this->sendError('job not found.', []);
        }
    }

    public function appliedByUser($id)
    {
        $jobs = JobApply::leftJoin('jobs', 'job_applies.job_id', '=', 'jobs.id')
                        ->where('job_applies.user_id', $id)
                        ->select('job_applies.*', 'jobs.*', 'jobs.user_id as job_create_user')
                        ->get();

        foreach ($jobs as $job) {
            if (!is_null($job->company_logo)) {
                $job->company_logo = asset($job->company_logo);
            }

            $city = City::where('id', $job->city_id)->value('name') ?? null;
            $country = Country::where('id', $job->country_id)->value('name') ?? null;
            $state = State::where('id', $job->state_id)->value('name') ?? null;

            unset($job->city_id, $job->country_id, $job->state_id);

            $job->city_name = $city;
            $job->country_name = $country;
            $job->state_name = $state;

        }

        return $this->sendResponse($jobs, 'Applied Jobs retrieved successfully.');
    }

}
