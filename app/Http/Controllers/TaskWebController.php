<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
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
}
