<?php
class PluginDAO
{
  private static $instance;

  private $db;
  private $gitHub;

  private function __construct($gitHubFacade)
  {
    if(!$gitHubFacade)
      throw ErrorFactory::makeError(ERROR_MISC_SYSTEM);

    $this->gitHub = $gitHubFacade;
    
    $this->db = DatabaseFactory::makeCouchDBDriver();
  }

  public static function getInstance($gitHubFacade = null)
  {
    if(!self::$instance)
      self::$instance = new PluginDAO($gitHubFacade);

    return self::$instance;
  }

  public function get($user, $plugin)
  {
    try
    {
      return new PluginVO($this->db->get("$user-$plugin"));
    }
    catch(SagException $e)
    {
      throw ErrorFactory::makeError(ERROR_NO_PLUGIN);
    }
  }

  public function addVersions($user, $plugin, $tags)
  {
    if(!is_array($tags))
      throw ErrorFactory::makeError(ERROR_MISC_SYSTEM);

    try
    {
      $pluginVO = self::get($user, $plugin);
    }
    catch(SagCouchException $e)
    {
      if($e->getCode() != 404)
        throw ErrorFactory::makeError(ERROR_DATABASE);

      //TODO create the plugin
    }
    catch(Exception $e)
    {
      throw ErrorFactory::makeError(ERROR_MISC_SYSTEM);
    }
  }
}
?>
