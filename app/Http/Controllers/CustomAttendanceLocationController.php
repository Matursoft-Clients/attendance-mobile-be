<?php

namespace App\Http\Controllers;

use App\Helpers\GetCurrentUserHelper;
use App\Models\CustomAttendanceLocation;
use App\Models\Employee;
use Illuminate\Http\Request;

class CustomAttendanceLocationController extends Controller
{
    public function getCustomAttendance(Request $request)
    {
        try {
            // Get Current User
            $employee = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

            $custom_attendance = CustomAttendanceLocation::where('employee_uuid', $employee->uuid)->first();

            if ($custom_attendance) {
                if (strtotime($custom_attendance->start_date) <= strtotime(date("Y-m-d H:i:s")) && strtotime($custom_attendance->end_date) >= strtotime(date("Y-m-d H:i:s"))) {
                    return response()->json([
                        'code' => 200,
                        'msg'  => "Here is the Custom Attendance Location",
                        'data' => [
                            "custom_attendance_location" => $custom_attendance
                        ]
                    ], 200);
                }
            }

            return response()->json([
                'code' => 200,
                'msg' => "User Doesn't Have Custom Attendance Location",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg' => 'Error, Please Contact Admin!',
            ], 400);
        }
    }
}
