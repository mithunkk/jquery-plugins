<?php
class CouchDBDriver implements DatabaseDriverI
{
  private $sag;

  public function __construct($sag)
  {
    $this->sag = $sag;
  }

  public function get($id)
  {
    return $this->sag->get($id)->body;
  }
}
?>
