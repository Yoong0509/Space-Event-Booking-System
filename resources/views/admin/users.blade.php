<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
            <a href="{{ route('admin.inquiries') }}" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-lg transition">
                <i class="fa-solid fa-envelope w-5"></i> Inquiries
                @if($stats['unread_inquiries'] > 0) <span class="ml-auto bg-red-500 text-xs font-bold px-2 py-0.5 rounded-full">{{ $stats['unread_inquiries'] }}</span> @endif
            </a>
            <a href="{{ route('home') }}" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-lg transition"><i class="fa-solid fa-house w-5"></i> Home Event</a>
        </nav>
    </div>

    <div class="flex-1 h-full overflow-y-auto p-8 relative">
        <div class="mb-8">
            <h2 class="text-3xl font-black text-gray-800">User Management</h2>
            <p class="text-gray-500">View registered users and assign Admin roles.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                <i class="fa-solid fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-12">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="p-4 border-b hover:bg-gray-200 transition cursor-pointer">
                                @php
                                    $isSortId = request('sort', 'id') === 'id';
                                    $nextDirId = ($isSortId && request('direction', 'desc') === 'desc') ? 'asc' : 'desc';
                                @endphp
                                <a href="{{ route('admin.users', ['sort' => 'id', 'direction' => $nextDirId]) }}" class="flex items-center gap-1 group">
                                    ID @if($isSortId) <i class="fa-solid fa-sort-{{ request('direction', 'desc') === 'asc' ? 'up' : 'down' }} text-blue-500"></i> @else <i class="fa-solid fa-sort opacity-30 group-hover:opacity-100 transition"></i> @endif
                                </a>
                            </th>

                            <th class="p-4 border-b hover:bg-gray-200 transition cursor-pointer">
                                @php
                                    $isSortName = request('sort') === 'name';
                                    $nextDirName = ($isSortName && request('direction') === 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ route('admin.users', ['sort' => 'name', 'direction' => $nextDirName]) }}" class="flex items-center gap-1 group">
                                    Name @if($isSortName) <i class="fa-solid fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-blue-500"></i> @else <i class="fa-solid fa-sort opacity-30 group-hover:opacity-100 transition"></i> @endif
                                </a>
                            </th>

                            <th class="p-4 border-b hover:bg-gray-200 transition cursor-pointer">
                                @php
                                    $isSortEmail = request('sort') === 'email';
                                    $nextDirEmail = ($isSortEmail && request('direction') === 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ route('admin.users', ['sort' => 'email', 'direction' => $nextDirEmail]) }}" class="flex items-center gap-1 group">
                                    Email @if($isSortEmail) <i class="fa-solid fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-blue-500"></i> @else <i class="fa-solid fa-sort opacity-30 group-hover:opacity-100 transition"></i> @endif
                                </a>
                            </th>

                            <th class="p-4 border-b text-center">Role</th>
                            <th class="p-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0">
                                <td class="p-4 text-gray-500 font-bold">#{{ $user->id }}</td>
                                <td class="p-4 font-bold text-gray-900">{{ $user->name }}</td>
                                <td class="p-4 text-gray-600">{{ $user->email }}</td>
                                <td class="p-4 text-center">
                                    @if($user->role === 'admin')
                                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold border border-purple-200"><i class="fa-solid fa-crown mr-1"></i> Admin</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">User</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right flex justify-end gap-2 items-center">
                                    
                                    <form action="{{ route('admin.users.role', $user->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg transition font-bold border border-blue-200 text-xs">
                                            {{ $user->role === 'admin' ? 'Demote' : 'Make Admin' }}
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition" title="Edit User">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    @if($user->id !== auth()->id()) 
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Delete User"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 bg-gray-50">{{ $users->appends(request()->query())->links() }}</div>
        </div>
    </div>
</body>
</html>