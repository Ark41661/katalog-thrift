<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BadgeController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $badges = $user->badges;
        $logs = $user->activityLogs()->latest()->take(50)->get();

        return view('member.badges', [
            'user'      => $user,
            'badges'    => $badges,
            'logs'      => $logs,
            'storeName' => config('catalog.store_name'),
        ]);
    }
}
