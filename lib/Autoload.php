<?php
// Actually this is a fake autoloader

// @TODO It seems that Composer doesn't know how to autoload the repo, notify the creator and/or correct it forking the project.
require dirname(__FILE__) . '/../vendor/jlogsdon/cli/lib/cli/cli.php';
\cli\register_autoload();

require 'const.php';
require 'BaseExecutor.php';
require 'Processor.php';
require 'Imagemagick.php';
require 'Thumbnails.php';
require 'Db.php';
require 'Cli.php';
