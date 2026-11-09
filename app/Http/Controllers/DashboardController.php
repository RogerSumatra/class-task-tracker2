<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isGuru()) {
            $recentTasks = Task::with('completions')
                ->where('created_by', $user->id)
                ->latest()
                ->take(5)
                ->get();

            $totalTasks = Task::where('created_by', $user->id)->count();

            return view('dashboard.guru', compact('recentTasks', 'totalTasks'));
        }

        // Siswa dashboard
        $recentTasks = Task::with(['completions' => fn ($q) => $q->where('user_id', $user->id)])
            ->latest()
            ->take(5)
            ->get();

        $totalTasks = Task::count();
        $completedTasks = $user->taskCompletions()->count();

        return view('dashboard.siswa', compact('recentTasks', 'totalTasks', 'completedTasks'));
    }
}
