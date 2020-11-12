<?php
$errors = "";
if(!empty($error)){
  $errors = '<div class="errmsg">Error: '.$error.'</div>';
}

function formatBytes($size, $precision = 2){
  $base = log($size, 1024);
  $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   

  return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>RT - <?php echo $title ?></title>
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
      <a href="#">Help</a>
      <a href="#">About</a>
    </div>
    
    <h2 class="line-title">Download Youtube Videos</h2>
    <hr class="st1-hr red-line" />
    <?php 
    echo $errors;
    if(empty($error)):
    ?>
    <div class="vinfo-sec">
      <div class="vis-center">
        <img class="video-banner" src="<?php echo $thumbnail ?>"/>
        <span class="video-title"><?php echo $title ?></span>
      </div>
      <b>Availabe formats <small>(<?php echo $count_formats ?>)</small> :</b>
      <table class="formats-list">
        <?php
        foreach ($formats as $f){
          $quality = $f['quality'];
          $mtype = explode('/', $f['mimeType']);
          $mtype = $mtype[1];
          $ftype = strtoupper($f['type']);
          $gen_link = $tf."?id={$id}&no=".$f['no']."&act=download";
          if(strtolower($f['Content-Length']) != 'n/a'){
            $size = formatBytes($f['Content-Length']);
          } else {
            $size = $f['Content-Length'];
          }
          if($f['no'] % 2 == 0){
            echo "<tr>\n          <td class=\"list-items\"><span class=\"type-tit\">Quality: {$quality}</span><span class=\"type-tit\">Size: {$size}</span><span class=\"type-tit\">Type: {$mtype} <small>({$ftype})</small></span><a class=\"download-btn-mini\" href=\"{$gen_link}\">Download</a></td>\n";
          } else {
            echo "          <td class=\"list-items\"><span class=\"type-tit\">Quality: {$quality}</span><span class=\"type-tit\">Size: {$size}</span><span class=\"type-tit\">Type: {$mtype} <small>({$ftype})</small></span><a class=\"download-btn-mini\" href=\"{$gen_link}\">Download</a></td>\n        </tr>\n";
          }
        }
        if($count_formats % 2 != 0){
          echo "        </tr>";
        }
        ?>
      </table>
    </div>
    <?php
    endif;
    ?>
    <div class="crdt">&copy; Monzurul Hasan</div>
  </body>
</html>
