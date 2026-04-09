<!DOCTYPE html>
<html lang="en">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Events - Event Booking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased flex flex-col min-h-screen">

    @include('layouts.navigation')
    <br>
    <div class="bg-gradient-to-r from-blue-900 to-indigo-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-black text-white mb-4">Discover All Events</h1>
            <p class="text-blue-200 text-lg">Find your next unforgettable experience.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full">
        
        <div class="bg-white p-4 rounded-2xl shadow-sm mb-8 border border-gray-100">
            <form action="{{ route('events.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                
                <div class="flex-grow relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search event name..." 
                           class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50">
                </div>

                <div class="md:w-64">
                    <select name="category_id" class="w-full py-3 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 bg-gray-50 cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-md">
                    Search
                </button>
                
                @if(request()->has('search') || request()->has('category_id'))
                    <a href="{{ route('events.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 px-6 rounded-xl transition text-center flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if($events->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm">
                <div class="text-gray-300 mb-4"><i class="fa-solid fa-magnifying-glass fa-4x"></i></div>
                <h3 class="text-2xl font-bold text-gray-800">No events found</h3>
                <p class="text-gray-500 mt-2">Try adjusting your search keywords or changing the category filter.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($events as $event)
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100 flex flex-col">
                        <div class="w-full h-48 flex items-center justify-center bg-gray-100 relative group">
                            <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" class="max-h-48 object-contain group-hover:scale-105 transition-transform duration-500">
                        </div>                    
                        <div class="p-6 flex-grow flex flex-col">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">{{ $event->title }}</h3>
                            
                            <p class="text-sm text-gray-600 mb-2 flex items-center">
                                <i class="fa-regular fa-calendar text-blue-500 w-5"></i>
                                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, h:i A') }}
                            </p>
                            <p class="text-sm text-gray-600 mb-6 flex items-center">
                                <i class="fa-solid fa-location-dot text-red-500 w-5"></i>
                                {{ $event->venue->name ?? 'TBA' }}
                            </p>
                            
                            <div class="mt-auto pt-4 border-t border-gray-100">
                                <a href="{{ route('events.show', $event->id) }}" 
                                   class="block w-full text-center bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 px-4 rounded-xl transition shadow">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif

    </div>

    @include('components.footer')

</body>
</html>