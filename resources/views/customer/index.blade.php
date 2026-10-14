<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event dan Tiket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
    <div class="max-w-5xl mx-auto">


        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Temukan Event</h1>
            <p class="text-gray-500 text-sm mt-1">Pilih event dan jumlah tiket yang ingin dipesan</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 text-center font-semibold">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 text-center font-semibold">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 font-semibold">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-wrap justify-center gap-3 mb-8">
            <button type="button" onclick="filterCategory('all', this)" class="btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-blue-600 text-white shadow-md">Semua Event</button>
            <button type="button" onclick="filterCategory('Musik', this)" class="btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-white text-gray-600 hover:bg-gray-200 border">Musik</button>
            <button type="button" onclick="filterCategory('Festival', this)" class="btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-white text-gray-600 hover:bg-gray-200 border">Festival</button>
            <button type="button" onclick="filterCategory('Seminar', this)" class="btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-white text-gray-600 hover:bg-gray-200 border">Seminar</button>
        </div>

        <form id="orderForm" action="{{ route('customer.checkout') }}" method="POST">
            @csrf

            <div class="bg-white p-6 rounded-xl shadow-sm border mb-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4 pb-2 border-b">Informasi Pemesan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required placeholder="Masukkan nama pemesan" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label for="customer_email" class="block text-sm font-semibold text-gray-600 mb-1">Email</label>
                        <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required placeholder="nama@email.com" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <h2 class="text-lg font-bold text-gray-700 mb-4">Pilih Event</h2>
            @if($events->isEmpty())
                <div class="bg-white border rounded-xl p-8 text-center text-gray-500">
                    Belum ada event yang tersedia.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div class="event-card bg-white rounded-xl shadow-sm border overflow-hidden flex flex-col justify-between" data-category="{{ $event->category }}">
                            <div>
                                @if($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-40 object-cover">
                                @else
                                    <div class="bg-gray-200 h-40 flex items-center justify-center text-gray-400 font-medium">Tanpa Gambar</div>
                                @endif

                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2.5 py-0.5 rounded">{{ $event->category }}</span>
                                        <span class="font-bold text-green-600">Rp {{ number_format($event->ticket_price) }}</span>
                                    </div>
                                    <h3 class="font-bold text-gray-800 text-lg">{{ $event->title }}</h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $event->description }}</p>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50 border-t">
                                <label for="event-{{ $event->id }}" class="block text-xs font-semibold text-gray-500 mb-1">Jumlah Tiket</label>
                                <input type="number" id="event-{{ $event->id }}" name="items[{{ $event->id }}]" min="0" value="{{ old('items.' . $event->id, 0) }}" data-name="{{ $event->title }}" data-price="{{ $event->ticket_price }}" class="item-qty w-full border rounded-lg px-3 py-1.5 text-center font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 text-right">
                <button type="button" onclick="showConfirmationModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-xl shadow-md transition">Pesan Tiket</button>
            </div>

            <div id="confirmModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl transform transition-all">
                    <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-4">Konfirmasi Pesanan</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex justify-between"><span class="font-semibold">Nama:</span> <span id="modalName" class="text-gray-900 font-bold"></span></div>
                        <div class="flex justify-between"><span class="font-semibold">Email:</span> <span id="modalEmail" class="text-gray-900 font-bold"></span></div>
                    </div>
                    <div class="border-t border-b py-3 mb-4 max-h-48 overflow-y-auto">
                        <p class="font-semibold text-xs text-gray-400 uppercase mb-2">Rincian Tiket</p>
                        <ul id="modalItemList" class="space-y-2 text-sm"></ul>
                    </div>
                    <div class="flex justify-between items-center text-lg font-bold text-gray-800 mb-6">
                        <span>Total Pembayaran:</span>
                        <span id="modalTotalPrice" class="text-green-600 text-xl">Rp 0</span>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeConfirmationModal()" class="w-1/2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 rounded-xl transition">Batal</button>
                        <button type="submit" class="w-1/2 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl shadow transition">Ya, Pesan Tiket</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function filterCategory(category, element) {
            document.querySelectorAll('.btn-category').forEach(button => {
                button.className = 'btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-white text-gray-600 hover:bg-gray-200 border';
            });
            element.className = 'btn-category px-5 py-2 rounded-full font-semibold text-sm transition bg-blue-600 text-white shadow-md';

            document.querySelectorAll('.event-card').forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                card.style.display = category === 'all' || cardCategory === category ? 'flex' : 'none';
            });
        }

        function showConfirmationModal() {
            const name = document.getElementById('customer_name').value.trim();
            const email = document.getElementById('customer_email').value.trim();

            if (!name || !email) {
                alert('Silakan isi nama lengkap dan email terlebih dahulu!');
                return;
            }

            const selectedItems = [];
            let grandTotal = 0;

            document.querySelectorAll('.item-qty').forEach(input => {
                const quantity = Number.parseInt(input.value, 10) || 0;
                if (quantity > 0) {
                    const price = Number.parseFloat(input.dataset.price);
                    const subtotal = quantity * price;
                    selectedItems.push({
                        name: input.dataset.name,
                        quantity,
                        price,
                        subtotal,
                    });
                    grandTotal += subtotal;
                }
            });

            if (selectedItems.length === 0) {
                alert('Pilih minimal satu event dengan jumlah tiket lebih dari 0!');
                return;
            }

            const itemList = document.getElementById('modalItemList');
            itemList.replaceChildren();
            selectedItems.forEach(item => {
                const listItem = document.createElement('li');
                listItem.className = 'flex justify-between items-center';

                const details = document.createElement('div');
                const itemName = document.createElement('span');
                itemName.className = 'font-bold text-gray-800';
                itemName.textContent = item.name;

                const itemPrice = document.createElement('span');
                itemPrice.className = 'text-xs text-gray-500 block';
                itemPrice.textContent = `x${item.quantity} @ Rp ${item.price.toLocaleString('id-ID')}`;

                const subtotal = document.createElement('span');
                subtotal.className = 'font-semibold text-gray-700';
                subtotal.textContent = `Rp ${item.subtotal.toLocaleString('id-ID')}`;

                details.append(itemName, itemPrice);
                listItem.append(details, subtotal);
                itemList.append(listItem);
            });

            document.getElementById('modalName').textContent = name;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalTotalPrice').textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;

            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeConfirmationModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>
