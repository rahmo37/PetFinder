<?php
$temp = "The date is ";
echo longdate(time());

function longdate($timestamp)
{
  global $temp;
  return $temp . date("l F jS Y", $timestamp);
}
