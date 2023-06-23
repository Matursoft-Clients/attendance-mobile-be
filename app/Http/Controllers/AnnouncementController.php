<?php

namespace App\Http\Controllers;

use App\Http\Requests\Announcement\IndexAnnouncementRequest;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index(IndexAnnouncementRequest $request)
    {
        try {
            $size = $request->s;

            $announcements = Announcement::select(
                'ANNOUNCEMENTS.title',
                'ANNOUNCEMENTS.slug',
            )->paginate($size);

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
}
