<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use DataTables;
use DB;

class BusinessesController extends AdminThemeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data  = business::with('businessCategory')->with('owner')
                   ->withAvg('ratings', 'rating')
                   ->orderBy('id', 'DESC')
                   ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_id', function($data){
                        $edit = !is_null($data->owner) ? $data->owner->name : '';
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
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" checked value="1" data-action="'.route("businesses.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }else{
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" data-action="'.route("businesses.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }

                    return $switch;

                })
                ->addColumn('action', function($data) {
                    $action = '<a href="'.route('businesses.show', $data).'" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    return $action;
                })
                ->rawColumns(['user_id', 'action', 'status'])
                ->make(true);
        }

        return view('admin.businesses.index');
    }

    public function show($id)
    {
        $business = business::find($id);
        return view('admin.businesses.show', compact('business'));
    }

    public function statusUpdate(Request $request)
    {
        $business = business::find($request->id);
        if (!is_null($business)) {
            $business->update(['status' => $request->status]);
        }

        $status = $request->status == 1 ? 'activated' : 'inactivated';
        notificationMsg('success', 'business '.$status.' sucessfully.');

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $business = business::find($id);
        if (!is_null($business)) {
            $business->delete();
        }

        notificationMsg('success', 'business deleted sucessfully.');

        return redirect()->back();
    }
}
