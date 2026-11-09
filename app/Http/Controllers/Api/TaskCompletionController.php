<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskCompletion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskCompletionController extends Controller
{
    /**
     * Mark a task as completed by the authenticated siswa.
     */
    public function store(Request $request, Task $task): JsonResponse
    {
        $userId = $request->user()->id;

        if ($task->isCompletedByUser($userId)) {
            return response()->json([
                'message' => 'Tugas sudah ditandai selesai sebelumnya.',
            ], 409);
        }

        TaskCompletion::create([
            'task_id' => $task->id,
            'user_id' => $userId,
            'completed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Tugas berhasil ditandai selesai.',
        ], 201);
    }

    /**
     * Unmark a task as completed by the authenticated siswa.
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        $userId = $request->user()->id;

        $deleted = TaskCompletion::where('task_id', $task->id)
            ->where('user_id', $userId)
            ->delete();

        if (! $deleted) {
            return response()->json([
                'message' => 'Tugas belum ditandai selesai.',
            ], 404);
        }

        return response()->json([
            'message' => 'Status selesai berhasil dibatalkan.',
        ]);
    }
}
