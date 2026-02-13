<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use DataTables;

class JobController extends AdminThemeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data  = Job::select('*')->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_id', function($data){
                        $edit = $data->getUser->name;
                    return '<div class="table-actions"> '. $edit .' </div>';

                })
                ->editColumn('created_at', function($data){
                        $date = $data->created_at->format('Y-m-d H:i:s');
                    return $date;

                })
                ->editColumn('status', function($data){

                        if($data->status == 1){
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" checked value="1" data-action="'.route("job.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }else{
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" data-action="'.route("job.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }

                    return $switch;

                })
                ->addColumn('action', function($data) {
                    $action = '<a href="'.route('job.show', $data).'" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    return $action;
                })
                ->rawColumns(['user_id', 'action', 'status'])
                ->make(true);
        }

        return view('admin.job.index');
    }

    public function show($id)
    {
        $job = Job::select('jobs.*', 'cities.name as city_name', 'states.name as state_name', 'countries.name as country_name')
                        ->leftJoin('cities', 'jobs.city_id', '=', 'cities.id')
                        ->leftJoin('states', 'jobs.state_id', '=', 'states.id')
                        ->leftJoin('countries', 'jobs.country_id', '=', 'countries.id')
                        ->where('jobs.id', $id)
                        ->first();
        return view('admin.job.show', compact('job'));
    }

    public function statusUpdate(Request $request)
    {
        $job = Job::find($request->id);
        if (!is_null($job)) {
            $job->update(['status' => $request->status]);
        }

        $status = $request->status == 1 ? 'activated' : 'inactivated';
        notificationMsg('success', 'Job '.$status.' sucessfully.');

        return response()->json(['success' => true]);
    }
}
