<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomAttendanceLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CronjobController extends Controller
{
    public function cronRefreshCustomAttendance()
    {
        CustomAttendanceLocation::where('end_date', '<', Carbon::now()->subDays(1))->delete();

        return 'OK';
    }
}
