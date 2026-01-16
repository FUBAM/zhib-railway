<?php
require __DIR__ . '/vendor/autoload.php';
$classes = [
    'App\\Models\\Events',
    'App\\Models\\Grub',
    'App\\Models\\Pembayaran',
    'App\\Models\\PesanGrup',
    'App\\Models\\PesertaKegiatan',
    'App\\Models\\Komunitas',
];
foreach ($classes as $c) {
    echo $c . ': ' . (class_exists($c) ? 'FOUND' : 'MISSING') . PHP_EOL;
}
