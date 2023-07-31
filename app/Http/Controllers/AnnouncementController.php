<?php

namespace App\Http\Controllers;

use App\Helpers\GetCurrentUserHelper;
use App\Http\Requests\Announcement\ShowAnnouncementRequest;
use App\Http\Requests\Announcement\UUIDAnnouncementRequest;
use App\Http\Requests\Paginate\IndexPaginateRequest;
use App\Models\Announcement;
use App\Models\Employee;
use App\Models\EmployeeHasAnnouncementNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    public function index(IndexPaginateRequest $request)
    {
        try {
            $size = $request->s;

            $employee = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

            $announcements = Announcement::select(
                'ANNOUNCEMENTS.title',
                'ANNOUNCEMENTS.slug',
                DB::raw("CONCAT('" . config('app.web_url') . "announcement/', thumbnail) as thumbnail"),
                DB::raw('DATE_FORMAT(ANNOUNCEMENTS.created_at, "%Y-%m-%d %H:%i:%s") as created_at_format'),
                DB::raw('(SELECT COUNT(*) FROM EMPLOYEE_HAS_ANNOUNCEMENT_NOTIFICATIONS WHERE EMPLOYEE_HAS_ANNOUNCEMENT_NOTIFICATIONS.announcement_uuid = ANNOUNCEMENTS.uuid AND EMPLOYEE_HAS_ANNOUNCEMENT_NOTIFICATIONS.employee_uuid = ' . "'" . $employee->uuid . "') as not_read"),
            )
                ->orderBy('ANNOUNCEMENTS.created_at', 'DESC')
                ->paginate($size);

            return response()->json([
                'code' => 200,
                'msg' => "Here is Your Announcements",
                'data' => [
                    'announcements' => $announcements
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg' => 'Error, Please Contact Admin!',
            ], 400);
        }
    }

    public function show(ShowAnnouncementRequest $request)
    {
        try {
            $slug         = $request->slug;
            $announcement = Announcement::select(
                'ANNOUNCEMENTS.title',
                'ANNOUNCEMENTS.slug',
                DB::raw("CONCAT('" . config('app.web_url') . "announcement/', thumbnail) as thumbnail"),
                'ANNOUNCEMENTS.content',
                DB::raw('DATE_FORMAT(ANNOUNCEMENTS.created_at, "%Y-%m-%d %H:%i:%s") as created_at_format')
            )->where('slug', $slug)->first();

            return response()->json([
                'code' => 200,
                'msg'  => "Here is The Announcement",
                'data' => $announcement,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg'  => 'Error, Please Contact Admin!',
            ], 400);
        }
    }

    public function amountAnnouncementNotification(Request $request)
    {
        try {

            $employee = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

            $amount_announcement_notifications = EmployeeHasAnnouncementNotification::where('EMPLOYEE_HAS_ANNOUNCEMENT_NOTIFICATIONS.employee_uuid', $employee->uuid)->count();

            return response()->json([
                'code' => 200,
                'msg' => "Here is Your amount announcement notifications",
                'data' => [
                    'amount_announcement_notifications' => $amount_announcement_notifications
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg' => 'Error, Please Contact Admin!',
            ], 400);
        }
    }

    public function deleteAnnouncementNotification(UUIDAnnouncementRequest $request)
    {
        try {
            $uuid = $request->uuid;

            $employee = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

            $announcement = EmployeeHasAnnouncementNotification::where('EMPLOYEE_HAS_ANNOUNCEMENT_NOTIFICATIONS.employee_uuid', $employee->uuid)->where('EMPLOYEE_HAS_ANNOUNCEMENT_NOTIFICATIONS.announcement_uuid', $uuid)->first();

            if (!$announcement) {
                return response()->json([
                    'code' => 422,
                    'msg'  => "You already have delete announcement notification",
                ], 422);
            }

            $announcement->delete();

            return response()->json([
                'code' => 200,
                'msg'  => "You have successfully delete announcement notification",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg'  => 'Error, Please Contact Admin!',
            ], 400);
        }
    }
}
