<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBadgeController extends Controller
{
    public function index(): View
    {
        $badges  = UserBadge::with('user')->latest()->get()->groupBy('badge_name')->map->first();
        $members = User::where('role', 'member')->orderBy('name')->get();

        return view('admin.badges', [
            'badges'    => $badges,
            'members'   => $members,
            'storeName' => config('catalog.store_name'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'badge_name' => ['required', 'string', 'max:100'],
            'badge_icon' => ['nullable', 'string', 'max:50'],
            'badge_type' => ['nullable', 'string', 'max:50'],
            'criteria'   => ['nullable', 'string', 'max:500'],
        ]);

        // Store a template badge (no user assigned)
        UserBadge::create([
            'user_id'    => User::where('role', 'member')->first()?->id ?? 1,
            'badge_name' => $data['badge_name'],
            'badge_icon' => $data['badge_icon'] ?? '🏆',
            'badge_type' => $data['badge_type'] ?? 'community',
            'criteria'   => $data['criteria'] ?? null,
        ]);

        return back()->with('success', 'Badge baru berhasil dibuat.');
    }

    public function assign(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id'  => ['required', 'exists:users,id'],
            'badge_id' => ['required', 'exists:user_badges,id'],
        ]);

        $template = UserBadge::findOrFail($data['badge_id']);

        $existing = UserBadge::where('user_id', $data['user_id'])
            ->where('badge_name', $template->badge_name)
            ->first();

        if ($existing) {
            return back()->with('success', 'User sudah memiliki badge ini.');
        }

        UserBadge::create([
            'user_id'    => $data['user_id'],
            'badge_name' => $template->badge_name,
            'badge_icon' => $template->badge_icon,
            'badge_type' => $template->badge_type,
            'criteria'   => $template->criteria,
        ]);

        $user = User::find($data['user_id']);
        $user?->addPoints(50, 'badge_awarded', 'Mendapat badge: ' . $template->badge_name);

        return back()->with('success', 'Badge berhasil diberikan ke member.');
    }

    public function destroy(UserBadge $badge): RedirectResponse
    {
        $badge->delete();
        return back()->with('success', 'Badge berhasil dihapus.');
    }
}
