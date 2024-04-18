<?php
  // The final keyword in php has two main purpose, Preventing class inheritance and Preventing method overriding
  final class User
  {
    final function copyright()
    {
      echo "This class was written by Joe Smith";
    }
  }
?>
