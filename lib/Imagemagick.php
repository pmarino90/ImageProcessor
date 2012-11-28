<?php

namespace ImageProcessor;

class Imagemagick extends \ImageProcessor\BaseExecutor
{
  public function __construct(Array $options)
  {
    $this->source_path = $options['source_path'];
    $this->target_path = $options['target_path'];
    $this->t_scale = $options['t_scale'];
    
    return $this;
  }
  
  public function run()
  {
    $cmd = "convert -density 400 '{$this->source_path}[0]' -resize {$this->t_scale}% '{$this->target_path}'";
    exec($cmd);
    $this->setProcessed();
    
    return $this->getProcessed();
  }

}