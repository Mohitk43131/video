<?php
function e($s){return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');}
function redirect($url){ header('Location: ' . $url); exit; }
function human_size($bytes){
  $units=['B','KB','MB','GB','TB']; $i=0; while($bytes>=1024 && $i<count($units)-1){$bytes/=1024;$i++;}
  return round($bytes,2).' '.$units[$i];
}