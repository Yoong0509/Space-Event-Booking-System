<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased flex h-screen w-full">

    <div class="w-64 bg-gray-900 text-white flex flex-col h-full shadow-2xl z-20 relative flex-shrink-0">
        <div class="p-6 border-b border-gray-800 flex items-center justify-center">
            <h1 class="text-2xl font-black tracking-widest text-blue-400">ADMIN<span class="text-white">SPEVENT</span></h1>
        </div>
        <nav class="flex-grow p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-lg transition"><i class="fa-solid fa-chart-pie w-5"></i> Dashboard</a>
            <a href="{{ route('admin.events.create') }}" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-lg transition"><i class="fa-solid fa-calendar-plus w-5"></i> Add New Event</a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-3 bg-blue-600 rounded-lg font-bold shadow-md"><i class="fa-solid fa-users w-5"></i> Manage Users</a>
            <a href="{{ route('admin.inquiries') }}" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-lg transition"><i class="fa-solid fa-envelope w-5"></i> Inquiries</a>
            <a href="{{ route('home') }}" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-lg transition"><i class="fa-solid fa-house w-5"></i> Home Event</a>
        </nav>
    </div>

    <div class="flex-1 h-full overflow-y-auto p-8 relative flex flex-col items-center justify-center">
        
        <div class="w-full max-w-lg">

            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-gray-900 p-8 text-white text-center relative">
                    <a href="{{ route('admin.users') }}" class="absolute top-6 left-6 text-gray-400 hover:text-white transition"><i class="fa-solid fa-arrow-left text-xl"></i></a>
                    
                    <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-gray-700 overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ asset($user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-user-pen text-3xl text-blue-400"></i>
                        @endif
                    </div>
                    <h2 class="text-2xl font-black">Edit User Details</h2>
                    <p class="text-gray-400 mt-1">ID #{{ $user->id }} | {{ $user->role === 'admin' ? 'Administrator' : 'Normal User' }}</p>
                </div>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl text-sm border border-red-100">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upload New Avatar</label>
                        <input type="file" name="avatar" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-300 rounded-xl bg-white shadow-sm">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 shadow-sm bg-gray-50 focus:bg-white transition">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 shadow-sm bg-gray-50 focus:bg-white transition">
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Reset Password <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full rounded-xl border-gray-300 focus:border-blue-500 shadow-sm bg-gray-50 focus:bg-white transition">
                        <p class="text-xs text-gray-500 mt-2">* Must be at least 8 characters long if changed.</p>
                    </div>

                    <button type="submit" class="w-full group flex items-center justify-center px-8 py-4 text-lg font-bold text-white transition-all duration-300 bg-gray-900 rounded-xl hover:bg-black hover:shadow-2xl hover:-translate-y-1 border border-gray-700">
                        <i class="fa-solid fa-save mr-2"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>