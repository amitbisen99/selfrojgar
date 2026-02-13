<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseBusiness;
use DataTables;

class FranchiseBusinessController extends AdminThemeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data  = FranchiseBusiness::with(['owner', 'franchiseCategory', 'city', 'state', 'country'])->orderBy('id', 'DESC')->get();
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
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" checked value="1" data-action="'.route("franchise-business.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }else{
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" data-action="'.route("franchise-business.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }

                    return $switch;

                })
                ->addColumn('action', function($data) {
                    $action = '<a href="'.route('franchise-business.show', $data).'" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    return $action;
                })
                ->rawColumns(['user_id', 'action', 'status'])
                ->make(true);
        }

        return view('admin.franchise-business.index');
    }

    public function show($id)
    {
        $franchiseBusiness = FranchiseBusiness::find($id);
        return view('admin.franchise-business.show', compact('franchiseBusiness'));
    }

    public function statusUpdate(Request $request)
    {
        $franchiseBusiness = FranchiseBusiness::find($request->id);
        if (!is_null($franchiseBusiness)) {
            $franchiseBusiness->update(['status' => $request->status]);
        }

        $status = $request->status == 1 ? 'activated' : 'inactivated';
        notificationMsg('success', 'Franchise '.$status.' sucessfully.');

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $franchiseBusiness = FranchiseBusiness::find($id);
        if (!is_null($franchiseBusiness)) {
            $franchiseBusiness->delete();
        }

        notificationMsg('success', 'Franchise deleted sucessfully.');

        return redirect()->back();
    }
}
