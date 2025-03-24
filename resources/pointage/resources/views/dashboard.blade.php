@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    @auth
        <!-- Welcome Section -->
        <div class="px-4 py-5 sm:px-0">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="mt-1 text-sm text-gray-600">Here's what's happening with your attendance today.</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Check In Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Check In
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $lastCheckIn ? $lastCheckIn->format('h:i A') : 'Not checked in' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('attendance.checkin') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Check In Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Check Out Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Check Out
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $lastCheckOut ? $lastCheckOut->format('h:i A') : 'Not checked out' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('attendance.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Check Out Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Today's Hours Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Today's Hours
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $todayHours ?? '0' }} hours
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            View attendance history →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Recent Activity
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Your recent attendance records
                    </p>
                </div>
                <div class="border-t border-gray-200">
                    <ul class="divide-y divide-gray-200">
                        @forelse($recentActivity ?? [] as $activity)
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if($activity->type === 'checkin')
                                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $activity->type === 'checkin' ? 'Checked In' : 'Checked Out' }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $activity->created_at->format('M d, Y h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-4 sm:px-6">
                                <p class="text-sm text-gray-500 text-center">No recent activity</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="text-center">
            <h2 class="text-2xl font-semibold text-gray-900">Please log in to access the dashboard</h2>
            <p class="mt-2 text-gray-600">You need to be authenticated to view this page.</p>
            <div class="mt-4">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Go to Login
                </a>
            </div>
        </div>
    @endauth
</div>
@endsection 