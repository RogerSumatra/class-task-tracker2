<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskWebController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Task::with(['creator', 'completions' => fn ($q) => $q->where('user_id', $user->id)]);

        if ($request->filled('search')) {
            $searchTerm = $request->string('search')->trim()->value();
            $query->where('title', 'like', "%{$searchTerm}%");
        }

        if ($request->filled('status')) {
            $userId = $user->id;
            if ($request->string('status')->exactly('selesai')) {
                $query->whereHas('completions', fn ($q) => $q->where('user_id', $userId));
            } elseif ($request->string('status')->exactly('belum')) {
                $query->whereDoesntHave('completions', fn ($q) => $q->where('user_id', $userId));
            }
        }

        $tasks = $query->latest()->paginate(10)->withQueryString();

        return view('tasks.index', compact('tasks'));
    }

    public function show(Request $request, Task $task): View
    {
        $task->load('creator', 'completions.user');
        $isCompleted = $task->isCompletedByUser($request->user()->id);

        return view('tasks.show', compact('task', 'isCompleted'));
    }

    public function toggleComplete(Request $request, Task $task): RedirectResponse
    {
        $user = $request->user();

        if (! $user->isSiswa()) {
            return back()->withErrors(['error' => 'Hanya siswa yang dapat menandai tugas selesai.']);
        }

        $completion = $task->completions()->where('user_id', $user->id)->first();

        if ($completion) {
            $completion->delete();
            $message = 'Status selesai berhasil dibatalkan.';
        } else {
            $task->completions()->create([
                'user_id' => $user->id,
                'completed_at' => now(),
            ]);
            $message = 'Tugas berhasil ditandai selesai.';
        }

        return back()->with('success', $message);
    }
}
