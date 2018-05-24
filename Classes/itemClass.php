<?php

//========================================
// Class item = represents a product that is a
//item is in shopping basket
class item {
  var $code; // code
  var $name; // name
  var $quantity; // quantity
  var $price; // price per item

  function item($code, $name, $quantity, $price)
  {
    $this->code = $code;
    $this->name = $name;
    $this->quantity = $quantity;
    $this->price = $price;
  }

  // Make individual item vector
  function getVector()
  {
    return "{" . $this->code . ", " . $this->quantity . ", " . number_format($this->price, 2) . "}";
  }
}