@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center w-full h-full flex-grow py-12">
    <div class="max-w-[335px] w-full lg:max-w-4xl mx-auto">
        <div class="text-[13px] leading-[20px] p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-6">Welcome to Samuel Svenk's CI/CD Dashboard Demo</h1>

                <p class="text-lg font-medium mt-8">
                    Please use the navigation links above to register or login and access the dashboard.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection