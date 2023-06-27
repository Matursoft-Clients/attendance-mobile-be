<?php

namespace App\Http\Controllers;

use App\Helpers\GetCurrentUserHelper;
use App\Http\Requests\DailyAttendance\DailyAttendanceRequest;
use App\Http\Requests\Presence\PresenceRequest;
use App\Models\CustomAttendanceLocation;
use App\Models\DailyAttendance;
use App\Models\Employee;
use App\Models\Setting;

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

    public function entry(PresenceRequest $request)
    {
        // Get value from request body
        $address             = $request->address;
        $latitude            = $request->latitude;
        $longitude           = $request->longitude;
        $reference_address   = $request->reference_address;
        $reference_latitude  = $request->reference_latitude;
        $reference_longitude = $request->reference_longitude;

        // Get Current User
        $employee = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

        $custom_attendance = CustomAttendanceLocation::where('employee_uuid', $employee->uuid)->first();

        $settings = Setting::select('SETTINGS.presence_entry_end')->first();

        if ($custom_attendance) {
            if (strtotime($custom_attendance->start_date) <= strtotime(date("Y-m-d H:i:s")) && strtotime($custom_attendance->end_date) >= strtotime(date("Y-m-d H:i:s"))) {
                DailyAttendance::create([
                    'employee_uuid'            => $employee->uuid,
                    'date'                     => date("Y-m-d H:i:s"),
                    'presence_entry_status'    => $settings->presence_entry_end >= date("H:i:s") ? 'on_time' : 'late',
                    'presence_entry_address'   => $address,
                    'presence_entry_latitude'  => $latitude,
                    'presence_entry_longitude' => $longitude,
                    'reference_address'        => $custom_attendance->presence_location_address,
                    'reference_latitude'       => $custom_attendance->presence_location_latitude,
                    'reference_longitude'      => $custom_attendance->presence_location_longitude,
                ]);

                return response()->json([
                    'code' => 200,
                    'msg' => "You have successfully do Presence Entry",
                ], 200);
            }
        }

        DailyAttendance::create([
            'employee_uuid'            => $employee->uuid,
            'date'                     => date("Y-m-d H:i:s"),
            'presence_entry_status'    => $settings->presence_entry_end >= date("H:i:s") ? 'on_time' : 'late',
            'presence_entry_address'   => $address,
            'presence_entry_latitude'  => $latitude,
            'presence_entry_longitude' => $longitude,
            'reference_address'        => $reference_address,
            'reference_latitude'       => $reference_latitude,
            'reference_longitude'      => $reference_longitude,
        ]);

        return response()->json([
            'code' => 200,
            'msg' => "You have successfully do Presence Entry",
        ], 200);
    }

    public function exit(PresenceRequest $request)
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
            'presence_exit_status'    => 'on_time',
            'presence_exit_address'   => $address,
            'presence_exit_latitude'  => $latitude,
            'presence_exit_longitude' => $longitude,
            'reference_address'        => 'makam',
            'reference_latitude'       => 18.23,
            'reference_longitude'      => 180,
        ]);
    }
}
