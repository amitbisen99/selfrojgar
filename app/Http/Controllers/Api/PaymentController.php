<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Validator;

class PaymentController extends BaseController
{
    public function create(Request $request)
    {
        $input = $request->all();
     
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'payment_id' => 'required',
            'amount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
     
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }
     
        $payment = Payment::create($input);
        return $this->sendResponse($payment, 'payment created successfully.');
    }
}
