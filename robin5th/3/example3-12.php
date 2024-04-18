<?php
function longdate($timestamp)
{
  return date("l F jS Y", $timestamp);
}
//   function longdate($timestamp) - This declares a function named longdate that takes one argument, $timestamp. The $timestamp is expected to be a Unix timestamp, which is the number of seconds since January 1, 1970, known as the Unix epoch.

// return date("l F jS Y", $timestamp); - Inside the function, the date function is called. The date function formats a Unix timestamp into a human-readable date. The string "l F jS Y" dictates the format:

// l (lowercase 'L') - Represents the full textual representation of the day of the week (e.g., Monday, Tuesday).

// F - Represents the full textual representation of a month (e.g., January, February).

// jS - Represents the day of the month with an ordinal suffix (e.g., 1st, 2nd, 3rd). The j gives the day number and the S adds the appropriate ordinal suffix.

// Y - Represents the full numeric representation of a year, 4 digits (e.g., 1999, 2003).

// So when you call longdate(1633036800), it might return a string like "Thursday October 1st 2021", depending on the timestamp provided. The function converts the given timestamp into a formatted string that is easier to read and understand than the Unix timestamp number.
echo longdate(1723036800);
