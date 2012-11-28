<?php
/**
* This class will be user when processing the Thumbnails queue.
* It is possible to generate a thumbnail from a starting image passing to the __contruct() method
* an array of options where the following keys are required:
* source_path (The source image path)
* target_path (Where to save the thumb)
* width (the target width)
* height (the target height)
* @autor Paolo Marino 
**/

namespace ImageProcessor;

class Thumbnails extends \ImageProcessor\BaseExecutor
{
  public function __construct(Array $options)
  {
    $this->source_path = $options['source_path'];
    $this->target_path = $options['target_path'];
    $this->t_width = $options['t_width'];
    $this->t_height = $options['t_height'];
    
    return $this;
  }
  
  public function run()
  {
    list($width, $height) = getimagesize($this->source_path);
    $thumb = imagecreatetruecolor($this->t_width, $this->t_height);
    $source = imagecreatefromjpeg($this->source_path);
        
		if (!imagecopyresized($thumb, $source, 0, 0, 0, 0, $this->t_width, $this->t_height, $width, $height))
    {
      $errors = true;
          
      return $this->getProcessed();
    }
    imagejpeg($thumb, $this->target_path);
    
    $this->setProcessed();
    
    return $this->getProcessed();
  }

}