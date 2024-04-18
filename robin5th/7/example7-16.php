<?php //upload2.php
echo <<<_END
    <html><head><title>PHP Form Upload</title></head><body>
    <form method='post' action='example7-16.php' enctype='multipart/form-data'>
    Select a JPG, GIF, PNG or TIF File:
    <input type='file' name='filename' size='10'>
    <input type='submit' value='Upload'></form>
_END;
var_dump($_FILES);
// echo($_FILES['filename']['tmp_name']);
if ($_FILES) {
  $name = $_FILES['filename']['name'];

  switch ($_FILES['filename']['type']) {
    case 'image/jpeg':
      $ext = 'jpg';
      break;
    case 'image/gif':
      $ext = 'gif';
      break;
    case 'image/png':
      $ext = 'png';
      break;
    case 'image/tiff':
      $ext = 'tif';
      break;
    default:
      $ext = '';
      break;
  }
  if ($ext) {
    $n = "img.$ext";
    move_uploaded_file($_FILES['filename']['tmp_name'], $n);
    echo "Uploaded image '$name' as '$n':<br>";
    echo "<img src='$n'>";
  } else echo "'$name' is not an accepted image file";
} else echo "No image has been uploaded";

echo "</body></html>";
