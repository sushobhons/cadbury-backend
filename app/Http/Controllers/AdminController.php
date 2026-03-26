<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use App\Models\PhotoUpload;
use App\Models\User;
use App\Models\Vote;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     * Show the admin login page.
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Handle an incoming authentication request for admin.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Authenticate the admin
        if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Regenerate session to prevent fixation attacks
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        // Get the total number of users
        $userCount = User::where('role', 'user')->count();
        $photoUploadCount = PhotoUpload::count();
        $voteCount = Vote::count();

        // Get the average ratings
        // $averageAroma = 0;
        // $averageTaste = 0;
        // $averageSmoothness = 0;
        // $averageRecommendation = 0;

        return view('dashboard', compact(
            'userCount',
            'photoUploadCount',
            'voteCount',
            // 'averageAroma',
            // 'averageTaste',
            // 'averageSmoothness',
            // 'averageRecommendation'
        ));
    }

    /**
     * Show the list of all users.
     */
    public function users()
    {
        $users = User::all();

        return view('users', compact('users'));
    }

    public function usersData()
    {
        $users = User::where('role', 'user')->get(); // Get users or apply your specific query


        return DataTables::of($users)
            ->addColumn('instagram_id', fn($user) => $user->instagram_id ?? 'N/A')
//            ->addColumn('taste', fn($user) => $user->taste ?? 'N/A')
//            ->addColumn('smoothness', fn($user) => $user->smoothness ?? 'N/A')
//            ->addColumn('recommendation', fn($user) => $user->recommendation ?? 'N/A')
            ->make(true);
    }

    public function exportUsers()
    {
        $users = User::select(['id', 'email', 'name', 'phone_number', 'instagram_id'])->where('role', 'user')->get();
        return response()->json($users);
    }

    /**
     * Show uploaded photo records.
     */
    public function photoUploads()
    {
        return view('photo-uploads');
    }

    /**
     * Data endpoint for uploaded photo list.
     */
    public function photoUploadsData()
    {
        $uploads = PhotoUpload::query()->latest('id');

        return DataTables::eloquent($uploads)
            ->addColumn('theme_name', fn (PhotoUpload $upload) => $upload->theme_name)
            ->addColumn('image_preview', fn (PhotoUpload $upload) => '<img src="'.e($upload->image_url).'" alt="Upload" class="h-16 w-16 rounded object-cover" />')
            ->addColumn('image_link', fn (PhotoUpload $upload) => '<a href="'.e($upload->image_url).'" target="_blank" class="text-indigo-600 hover:text-indigo-500">Open</a>')
            ->rawColumns(['image_preview', 'image_link'])
            ->make(true);
    }

    /**
     * Show vote records with per-shop totals.
     */
    public function votes()
    {
        $configuredShops = config('voting.shops', []);
        $dbCounts = Vote::query()
            ->selectRaw('shop_id, shop_name, COUNT(*) as total_votes')
            ->groupBy('shop_id', 'shop_name')
            ->orderByDesc('total_votes')
            ->get()
            ->keyBy('shop_id');

        $shopVoteCounts = collect($configuredShops)
            ->map(function (array $shop, int $shopId) use ($dbCounts) {
                $row = $dbCounts->get($shopId);

                return [
                    'shop_id' => $shopId,
                    'shop_name' => $shop['name'],
                    'shop_name_bn' => $shop['name_bn'] ?? null,
                    'total_votes' => $row ? (int) $row->total_votes : 0,
                ];
            })
            ->sortByDesc('total_votes')
            ->values();

        return view('votes', [
            'shopVoteCounts' => $shopVoteCounts,
        ]);
    }

    /**
     * Data endpoint for vote list.
     */
    public function votesData()
    {
        $votes = Vote::query()->latest('id');

        return DataTables::eloquent($votes)
            ->make(true);
    }

    /**
     * Destroy an authenticated admin session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
