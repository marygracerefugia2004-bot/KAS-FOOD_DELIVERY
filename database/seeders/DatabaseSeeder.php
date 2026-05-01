<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Food;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Do not truncate foods (may be referenced by carts/orders via FKs).

        // Admin
        User::updateOrCreate(['email' => 'admin@fooddash.com'], [
            'name'     => 'Admin User',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Driver
        User::updateOrCreate(['email' => 'driver@fooddash.com'], [
            'name'     => 'John Driver',
            'password' => Hash::make('password'),
            'role'     => 'driver',
            'phone'    => '+63 912 345 6789',
        ]);

        // Customer
        User::updateOrCreate(['email' => 'user@fooddash.com'], [
            'name'     => 'Jane Customer',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        // Foods
        $makeSvg = function (string $path, string $title, string $subtitle, string $bg = '#0D1B4B', string $accent = '#FF6B2C'): void {
            $safeTitle = htmlspecialchars($title, ENT_QUOTES);
            $safeSubtitle = htmlspecialchars($subtitle, ENT_QUOTES);
            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="900" height="600" viewBox="0 0 900 600">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{$bg}"/>
      <stop offset="1" stop-color="#15277A"/>
    </linearGradient>
    <filter id="s" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="12" stdDeviation="18" flood-color="#000000" flood-opacity="0.25"/>
    </filter>
  </defs>
  <rect width="900" height="600" fill="url(#g)"/>
  <circle cx="770" cy="120" r="110" fill="{$accent}" fill-opacity="0.18"/>
  <circle cx="130" cy="520" r="140" fill="{$accent}" fill-opacity="0.14"/>
  <g filter="url(#s)">
    <rect x="80" y="130" width="740" height="340" rx="26" fill="#FFFFFF" fill-opacity="0.08" stroke="#FFFFFF" stroke-opacity="0.18"/>
  </g>
  <text x="110" y="240" fill="#FFFFFF" font-size="56" font-family="Plus Jakarta Sans, Arial, sans-serif" font-weight="800">{$safeTitle}</text>
  <text x="110" y="310" fill="#FFFFFF" fill-opacity="0.78" font-size="28" font-family="Plus Jakarta Sans, Arial, sans-serif" font-weight="600">{$safeSubtitle}</text>
  <g transform="translate(110 365)">
    <rect width="190" height="56" rx="28" fill="{$accent}"/>
    <text x="95" y="37" text-anchor="middle" fill="#FFFFFF" font-size="20" font-family="Plus Jakarta Sans, Arial, sans-serif" font-weight="800">KAS Delivery</text>
  </g>
</svg>
SVG;
            Storage::disk('public')->put($path, $svg);
        };

        $foods = [
            // Main Dishes
            ['name'=>'Chicken Adobo','description'=>'Classic Filipino braised chicken in vinegar, soy sauce, garlic','price'=>149,'category'=>'main','prep_time'=>25,'image_path'=>'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=400'],
            ['name'=>'Beef Sinigang','description'=>'Sour tamarind soup with tender beef and fresh vegetables','price'=>189,'category'=>'main','prep_time'=>35,'image_path'=>'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=400'],
            ['name'=>'Crispy Pata','description'=>'Deep-fried pork leg, golden and crispy outside, juicy inside','price'=>249,'category'=>'main','prep_time'=>45,'image_path'=>'https://images.unsplash.com/photo-1606728035253-49e8a23146de?w=400'],
            ['name'=>'Pancit Canton','description'=>'Stir-fried noodles with vegetables and choice of meat','price'=>119,'category'=>'main','prep_time'=>20,'image_path'=>'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400'],
            ['name'=>'Kare-Kare','description'=>'Oxtail in rich peanut sauce with banana blossom','price'=>219,'category'=>'main','prep_time'=>40,'image_path'=>'https://images.unsplash.com/photo-1574653853027-5382a3d23a15?w=400'],
            ['name'=>'Lechon Kawali','description'=>'Crispy fried pork belly with liver sauce','price'=>229,'category'=>'main','prep_time'=>35,'image_path'=>'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=400'],
            ['name'=>'Chicken Inasal','description'=>'Grilled chicken marinated in calamansi and ginger','price'=>169,'category'=>'main','prep_time'=>30,'image_path'=>'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=400'],
            ['name'=>'Paksiw na Isda','description'=>'Filipino-style braised fish in vinegar and ginger','price'=>159,'category'=>'main','prep_time'=>25,'image_path'=>'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=400'],
            ['name'=>'Bicol Express','description'=>'Spicy pork stew in coconut milk with chili','price'=>199,'category'=>'main','prep_time'=>35,'image_path'=>'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=400'],
            ['name'=>'Pinakbet','description'=>'Mixed vegetables with shrimp paste and pork','price'=>139,'category'=>'main','prep_time'=>25,'image_path'=>'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400'],
            
            // Snacks
            ['name'=>'Lumpia Shanghai','description'=>'Crispy Filipino spring rolls filled with seasoned pork','price'=>99,'category'=>'snack','prep_time'=>15,'image_path'=>'https://images.unsplash.com/photo-1606525437679-037aca74a396?w=400'],
            ['name'=>'Tokwa at Baboy','description'=>'Tofu and pork with soy sauce and vinegar dip','price'=>89,'category'=>'snack','prep_time'=>15,'image_path'=>'https://images.unsplash.com/photo-1546069901-d202bf6d4c90?w=400'],
            ['name'=>'Siomai','description'=>'Steamed pork dumplings with soy sauce','price'=>79,'category'=>'snack','prep_time'=>12,'image_path'=>'https://images.unsplash.com/photo-1563245372-f21724e3856d?w=400'],
            ['name'=>'Turon','description'=>'Caramelized banana lumpia with caramel','price'=>69,'category'=>'snack','prep_time'=>10,'image_path'=>'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=400'],
            ['name'=>'Bibingka','description'=>'Rice cake baked in banana leaves with cheese','price'=>59,'category'=>'snack','prep_time'=>15,'image_path'=>'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=400'],
            
            // Desserts
            ['name'=>'Halo-Halo','description'=>'Crushed ice dessert with mixed fruits, beans, and leche flan','price'=>89,'category'=>'dessert','prep_time'=>10,'image_path'=>'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400'],
            ['name'=>'Leche Flan','description'=>'Caramelized egg custard, creamy and sweet','price'=>79,'category'=>'dessert','prep_time'=>20,'image_path'=>'https://images.unsplash.com/photo-1551024506-0bccd828d613?w=400'],
            ['name'=>'Biko','description'=>'Sweet sticky rice with coconut caramel','price'=>69,'category'=>'dessert','prep_time'=>15,'image_path'=>'https://images.unsplash.com/photo-1589119908995-c6837fa14848?w=400'],
            ['name'=>'Ube Halaya','description'=>'Purple yam jam with coconut milk','price'=>79,'category'=>'dessert','prep_time'=>15,'image_path'=>'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400'],
            ['name'=>'Mango Float','description'=>'Layers of graham, cream, and fresh mango','price'=>99,'category'=>'dessert','prep_time'=>10,'image_path'=>'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=400'],
            
            // Drinks
            ['name'=>'Buko Juice','description'=>'Fresh young coconut juice, naturally refreshing','price'=>59,'category'=>'drink','prep_time'=>5,'image_path'=>'https://images.unsplash.com/photo-1525385133512-2f346b384338?w=400'],
            ['name'=>'Sago at Gulaman','description'=>'Sweet tapioca pearls with brown sugar syrup','price'=>49,'category'=>'drink','prep_time'=>5,'image_path'=>'https://images.unsplash.com/photo-1558857563-b371033873b8?w=400'],
            ['name'=>'Calamansi Juice','description'=>'Fresh calamansi citrus drink, tangy and sweet','price'=>45,'category'=>'drink','prep_time'=>5,'image_path'=>'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=400'],
            ['name'=>'Taho','description'=>'Soft tofu with arnibal syrup and sago','price'=>55,'category'=>'drink','prep_time'=>5,'image_path'=>'https://images.unsplash.com/photo-1546173159-315724a31696?w=400'],
            ['name'=>'Iced Tea','description'=>'Refreshing iced tea with lemon','price'=>35,'category'=>'drink','prep_time'=>3,'image_path'=>'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400'],

            // More menu (local SVG images, no internet needed)
            ['name'=>'Garlic Butter Shrimp','description'=>'Juicy shrimp sautéed in garlic butter sauce','price'=>219,'category'=>'main','prep_time'=>25,'image_path'=>null],
            ['name'=>'Sisig','description'=>'Sizzling chopped pork with calamansi and chili','price'=>179,'category'=>'main','prep_time'=>20,'image_path'=>null],
            ['name'=>'BBQ Pork Skewers','description'=>'Smoky grilled pork barbecue sticks','price'=>129,'category'=>'snack','prep_time'=>15,'image_path'=>null],
            ['name'=>'Kikiam','description'=>'Street-style fish roll served with sweet-spicy sauce','price'=>69,'category'=>'snack','prep_time'=>10,'image_path'=>null],
            ['name'=>'Chicken Wings (Spicy)','description'=>'Crispy wings tossed in spicy glaze','price'=>159,'category'=>'snack','prep_time'=>18,'image_path'=>null],
            ['name'=>'Puto Cheese','description'=>'Steamed rice cakes topped with cheese','price'=>59,'category'=>'dessert','prep_time'=>10,'image_path'=>null],
            ['name'=>'Cassava Cake','description'=>'Moist cassava with creamy custard topping','price'=>79,'category'=>'dessert','prep_time'=>15,'image_path'=>null],
            ['name'=>'Ube Cheese Pandesal','description'=>'Soft ube rolls with melted cheese','price'=>69,'category'=>'dessert','prep_time'=>12,'image_path'=>null],
            ['name'=>'Strawberry Milk Tea','description'=>'Creamy milk tea with strawberry flavor','price'=>89,'category'=>'drink','prep_time'=>5,'image_path'=>null],
            ['name'=>'Iced Coffee','description'=>'Chilled coffee with milk and ice','price'=>75,'category'=>'drink','prep_time'=>4,'image_path'=>null],
            ['name'=>'Bottled Water','description'=>'Cold purified drinking water','price'=>20,'category'=>'drink','prep_time'=>1,'image_path'=>null],
        ];
        foreach ($foods as $f) {
            if (empty($f['image_path']) || !str_starts_with((string) $f['image_path'], 'http')) {
                $slug = Str::slug($f['name']);
                $svgPath = "foods/seed-{$slug}.svg";
                $makeSvg($svgPath, $f['name'], strtoupper($f['category'])." • {$f['prep_time']} min");
                $f['image_path'] = $svgPath;
            }

            Food::updateOrCreate(
                ['name' => $f['name']],
                $f
            );
        }

        $this->command->info('Seeded: admin@fooddash.com / driver@fooddash.com / user@fooddash.com — password: password');
    }
}