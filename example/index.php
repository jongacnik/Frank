<?php
  require('./../frank.php');
  $frank = new Frank(); // optionally pass in path to your content
  
  print_r($frank->site());
?>