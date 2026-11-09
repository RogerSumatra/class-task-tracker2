<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    /**
     * List tasks with search, filter by completion status, and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Task::with('creator', 'completions');

        // Search by title
        if ($request->filled('search')) {
            $searchTerm = $request->string('search')->trim()->value();
            $query->where('title', 'like', "%{$searchTerm}%");
        }

        // Filter by completion status for the authenticated user
        if ($request->filled('status')) {
            $userId = $request->user()->id;
            if ($request->string('status')->exactly('selesai')) {
                $query->whereHas('completions', fn ($q) => $q->where('user_id', $userId));
            } elseif ($request->string('status')->exactly('belum')) {
                $query->whereDoesntHave('completions', fn ($q) => $q->where('user_id', $userId));
            }
        }

        $tasks = $query->latest()->paginate(10)->withQueryString();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        $task->load('creator');

        return response()->json([
            'message' => 'Tugas berhasil dibuat.',
            'task' => new TaskResource($task),
        ], 201);
    }

    /**
     * Display the specified task.
     */
    public function show(Request $request, Task $task): JsonResponse
    {
        $task->load('creator', 'completions');

        return response()->json([
            'task' => new TaskResource($task),
        ]);
    }

    /**
     * Update the specified task.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());
        $task->load('creator');

        return response()->json([
            'message' => 'Tugas berhasil diperbarui.',
            'task' => new TaskResource($task),
        ]);
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'message' => 'Tugas berhasil dihapus.',
        ]);
    }
}
