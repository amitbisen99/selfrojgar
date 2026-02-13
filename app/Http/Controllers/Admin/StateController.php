<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use DataTables;

class StateController extends AdminThemeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data  = State::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data) {
                    $action = '<a href="'.route('state.edit', $data->id).'" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                    $action = $action.' <a class="btn btn-danger btn-flat btn-sm remove-crud" data-action="'. route('state.destroy',$data) .'" data-id="'. $data->id .'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    return $action;
                })
                ->editColumn('countries_id', function($data) {
                    $country = $data->country->name ?? '';
                    return $country;
                })
                ->editColumn('status', function($data){

                        if($data->status == 1){
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" checked value="1" data-action="'.route("state.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }else{
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" data-action="'.route("state.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }

                    return $switch;
                    
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.state.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $country = Country::pluck('name', 'id');
        return view('admin.state.create', compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'name' => 'required',
            'countries_id' => 'required',
        ]);

        State::create($input);
        
        notificationMsg('success', 'State created sucessfully.');
        return redirect()->route('state.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $state = State::find($id);
        $country = Country::pluck('name', 'id');
        return view('admin.state.edit', compact('state', 'country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $state = State::find($id);
        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
            'countries_id' => 'required',
        ]);

        $state->update($input);
        
        notificationMsg('success', 'State updated sucessfully.');
        return redirect()->route('state.index');
    }

    public function statusUpdate(Request $request)
    {
        $state = State::find($request->id);
        if (!is_null($state)) {
            $state->update(['status' => $request->status]);
        }

        $status = $request->status == 1 ? 'activated' : 'inactivated';
        notificationMsg('success', 'State '.$status.' sucessfully.');

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $State = State::find($id);
        $State->delete();
        
        notificationMsg('success', 'State removed sucessfully.');
        return redirect()->route('state.index');
    }
}
