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

                <div class="p-6">
                    <!-- Build Information -->
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
                    
                    @php
                        // Get the logs from build
                        $logs = $build->logs ?? 'No logs available.';
                        $accessibilityJson = null;
                        $performanceJson = null;
                        
                        // Extract JSON reports from logs without modifying the original display
                        if(preg_match('/Accessibility JSON Report:\s*({.+?})\s*\n/s', $logs, $accessMatches)) {
                            try {
                                $accessibilityJson = json_decode($accessMatches[1], true);
                            } catch(\Exception $e) {
                                // Silent failure
                            }
                        }
                        
                        if(preg_match('/Performance JSON Report:\s*(\[.+?\])\s*\n/s', $logs, $perfMatches)) {
                            try {
                                $performanceJson = json_decode($perfMatches[1], true);
                            } catch(\Exception $e) {
                                // Silent failure
                            }
                        }
                        
                        // Only add line breaks and preserving plain text formatting
                        $formattedLogs = nl2br(htmlspecialchars($logs));
                    @endphp
                    
                    <!-- Accessibility Report Section -->
                    @if($accessibilityJson)
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Accessibility Report</h4>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                            <!-- Summary -->
                            <div class="flex flex-wrap gap-4 mb-4">
                                <div class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full">
                                    <span class="text-gray-800 dark:text-gray-100">Total:</span> 
                                    <span class="font-medium">{{ $accessibilityJson['total'] ?? 0 }}</span>
                                </div>
                                <div class="px-3 py-1 bg-green-100 dark:bg-green-800 rounded-full">
                                    <span class="text-green-800 dark:text-green-100">Passes:</span> 
                                    <span class="font-medium">{{ $accessibilityJson['passes'] ?? 0 }}</span>
                                </div>
                                <div class="px-3 py-1 bg-red-100 dark:bg-red-800 rounded-full">
                                    <span class="text-red-800 dark:text-red-100">Errors:</span> 
                                    <span class="font-medium">{{ $accessibilityJson['errors'] ?? 0 }}</span>
                                </div>
                            </div>
                            
                            <!-- Issues by URL -->
                            @if(isset($accessibilityJson['results']) && is_array($accessibilityJson['results']))
                                @foreach($accessibilityJson['results'] as $url => $issues)
                                    <div class="mb-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                        <h5 class="font-medium mb-3 text-gray-900 dark:text-gray-100">
                                            URL: <span class="font-normal">{{ htmlspecialchars($url) }}</span>
                                        </h5>
                                        
                                        @if(is_array($issues) && count($issues) > 0)
                                            <div class="space-y-4">
                                            @foreach($issues as $issue)
                                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md shadow-sm">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="inline-block w-3 h-3 rounded-full 
                                                            {{ isset($issue['type']) && $issue['type'] == 'error' ? 'bg-red-500' : 'bg-yellow-500' }}">
                                                        </span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $issue['code'] ?? 'Unknown Issue' }}</span>
                                                    </div>
                                                    
                                                    <p class="mb-3 text-sm text-gray-800 dark:text-gray-200">{{ $issue['message'] ?? '' }}</p>
                                                    
                                                    @if(isset($issue['context']))
                                                        <div class="mb-2">
                                                            <span class="text-xs font-medium text-gray-800 dark:text-gray-200">Element:</span>
                                                            <pre class="mt-1 p-2 bg-gray-100 dark:bg-gray-800 rounded text-xs overflow-x-auto text-gray-800 dark:text-gray-200">{{ $issue['context'] ? htmlspecialchars_decode(htmlspecialchars($issue['context'])) : '' }}</pre>
                                                        </div>
                                                    @endif
                                                    
                                                    @if(isset($issue['selector']))
                                                        <div>
                                                            <span class="text-xs font-medium text-gray-800 dark:text-gray-200">Selector:</span>
                                                            <pre class="mt-1 p-2 bg-gray-100 dark:bg-gray-800 rounded text-xs overflow-x-auto text-gray-800 dark:text-gray-200">{!! htmlspecialchars($issue['selector']) !!}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-800 dark:text-gray-200">No issues found for this URL.</p>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Performance Report Section -->
                    @if($performanceJson && is_array($performanceJson))
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Performance Report</h4>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow">
                            @foreach($performanceJson as $pageData)
                                <div class="mb-6 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 pt-4' : '' }}">
                                    <h5 class="font-medium mb-3 text-gray-900 dark:text-gray-100">
                                        Page: <span class="font-normal">
                                            @if(isset($pageData['subject']) && !empty($pageData['subject']))
                                                {{ $pageData['subject'] === '/' ? 'Homepage' : htmlspecialchars($pageData['subject']) }}
                                            @else
                                                Unknown
                                            @endif
                                        </span>
                                    </h5>
                                    
                                    @if(isset($pageData['metrics']) && is_array($pageData['metrics']))
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-200 uppercase tracking-wider">Metric</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-200 uppercase tracking-wider">Value</th>
                                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-200 uppercase tracking-wider">Target</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach($pageData['metrics'] as $metric)
                                                        @php
                                                            $valueClass = '';
                                                            $targetIcon = '';
                                                            $targetText = isset($metric['desiredSize']) ? ($metric['desiredSize'] == 'smaller' ? 'Lower is better' : 'Higher is better') : '';
                                                            $formattedValue = $metric['value'] ?? '';
                                                            
                                                            // Format value with appropriate units
                                                            if($metric['name'] == 'Total Score') {
                                                                $score = intval($formattedValue);
                                                                if($score >= 90) {
                                                                    $valueClass = 'text-green-600 dark:text-green-400 font-medium';
                                                                    $targetIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>';
                                                                }
                                                                elseif($score >= 70) {
                                                                    $valueClass = 'text-yellow-600 dark:text-yellow-400 font-medium';
                                                                    $targetIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>';
                                                                }
                                                                else {
                                                                    $valueClass = 'text-red-600 dark:text-red-400 font-medium';
                                                                    $targetIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
                                                                }
                                                            } else {
                                                                // Add units based on metric name
                                                                if(in_array($metric['name'], ['Speed Index', 'First Contentful Paint', 'Largest Contentful Paint', 'Total Blocking Time'])) {
                                                                    $formattedValue .= 'ms';
                                                                } elseif(in_array($metric['name'], ['Transfer Size'])) {
                                                                    // Check if KB already exists in the value
                                                                    if(strpos($formattedValue, 'KB') === false && strpos($formattedValue, 'MB') === false) {
                                                                        $sizeInKB = floatval($formattedValue) / 1024;
                                                                        $formattedValue = number_format($sizeInKB, 1) . 'KB';
                                                                    }
                                                                }
                                                                
                                                                if(isset($metric['desiredSize']) && $metric['desiredSize'] == 'smaller') {
                                                                    $targetIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
                                                                } else {
                                                                    $targetIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>';
                                                                }
                                                            }
                                                        @endphp
                                                        <tr class="bg-white dark:bg-gray-800">
                                                            <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-gray-200">{{ htmlspecialchars($metric['name'] ?? '') }}</td>
                                                            <td class="px-4 py-3 text-sm {{ $valueClass }} text-gray-800 dark:text-gray-200">{{ htmlspecialchars($formattedValue) }}</td>
                                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                                                {!! $targetIcon !!}
                                                                <span class="ml-1">{{ $targetText }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-gray-800 dark:text-gray-200">No metrics available for this page.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Build Logs (Plain Text) -->
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Raw Build Logs</h4>
                        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 text-sm font-mono whitespace-pre-line text-gray-800 dark:text-gray-200 overflow-x-auto">
                            {!! $formattedLogs !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>