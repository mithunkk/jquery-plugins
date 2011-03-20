<?php
class GitHubFacade
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

  public function getRepoTags($filterHashes)
  {
    if(!is_array($filterHashes) || sizeof($filterHashes) < 0)
      throw ErrorFactory::makeError(ERROR_NO_TAGS);

    //Doesn't belong in the try/catch, because it does its own
    $repoInfo = self::getRepoInfo();
    
    if(!PluginFormValidator::mayUseRepo($repoInfo))
      throw ErrorFactory::makeError(ERROR_FORKED_REPO);

    $validTags = array(); //these tags have been validated against repo's info

    try
    {
      $tags = $this->github->getRepoApi()->getRepoTags($this->user, $this->repo);

      foreach($filterHashes as $tagHash)
      {
        if(($tagName = array_search($tagHash, $tags)) !== false)
        {
          $tag = new TagVO();
          $tag->name = $tagName;
          $tag->hash = $tagHash;

          $validTags[] = $tag;
        }
        else
          throw ErrorFactory::makeError(ERROR_UNKNOWN_TAG);
      }
    }
    catch(phpGitHubApiRequestException $e)
    {
      throw ErrorFactory::makeFromGitHubError($e);
    }

    return $validTags;
  }

  public function login()
  {
    try
    {
      $this->github->authenticate($this->user, $this->pass, phpGitHubApi::AUTH_HTTP_PASSWORD);

      $userInfo = $this->github->getUserApi()->show($this->user);

      $this->github->deauthenticate();

      if(!isset($userInfo['private_gist_count']))
        throw ErrorFactory::makeError(ERROR_INVALID_CREDENTIALS);

      return true;
    }
    catch(phpGitHubApiRequestException $e)
    {
      throw ErrorFactory::makeFromGitHubError($e);
    }
  }

  public function getPackageFile()
  {
    try
    {
      $fileMeta = array_shift($this->github->getCommitApi()->getFileCommits($this->user, $this->repo, 'master', 'package.json'));

      $file = $this->github->getObjectApi()->showBlob($this->user, $this->repo, $fileMeta['id'], 'package.json');

      try
      {
        return json_decode($file['data']);
      }
      catch(Exception $e)
      {
        //invalid JSON
        throw ErrorFactory::makeError(ERROR_INVALID_PACKAGE_FILE);
      }
    }
    catch(Exception $e)
    {
      throw ErrorFactory::makeError(ERROR_MISSING_PACKAGE_FILE);
    }
  }

  public function getSourceFileAt($hash)
  {
    try
    {
      $file = $this->github->getObjectApi()->showBlob($this->user, $this->repo, $hash, "jQuery.{$this->repo}.js");
      return $file['data'];
    }
    catch(Exception $e)
    {
      return null;
    }
  }
}
?>
