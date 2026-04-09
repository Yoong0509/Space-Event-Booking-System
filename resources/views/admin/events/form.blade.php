<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->exists ? 'Edit Event' : 'Create Ultimate Event' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased py-10 px-4">

    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-gray-900 p-8 text-white flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-black">{{ $event->exists ? 'Edit: ' . $event->title : 'Publish Event' }}</h2>
                <p class="text-gray-400 mt-2">{{ $event->exists ? 'Update your event details below.' : 'Upload poster, set custom venue, and define ticket pricing.' }}</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-2xl"></i></a>
        </div>

        <form action="{{ $event->exists ? route('admin.events.update', $event->id) : route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-6 py-4 rounded-xl shadow-sm">
                            <h4 class="font-bold mb-2"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Oops! Something went wrong:</h4>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
            @if($event->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Event Title</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Event Date & Time</label>
                    <input type="datetime-local" name="event_date" value="{{ old('event_date', $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i') : '') }}" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                    <select name="category_id" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 shadow-sm">
                        <option value="">Select Category...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Upload Event Poster {{ $event->exists ? '(Leave empty to keep current)' : '' }}
                    </label>
                    <div class="flex items-center gap-4">
                        @if($event->exists && $event->image)
                            <img src="{{ asset($event->image) }}" alt="Current Poster" class="w-12 h-12 rounded border border-gray-200 object-cover">
                        @endif
                        <input type="file" name="image" accept="image/*" {{ $event->exists ? '' : 'required' }} class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-300 rounded-xl bg-white shadow-sm">
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 mb-8">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Select Existing Venue</label>
                    <select name="venue_id" class="w-full md:w-1/2 rounded-xl border-gray-300 focus:border-blue-500 shadow-sm">
                        <option value="">-- Choose Venue --</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ old('venue_id', $event->venue_id) == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-300"></div></div>
                    <div class="relative flex justify-center"><span class="bg-gray-50 px-4 text-sm text-gray-500 font-bold uppercase">OR</span></div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-blue-600 mb-3">Create Custom Venue (Fills automatically to database)</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" name="new_venue" placeholder="Venue Name (e.g. Grand Hall)" class="w-full rounded-xl border-blue-200 focus:border-blue-500 shadow-sm bg-blue-50">
                        </div>
                        <div>
                            <input type="text" name="new_venue_address" placeholder="Full Address" class="w-full rounded-xl border-blue-200 focus:border-blue-500 shadow-sm bg-blue-50">
                        </div>
                        <div>
                            <input type="number" name="new_venue_capacity" placeholder="Capacity (e.g. 1000)" class="w-full rounded-xl border-blue-200 focus:border-blue-500 shadow-sm bg-blue-50">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">* If you type a name here, the dropdown selection above will be ignored.</p>
                </div>
            </div>

            <div class="mb-10">
                <label class="block text-sm font-bold text-gray-700 mb-2">Event Description</label>
                <textarea name="description" rows="4" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 shadow-sm">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="mb-10">
                <div class="flex justify-between items-end mb-4 border-b pb-4">
                    <h3 class="text-xl font-bold text-gray-800">Configure Ticket Types</h3>
                    <button type="button" onclick="addTicketRow()" class="bg-green-100 hover:bg-green-200 text-green-700 font-bold py-2 px-4 rounded-lg transition text-sm">
                        + Add Ticket Tier
                    </button>
                </div>
                
                <div id="tickets-container" class="space-y-4">
                    @if($event->exists && $event->tickets->count() > 0)
                        @foreach($event->tickets as $index => $ticket)
                            <div class="flex gap-4 items-center bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <div class="flex-grow">
                                    <input type="text" name="tickets[{{ $index }}][name]" value="{{ $ticket->name }}" placeholder="e.g. VIP Zone" required class="w-full rounded-lg border-gray-300">
                                </div>
                                <div class="w-1/4">
                                    <input type="number" name="tickets[{{ $index }}][price]" value="{{ $ticket->price }}" step="0.01" placeholder="Price (RM)" required class="w-full rounded-lg border-gray-300">
                                </div>
                                <div class="w-1/4">
                                    <input type="number" name="tickets[{{ $index }}][quantity]" value="{{ $ticket->quantity }}" placeholder="Total Stock" required class="w-full rounded-lg border-gray-300">
                                </div>
                                @if($index > 0)
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold px-2">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="flex gap-4 items-center bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <div class="flex-grow">
                                <input type="text" name="tickets[0][name]" placeholder="e.g. VIP Zone" required class="w-full rounded-lg border-gray-300">
                            </div>
                            <div class="w-1/4">
                                <input type="number" name="tickets[0][price]" step="0.01" placeholder="Price (RM)" required class="w-full rounded-lg border-gray-300">
                            </div>
                            <div class="w-1/4">
                                <input type="number" name="tickets[0][quantity]" placeholder="Total Stock" required class="w-full rounded-lg border-gray-300">
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100">
                <button type="submit" class="group flex items-center justify-center px-8 py-3 text-lg font-bold text-white transition-all duration-300 bg-gray-900 rounded-xl hover:bg-black hover:shadow-2xl hover:shadow-gray-900/50 hover:-translate-y-1 border border-gray-700">
                    {{ $event->exists ? 'Save Changes' : 'Publish Event' }}
                </button>
            </div>
        </form>
    </div>

    <script>
        // 如果是编辑模式，获取现有的票种数量作为起始索引，否则从 1 开始
        let ticketIndex = {{ $event->exists ? $event->tickets->count() : 1 }};

        function addTicketRow() {
            const container = document.getElementById('tickets-container');
            const newRow = document.createElement('div');
            newRow.className = "flex gap-4 items-center bg-white p-4 rounded-xl border border-gray-200 shadow-sm";
            newRow.innerHTML = `
                <div class="flex-grow">
                    <input type="text" name="tickets[${ticketIndex}][name]" placeholder="e.g. Standard / CAT 1" required class="w-full rounded-lg border-gray-300">
                </div>
                <div class="w-1/4">
                    <input type="number" name="tickets[${ticketIndex}][price]" step="0.01" placeholder="Price (RM)" required class="w-full rounded-lg border-gray-300">
                </div>
                <div class="w-1/4">
                    <input type="number" name="tickets[${ticketIndex}][quantity]" placeholder="Total Stock" required class="w-full rounded-lg border-gray-300">
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold px-2">
                    <i class="fa-solid fa-trash"></i>
                </button>
            `;
            container.appendChild(newRow);
            ticketIndex++;
        }
    </script>

</body>
</html>