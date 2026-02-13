<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobIndustry;
use App\Http\Controllers\Api\BaseController as BaseController;

class JobIndustryController extends BaseController
{
    public function index(Request $request)
    {
        $jobIndustries = JobIndustry::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();
        return $this->sendResponse($jobIndustries, 'Job Industries retrieved successfully.');
    }
}
