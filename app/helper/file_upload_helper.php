<?php

function uploadFile($file, $url, $ekstensiValid = ["jpg", "jpeg", "png"], $maxSize = 2000000)
{
  $msg = [];
  $namaGambar = $file["name"];
  $ukuran = $file["size"];
  $tmpName = $file["tmp_name"];
  // $ekstensiGambar = strtolower(end(explode(".", $namaGambar)));
  $ekstensiGambar = explode(".", $namaGambar);
  $ekstensiGambar = end($ekstensiGambar);
  $ekstensiGambar = strtolower($ekstensiGambar);
  // cek ekstensi
  if (!in_array($ekstensiGambar, $ekstensiValid)) {
    $msg = [
      "status" => false,
      "msg" => "Mohon untuk mengupload file gambar(jpg,png,jpeg)",
    ];
    return $msg;
  }
  // cek ukuran
  if ($ukuran > $maxSize) {
    $msg = [
      "status" => false,
      "msg" => "Ukuran Gamabar Terlalu besar",
    ];
    return $msg;
  }
  // generate nama gambar baru
  $namaGambar = uniqid() . ".$ekstensiGambar";
  // upload gambarnya
  if (move_uploaded_file($tmpName, $url . $namaGambar)) {
    $msg = [
      "status" => true,
      "nama_gambar" => $namaGambar
    ];
  } else {
    $msg = [
      "status" => false,
      "msg" => "Terjadi kesalahan, coba ulangi lagi!!",
    ];
  }
  return $msg;
}
