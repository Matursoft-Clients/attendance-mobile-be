<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        try {
            $settings = Setting::select(
                'SETTINGS.office_name',
                DB::raw("CONCAT('" . config('app.web_url') . "setting/', office_logo) as office_logo"),
                'SETTINGS.presence_entry_start',
                'SETTINGS.presence_entry_end',
                'SETTINGS.presence_exit',
                'SETTINGS.presence_meter_radius',
                'SETTINGS.mobile_app_version',
                'SETTINGS.play_store_url',
            )->first();

            return response()->json([
                'code' => 200,
                'msg'  => "Here is Your Settings",
                'data' => [
                    'settings' => $settings
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 400,
                'msg'  => 'Error, Please Contact Admin!',
            ], 400);
        }
    }
}
