<?php

function isLogIn($redirect = false, $url = "")
{
  if (!isset($_SESSION["login"])) {
    if ($redirect == true) return redirect($url);
    echo ("AKSES DENIED!!!");
    die;
  }
  if ($_SESSION["login"]["status"] != true) {
    if ($redirect == true) return redirect($url);
    echo ("AKSES DENIED!!!");
    die;
  }
}

function getRole()
{
  return $_SESSION["login"]["role"];
}

function getDataUserLogin($param = null)
{
  $idLogIn = $_SESSION["login"]["id"];
  $dataLogin = BaseModel::queryStatic("SELECT * FROM user WHERE id_user='{$idLogIn}' ", false);
  return ($param == null) ? $dataLogin : $dataLogin->$param;
}

function setSessionLogin($id, $role)
{
  $_SESSION["login"] = [
    "status" => true,
    "id" => $id,
    "role" => $role
  ];
}

function onlyUser($role, $redirect = false, $url = "")
{
  $levelUserLogin = getRole();
  if (is_array($role)) {
    if (!in_array($levelUserLogin, $role)) {
      if ($redirect == true) return redirect($url);
      echo ("AKSES DENIED!!!");
      die;
    }
  } else {
    if ($levelUserLogin != $role) {
      if ($redirect == true) return redirect($url);
      echo ("AKSES DENIED!!!");
      die;
    }
  }
}

function isLogOut($url = "/main/index.php")
{
  if (isset($_SESSION["login"])) {
    return redirect($url);
    die;
  }
}

function logout()
{
  unset($_SESSION['login']);
  session_destroy();
}