<?php 

$dialog = $_GET['d'];


if($dialog == 'find-showid'){
  include_once('dialogs/find-showid.php');
  return;
}


if($dialog == 'add-show'){
  include_once('dialogs/add-show.php');
  return;
}

if($dialog == 'movie-history'){
  include_once('dialogs/movie-history.php');
  return;
}

?>









