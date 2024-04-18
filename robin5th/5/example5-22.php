<?php
// The protected keyword in PHP is used to define the visibility of class properties and methods. A protected property or method can be accessed within the class it is defined in, as well as by classes that extend this class (child classes). It cannot be accessed from outside these classes, which makes it more restrictive than public but less restrictive than private.
class Example
{
  var $name = "Michael";   // Same as public but deprecated
  public $age = 23;        // Public property
  protected $usercount;    // Protected property

  private function admin() // Private method
  {
    // Admin code goes here
  }
}
