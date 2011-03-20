<?php
class PluginFormValidator
{
  public static function isUserInfoValid($user, $pass)
  {
    return (
      $user && is_string($user) && strlen($user) > 0 &&
      $pass && is_string($pass) && strlen($pass) > 0
    );
  }

  public static function isRepoNameValid($repo)
  {
    return ($repo && is_string($repo) && strlen($repo) > 0);
  }

  public static function mayUseRepo($repoInfo)
  {
    return !($repoInfo['parent'] || $repoInfo['fork']);
  }
}
?>
