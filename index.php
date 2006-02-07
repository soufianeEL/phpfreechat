<?php

require_once dirname(__FILE__)."/src/phpfreechat.class.php";
$params = array();
$params["nick"] = "guest".rand(1,10);  // setup the intitial nickname
$chat = new phpFreeChat( $params );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <title>phpFreeChat sources index</title>
    <?php $chat->printJavascript(); ?>
    <?php $chat->printStyle(); ?>
  </head>
    
  <body>
    <ul>
      <li><a href="demo/">Demos</a></li>
      <li><a href="README.en">Documentation - readme  [en]</a></li>
      <li><a href="README.fr">Documentation - readme [fr]</a></li>
      <li><a href="INSTALL.en">Documentation - install [en]</a></li>
      <li><a href="INSTALL.fr">Documentation - install [fr]</a></li>
    </ul>

  <p>See the quick demo :</p>
  <?php $chat->printChat(); ?>

  </body>
  
</html>