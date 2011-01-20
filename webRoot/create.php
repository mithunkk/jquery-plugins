<?php
/* 
 * Roughing in sand box code for what plugin creation will _probably_ look
 * like. No, this is not production code.
 */

require_once('./inc/autoloader.php');

if($_POST['doCreate'])
{
  if(!$_POST['username'] || !$_POST['password'] || !$_POST['repo'])
    throw new Exception('invalid input');

  $github = new phpGitHubApi();

  $github->authenticate($_POST['username'], $_POST['password'], phpGitHubApi::AUTH_HTTP_PASSWORD);
  $userInfo = $github->getUserApi()->show($_POST['username']);
  $github->deAuthenticate(); //don't need to be auth'd any longer

  if(!isset($userInfo["private_gist_count"]))
    throw new Exception('unauthorized');
  else
  {
    try
    {
      $repoInfo = $github->getRepoApi()->show($_POST['username'], $_POST['repo']);

      if($repoInfo['owner'] != $_POST['username'])
        throw new Exception('You are not the owner of this repo.');

      if($repoInfo['fork'] || $repoInfo['parent'])
        throw new Exception('You cannot add a forked repository.');

      echo "all's well"; 
    }
    catch(phpGitHubApiRequestException $e)
    {
      switch($e->getCode())
      {
        case 401:
          $msg = 'We are currently processing too many GitHub requests. Please try again in a few minutes.';
          break;

        case 404:
          $msg = 'That repo does not exist.';
          break;

        default:
          $msg = 'Unknown GitHub error.';
          break;
      }

      throw new Exception($msg);
    }
    catch(Exception $e)
    {
      throw new Exception('there was a problem');
    }
  }
}
?>

<html>
  <body>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="doCreate" value="1"/>
      Repo <input type="text" name="repo" value="sag"/><br/>
      User <input type="text" name="username" value="sbisbee"/><br/>
      Pass <input type="password" name="password"/><br/>
      <input type="submit">
    </form>
  </body>
</html>
