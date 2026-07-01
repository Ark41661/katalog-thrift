<?php

/**
 * Konfigurasi logo brand untuk chip filter di halaman katalog.
 *
 * CARA PAKAI:
 * -----------
 * 1. Taruh file logo (PNG/JPG/SVG/WEBP) di folder:
 *       public/icons/brands/
 *    Contoh: public/icons/brands/nike.png
 *
 * 2. Tambahkan entry di array di bawah ini:
 *       'Nama Brand' => '/icons/brands/nama-file.png',
 *
 *    Key harus SAMA PERSIS dengan nama brand di database (case-sensitive).
 *    Contoh: brand di DB = "Nike" → key = "Nike"
 *
 * 3. Kalau brand tidak ada logonya, hapus entry-nya atau isi null —
 *    chip akan tampil teks saja tanpa logo.
 *
 * FORMAT FILE YANG DIDUKUNG: PNG, JPG, SVG, WEBP
 * UKURAN IDEAL: logo persegi atau landscape, minimal 60x30px
 */

return [

    // ---------------------------------------------------------------
    // Daftar brand → path logo (relatif dari folder public/)
    // ---------------------------------------------------------------

    'Nike'     => '/icons/brands/NIKE.svg',
    'Adidas'   => '/icons/brands/adidas.png',
    'Stussy'   => '/icons/brands/Stussy.svg',
    'Carhartt' => '/icons/brands/Carhartt.png',
    'Dickies'  => '/icons/brands/dickies.png',
    'WHO.A.U'  => '/icons/brands/whoau.png',
    'Dirty Business'  => '/icons/brands/Dirty Business.png',
    'MLB'  => '/icons/brands/MLB (Boston Red Sox).png',
    'Feltics'  => '/icons/brands/Feltics.png',
    'Calle'  => '/icons/brands/Calle (CL).png',
    // Tambahkan brand baru di sini:
    // 'Supreme'  => '/icons/brands/supreme.png',
    // 'Bape'     => '/icons/brands/bape.png',

];
