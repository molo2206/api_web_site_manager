<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function statistic()
    {
        $team = Team::where('deleted', 0)->get();
        $user = User::with('role')->where('role_id', '!=', null)->get();
        $membre = User::where('type', 'Member')->get();
        $parten = User::where('type', 'Partner')->get();
        $donation = Donation::select(
            DB::raw('sum(amount) as amount'),
            DB::raw("DATE_FORMAT(created_at,'%M') as month")
        )->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        $donation_year = Donation::select(
            DB::raw('sum(amount) as amount'),
            DB::raw("DATE_FORMAT(created_at,'%Y') as year")
        )
            ->groupBy('year')
            ->get();
        $data = [
            "team" => count($team),
            "users" => count($user),
            "members" => count($membre),
            "partners" => count($parten),
            "latest_users" => $user,
            "donation" => $donation,
            "donation_year" => $donation_year
        ];
        return response()->json([
            "data" => $data
        ], 200);
    }
}
