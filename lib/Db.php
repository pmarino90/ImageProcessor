<?php
/**
* A nicer DB interface, better Use an ORM but that's not the moment!
* @autor Paolo Marino
**/

namespace ImageProcessor;
use PDO;

class Db
{
  
  protected $_db;
  
  
  public function __construct()
  {
    $this->connect();
  }
  
  private function connect()
  {
    $dsn = 'mysql:host=' . DB_HOST . ';port=3306;dbname=' . DB_NAME;
    
    try {
      $this->_db = new PDO($dsn, DB_USER, DB_PASS);
    }
    catch(PDOException $e) {
      \cli\err('DB error. Message: %s', $e->getMessage());
    }
  }
  
  public function exec($raw_sql)
  {
    $sql = $this->_db->prepare($raw_sql);
    
    $sql->execute();
    
    return $sql->fetchAll();
  }
  
  public function getQueueItem()
  {
    return $this->exec('
      SELECT * FROM '.QUEUE_DB_TABLE.' 
      WHERE processing = 0 
        AND err_count < '.ERROR_COUNT_THRESHOLD.'
      ORDER BY id
      LIMIT 0, 1;');
  }
  
  public function increseErrorCount($id, $err_message = "Not provided")
  {
    return $this->exec('
      UPDATE '.QUEUE_DB_TABLE.' 
      SET err_count = err_count + 1,
          err_message = '.$this->db->quote($err_message).',
          processing = 0
      WHERE id = '.$id.';
    ');
  }
  
  public function setProcessingItem($id)
  {
    return $this->exec('
      UPDATE '.QUEUE_DB_TABLE.' 
      SET processing = 1
      WHERE id = '.$id.';
    ');
  }
  
  public function deleteItem($id)
  {
    return $this->exec('
      DELETE FROM '.QUEUE_DB_TABLE.'
      WHERE id = '.$id.'
    ');
  }
  
  public function addItemToQueue($source_path, $target_path, $extension, $t_width = 0, $t_height = 0, $scale = 0)
  {
    return $this->exec('
    INSERT INTO '.QUEUE_DB_TABLE.' (source_path, target_path, extension, t_width, t_height, t_scale) 
    VALUES('.$this->_db->quote($source_path).', '.$this->_db->quote($target_path).', '.$this->_db->quote($extension).', '.$t_width.', '.$t_height.', '.$scale.');
    ');
  }
  
  
}