<?php

namespace App\Http\Controllers;

use App\Http\Requests\Announcement\ShowAnnouncementRequest;
use App\Http\Requests\Paginate\IndexPaginateRequest;
use App\Models\Announcement;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    public function index(IndexPaginateRequest $request)
    {
        try {
            $size = $request->s;

            $announcements = Announcement::select(
                'ANNOUNCEMENTS.title',
                'ANNOUNCEMENTS.slug',
                DB::raw("CONCAT('" . config('app.web_url') . "announcement/', thumbnail) as thumbnail"),
                DB::raw('DATE_FORMAT(ANNOUNCEMENTS.created_at, "%Y-%m-%d %H:%i:%s") as created_at_format')
            )->orderBy('ANNOUNCEMENTS.created_at', 'DESC')
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
}
