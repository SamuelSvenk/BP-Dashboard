<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Past builds') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($builds as $build)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-80 flex flex-col">
                        <div class="p-6 text-gray-900 dark:text-gray-100 flex-1 flex flex-col">
                            <h3 class="text-lg font-medium border-b pb-2 mb-3">Build #{{ $build->build_number }}</h3>
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Repository: {{ $build->repository }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Branch: {{ $build->branch }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Commit: {{ Str::limit($build->commit_hash, 10) }}</p>
                                    @if ($build->commit_message)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">Commit Message: {{ $build->commit_message }}</p>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    @if ($build->status === 'success')
                                        <div class="text-sm font-medium text-green-600 dark:text-green-400">Completed Successfully</div>
                                    @elseif ($build->status === 'failed')
                                        <div class="text-sm font-medium text-red-600 dark:text-red-400">Failed</div>
                                    @elseif ($build->status === 'partial')
                                        <div class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Partially Completed</div>
                                    @else
                                        <div class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ ucfirst($build->status) }}</div>
                                    @endif
                                    
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $build->completed_at ? $build->completed_at->format('F j, Y - H:i') : 'Not completed' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12">
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <p>No build history found</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if ($builds->count() > 0)
                <div class="mt-6">
                    {{ $builds->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>