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

  public function create($id, $data)
  {
    return $this->sag->put($id, $data)->body;
  }

  public function addAttachment($id, $rev, $name, $data)
  {
    return $this->sag->setAttachment($name, $data, "application/javascript", $id, $rev)->body;
  }
}
?>
