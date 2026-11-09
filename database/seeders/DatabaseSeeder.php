<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create fixed sample accounts
        $guru1 = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'guru@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Guru,
        ]);

        $guru2 = User::factory()->create([
            'name' => 'Siti Rahayu',
            'email' => 'guru2@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Guru,
        ]);

        $siswa1 = User::factory()->create([
            'name' => 'Andi Pratama',
            'email' => 'siswa@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Siswa,
        ]);

        $siswa2 = User::factory()->create([
            'name' => 'Dewi Lestari',
            'email' => 'siswa2@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Siswa,
        ]);

        // Create extra random users
        $extraGurus = User::factory()->guru()->count(2)->create();
        $extraSiswas = User::factory()->siswa()->count(5)->create();

        $allSiswas = collect([$siswa1, $siswa2])->merge($extraSiswas);

        // Create tasks for guru1
        $tasks1 = Task::factory()->count(5)->create([
            'created_by' => $guru1->id,
            'class_name' => 'Kelas 10A',
        ]);

        // Create tasks for guru2
        $tasks2 = Task::factory()->count(5)->create([
            'created_by' => $guru2->id,
            'class_name' => 'Kelas 11B',
        ]);

        // Create tasks for extra gurus
        foreach ($extraGurus as $guru) {
            Task::factory()->count(3)->create([
                'created_by' => $guru->id,
            ]);
        }

        $allTasks = $tasks1->merge($tasks2);

        // Create completions — siswa1 and siswa2 complete some tasks
        foreach ($allTasks->take(3) as $task) {
            TaskCompletion::create([
                'task_id' => $task->id,
                'user_id' => $siswa1->id,
                'completed_at' => now(),
            ]);
        }

        foreach ($allTasks->take(2) as $task) {
            TaskCompletion::create([
                'task_id' => $task->id,
                'user_id' => $siswa2->id,
                'completed_at' => now(),
            ]);
        }
    }
}
