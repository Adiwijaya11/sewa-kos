<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListingBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get existing Owners or create some if missing
        $ownerIds = User::where('role', 'owner')->pluck('id')->toArray();
        if (empty($ownerIds)) {
            // Create a backup owner
            $owner = User::create([
                'name' => 'Mega Pratama',
                'email' => 'owner_batch@kosinaja.com',
                'phone' => '082211447788',
                'role' => 'owner',
                'is_verified' => true,
                'password' => bcrypt('password')
            ]);
            $ownerIds[] = $owner->id;
        }

        // 2. Fetch all facilities to link
        $facilityIds = Facility::pluck('id')->toArray();
        if (empty($facilityIds)) {
            $facilityIds = [1, 2, 3, 4, 5, 6, 7, 8];
        }

        // 3. Setup lists for realistic Indonesian city mock data
        $citiesPool = [
            ['name' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'lat' => -6.2615, 'lng' => 106.8106],
            ['name' => 'Jakarta Pusat', 'province' => 'DKI Jakarta', 'lat' => -6.1865, 'lng' => 106.8270],
            ['name' => 'Jakarta Barat', 'province' => 'DKI Jakarta', 'lat' => -6.1683, 'lng' => 106.7588],
            ['name' => 'Bandung', 'province' => 'Jawa Barat', 'lat' => -6.9175, 'lng' => 107.6191],
            ['name' => 'Yogyakarta', 'province' => 'DIY', 'lat' => -7.7956, 'lng' => 110.3695],
            ['name' => 'Surabaya', 'province' => 'Jawa Timur', 'lat' => -7.2575, 'lng' => 112.7521],
            ['name' => 'Malang', 'province' => 'Jawa Timur', 'lat' => -7.9839, 'lng' => 112.6210],
            ['name' => 'Semarang', 'province' => 'Jawa Tengah', 'lat' => -6.9932, 'lng' => 110.4203],
            ['name' => 'Solo (Surakarta)', 'province' => 'Jawa Tengah', 'lat' => -7.5755, 'lng' => 110.8243],
            ['name' => 'Sleman', 'province' => 'DIY', 'lat' => -7.7210, 'lng' => 110.3639],
            ['name' => 'Medan', 'province' => 'Sumatera Utara', 'lat' => 3.5952, 'lng' => 98.6722],
            ['name' => 'Makassar', 'province' => 'Sulawesi Selatan', 'lat' => -5.1476, 'lng' => 119.4327],
            ['name' => 'Denpasar', 'province' => 'Bali', 'lat' => -8.6705, 'lng' => 115.2126],
            ['name' => 'Bogor', 'province' => 'Jawa Barat', 'lat' => -6.5971, 'lng' => 106.8060],
            ['name' => 'Depok', 'province' => 'Jawa Barat', 'lat' => -6.4025, 'lng' => 106.7942],
        ];

        $imagePool = [
            'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1617806118233-18e1db207f62?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1536376072261-38c75010e6c9?auto=format&fit=crop&w=800&q=80',
        ];

        $genderPool = ['putra', 'putri', 'campur'];
        $adjectives = ['Exclusive', 'Cozy Room', 'Premium', 'Minimalis', 'Luxury Suite', 'Griya', 'Residence', 'Smart Living', 'Asri', 'Nyaman', 'Modern'];
        $names = ['Emerald', 'Ruby', 'Sakura', 'Pandega', 'Cendrawasih', 'Nirwana', 'Melati', 'Lavender', 'Bougenville', 'Agung', 'Cendana', 'Kencana', 'Kartika', 'Indah', 'Pratama'];
        $roomSizes = ['3x3', '3x4', '4x4', '3.5x4', '4x5'];

        $totalToInsert = 1500;
        $batchSize = 300; // insert 300 rows at a time to prevent memory limits
        $batches = ceil($totalToInsert / $batchSize);

        $this->command->info("Memulai seeding 1500 data kos tambahan...");

        for ($b = 0; $b < $batches; $b++) {
            $listingsData = [];
            $currentBatchSize = min($batchSize, $totalToInsert - ($b * $batchSize));
            
            // Build listings bulk data
            for ($i = 0; $i < $currentBatchSize; $i++) {
                $city = $citiesPool[array_rand($citiesPool)];
                $gender = $genderPool[array_rand($genderPool)];
                $adj = $adjectives[array_rand($adjectives)];
                $name = $names[array_rand($names)];
                $roomSize = $roomSizes[array_rand($roomSizes)];
                $ownerId = $ownerIds[array_rand($ownerIds)];
                
                $title = "Kos " . ucfirst($gender) . " " . $name . " " . $adj . " " . $city['name'] . " " . (($b * $batchSize) + $i + 1);
                $price = rand(5, 25) * 100000; // Rp 500k - Rp 2.5M
                $totalRooms = rand(5, 20);
                $availableRooms = rand(1, $totalRooms);
                
                $listingsData[] = [
                    'owner_id' => $ownerId,
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . Str::lower(Str::random(5)),
                    'description' => "Hunian kos modern " . $gender . " berlokasi strategis di " . $city['name'] . ". Sangat cocok bagi mahasiswa maupun karyawan yang menginginkan tempat tinggal nyaman, bersih, dan berfasilitas lengkap dengan harga kompetitif. Lingkungan sangat aman, asri, serta dekat dengan akses transportasi umum, warung makan, minimarket, dan fasilitas publik lainnya.",
                    'price' => $price,
                    'address' => "Jl. " . $name . " Indah No. " . rand(1, 99) . ", Kelurahan " . $name . ", Kecamatan " . $city['name'],
                    'city' => $city['name'],
                    'province' => $city['province'],
                    'latitude' => $city['lat'] + (rand(-30, 30) / 10000),
                    'longitude' => $city['lng'] + (rand(-30, 30) / 10000),
                    'gender_type' => $gender,
                    'room_size' => $roomSize,
                    'max_people' => rand(1, 3),
                    'total_rooms' => $totalRooms,
                    'available_rooms' => $availableRooms,
                    'near_campus' => 'Kampus Terdekat (' . rand(3, 10) . ' mnt)',
                    'near_mall' => 'Mall Terdekat (' . rand(5, 15) . ' mnt)',
                    'near_hospital' => 'RS Terdekat (' . rand(4, 10) . ' mnt)',
                    'near_station' => 'Stasiun / Halte Transit (' . rand(3, 12) . ' mnt)',
                    'is_verified' => (rand(1, 10) <= 7), // 70% verified
                    'status' => 'active',
                    'views' => rand(0, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk Insert Listings
            DB::transaction(function () use ($listingsData, $facilityIds, $imagePool) {
                // Insert Listings
                Listing::insert($listingsData);
                
                // Fetch the recently inserted listings' IDs in this transaction
                $firstTitle = $listingsData[0]['title'];
                $lastTitle = end($listingsData)['title'];
                
                $insertedListings = Listing::whereBetween('title', [$firstTitle, $lastTitle])
                    ->orderBy('id', 'asc')
                    ->get(['id']);
                
                $facilitiesInsert = [];
                $imagesInsert = [];

                foreach ($insertedListings as $listing) {
                    // Seed random facilities (between 4 and 7)
                    $shuffledFacs = $facilityIds;
                    shuffle($shuffledFacs);
                    $selectedFacs = array_slice($shuffledFacs, 0, rand(4, 7));
                    
                    foreach ($selectedFacs as $facId) {
                        $facilitiesInsert[] = [
                            'listing_id' => $listing->id,
                            'facility_id' => $facId,
                        ];
                    }

                    // Seed 1-2 random images from pool
                    $shuffledImgs = $imagePool;
                    shuffle($shuffledImgs);
                    $imagesInsert[] = [
                        'listing_id' => $listing->id,
                        'image' => $shuffledImgs[0],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    if (rand(0, 1)) {
                        $imagesInsert[] = [
                            'listing_id' => $listing->id,
                            'image' => $shuffledImgs[1],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Bulk insert listing facilities and images
                DB::table('listing_facilities')->insert($facilitiesInsert);
                ListingImage::insert($imagesInsert);
            });

            $this->command->info("Batch " . ($b + 1) . " / " . $batches . " (" . count($listingsData) . " kos) berhasil dimasukkan.");
        }

        $this->command->info("Berhasil! 1500 data kos tambahan berhasil di-seeding dengan cepat!");
    }
}
