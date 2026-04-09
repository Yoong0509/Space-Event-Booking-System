<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Venue;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\PromoCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash; // 记得引入 Hash 来加密密码

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // 1. create some users (including an admin and some users)
        // ==========================================
        
        // create a fixed admin user for you to log in and manage the system 
        $admin = User::create([
            'name' => 'Yoong (Admin)',
            'email' => 'admin@utar.edu.my', 
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        
        $user = User::create([
            'name' => 'Yoong (User)',
            'email' => 'yoong@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        // randomly generate 5 more users using the factory (you can log in with any of these users, password is 'password123')
        User::factory(5)->create();


        // ==========================================
        // 2. create some categories
        // ==========================================
        $concertCategory = Category::create([
            'name' => 'Concert', 
            'slug' => 'concert', 
            'description' => 'Live music and concerts',
            'icon'=> 'images/events/concert_logo.jpg' 
        ]);
        
        $workshopCategory = Category::create([
            'name' => 'Workshop', 
            'slug' => 'workshop', 
            'description' => 'Tech and educational workshops',
            'icon'=> 'images/events/workshop_logo.jpg'
        ]);

        $sportsCategory = Category::create([
            'name' => 'Sports', 
            'slug' => 'sports', 
            'description' => 'Sporting events and matches',
            'icon'=> 'images/events/sport_logo.jpg'
        ]);

        $exhibitionCategory = Category::create([
            'name' => 'Exhibition', 
            'slug' => 'exhibition', 
            'description' => 'Art and cultural exhibitions',
            'icon'=> 'images/events/exhibition_logo.jpg'
        ]);

        $comicCategory = Category::create([
            'name' => 'Comic', 
            'slug' => 'comic', 
            'description' => 'Comic conventions and fan events',
            'icon'=> 'images/events/comic_logo.jpg'
        ]);

        $gamingCategory = Category::create([
            'name' => 'Gaming', 
            'slug' => 'gaming', 
            'description' => 'Esports and gaming events',
            'icon'=> 'images/events/game_logo.jpg'
        ]);




        // ==========================================
        // 3. create some venues
        // ==========================================
        $venue = Venue::create([
            'name' => 'Axiata Arena Bukit Jalil',
            'address' => 'Bukit Jalil, Kuala Lumpur, Malaysia',
            'capacity' => 10000,
        ]);

        $venue = Venue::create([
            'name' => 'Dewan Filharmonik PETRONAS',
            'address' => 'Kuala Lumpur, Malaysia',
            'capacity' => 15000,
        ]);

        $venue = Venue::create([
            'name' => 'Kuala Lumpur Convention Centre',
            'address' => 'Jalan Pinang, Kuala Lumpur, Malaysia',
            'capacity' => 5000,
        ]);

        $venue = Venue::create([
            'name' => 'Penang Art Gallery',
            'address' => 'Lebuh Light, George Town, Penang, Malaysia',
            'capacity' => 500,
        ]);

        $venue = Venue::create([
            'name' => 'Bukit Jalil National Stadium',
            'address' => 'Jalan Stadium, Bukit Jalil, Kuala Lumpur, Malaysia',
            'capacity' => 10000,
        ]);

        $venue = Venue::create([
            'name' => 'Malaysia International Trade and Exhibition Centre',
            'address' => 'Jalan Dutamas 2, Kuala Lumpur, Malaysia',
            'capacity' => 12000,
        ]);




        // ==========================================
        // 4. create some events linked to the categories and venues we just created
        // ==========================================
        $event = Event::create([
            'category_id' => $concertCategory->id,
            'venue_id' => 1,
            'title' => 'Jay Chou Carnival World Tour 2026',
            'description' => 'The highly anticipated Jay Chou concert is back!',
            'event_date' => Carbon::now()->addMonths(2), 
            'image' => 'images/events/jaychou-poster.jpg', 
        ]);

        $event = Event::create([
            'category_id' => $concertCategory->id,
            'venue_id' => 1,
            'title' => 'LANY “Soft” World Tour',
            'description' => 'The highly anticipated LANY concert is back!',
            'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2026-12-20 20:00:00'),
            'image' => 'images/events/lany-poster.jpg', 
        ]);

        $event = Event::create([
            'category_id' => $concertCategory->id,
            'venue_id' => 2,
            'title' => 'The Music of Queen…Lives On!',
            'description' => 'The highly anticipated Queen tribute concert is back!',
            'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2026-10-10 19:30:00'),
            'image' => 'images/events/queen-poster.jpg', 
        ]);

        $event = Event::create([
            'category_id' => $workshopCategory->id,
            'venue_id' => 3,
            'title' => 'AI & Cloud Computing Workshop',
            'description' => 'The highly anticipated AI & Cloud Computing Workshop is back!',
            'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2026-06-15 09:00:00'),
            'image' => 'images/events/ai-poster.jpg', 
        ]);

        $event = Event::create([
            'category_id' => $exhibitionCategory->id,
            'venue_id' => 4,
            'title' => 'Digital Art & NFT Expo',
            'description' => 'The highly anticipated Digital Art & NFT Expo is back!',
            'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2026-07-10 10:00:00'),
            'image' => 'images/events/art-poster.jpg', 
        ]);

        $event = Event::create([
            'category_id' => $sportsCategory->id,
            'venue_id' => 5,
            'title' => 'KL Charity Marathon 2026',
            'description' => 'The highly anticipated KL Charity Marathon 2026 is back!',
            'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2026-08-20 06:00:00'),
            'image' => 'images/events/marathon-poster.jpg', 
        ]);

        $event = Event::create([
            'category_id' => $comicCategory->id,
            'venue_id' =>3,
            'title' => 'Comic Fiesta 2026',
            'description' => 'The highly anticipated Comic Fiesta 2026 is back!',
            'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2026-07-18 10:00:00'),
            'image' => 'images/events/comic1-poster.jpg', 
        ]);

        $event = Event::create([
            'category_id' => $gamingCategory->id,
            'venue_id' => 6,
            'title' => 'Esports Championship 2026',
            'description' => 'The highly anticipated Esports Championship 2026 is back!',
            'event_date' => Carbon::createFromFormat('Y-m-d H:i:s', '2026-09-05 09:00:00'),
            'image' => 'images/events/game-poster.jpg', 
        ]);


        // ==========================================
        // 5. create some tickets linked to the events we just created
        // ==========================================
        Ticket::create([
            'event_id' => $event->id,
            'name' => 'VIP Zone (Standing)',
            'price' => 888.00,
            'stock' => 1000,
            'status' => 'available'
        ]);

        Ticket::create([
            'event_id' => $event->id,
            'name' => 'CAT 1 (Seated)',
            'price' => 588.00,
            'stock' => 2000,
            'status' => 'available'
        ]);

        Ticket::create([
            'event_id' => $event->id,
            'name' => 'CAT 2 (Seated)',
            'price' => 388.00,
            'stock' => 3000,
            'status' => 'available'
        ]);


        // ==========================================
        // 6. create some promo codes for testing the discount functionality
        // ==========================================

        PromoCode::create([
            'code' => 'UTAR2026',
            'type' => 'percentage', // 20% off
            'value' => 20.00,
            'usage_limit' => 100, // only allow 100 uses across all users
            'used_count' => 0,
            'valid_until' => \Carbon\Carbon::now()->addMonths(1), // one month from now
        ]);

        PromoCode::create([
            'code' => 'MINUS50',
            'type' => 'fixed', // directly reduce RM50
            'value' => 50.00,
            'usage_limit' => null, // unlimited uses
            'used_count' => 0,
            'valid_until' => null, // no expiration
        ]);

        
        
    }
}