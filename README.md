# mini fw php

### Struktur Folder
- admin
    - index.php
- app
    - core
        - config.php
        - basic_function.php
    - helper
        - auth_helper
        - flasher_helper
        - photo_helper
    - model
        - BaseModel.php
- assets
    - js
        - flasher.js
    - plugin
- init.php
---

> **PENTING** : File `init.php` adalah file yang bertugas untuk me-load semua konfigurasi dan function-function. Maka dari itu anda harus selalu meinclude file `init.php` tersebut.
---

### Konfigurasi Awal
Semua konfigurasi aplikasi ada di file `app/core/config.php`

1. Konfigurasi Database
    ```php
    const DB_HOST = "localhost";
    const DB_USER = "username";
    const DB_PASS = "password";
    const DB_NAME = "nama_database";
    ```
2. Konfigurasi BASE_URL
    ```php
    const BASE_URL = "http://localhost/nama_folder";
    ```
    > Note : Jangan gunakan tanda `/` pada akhir url.
3. Konfigurasi Style Flash Message
    ```php
    const FLASH_TYPE = 2;
    ```
    > Note : Terdapat dua value yaitu `1` atau `2`. Anda bisa mencobanya sendiri, tetapi defaultnya mengunakan yang `2`.
    
### Global Basic Function
Berikut ini beberapa function global default bawaan untuk mempermudah pekerjaan anda.

1. `base_url()`
    
    Function ini digunakan untuk mereturn url aplikasi anda. 
    ```php
    /* 
    http://localhost/nama_folder 
    */
    base_url(); 
    
    /* 
    http://localhost/nama_folder/admin/user.php 
    */
    base_url('/admin/user.php');
    ```

2. `assets()`

    Function ini digunakan untuk mereturn url folder `asstes`.
    ```html
    <link rel="stylesheet" href="<?= assets('plugins/sweetalert/sweetalert2.min.css'); ?>">
    ```
3. `url()`

    Function ini digunakan untuk mempermudah menuliskan url dengan parameter data GET.
    ```php
    /* 
    http://localhost/nama_folder/admin/user.php?id_hps=3&nama=anton 
    */
    url('/admin/user.php', ['id_hps' => 3, 'nama' => 'anton'] );
    ```
4. `redirect()`

    Function ini digunakan untuk meredirect/pindah ke halaman lain.
    ```php
    if(isset($_POST['tambah'])) {
        ....
        /*
        Ini akan otomatis redirect ke halaman user.php
        */
        return redirect('/admin/user.php');
    }
    ```
5. `helper()`

    Function ini digunakan untuk meload file-file helper yang ada di folder `app/helper`. Parameter pertama Function ini berupa `array`.
    ```php
    /* 
    Ini akan meload file 'auth_helper.php' dan 'flasher_helper.php' yang ada di folder app/helper
    */
    helper(['auth', 'flasher']);
    ```
    Anda juga dapat membuat helper sendiri dengan cara membuat file di folder `app/helper` dengan menambahkan `_helper` pada nama file php nya. Contoh : `coba_helper.php`. Setelah itu anda tinggal memanggilnya dengan Function `helper()`
    ```php
    helper(['coba']);
    ```
    Jika anda berada pada sub folder, Contoh anda sedang berada di `admin/setting/general.php`, Maka anda harus mengubah parameter kedua `$prefix` ke url yang benar, karena default `$prefix` berisi `../app/helper/`.
    ```php
    /*
    ubah parameter kedua agar sesuai saat anda berada di folder 'admin/setting/general.php'
    */
    helper(['auth'], '../../app/helper/');
    ```
6. `model()`

    Function ini digunakan untuk meload file class model yang ada di folder `app/model` lalu mereturn instance object dari kelas tersebut. Function ini memiliki 2 parameter yaitu `$modelName` dan `$prefix`. Default value dari `$prefix` adalah `../app/model/`.
    ```php
    $UserMdl = model('UserMdl');
    ```
    Jika anda berada di sub folder, Contoh `admin/setting/user.php`, Maka anda harus ubah dari value `$prefix`-nya.
    ```php
    /*
    Ketika anda berada di folder 'admin/setting/user.php'
    */
    $UserMdl = model('UserMdl', '../../app/model/');
    ```
7. `dd()`

    Function ini hanya mengabungkan antara `var_dump()` dan `die()`.
    ```php
    /*
    Ini akan menghasilkan sintax :
    var_dump($data_user);
    die;
    */
    dd($data_user);
    ```

### Model
Model adalah alternatif cara untuk akses/query ke database anda dengan lebih mudah daripada biasanya. Model ini adalah representesi dari sebuah tabel yang di simpan di dalam suatu file yang berada di folder `app/model`. Berikut penjelasan lengkapnya.

+ Membuat file Model dan konfigurasi awalnya

    Untuk mengunakan model anda cukup membuat satu file yang namanya di akhiri dengan `Mdl.php`, contoh : `UserMdl.php` yang di simpan di folder `app/model`. Isi file model tadi setidaknya harus mendklarasikan 3 attribut dalam classnya yaitu `$tblName`, `$idKey`, dan `$allowedField`, serta harus extends ke class `BaseModel`
    ```php
    class UserMdl extends BaseModel 
    {
        protected $tblName = 'nama_tabel';
        protected $idKey = 'nama_primary_key';
        protected $allowedField = ['column1', 'column2', 'column3'];
    }
    ```
    > Note : `$allowedField` diisi dengan nama kolom yang boleh di isi secara manual lewat aplikasi.

+ Meload model

    Setelah anda membuat file model, anda dapat meload/mengunakan model tersebut dengan function `model()`.
    ```php
    // Load model
    $UserMdl = model('UserMdl');
    ```
+ Explorasi Lebih Lanjut

    Berikut ini merupakan method-method yang tersedia untuk memudahkan query ke database anda :

    - `query($sql=null)`

        Method ini digunakan untuk mengeksekusi sintax sql ke database anda
        ```php
        ...
        $UserMdl->query('SELECT * FROM user');
        ```

    - `getAll()`

        Digunakan untuk mengambil semua data
        ```php
        ...
        $UserMdl->query('SELECT * FROM user')->getAll();
        ```

    - `getOne()`

        Digunakan untuk mengambil satu data saja
        ```php
        ...
        $UserMdl->query('SELECT * FROM user WHERE id=2')->getOne();
        ```
    
        > **Note** : method `getAll()` dan `getOne()` by default akan mengembalikan data berbentuk object. Jika anda ingin merubah return type nya menjadi array assosiatif anda tinggal ubah value dari properti `$resultType` di modelnya dengan value `array`
        > ```php
        > class UserMdl extends BaseModel 
        > {
        >   ...
        >   protected $resultType = 'array';   
        > }
        >```

    - `select($data = "*")`

        Digunakan untuk select data
        ```php
        ...
        /* 
        SELECT * FROM tbl_user;
        */
        $UserMdl->select()->getAll();

        /* 
        SELECT nama,username FROM tbl_user;
        */
        $UserMdl->select('nama, username')->getAll();
        ```

    - `where($nameField, $valueField, $operand = '=')`

        Digunakan untuk where clause
        ```php
        ...
        /* 
        SELECT * FROM tb_user WHERE username='aldi';
        */
        $UserMdl->select()
                ->where('username', 'aldi')
                ->getAll();

        /* 
        SELECT * FROM tbl_user WHERE nilai > 80;
        */
        $UserMdl->select()
                ->where('nilai', 80, '>')
                ->getAll();
        ```

    - `whereNull($nameField)`
        ```php
        /* 
        SELECT * FROM tb_user WHERE alamat IS NULL;
        */
        $UserMdl->select()
                ->whereNull('alamat')
                ->getAll();
        ```
    
    - `whereNotNull($nameField)`
        ```php
        /* 
        SELECT * FROM tb_user WHERE alamat IS NOT NULL;
        */
        $UserMdl->select()
                ->whereNotNull('alamat')
                ->getAll();
        ```
    
    - `whereBetween($nameField, $mulai, $selesai)`
        ```php
        /* 
        SELECT * FROM tb_user WHERE tgl_transaksi BETWEEN '2022-12-01' AND '2022-12-31';
        */
        $UserMdl->select()
                ->whereBetween('tgl_transaksi', '2022-12-01', '2022-12-31')
                ->getAll();
        ```

    - `orWhere($nameField, $valueField, $operand = '=')`
        ```php
        /* 
        SELECT * FROM tb_user WHERE nilai=90 OR nilai=70;
        */
        $UserMdl->select()
                ->where('nilai' , 90)
                ->orWhere('nilai', 70)
                ->getAll();
        ```
    
    - `orWhereNull($nameField)`
        ```php
        /* 
        SELECT * FROM tb_user WHERE nilai=90 OR alamat IS NULL;
        */
        $UserMdl->select()
                ->where('nilai' , 90)
                ->orWhereNull('alamat')
                ->getAll();
        ```

    - `orWhereNotNull($nameField)`
        ```php
        /* 
        SELECT * FROM tb_user WHERE nilai=90 OR alamat IS NOT NULL;
        */
        $UserMdl->select()
                ->where('nilai' , 90)
                ->orWhereNotNull('alamat')
                ->getAll();
        ```

    - `andWhere($nameField, $valueField, $operand = '=')`
        ```php
        /* 
        SELECT * FROM tb_user WHERE nilai=90 AND nilai=70;
        */
        $UserMdl->select()
                ->where('nilai' , 90)
                ->andWhere('nilai', 70)
                ->getAll();
        ```
    
    - `andWhereNull($nameField)`
        ```php
        /* 
        SELECT * FROM tb_user WHERE nilai=90 AND alamat IS NULL;
        */
        $UserMdl->select()
                ->where('nilai' , 90)
                ->andWhereNull('alamat')
                ->getAll();
        ```

    - `andWhereNotNull($nameField)`
        ```php
        /* 
        SELECT * FROM tb_user WHERE nilai=90 AND alamat IS NOT NULL;
        */
        $UserMdl->select()
                ->where('nilai' , 90)
                ->andWhereNotNull('alamat')
                ->getAll();
        ```
    
    - `orderBy($nameField, $orderType = "ASC")`
        ```php
        /* 
        SELECT * FROM tb_user ORDER BY nama ASC;
        */
        $UserMdl->select()
                ->orderBy('nama')
                ->getAll();
        
        /* 
        SELECT * FROM tb_user ORDER BY nama DESC;
        */
        $UserMdl->select()
                ->orderBy('nama', 'DESC')
                ->getAll();
        ```

    - `join($tblReference, $idForeign, $typeJoin = "INNER", $idPrimary = null)`
        ```php
        /* 
        SELECT * FROM tb_user 
        INNER JOIN detail_user 
        ON tb_user.id_detail=detail_user.id_detail;
        */
        $UserMdl->select()
                ->join('detail_user', 'id_detail')
                ->getAll();
        
        /* 
        SELECT * FROM tb_user 
        LEFT JOIN detail_user 
        ON user.detail_id=detail_user.id_detail;
        */
        $UserMdl->select()
                ->join('detail_user', 'detail_id', 'LEFT', 'id_detail')
                ->getAll();
        ```

+ Magic CRUD generator
    
    Berikut ini method-method untuk CRUD lebih mudah dan simpel.

    - `findAll($data = "*")`

        Method ini berfungsi untuk mengambil semua data.
        ```php
        /*
        SELECT * FROM tbl_user
        */
        $data = $UserMdl->findAll();
        ```
    
    - `findAll($data = "*")`

        Method ini berfungsi untuk mengambil satu data bedasar id primary key yang telah di set properti nama kolom primary key nya di properti `$idKey`.
        ```php
        /*
        SELECT nama, username FROM tbl_user
        WHERE id_user=3
        */
        $dt = $UserMdl->find(3, $data = "nama, username")
        ```
    
    - `insert($data)`

        Method ini digunakan untuk menambah data. Parameter `$data` harus bertype `array assosiatif` dengan key adalah nama-nama kolom di tabel anda. Perlu diingat `$data` nanti key nya akan dicocokan lagi dengan isi attribute `$allowedField` yang telah di definisikan di modelnya.
        ```php
        class UserMdl extends BaseModel
        {
            ...
            protected $allowedField = ['nama','username'];
            ...
        }
        ```
        ```php
        ...
        $data_insert = [
            'nama' => 'anton',
            'username' => 'anton123',
            'tambah' => true
        ];

        $UserMdl->insert($data_insert);
        ```
        Kode diatas akan menghasilkan sintax sql sebagai berikut
        ```sql
        INSERT INTO tbl_user (nama,username) VALUES ('anton', 'anton123');
        ```

    - `update($id, $data)`

        Method ini digunakan untuk mengupdate data. Parameter `$data` harus bertype `array assosiatif` dengan key adalah nama-nama kolom di tabel anda. Perlu diingat `$data` nanti key nya akan dicocokan lagi dengan isi attribute `$allowedField` yang telah di definisikan di modelnya. Parameter `$id` berisi id yang ingin di update.
        ```php
        class UserMdl extends BaseModel
        {
            ...
            protected $allowedField = ['nama','username'];
            ...
        }
        ```
        ```php
        ...
        $data_update = [
            'nama' => 'antons',
            'username' => 'anton1234',
            'edit' => true
        ];

        $UserMdl->update(3, $data_update);
        ```
        Kode diatas akan menghasilkan sintax sql sebagai berikut
        ```sql
        UPDATE tbl_user SET nama='antons', username='anton1234' WHERE id_user=3
        ```

    - `insertOrUpdate($data)`

        Method ini berguna untuk insert/update data, Jika array data yang anda kirim ada key dengan nama yang sama dengan nama kolom primary key nya, maka dia akan melakukan update, tetapi jika tidak ada maka akan melakukan insert data.
        ```php
        /*
        Kode ini akan melakukan insert data
        */
        $data = [
            'nama' => 'budi',
            'username' => 'budi123'
        ];
        $UserMdl->insertOrUpdate($data);
        ```
        ```php
        /*
        Kode ini akan melakukan update data
        */
        $data = [
            'id_user' => 3,
            'nama' => 'budi',
            'username' => 'budi123'
        ];
        $UserMdl->insertOrUpdate($data);
        ```

    - `delete($id)`

        Method ini digunakan untuk menghapus data dengan mengirimkan `$id` data yang mau di hapus.
        ```php
        ...
        $UserMdl->delete(3);
        ```
        Kode diatas akan menghasilkan sintax sql sebagai berikut
        ```sql
        DELETE FROM tbl_user WHERE id_user=3;
        ```

+ SoftDelete

    SoftDelete ini merupakan fitur untuk melakukan delete data secara tidak permanen. <br>
    Secara default fitur SoftDelete ini tidak aktif, jadi jika anda menghapus data mengunakan method `delete()` maka dia akan menghapus data secara permanen dari database anda. <br>
    Untuk mengaktifkan fitur ini anda perlu set properti `$softDelete` pada model anda menjadi `true`, dan juga di dalam tabel anda harus menambahkan kolom `deleted_at` dengan type `datetime` atau `timestamp` dan boleh `null`.
    ```php
    class UserMdl extends BaseModel 
    {
        protected $softDelete = true;
    }
    ```
    Jika anda mengaktifkan fitur ini dan anda mengunakan method `delete()` maka dibalik layar method ini akan mengupdate kolom `deleted_at` dengan diisi waktu sekarang.
    ```php
    $UserMdl->delete(3);
    ```
    ```sql
    UPDATE tbl_user SET deleted_at=NOW() WHERE id_user=3;
    ```
    Dan jika anda mengunakan method `findAll()` maka otomatis method ini hanya akan menampilkan data yang kolom `deleted_at` nya masih `NULL`
    ```php
    $data = $UserMdl->findAll();
    ```
    ```sql
    SELECT * FROM tbl_user WHERE deleted_at IS NULL;
    ```
    Jika anda mengaktifkan fitur SoftDelete ini tetapi tetap ingi menghapus data secara permanen, anda dapat mengunakan method `forceDelete($id)`.
    ```php
    $UserMdl->forceDelete(3);
    ```
    Jika anda ingin mengembalikan data yang terhapus dengan SoftDelete anda dapat mengunakan method `restore($id)`
    ```php
    $UserMdl->restore(3);
    ```
    ```sql
    UPDATE tbl_user SET deleted_at = NULL WHERE id_user=3;
    ```
    Jika anda ingin menampilkan data-data yang sudah terhapus dengan SoftDelete anda dapat mengunakan method `listDelete()`.
    ```php
    $dataListDelete = $UserMdl->listDelete();
    ```
    ```sql
    SELECT * FROM tbl_user WHERE delete_at IS NULL;
    ```

+ Magic CRUD Action

    Method-method ini berfungsi untuk mempermudah melakukan logic tambah,edit, dan delete data tanpa harus menuliskan banyak code-code php yang kebanyakan sama di tiap halamannya.

    Contoh Tidak mengunakan Magic CRUD Generator
    ```php
    ...
    // tambah data
    if(isset($_POST['tambah'])) {
        $UserMdl->insert($_POST);
        setFlasher('Selamat', 'success', 'Data Berhasil Ditambah');
        return redirect('/admin/user.php');
    }
    // edit data
    if(isset($_POST['edit'])) {
        $UserMdl->update($_POST['id_user'],$_POST);
        setFlasher('Selamat', 'success', 'Data Berhasil Diubah');
        return redirect('/admin/user.php');
    }
    // hapus data
    if(isset($_GET['id_hps'])) {
        $UserMdl->delete($_GET['id_hps']);
        setFlasher('Selamat', 'success', 'Data Berhasil Dihapus');
        return redirect('/admin/user.php');
    }
    ...
    ```
    Jika mengunakan Magic CRUD Generator maka code-code diatas bisa dirubah hanya dengan seperti ini
    ```php
    ...
    // tambah data
    $UserMdl->doInsert();
    // edit data
    $UserMdl->doUpdate();
    // hapus data
    $UserMdl->doDelete();
    ...
    ```
    > **Penting** : Sebelum anda mengunakan magic method ini anda harus men-set properti `$urlredirect` nya di model anda. Properti ini di gunakan untuk mengarahkan kemana halaman yang akan di tuju ketika setelah melakukan insert,update,atau delete dengan magic method ini.
    >```php
    > class UserMdl extends BaseModel
    > {
    >   ...
    >   protected $urlRedirect = '/admin/user.php';
    >   ...
    > }

    Berikut method-method dan penjelasannya : 

    - `doInsert()`

        Method ini untuk melakukan insert data dengan ketentuan:
        
        - Dalam form anda harus mengunakan method `POST`.
        - Dalam button `submit` nya harus di beri `name='tambah'`.

        Form HTML
        ```html
        ...
        <form action="" method="POST">
            <input type='text' name='nama'>
            <input type='text' name='username'>
            <button type='submit' name='tambah'>
                Tambah Data
            </button>
        </form>
        ...
        ```

        Logic PHP
        ```php
        ...
        // tambah data
        $Usermdl->doInsert();
        ...
        ```
    
    - `doUpdate()`

        Method ini untuk melakukan update data dengan ketentuan:
        
        - Dalam form anda harus mengunakan method `POST`.
        - Dalam button `submit` nya harus di beri `name='edit'`.
        - Dalam formnya juga harus menambahkan `input` dengan `name` adalah nama kolom primary key nya dan dengan `value` adalah id data yang mau di edit.

        Form HTML
        ```html
        ...
        <form action="" method="POST">
            <input type='hidden' name='id_user' value='3'>
            <input type='text' name='nama'>
            <input type='text' name='username'>
            <button type='submit' name='edit'>
                Edit Data
            </button>
        </form>
        ...
        ```

        Logic PHP
        ```php
        ...
        // tambah data
        $Usermdl->doUpdate();
        ...
        ```

    - `doDelete()`

        Method ini untuk melakukan hapus data dengan ketentuan:
        
        - Dalam tag `<a>` href nya harus mengirimkan data juga di url nya dengan key `id_hps`.

        Form HTML
        ```html
        ...
        <a href="<?= url('/admin/index.php', ['id_hps' =>3]) ?>" >
            Hapus Data
        </a>
        ...
        ```

        Logic PHP
        ```php
        ...
        // tambah data
        $Usermdl->doDelete();
        ...
        ```

### Flasher Helper

Flasher Helper merupakan helper function bawaan untuk membuat dan menampilkan Flash Message dengan Sweetalert. Untuk mengunakan helper ini anda harus me-load nya terlebih dahulu.
```php
...
helper(['flasher']);
...
```

- Membuat Flasher
    
    Untuk membuat flasher ini terdapat 4 function yaitu:

    - `setFlasher($title, $icon, $text)`
    - `setFlashInsert($is_success = true)`
    - `setFlashUpdate($is_success = true)`
    - `setFlashDelete($is_success = true)`

    ```php
    // logic tambah
    if(isset($_POST['tambah'])) {
        ....
        setFlasher('Selamat', 'success', 'Data Berhasil Ditambahkan');
        ...
    }
    ```

- Menampilkan Flasher

    Untuk menampilkannya cukup dengan cara memangil function `showFlasher()` di file htmlnya.

    ```html
    ...
    <?= showFlasher(); >
    ...
    ```

### Auth Helper
Helper ini digunakan untuk mempermudah sistem login anda. Sebelum mengunakan helper ini anda harus me-loadnya dulu
```php
...
helper(['auth']);
...
```
Berikut ini beberapa function-funtionnya

- `setSessionLogin($id, $role)`

    Function ini untuk membuat SESSION dengan `id` dan `role` tertentu
    ```php
    // logic login
    ...
    setSessionLogin($cek_user->id, 'admin');
    ```
- `isLogIn($redirect = false, $url = "")`

    Function ini digunakan untuk mengecek apakah seorang user sudah login atau belum, jika belum maka user tersebut tidak bisa mengakses halaman tertentu yang telah di batasi oleh function ini. 
    ```php
    // cek user sudah login atau belum
    isLogIn();
    ```
    > Note : Anda dapat otomatis meredirect user ke halaman tertentu jika belum login dengan mengisi parameter `$redirect` menjadi `true` dan `$url` ke url yang anda ingin tuju.

- `onlyUser($role, $redirect = false, $url = "")`

    Function ini akan membatasi hanya user dengan `role` tertentu yang bisa mengakses halamanya. Parameter `$role` harus berisi `array` contoh : `['admin', 'petugas']`.
    ```php
    ...
    // hanya admin dan petugas yang boleh mengkases
    onlyUser(['admin', 'petugas']);
    ```

- ` getDataUserLogin($param = null)`

    Function ini digunakan untuk mengembalikan data-data user yang sedang login. Untuk mengunakan Function ini pastikan anda punya tabel bernama `user` dan memiliki primary key `id_user`.
    ```php
    // akan mereturn semua data user yg sedang login
    $dataLogin = getDataUserLogin();

    // akan mereturn hanya usernamenya saja
    $usernameLogin = getDataUserLogin('username');
    ```

### Upload File Helper
Helper ini digunakan untuk mempermudah dalam mengupload sebuah file. Sebelum mengunakan helper ini anda perlu me-loadnya terlebih dahulu
```php
...
helper(['file_upload']);
...
```
Untuk mengunakannya anda hanya perlu memangil function `uploadFile()` dengan beberapa paremeter sebagai berikut :
    
- `$file` => array $_FILES dari form uploadnya, contoh : `$_FILES['gambar']`
- `$url` => path untuk upload filenya, contoh : `'../assets/img'`
- `$ekstensiValid` => array ekstensi-ekstensi apa saja yang di perbolehkan, default nya `['jpg', 'jpeg', 'png']`
- `$maxSize` => maksimal ukuran file dalam `byte`, default adalah `2000000`/`2MB`