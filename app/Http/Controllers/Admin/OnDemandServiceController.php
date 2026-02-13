<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnDemandCategory;
use App\Models\OnDemandService;
use DataTables;
use DB;

class OnDemandServiceController extends AdminThemeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data  = OnDemandService::with(['onDemandCategory', 'serviceProvider'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_id', function($data){
                        $edit = !is_null($data->serviceProvider) ? $data->serviceProvider->name : '';
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
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" checked value="1" data-action="'.route("on-demand-service.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }else{
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" data-action="'.route("on-demand-service.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }

                    return $switch;
                    
                })
                ->addColumn('action', function($data) {
                    $action = '<a href="'.route('on-demand-service.show', $data).'" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    return $action;
                })
                ->rawColumns(['user_id', 'action', 'status'])
                ->make(true);
        }

        return view('admin.on-demand-service.index');
    }

    public function show($id)
    {
        $onDemandService = OnDemandService::find($id);
        return view('admin.on-demand-service.show', compact('onDemandService'));
    }

    public function statusUpdate(Request $request)
    {
        $onDemandService = OnDemandService::find($request->id);
        if (!is_null($onDemandService)) {
            $onDemandService->update(['status' => $request->status]);
        }
        
        $status = $request->status == 1 ? 'activated' : 'inactivated';
        notificationMsg('success', 'on Demand Service '.$status.' sucessfully.');

        return response()->json(['success' => true]);
    }
}
