<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CustomerReturn;
use App\Models\Product;
use App\Models\Sales;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with varied time ranges across all tables.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow clean reset
        Schema::disableForeignKeyConstraints();

        DB::table('customer_returns')->truncate();
        DB::table('stock_outs')->truncate();
        DB::table('stock_ins')->truncate();
        DB::table('sales')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();

        $this->command->info('1. Seeding Users (Admin, Kasir, Manajer, Gudang)...');

        $admin = User::create([
            'name' => 'jujun',
            'email' => 'jujun11@gmail.com',
            'password' => '11',
            'role' => 'manajer',
        ]);
        User::where('id', $admin->id)->update(['created_at' => '2026-01-02 08:00:00', 'updated_at' => '2026-01-02 08:00:00']);

        $kasir = User::create([
            'name' => 'epi',
            'email' => 'epi11@gmail.com',
            'password' => '11',
            'role' => 'kasir',
        ]);
        User::where('id', $kasir->id)->update(['created_at' => '2026-01-05 09:15:00', 'updated_at' => '2026-01-05 09:15:00']);

        $manajer = User::create([
            'name' => 'asep',
            'email' => 'asep11@gmail.com',
            'password' => '11',
            'role' => 'gudang',
        ]);
        User::where('id', $manajer->id)->update(['created_at' => '2026-01-10 10:30:00', 'updated_at' => '2026-01-10 10:30:00']);

        $this->command->info('2. Seeding Categories across Jan - March 2026...');
        
        $categoriesData = [
            ['category_name' => 'Handle Gas', 'type_name' => 'Daytona', 'date' => '2026-01-10 09:00:00'],
            ['category_name' => 'Handle Rem', 'type_name' => 'Brembo', 'date' => '2026-01-14 14:20:00'],
            ['category_name' => 'Spakbor Depan', 'type_name' => 'TGP', 'date' => '2026-01-20 11:15:00'],
            ['category_name' => 'Spakbor Belakang', 'type_name' => 'MHR', 'date' => '2026-02-05 10:00:00'],
            ['category_name' => 'Oli', 'type_name' => 'Motul', 'date' => '2026-02-18 13:45:00'],
            ['category_name' => 'Lampu', 'type_name' => 'Osram', 'date' => '2026-03-02 08:30:00'],
            ['category_name' => 'Spion', 'type_name' => 'Rizoma', 'date' => '2026-03-15 15:10:00'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $cat = Category::create([
                'category_name' => $c['category_name'],
                'type_name' => $c['type_name'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
            Category::where('id', $cat->id)->update(['created_at' => $c['date'], 'updated_at' => $c['date']]);
            $categories[] = $cat;
        }


        $this->command->info('3. Seeding Products across Jan - April 2026 with Normal & Low Stock...');

        $productsData = [
            // Handle Gas (cat 0 - Daytona)
            ['name' => 'Handle Gas Daytona Racing Universal', 'cat' => 0, 'price' => 185000, 'stock' => 42, 'safety' => 15, 'daily' => 2.5, 'lead' => 4, 'reorder' => 25, 'date' => '2026-01-12 10:00:00'],
            ['name' => 'Handle Gas Daytona CNC Aluminium', 'cat' => 0, 'price' => 275000, 'stock' => 18, 'safety' => 10, 'daily' => 1.2, 'lead' => 5, 'reorder' => 16, 'date' => '2026-01-18 11:30:00'],
            ['name' => 'Handle Gas Daytona Slim Vario 150', 'cat' => 0, 'price' => 145000, 'stock' => 8, 'safety' => 10, 'daily' => 1.5, 'lead' => 3, 'reorder' => 15, 'date' => '2026-01-25 14:00:00'], // LOW STOCK
            
            // Handle Rem (cat 1 - Brembo)
            ['name' => 'Handle Rem Brembo Set Kanan Kiri', 'cat' => 1, 'price' => 320000, 'stock' => 25, 'safety' => 8, 'daily' => 0.8, 'lead' => 4, 'reorder' => 12, 'date' => '2026-02-02 09:15:00'],
            ['name' => 'Handle Rem Brembo CNC Universal', 'cat' => 1, 'price' => 250000, 'stock' => 6, 'safety' => 12, 'daily' => 1.8, 'lead' => 5, 'reorder' => 21, 'date' => '2026-02-10 13:20:00'], // LOW STOCK
            ['name' => 'Handle Rem Brembo Lipat Adjustable', 'cat' => 1, 'price' => 210000, 'stock' => 30, 'safety' => 10, 'daily' => 1.0, 'lead' => 6, 'reorder' => 16, 'date' => '2026-02-14 15:45:00'],

            // Spakbor Depan (cat 2 - TGP)
            ['name' => 'Spakbor Depan TGP Carbon Vario 125', 'cat' => 2, 'price' => 165000, 'stock' => 180, 'safety' => 40, 'daily' => 8.5, 'lead' => 2, 'reorder' => 57, 'date' => '2026-02-20 08:30:00'],
            ['name' => 'Spakbor Depan TGP Sport NMAX', 'cat' => 2, 'price' => 195000, 'stock' => 95, 'safety' => 25, 'daily' => 4.0, 'lead' => 3, 'reorder' => 37, 'date' => '2026-02-24 10:10:00'],
            ['name' => 'Spakbor Depan TGP Karbon Aerox 155', 'cat' => 2, 'price' => 185000, 'stock' => 22, 'safety' => 30, 'daily' => 6.0, 'lead' => 2, 'reorder' => 42, 'date' => '2026-03-01 11:00:00'], // LOW STOCK

            // Spakbor Belakang (cat 3 - MHR)
            ['name' => 'Spakbor Belakang MHR Undertail CBR 150', 'cat' => 3, 'price' => 225000, 'stock' => 110, 'safety' => 25, 'daily' => 3.5, 'lead' => 3, 'reorder' => 36, 'date' => '2026-03-05 14:00:00'],
            ['name' => 'Spakbor Belakang MHR Short R15 V3', 'cat' => 3, 'price' => 175000, 'stock' => 35, 'safety' => 15, 'daily' => 2.0, 'lead' => 4, 'reorder' => 23, 'date' => '2026-03-12 09:40:00'],
            ['name' => 'Spakbor Belakang MHR Hugger PCX 160', 'cat' => 3, 'price' => 195000, 'stock' => 14, 'safety' => 20, 'daily' => 4.2, 'lead' => 2, 'reorder' => 28, 'date' => '2026-03-18 16:15:00'], // LOW STOCK

            // Oli (cat 4 - Motul)
            ['name' => 'Oli Motul Scooter Power 10W-40 0.8L', 'cat' => 4, 'price' => 95000, 'stock' => 240, 'safety' => 50, 'daily' => 12.0, 'lead' => 2, 'reorder' => 74, 'date' => '2026-03-22 10:30:00'],
            ['name' => 'Oli Motul 5100 4T 10W-40 1L', 'cat' => 4, 'price' => 130000, 'stock' => 65, 'safety' => 20, 'daily' => 5.5, 'lead' => 2, 'reorder' => 31, 'date' => '2026-04-02 13:00:00'],

            // Lampu (cat 5 - Osram)
            ['name' => 'Lampu Osram LED H6 Motor 12V', 'cat' => 5, 'price' => 145000, 'stock' => 28, 'safety' => 10, 'daily' => 1.4, 'lead' => 5, 'reorder' => 17, 'date' => '2026-04-10 11:20:00'],
            ['name' => 'Lampu Osram Sein LED Motor Universal', 'cat' => 5, 'price' => 85000, 'stock' => 12, 'safety' => 5, 'daily' => 0.5, 'lead' => 7, 'reorder' => 9, 'date' => '2026-04-15 15:50:00'],

            // Spion (cat 6 - Rizoma)
            ['name' => 'Spion Rizoma Tomok Universal CNC', 'cat' => 6, 'price' => 350000, 'stock' => 20, 'safety' => 8, 'daily' => 1.0, 'lead' => 4, 'reorder' => 12, 'date' => '2026-04-18 09:00:00'],
            ['name' => 'Spion Rizoma Spy-R Sport Bulat', 'cat' => 6, 'price' => 285000, 'stock' => 15, 'safety' => 6, 'daily' => 0.8, 'lead' => 5, 'reorder' => 10, 'date' => '2026-04-22 14:30:00'],
        ];

        $products = [];
        foreach ($productsData as $p) {
            $prod = Product::create([
                'category_id' => $categories[$p['cat']]->id,
                'type_name' => $categories[$p['cat']]->type_name,
                'product_name' => $p['name'],
                'price' => $p['price'],
                'cost_price' => round($p['price'] * 0.70, -3),
                'stock' => $p['stock'],
                'safety_stock' => $p['safety'],
                'daily_usage' => $p['daily'],
                'lead_time' => $p['lead'],
                'reorder_point' => $p['reorder'],
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);
            Product::where('id', $prod->id)->update(['created_at' => $p['date'], 'updated_at' => $p['date']]);
            $products[] = $prod;
        }


        $this->command->info('4. Seeding Sales across Jan - July 2026 (Including Today & 7-Day Trend)...');

        $customers = [
            'Budi Santoso', 'Rina Wulandari', 'Agus Setiawan', 
            'Dewi Lestari', 'Hendra Kurniawan', 'Siti Nurhaliza', 
            'Rizky Pratama', 'Anisa Rahma', 'Doni Saputra',
            'Lina Marlina', 'Fajar Hidayat', 'Yuni Astuti'
        ];

        $salesDates = [
            // Jan 2026
            '2026-01-15 10:20:00', '2026-01-22 14:10:00', '2026-01-28 16:45:00',
            // Feb 2026
            '2026-02-04 09:30:00', '2026-02-12 11:15:00', '2026-02-19 15:20:00', '2026-02-25 13:40:00',
            // March 2026
            '2026-03-03 10:00:00', '2026-03-10 14:30:00', '2026-03-17 11:50:00', '2026-03-24 16:10:00', '2026-03-29 09:15:00',
            // April 2026
            '2026-04-05 13:20:00', '2026-04-12 10:45:00', '2026-04-18 15:00:00', '2026-04-26 11:30:00',
            // May 2026
            '2026-05-04 09:10:00', '2026-05-11 14:00:00', '2026-05-18 10:30:00', '2026-05-25 16:20:00', '2026-05-30 11:45:00',
            // June 2026 (Last Month)
            '2026-06-03 10:15:00', '2026-06-08 14:40:00', '2026-06-14 11:20:00', '2026-06-20 15:30:00', '2026-06-26 09:50:00', '2026-06-29 16:00:00',
            // July 2026 (Recent Days up to Today July 12)
            '2026-07-02 10:00:00', '2026-07-04 14:15:00', '2026-07-06 11:30:00',
            '2026-07-07 09:20:00', '2026-07-08 13:40:00', '2026-07-09 10:50:00',
            '2026-07-10 11:15:00', '2026-07-10 15:30:00',
            '2026-07-11 09:30:00', '2026-07-11 14:20:00', // Yesterday
            '2026-07-12 08:45:00', '2026-07-12 10:30:00', '2026-07-12 13:15:00' // Today
        ];

        $createdSales = [];
        foreach ($salesDates as $index => $date) {
            // Pick 1 to 3 random products
            $numItems = rand(1, 3);
            $selectedProds = collect($products)->random($numItems);
            
            $items = [];
            $totalPrice = 0;
            foreach ($selectedProds as $sp) {
                $qty = rand(1, 8);
                $subtotal = $sp->price * $qty;
                $totalPrice += $subtotal;
                $items[] = [
                    'product_id' => $sp->id,
                    'product_name' => $sp->product_name,
                    'price' => $sp->price,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ];
            }

            $regions = ['Jawa Barat', 'Jabodetabek', 'Jawa Tengah', 'Jawa Timur', 'Banten', 'Sumatera', 'Bali & Nusa Tenggara'];
            $segments = ['Retail', 'Grosir', 'Bengkel', 'Corporate'];

            $sale = Sales::create([
                'user_id' => ($index % 3 == 0) ? $admin->id : $kasir->id,
                'invoice_number' => 'INV-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'customer_name' => $customers[$index % count($customers)],
                'region' => $regions[$index % count($regions)],
                'customer_segment' => $segments[$index % count($segments)],
                'products' => $items,
                'total_price' => $totalPrice,
            ]);

            Sales::where('id', $sale->id)->update(['created_at' => $date, 'updated_at' => $date]);
            $createdSales[] = $sale;
        }


        $this->command->info('5. Seeding Stock In (Barang Masuk) across Jan - July 2026...');

        $stockInReasons = [
            'Pembelian Rutin dari Suplier PT Aneka Baru',
            'Restock Bulanan dari Distributor Utama',
            'Penerimaan Batch Gudang Pusat Jakarta',
            'Stok Tambahan Musiman dari Suplier Resmi',
            'Penggantian Barang Klaim Garansi Suplier',
            'Surplus Hasil Opname Gudang Q1/Q2',
        ];

        $stockInDates = [
            '2026-01-20 08:30:00', '2026-02-10 10:15:00', '2026-02-25 14:00:00',
            '2026-03-12 09:45:00', '2026-03-28 11:20:00', '2026-04-15 13:30:00',
            '2026-05-05 10:00:00', '2026-05-20 15:10:00', '2026-06-10 09:15:00',
            '2026-06-25 14:40:00', '2026-07-03 11:00:00', '2026-07-08 10:30:00',
            '2026-07-11 09:00:00'
        ];

        foreach ($stockInDates as $i => $date) {
            $sp = $products[$i % count($products)];
            $in = StockIn::create([
                'product_id' => $sp->id,
                'user_id' => ($i % 2 == 0) ? $manajer->id : $admin->id,
                'in_quantity' => rand(20, 80),
                'in_reason' => $stockInReasons[$i % count($stockInReasons)],
            ]);
            StockIn::where('id', $in->id)->update(['created_at' => $date, 'updated_at' => $date]);
        }


        $this->command->info('6. Seeding Stock Out (Barang Keluar) across Jan - July 2026...');

        $stockOutReasons = [
            'Pengiriman Pesanan Invoice Pelanggan Korporat',
            'Distribusi Staf Gudang ke Cabang Gudang Surabaya',
            'Pemakaian Internal Divisi Operasional & Gudang',
            'Stok Rusak/Kadaluarsa Saat Pemeriksaan Rutin',
            'Pengiriman Sampel Pengujian Klien PT Nusantara',
            'Alokasi Proyek Ekstensi Kantor Pusat',
        ];

        $stockOutDates = [
            '2026-01-28 14:00:00', '2026-02-15 11:30:00', '2026-03-05 15:20:00',
            '2026-03-20 10:10:00', '2026-04-10 13:45:00', '2026-04-28 09:30:00',
            '2026-05-12 14:15:00', '2026-05-28 11:00:00', '2026-06-15 10:20:00',
            '2026-06-28 16:00:00', '2026-07-05 13:30:00', '2026-07-09 15:00:00',
            '2026-07-11 16:30:00'
        ];

        foreach ($stockOutDates as $i => $date) {
            $sp = $products[($i + 3) % count($products)];
            $out = StockOut::create([
                'sales_id' => $createdSales[$i % count($createdSales)]->id,
                'product_id' => $sp->id,
                'user_id' => ($i % 2 == 0) ? $manajer->id : $admin->id,
                'out_quantity' => rand(3, 15),
                'out_reason' => $stockOutReasons[$i % count($stockOutReasons)],
            ]);
            StockOut::where('id', $out->id)->update(['created_at' => $date, 'updated_at' => $date]);
        }


        $this->command->info('7. Seeding Customer Returns (Barang Pengembalian) across May - July 2026...');

        $returnReasons = [
            'barang_cacat',
            'tidak_sesuai',
        ];

        $returnConditions = [
            'tidak_layak',
            'layak',
        ];

        $returnDates = [
            '2026-05-15 14:00:00', '2026-05-26 11:20:00',
            '2026-06-12 10:30:00', '2026-06-22 15:45:00', '2026-06-28 09:15:00',
            '2026-07-04 13:10:00', '2026-07-08 11:00:00', '2026-07-10 14:30:00',
            '2026-07-11 11:15:00'
        ];

        foreach ($returnDates as $i => $date) {
            $sale = $createdSales[($i + 5) % count($createdSales)];
            $items = $sale->products;
            $firstItem = $items[0] ?? null;
            $pid = $firstItem ? $firstItem['product_id'] : $products[0]->id;

            $ret = CustomerReturn::create([
                'sales_id' => $sale->id,
                'product_id' => $pid,
                'user_id' => ($i % 2 == 0) ? $kasir->id : $manajer->id,
                'return_quantity' => rand(1, 3),
                'return_reason' => $returnReasons[$i % count($returnReasons)],
                'return_condition' => $returnConditions[$i % count($returnConditions)],
            ]);
            CustomerReturn::where('id', $ret->id)->update(['created_at' => $date, 'updated_at' => $date]);
        }

        $this->command->info('Successfully seeded all tables with rich, realistic historical & recent data!');
    }
}
