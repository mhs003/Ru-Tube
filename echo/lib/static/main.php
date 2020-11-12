<?php
$errors = '';
if(!empty($error)){
  $errors = '<div class="errmsg">Error: '.$error.'</div>'; 
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Ru-Tube</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/script.js"></script>
  </head>
  <body style="">
    <div class="top-nav">
      <a href="." class="nav-name">Ru-Tube</a>
      <div onClick="navToggle(this)" class="nav-toggle">
        <span class="bar1"></span>
        <span class="bar2"></span>
        <span class="bar3"></span>
      </div>
      </div>
    <div id="nav-bd" class="nav-itm">
      <a href="index.php">Home</a>
      <a href="help.php">Help</a>
      <a href="about.php">About</a>
    </div>
    
    <h2 class="line-title">Download Youtube Videos</h2>
    <hr class="st1-hr red-line" />
    <?php echo $errors ?>
    <form method="post" action="<?php echo $tf ?>">
      <div class="url-bar-mn">
        <input id="urlBox" class="url-bar" type="url" name="url" onKeyUp="setLinkEdit()" placeholder="https://youtu.be/XXXXXXXXXX or, https://youtube.com/watch?v=XXXXXXXXXXX" value="<?php echo $url ?>" required/>
      </div>
      <div style="text-align: center">
        <button type="submit" class="ripl-btn waves-effect waves-light waves-ripple">Download</button>
      </div>
    </form>
    <div class="crdt">&copy; Monzurul Hasan</div>
  </body>
</html>
