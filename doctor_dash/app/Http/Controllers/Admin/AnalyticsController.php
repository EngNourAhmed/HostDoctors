<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(\App\Services\AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $stats = $this->analyticsService->getAnalyticsData();
        return view('admin.analytics.index', compact('stats'));
    }

    public function export()
    {
        $stats = $this->analyticsService->getAnalyticsData();
        $pdf = Pdf::loadView('pdfs.analytics_report', compact('stats'));
        return $pdf->download('analytics_report_' . now()->format('Ymd') . '.pdf');
    }

    public function userActivity(User $user)
    {
        $activities = Visit::where('user_id', $user->id)
            ->latest()
            ->limit(50)
            ->get()
            ->map(function ($visit) {
                return [
                    'date' => $visit->created_at->format('Y-m-d H:i:s'),
                    'time_ago' => $visit->created_at->diffForHumans(),
                    'page' => $this->analyticsService->getPageName($visit->path),
                    'ip' => $visit->ip_address,
                    'location' => $visit->country ?? 'Unknown',
                    'is_login' => $visit->is_login,
                ];
            });

        return response()->json([
            'user' => [
                'name' => $user->name,
                'role' => strtoupper($user->role),
            ],
            'activities' => $activities
        ]);
    }
}
