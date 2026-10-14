<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Event</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('events.index') }}" class="text-blue-600 hover:underline mb-4 inline-block font-semibold">&larr; Kembali</a>

        <div class="bg-white border shadow-sm rounded-lg p-6">
            <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama / Judul Event</label>
                    <input type="text" name="title" class="border rounded w-full py-2 px-3 text-gray-700" value="{{ old('title', $event->title) }}" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                    <select name="category" class="border rounded w-full py-2 px-3 text-gray-700" required>
                        <option value="Musik" {{ old('category', $event->category) == 'Musik' ? 'selected' : '' }}>Musik</option>
                        <option value="Festival" {{ old('category', $event->category) == 'Festival' ? 'selected' : '' }}>Festival</option>
                        <option value="Seminar" {{ old('category', $event->category) == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Harga Tiket</label>
                    <input type="number" name="ticket_price" class="border rounded w-full py-2 px-3 text-gray-700" value="{{ old('ticket_price', $event->ticket_price) }}" required>
                    @error('ticket_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea name="description" rows="4" class="border rounded w-full py-2 px-3 text-gray-700" required>{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Gambar</label>
                    @if($event->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $event->image) }}" class="w-16 h-16 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="image" class="border rounded w-full py-2 px-3 text-gray-700">
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    Update
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
