<!DOCTYPE html>
<html lang="en">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Segoe+UI:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

    @include('layouts.navigation')

    <div class="max-w-7xl mx-auto p-1 mt-1">

        <!-- carousel -->
        <div class="carousel">
            <div class="slide active">
                <img src="/images/events/banner1.jpg" alt="Spring Concert 2026">
            </div>
            <div class="slide">
                <img src="/images/events/banner2.jpg" alt="Tech Expo 2026">
            </div>
            <div class="slide">
                <img src="/images/events/banner3.jpg" alt="Charity Run 2026">
            </div>

            <div class="dots">
                <span class="dot active" data-index="0"></span>
                <span class="dot" data-index="1"></span>
                <span class="dot" data-index="2"></span>
            </div>
        </div>

        <!-- Categories -->
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Categories</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
            @foreach ($categories as $category)
                <a href="{{ route('events.index', ['category_id' => $category->id]) }}" class="flex flex-col items-center bg-white rounded-lg shadow-sm border border-gray-100 p-4 hover:shadow-xl hover:border-blue-300 transform hover:-translate-y-1 transition-all duration-300 cursor-pointer group">
                    
                    <div class="w-20 h-20 flex items-center justify-center bg-gray-50 rounded-full mb-3 group-hover:bg-blue-50 transition-colors duration-300">
                        <img src="{{ asset($category->icon) }}" alt="{{ $category->name }}" class="max-w-full max-h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                    </div>                    
                    
                    <h3 class="text-lg font-bold text-gray-700 text-center break-words group-hover:text-blue-600 transition-colors">
                        {{ $category->name }}
                    </h3>                
                </a>
            @endforeach
        </div>

        <!-- Upcoming Events Title -->
        <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Upcoming Events</h2>

        <!-- Upcoming Events List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($events as $event)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="w-full h-48 flex items-center justify-center bg-gray-100">
                        <img src="{{ $event->image }}" alt="{{ $event->title }}" class="max-h-48 object-contain transition-transform duration-500 hover:scale-105">
                    </div>                    
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $event->title }}</h3>
                        
                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-12 8h14a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, h:i A') }}
                        </p>

                        <p class="text-sm text-gray-600 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414a4 4 0 10-5.657 5.657l4.243 4.243a8 8 0 1111.314-11.314l-4.243 4.243z" />
                            </svg>
                            {{ $event->venue->name ?? 'TBA' }}
                        </p>
                        
                        <a href="{{ route('events.show', $event->id) }}" 
                            class="block w-full text-center bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-2 px-4 rounded transition-colors">
                            Book Tickets
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-10 text-center">
            <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-8 rounded-full transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1 text-lg">
                See All Events 
            </a>
        </div>
    </div>
<br><br>
@include('components.footer')
</body>
</html>