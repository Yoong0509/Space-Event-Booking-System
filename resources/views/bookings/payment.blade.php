<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment Gateway</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-200 font-sans antialiased min-h-screen flex items-center justify-center py-12">

    <div class="bg-white p-8 rounded-3xl shadow-2xl max-w-lg w-full border border-gray-100 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-green-400 to-blue-500"></div>
        <div class="text-center mb-8 mt-4">
            <h2 class="text-3xl font-black text-gray-900">Secure Checkout</h2>
            <p class="text-sm text-gray-500 mt-2 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Encrypted Connection
            </p>
        </div>

        <div class="bg-gray-50 p-6 rounded-2xl mb-8 text-center border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">Final Amount to Pay</p>
            <p class="text-4xl font-extrabold text-blue-600">RM {{ number_format($totalAmount, 2) }}</p>
            
            @if(isset($discountAmount) && $discountAmount > 0)
                <div class="mt-3 text-sm font-bold text-green-700 bg-green-100 inline-block px-4 py-1.5 rounded-full border border-green-200 shadow-sm">
                    Promo Applied: - RM {{ number_format($discountAmount, 2) }}
                </div>
            @endif
            
            <div class="mt-4">
                <p class="text-sm font-semibold text-gray-600 bg-gray-200 inline-block px-3 py-1 rounded-full">
                    Via: {{ $paymentMethod }}
                </p>
            </div>
        </div>

        <form action="{{ route('book.store') }}" method="POST">
            @csrf

            <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
            <input type="hidden" name="promo_code" value="{{ $promoCodeInput }}">
            
            @foreach($quantities as $ticketId => $qty)
                <input type="hidden" name="quantities[{{ $ticketId }}]" value="{{ $qty }}">
            @endforeach

            @if($paymentMethod === 'Credit/Debit Card')
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Card Number</label>
                        <input type="text" placeholder="0000 0000 0000 0000" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Expiry Date</label>
                            <input type="text" placeholder="MM/YY" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">CVC</label>
                            <input type="password" placeholder="123" maxlength="3" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Cardholder Name</label>
                        <input type="text" placeholder="JOHN DOE" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                    </div>
                </div>
            @endif

            @if($paymentMethod === 'FPX Online Banking')
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Select Your Bank</label>
                    <select class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                        <option value="">-- Choose a Bank --</option>
                        <option>Maybank2U</option>
                        <option>CIMB Clicks</option>
                        <option>Public Bank</option>
                        <option>RHB Now</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-3 italic">You will be redirected to your bank's secure login page after clicking Pay Now.</p>
                </div>
            @endif

            @if($paymentMethod === 'Touch n Go eWallet')
                <div class="mb-8 text-center bg-blue-50 p-6 rounded-xl border border-blue-100">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="font-bold text-gray-800">Touch 'n Go eWallet</p>
                    <p class="text-sm text-gray-600 mt-2">Open your app and scan the QR code, or enter your mobile number linked to TNG.</p>
                    <input type="text" placeholder="e.g. 0123456789" class="w-full mt-4 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm text-center" required>
                </div>
            @endif

            <button type="submit" class="w-full flex justify-center items-center gap-2 bg-gray-900 hover:bg-black text-white font-black py-4 px-4 rounded-xl text-lg shadow-xl hover:shadow-2xl transition duration-300 transform hover:-translate-y-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Confirm & Pay Now
            </button>
            
            <div class="mt-6 text-center">
                <button type="button" onclick="history.back()" class="text-sm font-semibold text-gray-500 hover:text-gray-800 transition">
                    Cancel Payment
                </button>
            </div>
        </form>

    </div>

</body>
</html>