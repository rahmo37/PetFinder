<?php

// The double :: is used to access static methods, static properties, and constants of a class without needing to instantiate an object of that class. In your example, User::pwd_string(); calls the static method pwd_string of the User class directly. This operator allows for the direct invocation of class methods and access to properties that are meant to be available at the class level, not at the instance level.
  User::pwd_string();

  class User
  {
    static function pwd_string()
    {
      echo "Please enter your password";
    }
  }
?>
