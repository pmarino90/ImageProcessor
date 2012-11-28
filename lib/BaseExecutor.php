<?php

namespace ImageProcessor;

abstract class BaseExecutor
{
  protected $source_path;
  protected $target_path;
  protected $t_scale;
  protected $t_width;
  protected $t_height;
  
  protected $processed = false;
  
  abstract public function __construct(Array $options);
  
  abstract public function run();
  
  public function getProcessed()
  {
    return $this->processed;
  }
  
  public function setProcessed()
  {
    $this->processed = true;
  }
}