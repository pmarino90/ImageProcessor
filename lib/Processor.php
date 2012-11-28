<?php 

namespace ImageProcessor;

class Processor 
{
  
  protected $_queue_item;
  protected $_queue_man;
  
  
  public function __construct()
  {
    $this->__retrieveQueueItem();
    
    return $this;
  }
  
  private function __retrieveQueueItem()
  {
    $this->_queue_man = new \ImageProcessor\Db();
    $this->_queue_item = $this->_queue_man->getQueueItem();
    
    if (0 === count($this->_queue_item))
    {
      return false;
    }
    $this->_queue_item = $this->_queue_item[0];
    
    return true;
  }
  
  public function hasItem()
  {
    return count($this->_queue_item) > 0;
  }
  
  public function getItemId()
  {
    return $this->_queue_item['id'];
  }
  
  public function start()
  {
    if (!$this->__checkSourceFile())
    {
      \cli\error('Source file not found. Stopping.');
      
      return false;
    }
    
    // choose wether to use Imagemagick or GD.
    // if we have to extract an image from a PDF we use Imagemagick
    if ($this->_queue_item['extension'] == '.pdf')
    {
      \cli\line('* Using Imagemagick');
      $executor = new \ImageProcessor\Imagemagick(array(
        'source_path' => $this->_queue_item['source_path'],
        'target_path' => $this->_queue_item['target_path'],
        't_scale'     => $this->_queue_item['t_scale']
      ));
    }
    elseif ($this->_queue_item['extension'] == '.jpg')
    {
      \cli\line('* Using GD');
      $executor = new \ImageProcessor\Thumbnails(array(
        'source_path' => $this->_queue_item['source_path'],
        'target_path' => $this->_queue_item['target_path'],
        't_width'     => $this->_queue_item['t_width'],
        't_height'    => $this->_queue_item['t_height'],
      ));
    }
    else
    {
      \cli\error('Sorry but I cannot handle this stuff!');
    }
        
    if ($executor->run())
    {
      $this->_queue_man->deleteItem($this->_queue_item['id']);
      \cli\line('Item processed!');
      
      return true;
    }
    $this->_queue_man->increseErrorCount($this->_queue_item['id']);
    
    return false;
  }
  
  private function __checkSourceFile()
  {
    if (!file_exists($this->_queue_item['source_path']))
    {
      $this->_queue_man->increseErrorCount($this->_queue_item['id'], "Source file not found.");
      
      return false;
    }
    
    return true;
  }
  
  
}