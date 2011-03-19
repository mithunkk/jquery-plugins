<?php
class GitHubDAO
{
  private static $instance;

  private $user;
  private $pass;

  private $github;

  public function __construct($user, $pass, $repo)
  {
    if(!PluginFormValidator::isUserInfoValid($user, $pass))
      throw new Exception("Username and password are required.");

    if(!PluginFormValidator::isRepoNameValid($repo))
      throw new Exception("Repo name is required.");

    $this->user = $user;
    $this->pass = $pass;
    $this->repo = $repo;

    $this->github = new phpGitHubApi();
  }

  private function getRepoInfo()
  {
    try
    {
      $repo = $this->github->getRepoApi()->show($this->user, $this->repo);
    }
    catch(phpGitHubApiRequestException $e)
    {
      throw ErrorFactory::makeFromGitHubError($e);
    }

    if($repo['owner'] != $this->user)
      throw ErrorFactory::makeError(ERROR_NOT_REPO_OWNER);

    return $repo;
  }

  public function getRepoTags()
  {
    //Doesn't belong in the try/catch, because it does its own
    $repoInfo = self::getRepoInfo();
    
    if(!PluginFormValidator::mayUseRepo($repoInfo))
      throw ErrorFactory::makeError(ERROR_FORKED_REPO);

    try
    {
      return $this->github->getRepoApi()->getRepoTags($this->user, $this->repo);
    }
    catch(phpGitHubApiRequestException $e)
    {
      throw ErrorFactory::makeFromGitHubError($e);
    }
  }

  public function getUserInfo()
  {
    try
    {
      $this->github->authenticate($this->user, $this->pass, phpGitHubApi::AUTH_HTTP_PASSWORD);

      $userInfo = $github->getUserApi()->show($this->user);

      $this->github->deauthenticate();

      return $userInfo;
    }
    catch(phpGitHubApiRequestException $e)
    {
      throw ErrorFactory::makeFromGitHubError($e);
    }
  }

  public function getPluginFile($tag)
  {
    if(is_string($tags))
      $tags = array($tags);
    elseif(!is_array($tags) || sizeof($tags) <= 0)
      throw new Exception('You did not select any tags.');
  }

  public function getVersionFiles($tags = array())
  {
    if(is_string($tags))
      $tags = array($tags);
    elseif(!is_array($tags) || sizeof($tags) <= 0)
      throw new Exception('You did not select any tags.');
  }
}
?>
