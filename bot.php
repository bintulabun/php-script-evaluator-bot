<?php

//ganti xxx dengan token botmu
$token = "xxx";

//ganti yyy dengan id telegram-mu
$owner_chat_id = "yyy";

$telegram = "https://api.telegram.org/bot$token";

$input = file_get_contents("php://input");

$dekode = json_decode($input,true);

$script = $dekode[message][text];

$message_id = $dekode[message][message_id];

$length = strlen($script);

$chat_id = $dekode[message][chat][id];

$first_name = $dekode[message][chat][first_name];

$last_name = $dekode[message][chat][last_name];

$username = $dekode[message][chat][username];

if (isset($script)){

    if ($chat_id == $owner_chat_id){

     $filename = "file.php";
        
     $file = fopen($filename,"w"); 

     fwrite($file,$script);

     fclose($file);

     $web = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"]."/".$filename;

     $getweb = file_get_contents($web);

     $strip_tags = strip_tags($getweb);

     $hasil = urlencode($strip_tags);
     
  if(empty($hasil) OR $hasil == ""){

   $pesan_balik = "hasil kosong";

  }else{

   $pesan_balik = $hasil;

  }

        $url = "$telegram/sendMessage?parse_mode=HTML&chat_id=$chat_id&text=$pesan_balik&reply_to_message_id=$message_id";

        file_get_contents($url);

    }else{

        //pesan balik kepada pemilik bot

        if ($length>4000){

        $script = "(pesan tidak ditampilkan karena melebihi 4000 karakter)";

        }

        $pesan_balik = "Ada orang lain mengirim perintah di bot ini%0A%0AChat id: $chat_id%0AFirst name: $first_name%0ALast name: $last_name%0AUsername: $username%0APanjang teks: $length karakter%0ATeks:%0A%0A$script";

        $url = "$telegram/sendMessage?parse_mode=HTML&chat_id=$owner_chat_id&text=$pesan_balik";

        file_get_contents($url);
        
        //pesan balik kepada pengirim

        $pesan_balik = "Maaf, Anda tidak diizinkan mengakses bot ini.";

        $url = "$telegram/sendMessage?parse_mode=HTML&chat_id=$chat_id&text=$pesan_balik";

        file_get_contents($url);

    }

}

?>
