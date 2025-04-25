<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Build #{{ $build->build_number }}
            </h2>
            <div>
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Build Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $build->repository }} / {{ $build->branch }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Build #{{ $build->build_number }}
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @if(in_array($build->status, ['success', 'failed', 'partial']))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $build->status === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                                    {{ $build->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                                    {{ $build->status === 'partial' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : '' }}">
                                    {{ ucfirst($build->status) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ $build->status === 'queued' ? 'Queued' : 'In Progress' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Build Information -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Build Information</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Repository</h5>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $build->repository }}</p>
                                </div>
                                
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Branch</h5>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $build->branch }}</p>
                                </div>
                                
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Commit Hash</h5>
                                    <div class="mt-1 flex items-center">
                                        <code class="bg-gray-100 dark:bg-gray-700 p-2 rounded font-mono text-sm text-gray-900 dark:text-gray-100 mr-2">{{ $build->commit_hash }}</code>
                                        <button 
                                            onclick="navigator.clipboard.writeText('{{ $build->commit_hash }}').then(() => {
                                                this.querySelector('span').textContent = 'Copied!';
                                                setTimeout(() => { this.querySelector('span').textContent = 'Copy'; }, 2000);
                                            })" 
                                            class="inline-flex items-center text-sm text-gray-600 hover:text-blue-500 transition"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5" />
                                            </svg>
                                            <span>Copy</span>
                                        </button>
                                    </div>
                                </div>
                                
                                @if($build->commit_message)
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Commit Message</h5>
                                    <p class="mt-1 bg-gray-100 dark:bg-gray-700 p-4 rounded text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $build->commit_message }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Timeline</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Started</h5>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $build->created_at->format('F j, Y - H:i:s') }}</p>
                                </div>
                                
                                @if($build->completed_at)
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</h5>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($build->completed_at)->format('F j, Y - H:i:s') }}</p>
                                </div>
                                
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</h5>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($build->created_at)->diffForHumans(\Carbon\Carbon::parse($build->completed_at), ['short' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}
                                    </p>
                                </div>
                                @elseif(in_array($build->status, ['in_progress', 'queued']))
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h5>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">
                                        {{ $build->status === 'queued' ? 'Waiting in queue' : 'Currently building...' }}
                                    </p>
                                </div>
                                
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Time elapsed</h5>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($build->created_at)->diffForHumans(null, ['short' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Build Logs -->
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Build Logs</h4>
                        
                        @if(isset($build->logs))
                            <div class="bg-gray-100 dark:bg-gray-700 rounded text-sm font-mono text-gray-900 dark:text-gray-100 overflow-hidden">
                                <div class="log-container p-4 overflow-x-auto">
                                    @php
                                        $logs = $build->logs;
                                        
                                        // Format section headers (without emojis)
                                        $patterns = [
                                            '/Build start log generated at (.+)/i',
                                            '/Build completed at (.+)/i',
                                            '/Tests completed at (.+)/i',
                                            '/Accessibility testing completed at (.+)/i',
                                            '/Deployment completed at (.+)/i',
                                            '/Final log generated at (.+)/i',
                                        ];
                                        
                                        $replacements = [
                                            '<div class="log-section"><span class="text-blue-600 dark:text-blue-400 font-semibold">Build Started</span> <span class="text-gray-600 dark:text-gray-400">$1</span></div>',
                                            '<div class="log-section mt-4"><span class="text-green-600 dark:text-green-400 font-semibold">Build Completed</span> <span class="text-gray-600 dark:text-gray-400">$1</span></div>',
                                            '<div class="log-section mt-4"><span class="text-yellow-600 dark:text-yellow-400 font-semibold">Tests Completed</span> <span class="text-gray-600 dark:text-gray-400">$1</span></div>',
                                            '<div class="log-section mt-4"><span class="text-purple-600 dark:text-purple-400 font-semibold">Accessibility Testing Completed</span> <span class="text-gray-600 dark:text-gray-400">$1</span></div>',
                                            '<div class="log-section mt-4"><span class="text-indigo-600 dark:text-indigo-400 font-semibold">Deployment Completed</span> <span class="text-gray-600 dark:text-gray-400">$1</span></div>',
                                            '<div class="log-section mt-4"><span class="text-pink-600 dark:text-pink-400 font-semibold">Final Log Generated</span> <span class="text-gray-600 dark:text-gray-400">$1</span></div>',
                                        ];
                                        
                                        // Format JSON for accessibility report
                                        if(preg_match('/Accessibility JSON Report:\s*({.+})/s', $logs, $matches)) {
                                            try {
                                                $json = $matches[1];
                                                $jsonData = json_decode($json, true);
                                                
                                                if($jsonData) {
                                                    $jsonHTML = '<div class="mt-2 p-3 bg-white dark:bg-gray-800 rounded border border-gray-300 dark:border-gray-600">';
                                                    $jsonHTML .= '<div class="flex items-center gap-2 mb-2">';
                                                    $jsonHTML .= '<h5 class="font-semibold">Accessibility Summary:</h5>';
                                                    
                                                    // Summary badges with colored text instead of background
                                                    $jsonHTML .= '<span class="px-2 py-1 text-xs font-semibold text-blue-700 dark:text-blue-400">Total: ' . $jsonData['total'] . '</span>';
                                                    $jsonHTML .= '<span class="px-2 py-1 text-xs font-semibold text-green-700 dark:text-green-400">Passes: ' . $jsonData['passes'] . '</span>';
                                                    $jsonHTML .= '<span class="px-2 py-1 text-xs font-semibold text-red-700 dark:text-red-400">Errors: ' . $jsonData['errors'] . '</span>';
                                                    $jsonHTML .= '</div>';
                                                    
                                                    if(isset($jsonData['results']) && is_array($jsonData['results'])) {
                                                        foreach($jsonData['results'] as $url => $issues) {
                                                            $jsonHTML .= '<div class="mb-2">';
                                                            $jsonHTML .= '<h6 class="font-medium text-xs mb-1">URL: <span class="font-normal">' . htmlspecialchars($url) . '</span></h6>';
                                                            
                                                            foreach($issues as $issue) {
                                                                $jsonHTML .= '<div class="ml-4 mb-4 p-2 bg-gray-50 dark:bg-gray-700 rounded text-xs">';
                                                                $jsonHTML .= '<div class="flex items-center gap-1">';
                                                                $jsonHTML .= '<span class="inline-block w-2 h-2 rounded-full ' . ($issue['type'] == 'error' ? 'bg-red-500' : 'bg-yellow-500') . '"></span>';
                                                                $jsonHTML .= '<span class="font-medium">' . htmlspecialchars($issue['code']) . '</span>';
                                                                $jsonHTML .= '</div>';
                                                                $jsonHTML .= '<p class="ml-3 mt-1">' . htmlspecialchars($issue['message']) . '</p>';
                                                                $jsonHTML .= '<div class="ml-3 mt-1 mb-1 font-medium text-xs">Element:</div>';
                                                                $jsonHTML .= '<div class="ml-3 p-2 bg-gray-100 dark:bg-gray-600 rounded overflow-x-auto break-all">';
                                                                $jsonHTML .= '<code class="text-xs whitespace-pre-wrap">' . htmlspecialchars($issue['context']) . '</code>';
                                                                $jsonHTML .= '</div>';
                                                                $jsonHTML .= '<div class="ml-3 mt-1 mb-1 font-medium text-xs">Selector:</div>';
                                                                $jsonHTML .= '<div class="ml-3 p-2 bg-gray-100 dark:bg-gray-600 rounded overflow-x-auto">';
                                                                $jsonHTML .= '<code class="text-xs">' . htmlspecialchars($issue['selector']) . '</code>';
                                                                $jsonHTML .= '</div>';
                                                                $jsonHTML .= '</div>';
                                                            }
                                                            
                                                            $jsonHTML .= '</div>';
                                                        }
                                                    }
                                                    
                                                    $jsonHTML .= '</div>';
                                                    
                                                    $logs = preg_replace('/Accessibility JSON Report:\s*({.+})/s', 'Accessibility JSON Report: ' . $jsonHTML, $logs);
                                                }
                                            } catch(\Exception $e) {
                                                // If JSON parsing fails, keep the original
                                            }
                                        }
                                        
                                        // Apply all formatting replacements
                                        $logs = preg_replace($patterns, $replacements, $logs);
                                        
                                        // Format key-value pairs in the logs
                                        $logs = preg_replace('/(Repository|Branch|Commit|Commit Message): (.+)/', '<span class="text-gray-600 dark:text-gray-400">$1:</span> <span class="font-medium">$2</span>', $logs);
                                        
                                        // Highlight success/failure messages
                                        $logs = preg_replace('/(completed successfully|Passed)/', '<span class="text-green-600 dark:text-green-400 font-medium">$1</span>', $logs);
                                        $logs = preg_replace('/(failed)/i', '<span class="text-red-600 dark:text-red-400 font-medium">$1</span>', $logs);
                                        
                                        // Echo the formatted logs
                                        echo $logs;
                                    @endphp
                                </div>
                            </div>
                            <style>
                                .log-section {
                                    padding: 6px 0;
                                    border-bottom: 1px solid rgba(156, 163, 175, 0.2);
                                }
                                .log-container code {
                                    word-break: break-all;
                                    white-space: pre-wrap;
                                }
                            </style>
                        @else
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded text-sm text-gray-500 dark:text-gray-400">
                                No build logs available.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>