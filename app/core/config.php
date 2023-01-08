<?php

/*
----------------------
    DATABASE
----------------------
*/
const DB_HOST = "localhost";
const DB_USER = "username_database";
const DB_PASS = "password_database";
const DB_NAME = "nama_databse";

/*
----------------------
    BASE URL
----------------------
BASE_URL isikan dengan url nama folder anda
Note : Jangan gunakan tanda slash(/) pada akhir
*/
const BASE_URL = "http://localhost/mini-fw-php";

/*
----------------------
    FLASH TYPE
----------------------
Note : Gunakan value 1 atau 2 
*/
const FLASH_TYPE = 2;

/*
----------------------
    DEVELOPER MODE
----------------------
Note : Set true jika ingin menampilkan pesan errornya dan set false untuk menyembunyikan pesan errornya
*/
const DEVELOPER_MODE = true;

/*
----------------------
    KONFIGURASI LAINNYA
----------------------
*/
session_start();
date_default_timezone_set("Asia/Jakarta");
