<?php

namespace App\Http\Controllers;

use App\Helpers\GetCurrentUserHelper;
use App\Http\Requests\DailyAttendance\DailyAttendanceRequest;
use App\Http\Requests\Presence\EntryPresenceRequest;
use App\Models\DailyAttendance;
use App\Models\Employee;

class DailyAttendanceController extends Controller
{
    public function dailyAttendances(DailyAttendanceRequest $request)
    {
        try {
            $date = $request->date;

            // Get Current User
            $employee = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

            $daily_attendance = DailyAttendance::where('employee_uuid', $employee->uuid)->whereDate('date', $date)->first();

            if (!$daily_attendance) {
                $new_date = strtotime($date);
                $new_date = date('d F Y', $new_date);
                return response()->json([
                    'code' => 200,
                    'msg' => "The User hasn't Attended on " . $new_date,
                ], 200);
            }

            return response()->json([
                'code' => 200,
                'msg'  => "Here is the Daily Attendance",
                'data' => [
                    "attendance" => $daily_attendance
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg'  => 'Error, Please Contact Admin!',
            ], 400);
        }
    }

    public function entry(EntryPresenceRequest $request)
    {
        // Get value from request body
        $latitude  = $request->latitude;
        $longitude = $request->longitude;
        $address   = $request->address;

        // Get Current User
        $employee = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

        DailyAttendance::create([
            'employee_uuid'            => $employee->uuid,
            'date'                     => date("Y-m-d H:i:s"),
            'presence_entry_status'    => 'on_time',
            'presence_entry_address'   => $address,
            'presence_entry_latitude'  => $latitude,
            'presence_entry_longitude' => $longitude,
            'reference_address'        => 'makam',
            'reference_latitude'       => 18.23,
            'reference_longitude'      => 180,
        ]);
    }
}
