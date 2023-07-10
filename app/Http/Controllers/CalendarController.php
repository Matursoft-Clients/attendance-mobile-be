<?php

namespace App\Http\Controllers;

use App\Helpers\GetCurrentUserHelper;
use App\Http\Requests\Calendar\CalendarRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getCalendar(CalendarRequest $request)
    {
        $date = $request->date;
        [$year, $month] = explode('-', $date);

        $employee = GetCurrentUserHelper::getCurrentUser($request->bearerToken(), new Employee());

        $calendars = DB::select("SELECT 
                                    DATE_FORMAT(DAILY_ATTENDANCES.date, '%d') as date,
                                    DAILY_ATTENDANCES.presence_entry_status,
                                    DAILY_ATTENDANCES.presence_entry_hour,
                                    DAILY_ATTENDANCES.presence_exit_status,
                                    DAILY_ATTENDANCES.presence_exit_hour
                                FROM DAILY_ATTENDANCES
                                    WHERE DAILY_ATTENDANCES.employee_uuid = '" . $employee->uuid . "'
                                        AND DATE_FORMAT(DAILY_ATTENDANCES.date, '%Y-%m') = '" . $date . "'");

        // Membuat tanggal awal dan akhir bulan berdasarkan parameter yang diberikan
        $startDate = date("$year-$month-01");
        $endDate = date("$year-$month-t", strtotime($startDate));

        // Melakukan perulangan dari tanggal awal hingga tanggal akhir
        $dailyDataPresences = [];
        for ($date = $startDate; $date <= $endDate; $date = date("Y-m-d", strtotime("$date +1 day"))) {
            $dayDate = date("d", strtotime($date));

            $absen = collect($calendars)->first(function ($item) use ($dayDate) {
                return $item->date == $dayDate;
            });

            $dailyDataPresences[] = [
                'day' => $dayDate,
                'absen' => $absen
            ];
        }

        return response()->json([
            'code' => 200,
            'msg' => "Here is your calendar",
            'date' => $dailyDataPresences
        ], 200);
    }
}
