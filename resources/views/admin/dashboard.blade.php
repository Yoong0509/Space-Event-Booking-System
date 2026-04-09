<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased flex h-screen w-full">

    <div class="w-64 bg-gray-900 text-white flex flex-col h-full shadow-2xl z-20 relative flex-shrink-0">
        <div class="p-6 border-b border-gray-800 flex items-center justify-center">
            <h1 class="text-2xl font-black tracking-widest text-blue-400">ADMIN<span class="text-white">SPEVENT</span></h1>
        </div>
        <nav class="flex-grow p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-3 bg-blue-600 rounded-lg font-bold shadow-md">
                <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
            </a>
            <a href="{{ route('admin.events.create') }}" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-lg transition">
                <i class="fa-solid fa-calendar-plus w-5"></i> Add New Event
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-lg transition">
                <i class="fa-solid fa-users w-5"></i> Manage Users
            </a>
            <a href="{{ route('admin.inquiries') }}" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-lg transition">
                <i class="fa-solid fa-envelope w-5"></i> Inquiries
                @if($stats['unread_inquiries'] > 0)
                    <span class="ml-auto bg-red-500 text-xs font-bold px-2 py-0.5 rounded-full">{{ $stats['unread_inquiries'] }}</span>
                @endif
            </a>
            <a href="{{ route('home') }}" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-lg transition">
                <i class="fa-solid fa-house w-5"></i> Home Event
            </a>
        </nav>
        <div class="p-4 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left flex items-center gap-3 p-3 hover:bg-red-600 hover:text-white text-gray-400 rounded-lg transition">
                    <i class="fa-solid fa-right-from-bracket w-5"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="flex-1 h-full overflow-y-auto p-8 relative">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-black text-gray-800">Overview</h2>
                <p class="text-gray-500">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <a href="{{ route('admin.events.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition">
                + Create Event
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                <i class="fa-solid fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase">Total Revenue</p>
                    <p class="text-2xl font-black text-green-600">RM {{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500 text-xl"><i class="fa-solid fa-money-bill-wave"></i></div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase">Tickets Sold</p>
                    <p class="text-2xl font-black text-blue-600">{{ $stats['total_tickets_sold'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 text-xl"><i class="fa-solid fa-ticket"></i></div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase">Registered Users</p>
                    <p class="text-2xl font-black text-purple-600">{{ $stats['total_users'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-500 text-xl"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase">New Inquiries</p>
                    <p class="text-2xl font-black text-red-600">{{ $stats['unread_inquiries'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-500 text-xl"><i class="fa-solid fa-envelope"></i></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-12">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Manage Events (CRUD)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            
                            <th class="p-4 border-b hover:bg-gray-200 transition cursor-pointer">
                                @php
                                    // 智能判断下一次点击是升序还是降序
                                    $isSortId = request('sort', 'id') === 'id';
                                    $nextDir = ($isSortId && request('direction', 'desc') === 'desc') ? 'asc' : 'desc';
                                @endphp
                                <a href="{{ route('admin.dashboard', ['sort' => 'id', 'direction' => $nextDir]) }}" class="flex items-center gap-1 group">
                                    ID 
                                    @if($isSortId)
                                        <i class="fa-solid fa-sort-{{ request('direction', 'desc') === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                    @else
                                        <i class="fa-solid fa-sort opacity-30 group-hover:opacity-100 transition"></i>
                                    @endif
                                </a>
                            </th>

                            <th class="p-4 border-b hover:bg-gray-200 transition cursor-pointer">
                                @php
                                    $isSortTitle = request('sort') === 'title';
                                    $nextTitleDir = ($isSortTitle && request('direction') === 'asc') ? 'desc' : 'asc';
                                @endphp
                                <a href="{{ route('admin.dashboard', ['sort' => 'title', 'direction' => $nextTitleDir]) }}" class="flex items-center gap-1 group">
                                    Event Name
                                    @if($isSortTitle)
                                        <i class="fa-solid fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                    @else
                                        <i class="fa-solid fa-sort opacity-30 group-hover:opacity-100 transition"></i>
                                    @endif
                                </a>
                            </th>

                            <th class="p-4 border-b">Date & Time</th>
                            <th class="p-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($events as $event)
                            <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0">
                                <td class="p-4 text-gray-500 font-bold">#{{ $event->id }}</td>
                                <td class="p-4 font-bold text-gray-900">{{ $event->title }}</td>
                                <td class="p-4 text-gray-600">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, h:i A') }}</td>
                               
                                <td class="p-4 text-right flex justify-end gap-2">
                                    <a href="{{ route('admin.events.edit', $event->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded transition inline-block">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded transition"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $events->appends(request()->query())->links() }}
            </div>
        </div>
        
    </div>

</body>
</html>