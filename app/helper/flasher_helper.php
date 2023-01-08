<?php


function showFlasher()
{
  if (isset($_SESSION["CRUD"])) {
    $data = $_SESSION["CRUD"];
    unset($_SESSION["CRUD"]);
    return "
    <div id='flash-crud' data-flashType='" . FLASH_TYPE . "' data-title='" . $data['title'] . "' data-text='" . $data['text'] . "' data-icon='" . $data['icon'] . "' data-flash=true>
    </div>
    ";
  }
}

function setFlasher($title, $icon, $text)
{
  $_SESSION["CRUD"] = [
    'title' => $title,
    'icon' => $icon,
    'text' => $text
  ];
}

function setFlashInsert($is_success = true)
{
  if ($is_success == true) {
    $title = "SELAMAT";
    $icon = "success";
    $text = "Data Berhasil DITAMBAHKAN!!";
  } else {
    $title = "OUPSS";
    $icon = "error";
    $text = "Data Gagal DITAMBAHKAN!!";
  }
  setFlasher($title, $icon, $text);
}

function setFlashUpdate($is_success = true)
{
  if ($is_success == true) {
    $title = "SELAMAT";
    $icon = "success";
    $text = "Data Berhasil DIUPDATE!!";
  } else {
    $title = "OUPSS";
    $icon = "error";
    $text = "Data Gagal DIUPDATE!!";
  }
  setFlasher($title, $icon, $text);
}

function setFlashDelete($is_success = true)
{
  if ($is_success == true) {
    $title = "SELAMAT";
    $icon = "success";
    $text = "Data Berhasil DIHAPUS!!";
  } else {
    $title = "OUPSS";
    $icon = "error";
    $text = "Data Gagal DIHAPUS!!";
  }
  setFlasher($title, $icon, $text);
}
