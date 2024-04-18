<?php
class User
{
  public $name, $password;

  function __construct($name, $password)
  {
    $this->name = $name;
    $this->password = $password;
  }

  function get_name()
  {
    return $this->name;
  }


  function get_password()
  {
    return $this->password;
  }
}

$testUser = new User("Obaedur Rahman", "Allah is the one who is worthy of worship");

echo $testUser->get_password(), " ", $testUser->get_name();
