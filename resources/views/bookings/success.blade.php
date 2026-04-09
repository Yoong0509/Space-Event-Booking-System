<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful!</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen flex items-center justify-center py-12 px-4">

    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-2xl max-w-xl w-full border border-gray-100 text-center relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-3 bg-gradient-to-r from-green-400 to-teal-500"></div>

        <div class="mx-auto w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6 shadow-inner">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-black text-gray-900 mb-2">Payment Successful!</h1>
        <p class="text-gray-500 mb-8">Thank you for your purchase. Your tickets are ready.</p>

        <div class="bg-gray-50 rounded-2xl p-6 text-left border border-gray-100 shadow-sm mb-8 relative">
            
            <div class="absolute -left-3 top-1/2 w-6 h-6 bg-white rounded-full border-r border-gray-100"></div>
            <div class="absolute -right-3 top-1/2 w-6 h-6 bg-white rounded-full border-l border-gray-100"></div>

            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Booking Reference</p>
            <p class="text-xl font-black text-blue-600 mb-6">{{ $booking->booking_reference }}</p>

            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Event</p>
                    <p class="font-bold text-gray-800">{{ $booking->event->title }}</p>
                </div>
                
                <div class="flex justify-between border-t border-dashed border-gray-300 pt-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-500">Payment Method</p>
                        <p class="font-bold text-gray-800">{{ $booking->payment_method }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Paid</p>
                        <p class="font-black text-green-600 text-xl">RM {{ number_format($booking->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('history') }}" class="w-full sm:w-auto bg-gray-900 hover:bg-black text-white font-bold py-3 px-8 rounded-xl shadow-lg transition">
                View My Tickets
            </a>
            <a href="{{ route('home') }}" class="w-full sm:w-auto bg-white border-2 border-gray-200 hover:border-gray-300 text-gray-700 font-bold py-3 px-8 rounded-xl transition">
                Back to Home
            </a>
        </div>

    </div>

</body>
</html>