<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_events_instead_of_restaurant_menu(): void
    {
        $event = Event::create([
            'title' => 'Jazz Festival',
            'category' => 'Festival',
            'ticket_price' => 250000,
            'description' => 'Festival musik jazz tahunan.',
        ]);

        $response = $this->get(route('customer.index'));

        $response->assertOk();
        $response->assertSee('Jazz Festival');
        $response->assertSee('name="items['.$event->id.']"', false);
        $response->assertSee('name="customer_email"', false);
        $response->assertDontSee('Menu Restoran');
        $response->assertDontSee('Nomor Meja');
    }

    public function test_checkout_saves_ticket_order_and_detail(): void
    {
        $event = Event::create([
            'title' => 'Jazz Festival',
            'category' => 'Festival',
            'ticket_price' => 250000,
            'description' => 'Festival musik jazz tahunan.',
        ]);

        $response = $this->post(route('customer.checkout'), [
            'customer_name' => 'Ayu Pratama',
            'customer_email' => 'ayu@example.com',
            'items' => [
                $event->id => 2,
            ],
        ]);

        $response->assertRedirect(route('customer.index'));
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Ayu Pratama',
            'customer_email' => 'ayu@example.com',
            'total_price' => 500000,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('order_details', [
            'event_id' => $event->id,
            'quantity' => 2,
            'subtotal' => 500000,
        ]);
    }
}
