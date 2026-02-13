<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;

class NotificationController extends BaseController
{
    public function index(Request $request, $id)
    {
        $notification = Notification::where('created_or_update_by', $id)->get();

        return $this->sendResponse($notification, 'Notification retrieved successfully.');
    }
}
