<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use DataTables;

class ProductCategoryController extends AdminThemeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data  = ProductCategory::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data) {
                    $action = '<a href="'.route('product-category.edit', $data->id).'" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                    $action = $action.' <a class="btn btn-danger btn-flat btn-sm remove-crud" data-action="'. route('product-category.destroy',$data) .'" data-id="'. $data->id .'" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    return $action;
                })
                ->editColumn('status', function($data){

                        if($data->status == 1){
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" checked value="1" data-action="'.route("product-category.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }else{
                            $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" data-action="'.route("product-category.status").'" data-id="'.$data->id.'"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                        }

                    return $switch;
                    
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.productCategory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.productCategory.create');
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

        ProductCategory::create($input);
        
        notificationMsg('success', 'Product Category created sucessfully.');
        return redirect()->route('product-category.index');
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
        $productCategory = ProductCategory::find($id);
        return view('admin.productCategory.edit', compact('productCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $productCategory = ProductCategory::find($id);
        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
        ]);

        $productCategory->update($input);
        
        notificationMsg('success', 'Country updated sucessfully.');
        return redirect()->route('product-category.index');
    }

    public function statusUpdate(Request $request)
    {
        $productCategory = ProductCategory::find($request->id);
        if (!is_null($productCategory)) {
            $productCategory->update(['status' => $request->status]);
        }

        $status = $request->status == 1 ? 'activated' : 'inactivated';
        notificationMsg('success', 'Country '.$status.' sucessfully.');

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $productCategory = ProductCategory::find($id);
        $productCategory->delete();
        
        notificationMsg('success', 'Country removed sucessfully.');
        return redirect()->route('product-category.index');
    }
}
