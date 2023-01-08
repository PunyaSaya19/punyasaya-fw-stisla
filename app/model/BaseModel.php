<?php

class BaseModel
{
  protected $conDB;
  protected $sql = "";
  protected $result;
  protected $tblName; // isi dengan nama tabel anda
  protected $idKey = 'id'; // isi dengan nama kolom primary key anda
  protected $softDelete = false; // ubah jadi 'true' jika anda ingin mengunakan fitur soft delete dengan syarat anda harus memiliki kolom yang defaultnya bernama 'deleted_at' dengan type 'datetime' atau 'timestamp' dan boleh null 
  protected $columnSoftDelete = 'deleted_at'; // nama kolom untuk soft delete
  protected $allowedField = []; // isi dengan nama-nama kolom anda yang boleh diisi secara manual
  protected $resultType = 'object'; // gunakan 'object' untuk mereturn data berbentuk objek atau gunakan array untuk mereturn data berbentuk array assosiatif
  protected $urlRedirect = ""; // url untuk redirect ketika tambah,edit dan hapus data ketika mengunakan method doInsert/doUpdate/doDelete

  public function __construct()
  {
    try {
      $this->conDB = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    } catch (Exception $e) {
      echo "koneksi gagal" . $e->getMessage();
      die;
    }
  }

  public function esc($dt)
  {
    return mysqli_real_escape_string($this->conDB, $dt);
  }

  public function query($sql = null)
  {
    if ($sql != null) {
      $this->sql = $sql;
    }
    if(DEVELOPER_MODE == true) {
      $this->result = mysqli_query($this->conDB, $this->sql) or die(mysqli_error($this->conDB));
    } else {
      $this->result = mysqli_query($this->conDB, $this->sql);
    }
    return $this;
  }

  public function isSuccess()
  {
    return (mysqli_affected_rows($this->conDB) > 0) ? true : false;
  }

  public function getOne()
  {
    $this->query($this->sql);
    return ($this->resultType == 'object') ? mysqli_fetch_object($this->result) : mysqli_fetch_assoc($this->result);
  }

  public function getAll()
  {
    $this->query($this->sql);
    $data = [];
    if ($this->resultType == 'object') {
      while ($r = mysqli_fetch_object($this->result)) {
        $data[] = $r;
      }
    } else {
      while ($r = mysqli_fetch_assoc($this->result)) {
        $data[] = $r;
      }
    }
    return $data;
  }

  // basic sql sintax
  public function select($data = "*")
  {
    $this->sql = "SELECT {$data} FROM {$this->tblName} ";
    return $this;
  }

  public function where($nameField, $valueField, $operand = '=', $protect = true)
  {
    $valFilter = ($protect == true) ? $this->esc($valueField) : $valueField;
    $this->sql .= " WHERE $nameField $operand '$valFilter' ";
    return $this;
  }

  public function whereNull($nameField)
  {
    $this->sql .= " WHERE $nameField IS NULL ";
    return $this;
  }

  public function whereNotNull($nameField)
  {
    $this->sql .= " WHERE $nameField IS NOT NULL ";
    return $this;
  }

  public function whereBetween($nameField, $mulai, $selesai)
  {
    $this->sql .= " WHERE $nameField BETWEEN '$mulai' AND '$selesai' ";
    return $this;
  }

  public function orWhere($nameField, $valueField, $operand = '=', $protect = true)
  {
    $valFilter = ($protect == true) ? $this->esc($valueField) : $valueField;
    $this->sql .= " OR $nameField $operand '$valFilter' ";
    return $this;
  }

  public function orWhereNull($nameField)
  {
    $this->sql .= " OR $nameField IS NULL ";
    return $this;
  }

  public function orWhereNotNull($nameField)
  {
    $this->sql .= " OR $nameField IS NOT NULL ";
    return $this;
  }

  public function andWhere($nameField, $valueField, $operand = '=', $protect = true)
  {
    $valFilter = ($protect == true) ? $this->esc($valueField) : $valueField;
    $this->sql .= " AND $nameField $operand '$valFilter' ";
    return $this;
  }

  public function andWhereNull($nameField)
  {
    $this->sql .= " AND $nameField IS NULL ";
    return $this;
  }

  public function andWhereNotNull($nameField)
  {
    $this->sql .= " AND $nameField IS NOT NULL ";
    return $this;
  }

  public function orderBy($nameField, $orderType = "ASC")
  {
    $this->sql .= " ORDER BY $nameField $orderType ";
    return $this;
  }

  public function join($tblReference, $idForeign, $typeJoin = "INNER", $idPrimary = null)
  {
    if ($idPrimary == null) {
      $this->sql .= " {$typeJoin} JOIN {$tblReference} ON {$this->tblName}.{$idForeign} = {$tblReference}.{$idForeign} ";
    } else {
      $this->sql .= " {$typeJoin} JOIN {$tblReference} ON {$this->tblName}.{$idForeign} = {$tblReference}.{$idPrimary} ";
    }
    return $this;
  }

  // CRUD generators
  public function findAll($data = "*")
  {
    if ($this->softDelete == true) {
      $dt = $this->select($data)->whereNull($this->columnSoftDelete)->getAll();
    } else {
      $dt = $this->select($data)->getAll();
    }
    return $dt;
  }

  public function find($id, $data = "*")
  {
    $id = $this->esc($id);
    return $this->select($data)->where($this->idKey, $id)->getOne();
  }

  public function insert($data, $protect = true)
  {
    $this->sql = "INSERT INTO {$this->tblName} ";
    $col = "(";
    $val = "(";
    foreach ($data as $k => $v) {
      if (in_array($k, $this->allowedField)) {
        $col .= "$k, ";
        $vl = ($protect == true) ? $this->esc($v) : $v;
        if($vl == 'NULL') {
          $val .= "NULL, ";
        } else {
          $val .= "'{$vl}', ";
        }
      }
    }
    $col = substr($col, 0, -2) . ")";
    $val = substr($val, 0, -2) . ")";
    $this->sql .= $col . " VALUES " . $val;
    return $this->query()->isSuccess();
  }

  public function update($id, $data, $protect = true)
  {
    $this->sql = "UPDATE {$this->tblName} SET ";
    foreach ($data as $k => $v) {
      if (in_array($k, $this->allowedField)) {
        $vl = ($protect == true) ? $this->esc($v) : $v;
        if($vl == 'NULL') {
          $this->sql .= "{$k} = NULL, ";
        } else {
          $this->sql .= "{$k} = '{$vl}', ";
        }
      }
    }
    $this->sql = substr($this->sql, 0, -2);
    $this->sql .= " WHERE {$this->idKey} = '$id' ";
    return $this->query()->isSuccess();
  }

  public function insertOrUpdate($data)
  {
    if (isset($data[$this->idKey])) {

      $id = $data[$this->idKey];
      // cek apakah id sudah ada atau blm
      $cek = $this->find($id);
      if ($cek == null) {
        // do insert
        return $this->insert($data);
      } else {
        // do update
        unset($data[$this->idKey]);
        return $this->update($id, $data);
      }
    } else {
      // do insert
      unset($data[$this->idKey]);
      return $this->insert($data);
    }
  }

  public function delete($id)
  {
    if ($this->softDelete == false) {
      return $this->forceDelete($id);
    } else {
      return $this->update($id, [$this->columnSoftDelete => date('Y-m-d H:i:s')]);
    }
  }

  public function forceDelete($id)
  {
    $this->sql = "DELETE FROM {$this->tblName} WHERE {$this->idKey} = '{$id}' ";
    return $this->query()->isSuccess();
  }

  public function listDelete()
  {
    return $this->select()->whereNotNull($this->columnSoftDelete)->getAll();
  }

  public function restore($id)
  {
    return $this->update($id, [$this->columnSoftDelete => 'NULL']);
  }

  // magic method crud
  public function doInsert()
  {
    // syarat mengunakan fungsi ini harus memberi name 'tambah' ke tombol submit nya
    if (isset($_POST['tambah'])) {
      $result = $this->insertOrUpdate($_POST);
      setFlashInsert($result);
      return redirect($this->urlRedirect);
    }
  }

  public function doUpdate()
  {
    // syarat mengunakan fungsi ini harus memberi name 'edit' ke tombol submit nya dan juga mengirimkan id yg mau di update juga
    if(isset($_POST['edit'])) {
      $result = $this->insertOrUpdate($_POST);
      setFlashUpdate($result);
      return redirect($this->urlRedirect);
    }
  }

  public function doDelete()
  {
    // syarat mengunakan fungsi ini harus mengirimkan parameter 'id_hps' di url
    if(isset($_GET['id_hps'])) {
      $result = $this->delete($_GET['id_hps']);
      setFlashDelete($result);
      return redirect($this->urlRedirect);
    }
    
  }

  // Static function untuk query manual
  public static function queryStatic($query, $all = true, $fetchMode = true)
  {
    $conDB = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $result = mysqli_query($conDB, $query) or die(mysqli_error($conDB));
    if ($fetchMode == true) {
      if ($all == true) {
        $data = [];
        while ($r = mysqli_fetch_object($result)) {
          $data[] = $r;
        }
        return $data;
      } else {
        return mysqli_fetch_object($result);
      }
    } else {
      return mysqli_affected_rows($conDB);
    }
  }
}
