<?php

namespace App\Http\Controllers;

// Import model yang digunakan untuk berinteraksi dengan database
use App\Models\Event;       // Model untuk tabel orders (data pesanan utama)
use App\Models\Order;       // Model untuk tabel events (data event)
use App\Models\OrderDetail; // Model untuk tabel order_details (rincian item dalam pesanan)
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Menampilkan katalog event dan halaman pemesanan tiket.
     */
    public function index(): View
    {
        return view('customer.index', [
            'events' => Event::query()->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * (Opsional / Tidak digunakan karena form pemesanan langsung ada di index)
     */
    public function create()
    {
        //
    }

    /**
     * Memproses dan menyimpan pesanan baru dari pelanggan ke database.
     * URL: POST /checkout
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi input form: memastikan nama, email, dan daftar item sesuai aturan
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'items' => 'required|array',
            'items.*' => 'nullable|integer|min:0',
        ]);

        // 2. Filter event yang dipesan: hanya ambil item dengan tiket (quantity) lebih dari 0
        $orderedItems = array_filter($request->items, fn ($qty) => $qty > 0);

        // Jika pelanggan tidak memilih tiket satupun, kembalikan dengan pesan error
        if (empty($orderedItems)) {
            return back()->with('error', 'Pilih minimal satu event!');
        }

        // 3. Memulai Database Transaction
        DB::beginTransaction();
        try {
            // 4. Membuat record pesanan baru di tabel 'orders'
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'total_price' => 0,
                'status' => 'pending',
            ]);

            $totalPrice = 0;

            // 5. Looping setiap event yang dipesan untuk menghitung subtotal dan menyimpan ke 'order_details'
            foreach ($orderedItems as $eventId => $quantity) {
                $event = Event::findOrFail($eventId);

                $subtotal = $event->ticket_price * $quantity;
                $totalPrice += $subtotal;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            // 6. Update total harga akhir pada pesanan utama
            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            return back()->with('success', 'Pesanan #'.$order->id.' berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memproses pesanan: '.$e->getMessage());
        }
    }

    /**
     * Menampilkan dashboard admin yang berisi rekapitulasi semua pesanan masuk.
     * URL: GET /dashboard
     *
     * @return View
     */
    public function adminDashboard()
    {
        $orders = Order::with('orderDetails.event')->latest()->get();
        $totalEvents = Event::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'confirmed')->sum('total_price');
        $pendingOrders = Order::where('status', 'pending')->count();

        return view('dashboard', compact('orders', 'totalEvents', 'totalOrders', 'totalRevenue', 'pendingOrders'));
    }

    /**
     * Memperbarui status pesanan (pending -> confirmed -> cancelled).
     * URL: PATCH /orders/{id}/status
     *
     * @param  int  $id
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $status = strtolower($request->input('status', 'confirmed'));
        if ($status === 'selesai' || $status === 'lunas') {
            $status = 'confirmed';
        }

        $order = Order::findOrFail($id);
        $order->update(['status' => $status]);

        return back()->with('success', 'Status pesanan #'.$order->id.' berhasil diperbarui!');
    }

    /**
     * Display the specified resource.
     * (Disediakan oleh resource controller jika dibutuhkan di kemudian hari)
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * (Disediakan oleh resource controller jika dibutuhkan di kemudian hari)
     */
    public function edit(Order $order)
    {
        //
    }

    public function update(Request $request, Order $order)
    {
        //
    }

    public function destroy(Order $order)
    {
        //
    }
}
