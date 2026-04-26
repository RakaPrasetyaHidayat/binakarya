<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing menus to avoid duplicates
        Menu::truncate();

        // 1. Beranda
        Menu::create([
            'label' => 'Beranda',
            'url' => '/',
            'order' => 1,
            'is_active' => true,
        ]);

        // 2. Tentang Kami
        Menu::create([
            'label' => 'Tentang Kami',
            'url' => '/tentang-kami',
            'order' => 2,
            'is_active' => true,
        ]);

        // 3. Layanan (Parent Dropdown)
        $layanan = Menu::create([
            'label' => 'Layanan',
            'url' => '#',
            'order' => 3,
            'is_active' => true,
        ]);

        Menu::create([
            'label' => 'Penerbitan Buku',
            'url' => '/layanan',
            'order' => 1,
            'parent_id' => $layanan->id,
            'is_active' => true,
            'is_external' => false,
        ]);

        Menu::create([
            'label' => 'Jurnal',
            'url' => 'https://journal.binakaryacendekia.id',
            'order' => 2,
            'parent_id' => $layanan->id,
            'is_active' => true,
            'is_external' => true,
            'target' => '_blank',
        ]);

        Menu::create([
            'label' => 'Pelatihan',
            'url' => 'https://visioncenter.id',
            'order' => 3,
            'parent_id' => $layanan->id,
            'is_active' => true,
            'is_external' => true,
            'target' => '_blank',
        ]);

        // 4. Buku
        Menu::create([
            'label' => 'Buku',
            'url' => '/buku',
            'order' => 4,
            'is_active' => true,
        ]);

        // 5. Blog
        Menu::create([
            'label' => 'Blog',
            'url' => '/blog',
            'order' => 5,
            'is_active' => true,
        ]);

        // 6. Kontak
        Menu::create([
            'label' => 'Kontak',
            'url' => '/kontak',
            'order' => 6,
            'is_active' => true,
        ]);
    }
}
