<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\Job;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\ImageUpload;

class JobController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::select('jobs.id', 'jobs.user_id', 'jobs.job_industry_id', 'jobs.role', 'jobs.company_name', 'jobs.company_logo', 'jobs.start_salary', 'jobs.end_salary', 'jobs.type', 'jobs.work_type', 'jobs.address', 'jobs.skills', 'jobs.about', 'jobs.description', 'jobs.salary_type', 'jobs.employe_level', 'jobs.latitude', 'jobs.longitude', 'jobs.status', 'jobs.created_at', 'jobs.updated_at', 'jobs.updated_at', 'cities.name as city_name', 'states.name as state_name', 'countries.name as country_name')
                    ->leftJoin('cities', 'jobs.city_id', '=', 'cities.id')
                    ->leftJoin('states', 'jobs.state_id', '=', 'states.id')
                    ->leftJoin('countries', 'jobs.country_id', '=', 'countries.id')
                    ->with(['jobApplies' => function ($query) {
                        $query->select('id as job_applies_id', 'job_id', 'user_id')
                              ->with(['user' => function ($userQuery) {
                                  $userQuery->select('id', 'name', 'profile_pic', 'email'); // Select the user's ID and name
                              }]);
                    }])
                    ->where('jobs.status', 1)
                    ->orderBy('jobs.created_at', 'desc')
                    ->get();
                    foreach ($jobs as $key => $job) {
                        $job->job_industry_name = !is_null($job->jobIndustry) ? $job->jobIndustry->name : '';
                        unset($job->jobIndustry);
                    }
        return $this->sendResponse($jobs, 'Job retrieved successfully.');
    }

    public function filter(Request $request)
    {
        $query = Job::query();

        if ($request->role) {
            $query = $query->where('role', 'like', '%'.$request->role.'%');
        }
        if($request->company_name){
            $query = $query->where('company_name', 'like', '%'.$request->company_name.'%');
        }
        if($request->city_id){
            $query = $query->where('city_id', $request->city_id);
        }
        if($request->state_id){
            $query = $query->where('state_id', $request->state_id);
        }
        if($request->country_id){
            $query = $query->where('country_id', $request->country_id);
        }
        if($request->job_industry_id){
            $query = $query->where('job_industry_id', $request->job_industry_id);
        }

        if($request->work_type){
            $query = $query->where('work_type', $request->work_type);
        }

        if($request->type){
            $query = $query->where('type', $request->type);
        }

        if($request->user_id){
            $query = $query->where('user_id', $request->user_id);
        }

        if ($request->start_salary && $request->end_salary) {
            $query = $query->where(function ($query) use ($request) {
                $query->whereBetween('start_salary', [$request->start_salary, $request->end_salary])
                      ->orWhereBetween('end_salary', [$request->start_salary, $request->end_salary]);
            });
        } else {
            if ($request->start_salary) {
                $query = $query->where('start_salary', '>=', $request->start_salary)
                               ->orWhere('end_salary', '>=', $request->start_salary);
            }

            if ($request->end_salary) {
                $query = $query->where('start_salary', '<=', $request->end_salary)
                               ->orWhere('end_salary', '<=', $request->end_salary);
            }
        }


        if($request->employe_level){
            $query = $query->where('employe_level', 'like', '%'.$request->employe_level.'%');
        }

        if ($request->latitude && $request->longitude && $request->radius) {
            $query->where(haversineFormula($request->latitude, $request->longitude, $request->radius));
        }

        if($request->search){
            $query = $query->Where('role', 'like', '%'.$request->search.'%')->orWhere('company_name', 'like', '%'.$request->search.'%')->orWhere('skills', 'like', '%'.$request->search.'%')
                            ->orWhereHas('jobIndustry', function ($subQuery) use ($request) {
                                  $subQuery->where('name', 'like', '%' . $request->search . '%');
                              });
        }


        $query = $query->with(['jobApplies' => function ($query) {
                        $query->select('id as job_applies_id', 'job_id', 'user_id')
                              ->with(['user' => function ($userQuery) {
                                  $userQuery->select('id', 'name', 'profile_pic', 'email'); // Select the user's ID and name
                              }]);
                    }])
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc');

        $jobs = $query->get();

        foreach ($jobs as $key => $job) {
            $city = City::where('id', $job->city_id)->value('name') ?? null;
            $country = Country::where('id', $job->country_id)->value('name') ?? null;
            $state = State::where('id', $job->state_id)->value('name') ?? null;

            $job->job_industry_name = !is_null($job->jobIndustry) ? $job->jobIndustry->name : '';
            unset($job->jobIndustry);

            unset($job->city_id, $job->country_id, $job->state_id);

            $job->city_name = $city;
            $job->country_name = $country;
            $job->state_name = $state;
        }

        return $this->sendResponse($jobs, 'Job retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
     
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'job_industry_id' => 'required',
            'role' => 'required',
            'company_name' => 'required',
            'start_salary' => 'required',
            'end_salary' => 'required',
            'type' => 'required',
            'work_type' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'skills' => 'required',
            'about' => 'required',
            'description' => 'required',
            'salary_type' => 'required',
            'employe_level' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
     
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        if (isset($input['company_logo']) && !empty($input['company_logo'])) {
            $input['company_logo'] = ImageUpload::upload('uploads/companyLogo/', $request->company_logo);
        }
     
        $job = Job::create($input);

        $city = City::where('id', $job->city_id)->value('name') ?? NULL;
        $country = Country::where('id', $job->country_id)->value('name') ?? NULL;
        $state = State::where('id', $job->state_id)->value('name') ?? NULL;

        unset($job->city_id, $job->country_id, $job->state_id);

        $job['city_name'] = $city;
        $job['country_name'] = $country;
        $job['state_name'] = $state;
        $job->job_industry_name = !is_null($job->jobIndustry) ? $job->jobIndustry->name : '';
        unset($job->jobIndustry);
        $name = $job->getUser ? $job->getUser->name : '';
        $title = $job->company_name.' is looking for '.$job->role;

        createNotification($title, 'Job', $job->id, 'created');

        return $this->sendResponse($job, 'Job created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::with(['jobApplies' => function ($query) {
                        $query->select('id as job_applies_id', 'job_id', 'user_id')
                              ->with(['user' => function ($userQuery) {
                                  $userQuery->select('id', 'name', 'profile_pic', 'email'); // Select the user's ID and name
                              }]);
                    }])
                    ->where('id', $id)
                    ->where('status', 1)
                    ->first();

        $city = City::where('id', $job->city_id)->value('name') ?? NULL;
        $country = Country::where('id', $job->country_id)->value('name') ?? NULL;
        $state = State::where('id', $job->state_id)->value('name') ?? NULL;

        unset($job->city_id, $job->country_id, $job->state_id);
        $job->job_industry_name = !is_null($job->jobIndustry) ? $job->jobIndustry->name : '';
        unset($job->jobIndustry);   
        $job['city_name'] = $city;
        $job['country_name'] = $country;
        $job['state_name'] = $state;

        if (is_null($job)) {
            return $this->sendError('Job not found.');
        }else{
            return $this->sendResponse($job, 'Job retrieved successfully');
        }
    }

    public function jobByUser($userId)
    {
        $jobs = Job::select('jobs.id', 'jobs.user_id', 'jobs.job_industry_id', 'jobs.role', 'jobs.company_name', 'jobs.company_logo', 'jobs.start_salary', 'jobs.end_salary', 'jobs.type', 'jobs.work_type', 'jobs.address', 'jobs.skills', 'jobs.about', 'jobs.description', 'jobs.salary_type', 'jobs.employe_level', 'jobs.latitude', 'jobs.longitude', 'jobs.status', 'jobs.created_at', 'jobs.updated_at', 'jobs.updated_at', 'cities.name as city_name', 'states.name as state_name', 'countries.name as country_name')
                    ->leftJoin('cities', 'jobs.city_id', '=', 'cities.id')
                    ->leftJoin('states', 'jobs.state_id', '=', 'states.id')
                    ->leftJoin('countries', 'jobs.country_id', '=', 'countries.id')
                    ->where('jobs.user_id', $userId)
                    ->where('jobs.status', 1)
                    ->with(['jobApplies' => function ($query) {
                        $query->select('id as job_applies_id', 'job_id', 'user_id')
                              ->with(['user' => function ($userQuery) {
                                  $userQuery->select('id', 'name', 'profile_pic', 'email'); // Select the user's ID and name
                              }]);
                    }])
                    ->orderBy('created_at', 'desc')
                    ->get();
        foreach ($jobs as $key => $job) {
            $job->job_industry_name = !is_null($job->jobIndustry) ? $job->jobIndustry->name : '';
            unset($job->jobIndustry);
        }
        if ($jobs->count() > 0) {
            return $this->sendResponse($jobs, 'Job retrieved successfully.');
        }else{
            return $this->sendResponse([], 'Job not found.');
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request ,$id)
    {
        $input = $request->all();

        $job = Job::find($id);

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'job_industry_id' => 'required',
            'role' => 'required',
            'company_name' => 'required',
            'start_salary' => 'required',
            'end_salary' => 'required',
            'type' => 'required',
            'work_type' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'skills' => 'required',
            'about' => 'required',
            'description' => 'required',
            'salary_type' => 'required',
            'employe_level' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
     
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        if (isset($input['company_logo']) && !empty($input['company_logo'])) {
            $input['company_logo'] = ImageUpload::upload('uploads/companyLogo/', $request->company_logo);
        }

        $job->update($input);

        $job = Job::where('id', $id)
                   ->with(['jobApplies' => function ($query) {
                       $query->select('id as job_applies_id', 'job_id', 'user_id')
                             ->with(['user' => function ($userQuery) {
                                 $userQuery->select('id', 'name', 'profile_pic', 'email');
                             }]);
                   }])
                   ->first(); // Use first() instead of get()

        if ($job) { // Ensure that $job is not null
            $city = City::where('id', $job->city_id)->value('name') ?? null;
            $country = Country::where('id', $job->country_id)->value('name') ?? null;
            $state = State::where('id', $job->state_id)->value('name') ?? null;

            unset($job->city_id, $job->country_id, $job->state_id);

            $job->city_name = $city;
            $job->country_name = $country;
            $job->state_name = $state;
            $job->job_industry_name = !is_null($job->jobIndustry) ? $job->jobIndustry->name : '';
            unset($job->jobIndustry);
        }

        $name = $job->getUser ? $job->getUser->name : '';
        $title = $job->company_name.' is looking for '.$job->role;
        
        createNotification($title, 'Job', $job->id, 'update');
        return $this->sendResponse($job, 'Job Updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job = Job::find($id);
        if (!is_null($job)) {
            $job->delete();
            return $this->sendResponse([], 'Job Deleted successfully.');

        }else{
            return $this->sendError('Job not found.');            
        }
    }
}
