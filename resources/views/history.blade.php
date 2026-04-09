<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('My Tickets History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($bookings->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-2xl p-12 text-center border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800">No Tickets Yet</h3>
                    <p class="text-gray-500 mt-2">Looks like you haven't booked any events.</p>
                    <a href="{{ route('home') }}" class="inline-block mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl transition shadow">Browse Events</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($bookings as $booking)
                        <div class="p-6 bg-white shadow-sm sm:rounded-2xl border border-gray-100 flex flex-col justify-between hover:shadow-md transition">
                            <div class="mb-4 border-b pb-4">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Booking Reference</p>
                                <p class="text-2xl font-black text-gray-900">{{ $booking->booking_reference }}</p>
                            </div>
                            
                            <div class="mb-6">
                                <p class="font-bold text-gray-800 text-lg">{{ $booking->event->title }}</p>
                                <p class="text-sm text-gray-500 flex items-center mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-12 8h14a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($booking->event->event_date)->format('D, d M Y • h:i A') }}
                                </p>
                            </div>

                            <div class="mt-auto">
                                <a href="{{ route('bookings.show', $booking->id) }}" class="block w-full text-center bg-gray-900 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-xl transition shadow">
                                    View E-Tickets
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif

        </div>
    </div>
    @include('components.footer')
</x-app-layout>