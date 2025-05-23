{{-- filepath: /root/blog/resources/views/past-builds.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Past builds') }}
            </h2>
            
            <!-- Status Filter -->
            <div class="flex space-x-2">
                <a href="{{ route('past-builds') }}" 
                   class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium
                   {{ !request()->has('status') ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                    All
                    <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-600">
                        {{ $counts['all'] }}
                    </span>
                </a>
                
                <a href="{{ route('past-builds', ['status' => 'success']) }}" 
                   class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium
                   {{ request('status') === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                    Success
                    <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-600">
                        {{ $counts['success'] }}
                    </span>
                </a>
                
                <a href="{{ route('past-builds', ['status' => 'failed']) }}"
                   class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium
                   {{ request('status') === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                    Failed
                    <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-600">
                        {{ $counts['failed'] }}
                    </span>
                </a>
                
                <a href="{{ route('past-builds', ['status' => 'partial']) }}"
                   class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium
                   {{ request('status') === 'partial' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                    Partial
                    <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full bg-gray-200 dark:bg-gray-600">
                        {{ $counts['partial'] }}
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Show current filter if active -->
            @if(request()->has('status'))
                <div class="mb-4 px-4">
                    <div class="flex items-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mr-2">
                            Showing {{ request('status') }} builds
                        </p>
                        <a href="{{ route('past-builds') }}" class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                            Clear filter ×
                        </a>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($builds as $build)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-80 flex flex-col">
                        <div class="p-6 text-gray-900 dark:text-gray-100 flex-1 flex flex-col">
                            <h3 class="text-lg font-medium border-b pb-2 mb-3">
                                <a href="{{ route('builds.show', $build) }}" class="hover:text-blue-500 transition">
                                    Build #{{ $build->build_number }}
                                </a>
                            </h3>
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Repository: {{ $build->repository }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Branch: {{ $build->branch }}</p>
                                    
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 group flex items-center">
                                        Commit: 
                                        <span class="mr-1">{{ Str::limit($build->commit_hash, 10) }}</span>
                                        <button 
                                            onclick="navigator.clipboard.writeText('{{ $build->commit_hash }}').then(() => {
                                                this.querySelector('span').textContent = 'Copied!';
                                                setTimeout(() => { this.querySelector('span').textContent = 'Copy'; }, 2000);
                                            })" 
                                            class="inline-flex items-center text-xs text-gray-400 hover:text-blue-500 transition"
                                            title="{{ $build->commit_hash }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5" />
                                            </svg>
                                            <span>Copy</span>
                                        </button>
                                    </p>
                                    
                                    @if ($build->commit_message)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
                                          Commit Message: {{ $build->commit_message }}
                                        </p>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <div class="inline-flex px-2 py-1 rounded-full text-xs font-medium
                                        {{ $build->status === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                        {{ $build->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                                        {{ $build->status === 'partial' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}">
                                        {{ ucfirst($build->status) }}
                                    </div>
                                    
                                    @if($build->completed_at)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Completed: {{ \Carbon\Carbon::parse($build->completed_at)->format('F j, Y - H:i') }}
                                    </div>
                                    @endif
                                    
                                    <a href="{{ route('builds.show', $build) }}" class="mt-3 inline-block text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        View details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-12">
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <p>
                                @if(request()->has('status'))
                                    No {{ request('status') }} builds found
                                @else
                                    No past builds found
                                @endif
                            </p>
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