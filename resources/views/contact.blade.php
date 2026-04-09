<!DOCTYPE html>
<html lang="en">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Event Booking System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased flex flex-col min-h-screen">

    @include('layouts.navigation')
    <br>
    <div class="bg-gradient-to-r from-blue-900 to-indigo-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-black text-white mb-4">Get In Touch</h1>
            <p class="text-blue-200 text-lg">Have questions? We'd love to hear from you.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-grow w-full">
        
        @if(session('success'))
            <div class="mb-8 bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-xl shadow-sm flex items-center">
                <i class="fa-solid fa-circle-check text-2xl mr-3"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden flex flex-col md:flex-row">
            
            <div class="md:w-1/3 bg-gray-900 p-10 text-white flex flex-col justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-6 text-blue-400">Contact Information</h3>
                    <p class="text-gray-400 mb-8 leading-relaxed">Fill up the form and our team will get back to you within 24 hours.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <i class="fa-solid fa-phone text-blue-400 mt-1 mr-4 text-xl"></i>
                            <div>
                                <p class="font-bold">Phone</p>
                                <p class="text-gray-400">+60 3-1234 5678</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fa-solid fa-envelope text-blue-400 mt-1 mr-4 text-xl"></i>
                            <div>
                                <p class="font-bold">Email</p>
                                <p class="text-gray-400">support@utarevents.com</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fa-solid fa-location-dot text-blue-400 mt-1 mr-4 text-xl"></i>
                            <div>
                                <p class="font-bold">Office Address</p>
                                <p class="text-gray-400">Jalan Sungai Long, Bandar Sungai Long, 43000 Kajang, Selangor.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:w-2/3 p-10">
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Your Name</label>
                            <input type="text" name="name" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Subject</label>
                        <input type="text" name="subject" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Message</label>
                        <textarea name="message" rows="5" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"></textarea>
                    </div>

                    <button type="submit" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 px-8 rounded-xl transition shadow-md w-full md:w-auto">
                        Send Message <i class="fa-solid fa-paper-plane ml-2"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>

    @include('components.footer')

</body>
</html>