<?php

function base_url($url = null)
{
  return ($url == null) ? BASE_URL : BASE_URL . $url;
}

function assets($url)
{
  return base_url("/assets/$url");
}

function url($url, $data = null)
{
  if($data != null) {
    $url = $url . "?";
    foreach($data as $k => $v) {
      $url .= "$k=$v&";
    }
    $url = substr($url, 0, -1);
  }
  return base_url($url);
}

function redirect($url)
{
  echo "
  <script>
    window.location.href = '" . base_url($url) . "';
  </script>
  ";
  die;
}

function helper($arrHelp, $prefix = "../app/helper/")
{
  foreach ($arrHelp as $h) {
    require_once($prefix . $h . "_helper.php");
  }
}

function model($modelName, $prefix = "../app/model/")
{
  require_once($prefix . $modelName . ".php");
  return new $modelName();
}

function dd($data)
{
  var_dump($data);
  die;
}
