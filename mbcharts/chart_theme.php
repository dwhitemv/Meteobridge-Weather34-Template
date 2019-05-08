<?php
include('../settings.php');

$animationduration = '500';

if ($charttheme == 'light') {
  $backgroundcolor  = '#fff';
  $fontcolor        = '#555';
  $gridcolor        = '#555';
  $line1color       = 'rgba(255, 124, 57, 0.8)';
  $line2color       = '#1ABECE';
  $line2markercolor = '#33D7E7';
  $xcrosshaircolor  = '#009bab';
  $ycrosshaircolor  = '#ff832f';
  $fontcolorsmall   = '#000';
} else {
  $backgroundcolor  = 'RGBA(37, 41, 45, 0.9)';
  $fontcolor        = '#ccc';
  $gridcolor        = 'RGBA(64, 65, 66, 0.8)';
  $line1color       = 'rgba(255, 148, 82, 0.95)';
  $line2color       = '#00A4B4';
  $line2markercolor = '#007181';
  $xcrosshaircolor  = '#009bab';
  $ycrosshaircolor  = '#ff832f';
  $fontcolorsmall   = '#fff';
}

?>