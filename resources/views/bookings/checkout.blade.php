<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Confirm Your Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased py-12">

    <div class="max-w-4xl mx-auto px-4 flex flex-col md:flex-row gap-8">
        
        <div class="md:w-1/2">
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">Order Summary</h2>
                
                <div class="mb-6">
                    <p class="text-sm text-gray-500 mb-1">Event</p>
                    <p class="text-xl font-bold text-gray-800">{{ $event->title }}</p>
                </div>

                <p class="text-sm text-gray-500 mb-3">Selected Tickets ({{ count($orderItems) }} categories)</p>
                
                <div class="space-y-3 mb-6 max-h-60 overflow-y-auto">
                    @foreach($orderItems as $item)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <div>
                                <span class="font-bold text-gray-800">{{ $item['ticket']->name }}</span>
                                <span class="text-sm font-bold text-blue-600 ml-2 bg-blue-100 px-2 py-0.5 rounded-full">x {{ $item['quantity'] }}</span>
                            </div>
                            <span class="text-green-600 font-semibold">RM {{ number_format($item['subtotal'], 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-between items-center">
                        <p class="text-xl font-bold text-gray-900">Total Amount</p>
                        <p class="text-3xl font-black text-blue-600">RM {{ number_format($totalAmount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:w-1/2">
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-4">Payment Method</h2>
                
                <form action="{{ route('payment.show') }}" method="POST">
                    @csrf
                    
                    @foreach($orderItems as $item)
                        <input type="hidden" name="quantities[{{ $item['ticket']->id }}]" value="{{ $item['quantity'] }}">
                    @endforeach

                    <div class="mb-6 border-b border-gray-100 pb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Promo Code (Optional)</label>
                        <input type="text" name="promo_code" placeholder="e.g. UTAR2026" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm uppercase uppercase">
                        <p class="text-xs text-gray-500 mt-2">Discount will be applied on the next secure payment page.</p>
                    </div>
                    <div class="space-y-4 mb-8">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="Credit/Debit Card" class="w-5 h-5 text-blue-600" required checked>
                            <span class="ml-4 font-bold text-gray-800 flex items-center gap-2">
                                💳 Credit / Debit Card
                            </span>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="FPX Online Banking" class="w-5 h-5 text-blue-600">
                            <span class="ml-4 font-bold text-gray-800 flex items-center gap-2">
                                🏦 FPX Online Banking
                            </span>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="Touch n Go eWallet" class="w-5 h-5 text-blue-600">
                            <span class="ml-4 font-bold text-gray-800 flex items-center gap-2">
                                📱 Touch 'n Go eWallet
                            </span>
                        </label>
                    </div>

                   <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-xl text-lg shadow-lg transition">
                        Proceed to Payment
                    </button>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('events.show', $event->id) }}" class="text-sm text-gray-500 hover:text-gray-800 transition">Cancel and select seats again</a>
                    </div>
                </form>
            </div>
        </div>

    </div>

</body>
</html>