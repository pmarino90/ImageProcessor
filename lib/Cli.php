<?php
/**
* Image Processor
* Processes a queue of file, creating images from a PDF and generates all the required Thimbnails.
* @autor Paolo Marino 
**/

namespace ImageProcessor;

class Cli
{
  
  public static function run()
  {
    $count = 0;
    
    while($count < 10)
    {
      $processor = new \ImageProcessor\Processor();
      \cli\line('****** ImageProcessor ******');
      \cli\line('Retrieving an item to process...');
    
      if ($processor->hasItem())
      {
        \cli\line('Item with id = %s found. Processing...', $processor->getItemId());
        $processor->start();
      }
      else
      {
        \cli\line('Item not found.');
        
        break;
      }
      
      $count ++;
      sleep(5);
    }
  }
}