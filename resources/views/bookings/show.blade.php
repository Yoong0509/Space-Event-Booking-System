<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center">
                {{ __('E-Tickets') }}: {{ $booking->booking_reference }}
            </h2>
            <span class="bg-green-100 text-green-700 font-bold px-4 py-2 rounded-full text-sm shadow-sm">Payment Confirmed</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="relative bg-blue-900 rounded-t-3xl p-8 md:p-12 text-white text-center shadow-lg overflow-hidden">
                <img src="{{ asset($booking->event->image) }}" alt="Background" class="absolute inset-0 w-full h-full object-cover opacity-30 blur-sm mix-blend-overlay">
                <div class="relative z-10">
                    <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight">{{ $booking->event->title }}</h1>
                    <p class="text-blue-200 font-semibold text-lg flex items-center justify-center gap-2 mb-6">
                        {{ $booking->event->venue->name ?? 'Venue TBA' }}
                    </p>
                    
                    <div class="flex justify-center gap-4 md:gap-8 mt-6 border-t border-white/20 pt-6">
                        <div class="bg-black/40 backdrop-blur-sm px-6 py-3 rounded-2xl border border-white/10 shadow-inner">
                            <p class="text-xs text-blue-300 uppercase tracking-widest font-bold mb-1">Total Tickets</p>
                            <p class="text-2xl font-black">{{ $booking->tickets->sum('pivot.quantity') }} <span class="text-sm font-medium">Items</span></p>
                        </div>
                        <div class="bg-black/40 backdrop-blur-sm px-6 py-3 rounded-2xl border border-white/10 shadow-inner">
                            <p class="text-xs text-blue-300 uppercase tracking-widest font-bold mb-1">Total Paid</p>
                            <p class="text-2xl font-black text-green-400">RM {{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8 rounded-b-3xl shadow-lg border border-gray-100">
                <h3 class="text-xl font-bold border-b border-gray-200 pb-4 mb-6 text-gray-800">Your Valid Tickets</h3>

                <div class="space-y-6">
                    @foreach($booking->tickets as $ticket)
                        @for($i = 1; $i <= $ticket->pivot->quantity; $i++)
                            
                            <div class="flex flex-col md:flex-row border-2 border-dashed border-gray-300 rounded-xl overflow-hidden bg-gray-50 hover:border-blue-400 transition-colors duration-300 shadow-sm">
                                
                                <div class="bg-white p-6 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-dashed border-gray-300 min-w-[200px]">
                                    @php
                                        $qrHash = hash('sha256', $booking->booking_reference . '-' . $ticket->id . '-' . $i);
                                    @endphp
                                    
                                    <div style="width: 140px; height: 140px; background-color: white; padding: 10px; border: 1px solid #e5e7eb; border-radius: 0.75rem; display: block; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                                        {!! QrCode::size(120)->generate($qrHash) !!}
                                    </div>
                                    
                                    <p class="text-xs font-mono text-gray-500 mt-4 font-bold tracking-widest uppercase">Scan to Enter</p>
                                </div>

                                <div class="p-6 flex-grow flex flex-col justify-between relative">
                                    
                                    <div class="absolute right-4 top-4 opacity-10 pointer-events-none">
                                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1v12zm0 2v5h2v-5H4z"></path></svg>
                                    </div>

                                    <div class="flex items-start gap-4 mb-4 border-b border-gray-200 pb-4 relative z-10">
                                        <img src="{{ asset($booking->event->image) }}" alt="Event Poster" class="w-16 h-16 object-cover rounded-lg shadow-sm border border-gray-200 flex-shrink-0">
                                        <div>
                                            <p class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-1">{{ $ticket->name }}</p>
                                            <p class="text-2xl md:text-3xl font-black text-gray-900">Ticket {{ $i }} <span class="text-base text-gray-500 font-medium">of {{ $ticket->pivot->quantity }}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 relative z-10">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Date</p>
                                            <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->event->event_date)->format('d M Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Time</p>
                                            <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->event->event_date)->format('h:i A') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Price Paid</p>
                                            <p class="font-black text-green-600 text-lg">RM {{ number_format($ticket->pivot->price, 2) }}</p>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        @endfor
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    @include('components.footer')
</x-app-layout>