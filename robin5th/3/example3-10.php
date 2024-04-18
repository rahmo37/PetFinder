<?php
$number = 12345 * 67890;
echo substr($number, 3, 1);
// echo substr($number, 3, 1); - This line uses the substr function to get a substring of the $number variable. The substr function takes three parameters: the string to extract from, the starting position, and the length of the substring. Here, it is told to start at position 3 (remember, positions in strings are zero-indexed, so this refers to the fourth digit) and to get the length of 1 character.


// $number holds the result of 12345 * 67890, which is 838102050.
// substr($number, 3, 1) is called, which looks at the string "838102050".
// It starts from the fourth character (since we start counting from 0) which is 1.
// It takes 1 character from there, so it grabs the single character 1.
// That single character 1 is what is echoed out
// 
