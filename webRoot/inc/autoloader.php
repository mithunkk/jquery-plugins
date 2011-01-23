<?php
/*
 * autoloader.php
 * by Sam Bisbee <sam@sbisbee.com> on 2010-02-13
 * Released to the public domain.
 *
 * This allows us to instantiate classes without needing to drop in include()
 * and require() statements everywhere.
 */

function __autoload($class)
{
  require_once("$class.php");
}

function dirToIncPath($dirPath)
{
  if(!is_dir($dirPath))
    return;

  set_include_path(get_include_path().':'.$dirPath);

  $dir = opendir($dirPath);
  while($dir && ($subDir = readdir($dir)) !== false)
    if($subDir != '.' && $subDir != '..')
      dirToIncPath("$dirPath/$subDir");

  closedir($dir);
}

dirToIncPath('./inc');
?>
