<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function index()
    {
        try {
            $banners = Banner::select(
                'BANNERS.name',
                DB::raw("CONCAT('" . config('app.base_url') . "/storage/BANNERS/image/', image) as image"),
            )->get();

            return response()->json([
                'code' => 200,
                'msg' => "Here is Your Banners",
                'data' => [
                    'banners' => $banners
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
