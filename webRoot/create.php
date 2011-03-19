<?php
if(sizeof($_POST) > 0)
{
  require_once './inc/Config.php';
  require_once './inc/autoloader.php';

  $gitHub = new GitHubFacade($_POST['username'], $_POST['password'], $_POST['repo']);

  if($gitHub->login())
  {
    $pluginDAO = PluginDAO::getInstance($gitHub);
    $pluginDAO->addVersions($_POST['username'], $_POST['repo'], $gitHub->getRepoTags($_POST['hash']));
  }

  die;
}
?>

<!DOCTYPE html> 
 
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6 jquery"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7 jquery"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8 jquery"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9 jquery"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js jquery"> <!--<![endif]-->
<head> 

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>jQuery: The Write Less, Do More, JavaScript Library</title> 
 
<meta name="author" content=""> 
<meta name="description" content="jQuery: The Write Less, Do More, JavaScript Library"> 

<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">

<link rel="shortcut icon" href="assets/i/favicon.ico">
<link rel="stylesheet" href="assets/css/style.css?v=1">
<link rel="stylesheet" href="assets/css/syntax.css?v=1">

<script src="assets/js/modernizr-1.5.min.js"></script>
<!--[if (gte IE 6)&(lte IE 8)]>
<script src="assets/js/selectivizr.js"></script>
<![endif]-->
</head> 
<body>

<!-- projects -->
<div class="project-select">
  <ul class="constrain"> 
    <li>
      <a href="#" title="" class=""> 
        <img src="assets/i/logo-top-jquery.png" alt="jQuery" /> 
        <em>The core JS framework that allows you to write less, do more.</em> 
      </a>
    </li> 
    <li>
      <a href="#" title="" class=""> 
        <img src="assets/i/logo-top-ui.png" alt="jQuery UI" /> 
        <em>The officially supported User Interface library for jQuery.</em> 
      </a>
    </li> 
    <li>
      <a href="#" title="" class=""> 
        <img src="assets/i/logo-top-mobile.png" alt="jQuery Mobile"> 
        <em>Build mobile web apps with jQuery using this framework.</em> 
      </a>
    </li> 
    <li>
      <a href="#" title="" class=""> 
        <img src="assets/i/logo-top-sizzle.png" alt="SizzleJS" /> 
        <em>A smoking fast CSS selector engine for JavaScript.</em> 
      </a>
    </li> 
    <li>
      <a href="#" title="" class=""> 
        <img src="assets/i/logo-top-qunit.png" alt="QUnit" /> 
        <em>Write solid JavaScript apps by unit testing with QUnit.</em> 
      </a>
    </li>
  </ul>
</div>
<!-- /projects -->

<!-- nav -->
<header class="border clearfix">
  
  <nav class="constrain clearfix top">
    <ul class="projects">
      <li class="jquery"><a href="#" title="jQuery">jQuery</a></li>
      <li class="jquery-ui"><a href="#" title="jQuery UI">jQuery UI</a></li>
      <li class="jquery-mobile"><a href="#" title="jQuery Mobile">jQuery Mobile</a></li>
      <li class="toggle-projects"><a href="#" title="All Projects">All Projects</a></li>
    </ul>
    <ul class="links">
      <li class="dropdown"><a href="#" title="Support">Support</a>
        <ul>
          <li><a href="#" title="Forum">Forum</a></li>
          <li><a href="#" title="IRC/Chat">IRC/Chat</a></li>
          <li><a href="#" title="Getting Help">Getting Help</a></li>
          <li><a href="#" title="Report a Bug">Report a Bug</a></li>
          <li><a href="#" title="Enterprise Support">Enterprise Support</a></li>
        </ul>
      </li>
      <li class="dropdown"><a href="#" title="Community">Community</a>
        <ul>
           <li><a href="#" title="Blog">Blog</a></li>
           <li><a href="#" title="Podcast">Podcast</a></li>
           <li><a href="#" title="Forums">Forums</a></li>
           <li><a href="#" title="Meetups">Meetups</a></li>
           <li><a href="#" title="Events">Events</a></li>
        </ul>
      </li>
      <li><a href="#" title="Contribute">Contribute</a></li>
      <li><a href="#" title="Donate">Donate</a></li>
      <li class="dropdown"><a href="#" title="">About</a>
        <ul class="last">
          <li><a href="#" title="Overview">Overview</a></li>
          <li><a href="#" title="Projects">Projects</a></li>
          <li><a href="#" title="Team">Team</a></li>
          <li><a href="#" title="History">History</a></li>
          <li><a href="#" title="Sponsors">Sponsors</a></li>
          <li><a href="#" title="Contact">Contact</a></li>
        </ul>
      </li>
    </ul>
  </nav>
  
</header>
<!-- /nav -->

<!-- container --> 
<div id="container" class="constrain"> 
  
  <!-- header -->
  <header class="clearfix">
  
    <!-- logo -->
    <h1><a href="#" title="jQuery">jQuery</a></h1>
    <!-- /logo -->
    
    <!-- ads or events -->
    <aside></aside>
    <!-- /ads  or events -->
    
    <!-- secondary nav -->
    <nav class="clearfix">
      <ul>
        <li><a href="#" title="Overview">Overview</a></li>
        <li class="active"><a href="#" title="Plugins">Plugins</a></li>
        <li><a href="#" title="API">API</a></li>
        <li><a href="#" title="Documentation">Documentation</a></li>
        <li><a href="#" title="Development">Development</a></li>
        <li><a href="#" title="Download">Download</a></li>
      </ul>
      <form method="get" action="" class="search">
        <input type="text" id="search" name="search"></li>
        <label for="search" class="text">Search jQuery.com</label> 
        <a href="#" class="icon icon-search" title="Submit Search">Submit Search</a>
      </form>
    </nav>
    <!-- /secondary nav -->

  </header>
  <!-- /header -->
  
  <!-- body -->
  <div id="body" class="clearfix">
      
    <h2 class="title" id="pageName">Create a Plugin</h2>
    
    <div class="col2-1">
      <div id="pageContent">
      </div>
      <div>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <input type="hidden" name="doCreate" value="1"/>
          User <input type="text" name="username"/><br/>
          Repo <input type="text" name="repo"/><br/>

          <a href="#" id="getTags">Get Tags</a>
          <ul id="tags"></ul>

          Pass <input type="password" name="password"/><br/>
          <input type="submit">
        </form>
      </div>
    </div>
    
    <div class="col2-1">
      
      
    </div>
    
  </div>
  <!-- /body -->

</div>
<!-- /container -->

<!-- footer -->
<footer class="clearfix">
  
  <div class="constrain">
  
    <div class="col7-3 col">
      <h3><span>Links</span></h3>
      
      
    </div>
    
    <div class="col7-2 col">
      <h3><span>Presentations</span></h3>
      <ul class="presentations">
        <li>
          <a href="#">
            <span><img src="content/presentations/building-spas-jquerys-best-friends.jpg" width="142" height="92" /></span>
            <strong>Building Single Page Applications With jQuery’s Best Friends</strong><br />
            <cite>Addy Osmoni</cite>
          </a>
        </li>
        <li>
          <a href="#">
            <span><img src="content/presentations/addyosmani-2.jpg" width="142" height="92" /></span>
            <strong>jQuery Performance<br />Tips &amp; Tricks</strong><br />
            <cite>Addy Osmoni</cite>
          </a>
        </li>
      </ul>
    </div>
    
    <div class="col7-2 col">
      <h3><span>Books</span></h3>
      <ul class="books">
        <li>
          <a href="#">
            <span class="bottom"><img src="content/books/learning-jquery-1.3.jpg" width="92" height="114" /></span>
            <strong>Learning jQuery 1.3</strong><br />
            <cite>Karl Swedberg and Jonathan Chaffer</cite>
          </a>
        </li>
        <li>
          <a href="#">
            <span><img src="content/books/jquery-in-action.jpg" width="92" height="114" /></span>
            <strong>jQuery in Action</strong><br />
            <cite>Bear Bibeault and Yehuda Katz</cite>
          </a>
        </li>
        <li>
          <a href="#">
            <span><img src="content/books/jquery-enlightenment.jpg" width="92" height="114" /></span>
            <strong>jQuery Enlightenment</strong><br />
            <cite>Cody Lindley</cite>
          </a>
        </li>
      </ul>
    </div>
    <div id="legal">
      
    </div>
  </div>
</footer>
<!-- /footer -->

<!-- scripts -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="assets/js/jquery-1.5.1.min.js"%3E%3C/script%3E'))</script>
<script src="assets/js/underscore.js"></script> 
<script src="assets/js/jquery.tmpl.js"></script> 
<script src="assets/js/jquery.tmplPlus.js"></script> 
<script id="tooltip-template"> 
  <div class="tooltip">
    <a href="${url}" title="${title}" class="jq-tooltip-branding"><img src="${preview}" /></a>
    <ul>{{each(i,link) links}}<li><a href="${link[1]}">${link[0]}</a></li>{{/each}}</ul>
  </div>
</script> 
<script src="assets/js/plugins.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/js/create.js"></script>

<!-- /scripts -->

</body> 
</html>
