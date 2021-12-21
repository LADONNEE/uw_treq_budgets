<?php

namespace App\Http\Controllers;

use App\Api\ApprovalApi;
use App\Models\EffortReport;

class ApprovalController extends Controller
{
    public function __invoke(EffortReport $effortReport)
    {
        $api = new ApprovalApi($effortReport, user());
        return response()->json($api->getReport());
    }
}
