<?php
	$coordinates = $_REQUEST['coordinates'];
?>

<iframe
  style="width:100%;height:100%;"
  frameborder="0" style="border:0"
  src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDclZ4x-p5A1VQuKtjhLhvDBMg5xXzUvHs
    &q=<?=$coordinates?>">
</iframe>