<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WholeSellCategory;
use DataTables;

class WholeSellCategoryController extends AdminThemeController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data  = WholeSellCategory::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data) {
                    $action = '<a href="'.route('whole-sell-category.edit', $data->id).'" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                    $action = $action.' <a class="btn btn-danger btn-flat btn-sm remove-crud" data-action="'. route('whole-sell-category.destroy',$data) .'" data-id="'. $data->id .'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    return $action;
                })
                ->editColumn('status', function($data){

                        if($data->status == 1){
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" checked value="1" data-action="'.route("whole-sell-category.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }else{
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" data-action="'.route("whole-sell-category.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }

                    return $switch;
                    
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.whole-sell-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.whole-sell-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
        ]);

        WholeSellCategory::create($input);
        
        notificationMsg('success', 'Whole Sell Category created sucessfully.');
        return redirect()->route('whole-sell-category.index');
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
    public function edit(string $id)
    {
        $wholeSellCategory = WholeSellCategory::find($id);
        return view('admin.whole-sell-category.edit', compact('wholeSellCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $wholeSellCategory = WholeSellCategory::find($id);
        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
        ]);

        $wholeSellCategory->update($input);
        
        notificationMsg('success', 'Whole Sell Category updated sucessfully.');
        return redirect()->route('whole-sell-category.index');
    }

    public function statusUpdate(Request $request)
    {
        $wholeSellCategory = WholeSellCategory::find($request->id);
        if (!is_null($wholeSellCategory)) {
            $wholeSellCategory->update(['status' => $request->status]);
        }

        $status = $request->status == 1 ? 'activated' : 'inactivated';
        notificationMsg('success', 'Whole Sell Category '.$status.' sucessfully.');

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $wholeSellCategory = WholeSellCategory::find($id);
        $wholeSellCategory->delete();
        
        notificationMsg('success', 'Whole Sell Category removed sucessfully.');
        return redirect()->route('whole-sell-category.index');
    }
}
