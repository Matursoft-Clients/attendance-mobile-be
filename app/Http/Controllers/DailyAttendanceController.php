<?php

namespace App\Http\Controllers;

use App\Helpers\GetCurrentUserHelper;
use App\Http\Requests\DailyAttendance\DailyAttendanceRequest;
use App\Http\Requests\Presence\EntryPresenceRequest;
use App\Http\Requests\Presence\ExitPresenceRequest;
use App\Models\Branch;
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
                    'msg'  => "The User hasn't Attended on " . $new_date,
                    'data' => [
                        'attendance' => [
                            'status' => false
                        ]
                    ]
                ], 200);
            }

            $daily_attendance['status'] = true;

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

        // Get Current User
        $employee = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

        $daily_attendance = DailyAttendance::where('employee_uuid', $employee->uuid)->whereDate('date', date("Y-m-d"))->first();

        // Cek Data is Already Exist or Not
        if ($daily_attendance) {
            return response()->json([
                'code' => 200,
                'msg'  => "You have Already Done Presence Entry",
            ], 200);
        }

        // Get Employee in Custom Attendance
        $custom_attendance = CustomAttendanceLocation::where('employee_uuid', $employee->uuid)
            ->whereDate('start_date', '<=', date("Y-m-d H:i:s"))
            ->whereDate('end_date', '>=', date("Y-m-d H:i:s"))
            ->first();

        // Get Default Setting for Attendance
        $settings = Setting::first();

        // Get Branch 
        $branch = Branch::where('uuid', $employee->branch_uuid)->first();

        if ($custom_attendance) {
            if (strtotime($custom_attendance->start_date) <= strtotime(date("Y-m-d H:i:s")) && strtotime($custom_attendance->end_date) >= strtotime(date("Y-m-d H:i:s"))) {
                DailyAttendance::create([
                    'employee_uuid'            => $employee->uuid,
                    'date'                     => date("Y-m-d H:i:s"),
                    'presence_entry_status'    => $settings->presence_entry_start > date("H:i:s") ? 'not_valid' : ($settings->presence_entry_end >= date("H:i:s") ? 'on_time' : 'late'),
                    'presence_entry_hour'      => date("H:i"),
                    'presence_entry_latitude'  => $latitude,
                    'presence_entry_longitude' => $longitude,
                    'reference_address'        => $custom_attendance->presence_location_address,
                    'reference_latitude'       => $custom_attendance->presence_location_latitude,
                    'reference_longitude'      => $custom_attendance->presence_location_longitude,
                ]);

                return response()->json([
                    'code' => 200,
                    'msg'  => "You have Successfully Do Presence Entry",
                ], 200);
            }
        }


        DailyAttendance::create([
            'employee_uuid'            => $employee->uuid,
            'date'                     => date("Y-m-d H:i:s"),
            'presence_entry_status'    => $settings->presence_entry_start > date("H:i:s") ? 'not_valid' : ($settings->presence_entry_end >= date("H:i:s") ? 'on_time' : 'late'),
            'presence_entry_hour'      => date("H:i"),
            'presence_entry_latitude'  => $latitude,
            'presence_entry_longitude' => $longitude,
            'reference_address'        => $branch->presence_location_address,
            'reference_latitude'       => $branch->presence_location_latitude,
            'reference_longitude'      => $branch->presence_location_longitude,
        ]);

        return response()->json([
            'code' => 200,
            'msg'  => "You have Successfully Do Presence Entry",
        ], 200);
    }

    public function exit(ExitPresenceRequest $request)
    {
        // Get value from request body
        $latitude  = $request->latitude;
        $longitude = $request->longitude;

        // Get Current User
        $employee = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

        $settings = Setting::select('SETTINGS.presence_exit')->first();

        $daily_attendance = DailyAttendance::where('employee_uuid', $employee->uuid)->whereDate('date', date("Y-m-d"))->first();

        if (!$daily_attendance) {
            return response()->json([
                'code' => 200,
                'msg'  => "You have to Do Presence Entry First",
            ], 200);
        }

        $daily_attendance->update([
            'presence_exit_status'    => $settings->presence_exit > date("H:i:s") ? 'early' : 'on_time',
            'presence_exit_hour'      => date("H:i"),
            'presence_exit_latitude'  => $latitude,
            'presence_exit_longitude' => $longitude,
        ]);

        return response()->json([
            'code' => 200,
            'msg'  => "You have Successfully Do Presence Exit",
        ], 200);
    }

    public function cronjob()
    {
        $employees_uuid = Employee::pluck('uuid')->all();

        for ($i = 0; $i < count($employees_uuid); $i++) {
            $daily_attendance = DailyAttendance::where('employee_uuid', $employees_uuid)->whereDate('date', date("Y-m-d"))->first();

            if (!$daily_attendance) {
                DailyAttendance::create([
                    'employee_uuid'         => $employees_uuid,
                    'date'                  => date("Y-m-d H:i:s"),
                    'presence_entry_status' => 'not_present',
                    'presence_exit_status'  => 'not_present',
                ]);
            }

            if ($daily_attendance->presence_exit_status === null) {
                $daily_attendance->update([
                    'presence_exit_status' => 'not_present',
                ]);
            }
        }

        return response()->json([
            'code' => 200,
            'msg' => "Presence cronjob has been successfully performed.",
        ], 200);
    }
}
