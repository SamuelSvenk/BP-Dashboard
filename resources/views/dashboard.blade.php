@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats summary card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Overview</h3>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4 flex-1 min-w-[200px]">
                            <div class="inline-flex p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-500 dark:text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Total Builds</p>
                                <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $totalBuilds }}</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4 flex-1 min-w-[200px]">
                            <div class="inline-flex p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-500 dark:text-green-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
                                <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $completedBuilds }}</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4 flex-1 min-w-[200px]">
                            <div class="inline-flex p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-500 dark:text-yellow-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">In Progress</p>
                                <p class="text-xl font-semibold text-gray-700 dark:text-gray-200">{{ $inProgressBuilds }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Top row: Recent Builds and Performance -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Card 1 - Recent Builds -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-full">
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Recent Builds</h3>
                        <div class="flex-grow">
                            <div class="space-y-2 flex flex-col">
                                @forelse($latestBuilds as $build)
                                    <div class="flex items-center py-1">
                                        @if($build->status === 'success')
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                        @elseif($build->status === 'failed')
                                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                        @elseif($build->status === 'partial')
                                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                        @else
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                        @endif
                                        <a href="{{ route('past-builds') }}" class="flex-grow flex items-center hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                            <div class="flex-grow text-sm text-gray-600 dark:text-gray-400">
                                                Build #{{ $build->build_number }} <span class="mx-1">•</span> {{ $build->repository }}
                                            </div>
                                            <div class="flex items-center">
                                                <span class="mx-1 text-xs text-gray-400">•</span>
                                                <span class="text-xs text-gray-400">
                                                    {{ $build->completed_at ? $build->completed_at->diffForHumans() : 'In progress' }}
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                                        No builds found
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        @if($latestBuilds->count() > 0)
                            <div class="pt-3 mt-auto border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('past-builds') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                    View all builds →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Card 2 - Performance -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Performance</h3>
                        <div class="flex flex-col items-center">
                            <!-- Dynamic circular progress indicator -->
                            <div class="relative w-32 h-32 mb-4">
                                <svg class="w-full h-full" viewBox="0 0 100 100">
                                    <!-- Background circle -->
                                    <circle class="text-gray-200 dark:text-gray-700" stroke-width="8" stroke="currentColor" fill="transparent" r="45" cx="50" cy="50" />
                                    <!-- Progress circle (dynamic) -->
                                    @php
                                        $circumference = 2 * pi() * 45;
                                        $offset = $circumference - ($successRate / 100) * $circumference;
                                    @endphp
                                    <circle 
                                        class="text-indigo-600 dark:text-indigo-500" 
                                        stroke-width="8" 
                                        stroke="currentColor" 
                                        fill="transparent" 
                                        r="45" 
                                        cx="50" 
                                        cy="50" 
                                        stroke-dasharray="{{ $circumference }}" 
                                        stroke-dashoffset="{{ $offset }}" 
                                        stroke-linecap="round" 
                                    />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $successRate }}%</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Overall success rate</p>
                        </div>
                    </div>
                </div>
                
                <!-- Card 3 - Build Status Distribution -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Build Status Distribution</h3>
                        <div class="flex flex-col items-center">
                            <div class="h-64 w-full">
                                <canvas id="buildStatusChart"></canvas>
                            </div>
                            <!-- Legend -->
                            <div class="flex flex-wrap justify-center gap-4 mt-4">
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Success</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Partial</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Failed</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">In Progress</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom row: Additional charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card 4 - Weekly Build Activity -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Weekly Build Activity</h3>
                        <div class="h-64">
                            <canvas id="buildActivityChart"></canvas>
                        </div>
                        <div class="text-xs text-center text-gray-500 dark:text-gray-400 mt-2">
                            Build activity over the past 7 days
                        </div>
                    </div>
                </div>
                
                <!-- Card 5 - Branch Comparison -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Branch Activity</h3>
                        <div class="h-64">
                            <canvas id="branchChart"></canvas>
                        </div>
                        <div class="text-xs text-center text-gray-500 dark:text-gray-400 mt-2">
                            Number of builds per branch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Dashboard') }}
    </h2>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Register the plugin
            Chart.register(ChartDataLabels);
            
            // Build Status Distribution Chart
            const statusCtx = document.getElementById('buildStatusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: ['Success', 'Partial', 'Failed', 'In Progress'],
                    datasets: [{
                        data: [{{ $successBuilds }}, {{ $partialBuilds }}, {{ $failedBuilds }}, {{ $inProgressBuilds }}],
                        backgroundColor: [
                            '#10B981', // green-500
                            '#F59E0B', // yellow-500
                            '#EF4444', // red-500
                            '#3B82F6'  // blue-500
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            display: true,
                            color: '#fff',
                            font: {
                                weight: 'bold'
                            },
                            formatter: function(value, context) {
                                return value;
                            }
                        }
                    }
                }
            });

            // Weekly Build Activity Chart
            const activityCtx = document.getElementById('buildActivityChart').getContext('2d');
            new Chart(activityCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($dailyLabels) !!},
                    datasets: [{
                        label: 'Builds',
                        data: {!! json_encode($dailyBuilds) !!},
                        backgroundColor: '#3B82F6', // blue-500
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.parsed.y} builds`;
                                }
                            }
                        }
                    }
                }
            });
            
            // Branch Activity Chart
            const branchCtx = document.getElementById('branchChart').getContext('2d');
            new Chart(branchCtx, {
                type: 'bar', 
                data: {
                    labels: {!! json_encode($branches) !!},
                    datasets: [{
                        label: 'Builds',
                        data: {!! json_encode($branchBuilds) !!},
                        backgroundColor: '#8B5CF6', // purple-500
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'y', 
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.parsed.x} builds`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush