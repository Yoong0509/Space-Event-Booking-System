<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - Buy Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased pb-20">

    @include('layouts.navigation')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="relative w-full h-[60vh] min-h-[400px] rounded-3xl overflow-hidden shadow-2xl">
            <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 w-full p-8 md:p-12 text-white">
                <span class="bg-blue-600/90 backdrop-blur text-white text-xs font-bold uppercase tracking-widest py-1.5 px-4 rounded-full mb-4 inline-block">
                    {{ $event->category->name ?? 'Live Event' }}
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 tracking-tight leading-tight">{{ $event->title }}</h1>
                <div class="flex flex-wrap items-center gap-6 text-gray-200 text-lg">
                    <span class="flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('D, d M Y • h:i A') }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $event->venue->name ?? 'Venue TBA' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 flex flex-col lg:flex-row gap-12">
        
        <div class="lg:w-2/3">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">About this Event</h2>
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-lg text-gray-600 leading-relaxed space-y-4">
                <p>{{ $event->description }}</p>
                <p>Don't miss out on this spectacular experience. Bring your friends and enjoy an unforgettable night!</p>
            </div>

            <h2 class="text-3xl font-bold text-gray-900 mt-12 mb-6">Location</h2>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-4">
                <div class="bg-blue-100 p-4 rounded-full text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m22 4v-4m-3-14l-4-4-4 4-4-4-4 4-4-4-4 4v16a2 2 0 002 2h20a2 2 0 002-2V7z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-gray-900">{{ $event->venue->name ?? 'TBA' }}</h4>
                    <p class="text-gray-500 mt-1">{{ $event->venue->address ?? 'TBA' }}</p>
                    <p class="text-sm text-blue-600 font-semibold mt-2">Capacity: {{ number_format($event->venue->capacity) }} pax</p>
                </div>
            </div>
        </div>

        <div class="lg:w-1/3">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden sticky top-24">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white text-center">
                    <h3 class="text-2xl font-bold">Select Your Tickets</h3>
                    <p class="text-blue-100 text-sm mt-1">Choose your preferred seat</p>
                </div>
                
                <div class="p-6">
                    @if($event->tickets->count() > 0)
                        @if(session('error'))
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl shadow-sm">
                                <strong class="font-bold">Oops!</strong>
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif
                    <form action="{{ route('checkout') }}" method="POST">
                        @csrf
                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar mb-6">
                            @foreach($event->tickets as $ticket)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white border-2 border-gray-100 rounded-xl transition-all duration-200 {{ $ticket->status !== 'available' ? 'opacity-60 bg-gray-50' : 'hover:border-blue-300' }}">
                                    
                                    <div class="mb-3 sm:mb-0">
                                        <p class="text-xl font-black text-gray-900">{{ $ticket->name }}</p>
                                        <p class="text-sm font-semibold text-blue-600 mt-1">RM {{ number_format($ticket->price, 2) }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            @if($ticket->status === 'available')
                                                Available: {{ $ticket->stock }} tickets
                                            @else
                                                Fully Booked
                                            @endif
                                        </p>
                                    </div>
                                    
                                    @if($ticket->status === 'available' && $ticket->stock > 0)
                                        <div class="flex items-center gap-3 bg-gray-50 p-2 rounded-lg border border-gray-200">
                                            <span class="text-sm font-bold text-gray-600">Qty:</span>
                                            <input type="number" name="quantities[{{ $ticket->id }}]" min="0" max="{{ $ticket->stock }}" value="0" 
                                                class="w-20 text-center font-bold text-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center px-4 py-2 bg-red-100 rounded-lg border border-red-200">
                                            <span class="font-bold text-red-600 uppercase tracking-wider text-sm">Sold Out</span>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition">
                            Proceed to Checkout
                        </button>
                    </form>
                    @else
                        <div class="text-center py-8">
                            <h4 class="text-xl font-bold text-gray-900">Sold Out</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <br><br><br><br>
@include('components.footer')
</body>
</html>