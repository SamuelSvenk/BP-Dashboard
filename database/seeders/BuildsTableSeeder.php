<?php

namespace Database\Seeders;

use App\Models\Build;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BuildsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample builds
        $statuses = ['success', 'failed', 'partial', 'in_progress', 'queued'];
        $repositories = ['main-service', 'auth-service', 'api-gateway', 'frontend-app', 'notification-service'];
        $branches = ['main', 'develop', 'feature/login', 'feature/api', 'bugfix/auth', 'release/v1.2'];
        
        for ($i = 1; $i <= 20; $i++) {
            $status = $statuses[array_rand($statuses)];
            $completedAt = null;
            
            if ($status !== 'in_progress' && $status !== 'queued') {
                $completedAt = Carbon::now()->subHours(rand(1, 48));
            }
            
            Build::create([
                'build_number' => '10' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'repository' => $repositories[array_rand($repositories)],
                'branch' => $branches[array_rand($branches)],
                'commit_hash' => substr(md5(rand()), 0, 10),
                'commit_message' => 'Update ' . ['readme', 'configuration', 'dependencies', 'tests', 'api'][rand(0, 4)] . ' for better performance',
                'status' => $status,
                'completed_at' => $completedAt,
                'created_at' => Carbon::now()->subHours(rand(49, 100)),
                'updated_at' => $completedAt ?? Carbon::now()->subMinutes(rand(5, 60)),
                'logs' => 'Build logs for build number 10' . str_pad($i, 2, '0', STR_PAD_LEFT),
            ]);
        }
    }
}