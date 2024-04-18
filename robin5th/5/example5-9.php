<?php
// function_exists() function is used to check whether a function with a specified name is already defined or not. It returns true if the function exists and is defined; otherwise, it returns false. This is particularly useful when working with conditional functions, to avoid redefining functions, or when working with optional plugins or modules where a function might only be available if a certain plugin is installed
if (function_exists("array_combine")) {
  echo "Function exists";
} else {
  echo "Function does not exist - better write our own";
}
