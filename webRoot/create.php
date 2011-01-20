<?php
/* 
 * Roughing in sand box code for what plugin creation will _probably_ look
 * like. No, this is not production code.
 */

require_once('./inc/autoloader.php');

if($_POST['doCreate'])
{
  if(!$_POST['username'])
    throw new Exception('Forgot username.');

  if(!$_POST['password'])
    throw new Exception('Forgot password.');

  if(!$_POST['repo'])
    throw new Exception('Forgot repo.');

  if(!is_array($_POST['hash']) || count($_POST['hash']) <= 0)
    throw new Exception('No tags selected.');

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

      $availableTags = $github->getRepoApi()->getRepoTags($_POST['username'], $_POST['repo']);

      foreach($_POST['hash'] as $hash)
      {
        //TODO error if a submitted tag is already added

        if(!in_array($hash, $availableTags))
          throw new Exception('That is not a tag in your repository.');

        //TODO get plugin.json from each tag here
      } 
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
  }
}
?>

<html>
  <head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
      var getTagsButton = $('#getTags');

      $(getTagsButton).click(function() {
        var user = $(getTagsButton).parent().children('input[name=username]').val();
        var repo = $(getTagsButton).parent().children('input[name=repo]').val();

        $.ajax({
          url: 'http://github.com/api/v2/json/repos/show/'+user+'/'+repo+'/tags',
          dataType: 'jsonp',
          success: function(data) {
            for(var name in data.tags)
              $('#tags').append('<li><input type="checkbox" name="hash[]" value="'+data.tags[name]+'"/> '+name);
          }
        });

        return false;
      });
    });
    </script>
  </head>
  <body>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="doCreate" value="1"/>
      User <input type="text" name="username" value="sbisbee"/><br/>
      Repo <input type="text" name="repo" value="sag"/><br/>

      <a href="#" id="getTags">Get Tags</a>
      <ul id="tags"></ul>

      JSON file location <input type="text" name="jsonPath"/>/plugin.json<br/>
      Pass <input type="password" name="password"/><br/>
      <input type="submit">
    </form>
  </body>
</html>
