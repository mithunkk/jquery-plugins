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
      return $this->db->get("$user-$plugin");
    }
    catch(SagException $e)
    {
      throw ErrorFactory::makeError(ERROR_NO_PLUGIN);
    }
  }

  public function addVersions($user, $pluginName, $tags)
  {
    if(!is_array($tags) || sizeof($tags) <= 0)
      throw ErrorFactory::makeError(ERROR_MISC_SYSTEM);

    $packageJSON = $this->gitHub->getPackageFile();

    if(!PackageValidator::isValidJSON($packageJSON))
      throw ErrorFactory::makeError(ERROR_INVALID_PACKAGE_FILE);

    try
    {
      $plugin = self::get($user, $pluginName);
    }
    catch(SagCouchException $e)
    {
      if($e->getCode() != 404)
        throw ErrorFactory::makeError(ERROR_DATABASE);

      $plugin = self::create($user, $pluginName, $packageJSON);
    }
    catch(Exception $e)
    {
      throw ErrorFactory::makeError(ERROR_MISC_SYSTEM);
    }

    foreach($tags as $tagVO)
    {
      //Allow tags to start with a v, as in v1.0.0
      if(substr($tagVO->name, 0, 1) == 'v')
        $tagVO->name = substr($tagVO->name, 1);

      //Allow plugins to have < 3 version places

      $tagVO->name = explode('.', $tagVO->name);

      while(sizeof($tagVO->name) < 3)
        $tagVO->name[] = '0';

      $tagVO->name = implode('.', $tagVO->name);
        
      //TODO version/tag name validator

      //TODO make sure we don't already have a source file for a tag/version

      $srcFile = $this->gitHub->getSourceFileAt($tagVO->hash);

      if(!$srcFile)
        throw ErrorFactory::makeError(ERROR_MISSING_SOURCE_FILE, $tagVO->name);

      $plugin->_rev = $this->db->addAttachment($plugin->_id, $plugin->_rev, $tagVO->name, $srcFile)->rev;
    }

    return true;
  }

  private function create($user, $pluginName, $data)
  {
    try
    {
      $meta = $this->db->create("{$user}-{$pluginName}", $data);

      $data->_id = $meta->id;
      $data->_rev = $meta->rev;

      return $data;
    }
    catch(Exception $e)
    {
      throw ErrorFactory::makeError(ERROR_PLUGIN_ALREADY_EXISTS);
    }
  }
}
?>
