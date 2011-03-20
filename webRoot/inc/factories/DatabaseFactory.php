<?php
class DatabaseFactory
{
  public static function makeCouchDBDriver()
  {
    return new CouchDBDriver(self::makeCouchDBLibrary());
  }

  public static function makeCouchDBLibrary()
  {
    $sag = new Sag('205.186.144.58');
    $sag->setDatabase('plugins');
    
    return $sag;
  }
}
?>
