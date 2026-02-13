<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use DataTables;

class PaymentController extends AdminThemeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data  = Payment::select('*')->orderBy('id', 'DESC');
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
                ->addColumn('action', function($data) {
                    $action = '<a href="'.route('payment.show', $data).'" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    return $action;
                })
                ->rawColumns(['user_id', 'action'])
                ->make(true);
        }

        return view('admin.payment.index');
    }

    public function show(Payment $payment)
    {
        return view('admin.payment.show', compact('payment'));
    }

    public function statusUpdate(Request $request)
    {
        $payment = Payment::find($request->id);
        if (!is_null($payment)) {
            $payment->update(['status' => $request->status]);
        }
        return response()->json(['success' => true]);
    }
}
    }
}
