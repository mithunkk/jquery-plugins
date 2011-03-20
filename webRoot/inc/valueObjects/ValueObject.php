<?php
abstract class ValueObject
{
  public function __construct($src = null)
  {
    if($src)
      self::populate($src);
  }

  /** 
   * @var mixed An associative array or object that will be mapped into the VO.
   * @return void
   */
  public function populate($src)
  {
    if(!is_object($src) && !is_array($src))
      throw new Exception('You can only populate VOs with objects and arrays.');

    foreach($src as $k => $v) 
      if($k != "docType" && property_exists($this, $k))
        $this->$k = $v; 

    if(!empty($src->_id))
      $this->_id = $src->_id;

    if(!empty($src->_rev))
      $this->_rev = $src->_rev;
  }

  /** 
   * @var ValueObject Another VO of the same type to compare to.
   * @return bool
   */
  public function equals($alt)
  {
    if(!isset($alt) || !($this instanceof $alt))
      return false;

    foreach($alt as $k => $v) 
      if($this->$k !== $v) 
        return false;

    return true;
  }
}
?>
