<?php
require "itemClass.php"; // basket class uses the item class to store the item details such as code, name, quantity and price

/**
 * basket class represents shopping basket with
 * the variable $session_basket - the Array of item instances in basket
 */
class basket {
  /**
   * constructor
   */
  function basket() {
    $this->sessionStart();
  }

  /**
   * start session OR if one already created
   * retrieve shopping_basket
   */
  function sessionStart() {
    global $session_basket;      //global variable ---array of items in basket

    //start session or retrieve if already exists with client
    session_start();

    //if previously started grab data associated with session_basket
    if(isset($_SESSION['session_basket']))
    {
      $session_basket = $_SESSION['session_basket'];
    }
    else
    {
      //if no session_basket initially to empty array
      $session_basket = Array();

      //store in SESSION variables
      $_SESSION['session_basket'] = $session_basket;
    }
  }

  /**
   *determine the number of elements in basket
   */
  function basketSize() {
    global $session_basket;

    // make session if not found
    if ($session_basket == "") {
      $this->sessionStart();
    }

    if (! is_array($session_basket)) {
      return 0;
    }

    return count($session_basket); //number of elements in the array $session_basket
  }

  /**
   * register item in session to add or modify something in basket/cart
   * takes a code (productid), name, quantity, and price inputs to save to the session
   */
  function registerItem($code, $name, $quantity, $price) {
    global $session_basket;

    // make session if not found
    if ($session_basket == "") {
      $this->sessionStart();
    }

    // test to see if this product (with id $code) is currently IN basket, if so EDIT IT (update)
    if (! $this->editItem($code, $name, $quantity, $price)) {
      $item = new item($code, $name, $quantity, $price); //if NOT in basket CREATE item
      $session_basket[] = $item;  // ADD the new item to the array $session_basket
    }

    //Make sure to add updated $session_basket array to the SESSION variable
    $_SESSION['session_basket'] = $session_basket;
  }

  /**
   * see if product (with product id $code) is in the current $session_basket array
   * if exist, modify it and return true
   * else retrun false
   */
  function editItem($code, $name, $quantity, $price) {
    global $session_basket;

    // make session if not found
    if ($session_basket == "") {
      $this->sessionStart();
      return false;
    }

    reset($session_basket);  //segt pointer in array to first element

    //cycle through elements in basket (array $session_basket) until you find the $item you want to edit
    while(list($k, $v) = each ($session_basket)) { //search in $session_basket

      if ($session_basket[$k]->code == $code) { //if found matching code (product id)
        // Found same code --- upade with new values the item
        $session_basket[$k]->name = $name;
        $session_basket[$k]->quantity = $quantity;
        $session_basket[$k]->price = $price;

        return true; //return true we updated it
      }
    }

    return false; //could not find the product in the basket
  }

  /**
   * delete item from basket ($session_basket  array) that has product id of $code and name of $name
   */
  function deleteItem($code, $name) {
    global $session_basket;

    // make session if not found
    if ($session_basket == "") {
      $this->sessionStart();
    }

    reset($session_basket);  //set pointer in array to first element

    //cycle through basket (array $session_basket) and look to see if item is there
    while(list($k, $v) = each ($session_basket)) { //look through each item in basket

      if ($session_basket[$k]->code == $code) { //if this item's code matches $code then we found the one to remove
        unset($session_basket[$k]); //remove this item from the $session_basket array

        //Make sure to add updated $session_basket array to the SESSION variable
        $_SESSION['session_basket'] = $session_basket;

        return true;
      }
    }
  }

  /*
   * Return the number of a particular item in the cart
   */
  function getItemQty($code) {
    global $session_basket;

    while(list($k, $v) = each ($session_basket)) { //look through each item in basket
      if ($session_basket[$k]->code == $code) { //if this item's code matches $code then we found the one
        return $session_basket[$k]->quantity; // return the item quantity
      }
    }

    return 0;
  }

  /*
   * Get the string representation of the basket contents
   */
  function getVector() {
    global $session_basket;

    $vector = "";
    $initial = true;
    while(list($k, $v) = each ($session_basket)) { //look through each item in basket
      if (!$initial) // Only add comma if this is not the first item in basket
        $vector .= ", ";
      else
        $initial = false;
      $vector .= $session_basket[$k]->getVector(); // Add vector string for individual item
    }

    return $vector;
  }

  /*
   * Get order total with tax and shipping
   */
  function getTotal() {
    global $session_basket;
    $subtotal = 0;
    $quantity = 0;

    foreach ($session_basket as $k => $v) {
      $quantity += $v->quantity;
      $subtotal += $v->quantity * $v->price;
      $subtotal = number_format($subtotal, 2);
    }

    $subtotal = number_format($subtotal, 2);
    $tax = number_format($subtotal * .08, 2);
    $shipping = number_format(2.00, 2);
    $total = number_format($subtotal + $tax + $shipping, 2);

    return $total;
  }

  /*
   * Generate an array of the basket data that will represent an order document
   */
  function toArray() {
    $a = array(
      'date' => date("c"),
      'order_vector' => $this->getVector(),
      'total' => $this->getTotal()
    );

    return $a;
  }

  /*
   * Render the basket contents into a form HTML when visiting cart.php
   */
  function toFormHTML() {
    global $session_basket;

    $subtotal = "0.00"; // For calculating the subtotal of all items in the cart so far
    $quantity = 0; // For adding up the number of total items in the cart

    // Output the heading part of the basket display
    echo <<<HEADING
    <div id="cart">
      <div id="carttitlediv">
        <span id="carttitle">Shopping Cart </span><img id="carttitleimg" src="Images/shoppingcart.png"/>
      </div>
      <div id="cartlist">
        <div id="header">
          <div class="itemimg"></div>
          <div class="iteminfo">Description</div>
          <div class="itemprice">Price</div>
          <div class="itemqty">Quantity</div>
        </div>
        <div id="items">

HEADING;

    // For an empty basket display some kind words by default to add an item to the basket
    if ($this->basketSize() == 0)
    {
      echo <<<EMPTY
          <div class="item">
            <div class="itemimg"><img src="Images/product1_small.png" style="visibility: hidden"></div>
            <div class="iteminfo">Basket is Empty! Please add a product.</div>
            <div class="itemprice price"><span style="visibility: hidden">$0.00</span></div>
            <div class="itemqty"><span style="visibility: hidden">0</span></div>
          </div>

EMPTY;
    }

    // Go through each item in the basket, add to the subtotal and quantity
    // Display each item in a separate row with its picture, name, price and quantity
    foreach ($session_basket as $k => $v)
    {
      $quantity += $v->quantity;
      $subtotal += $v->quantity * $v->price;
      $subtotal = number_format($subtotal,2);
      echo <<<ITEM
          <div class="item" id="product{$v->code}">
            <div class="itemimg"><img src="Images/product{$v->code}_small.png" alt="{$v->name}"></div>
            <div class="iteminfo"><a href="product{$v->code}.html">{$v->name}</a></div>
            <div class="itemprice price">\${$v->price}</div>
            <div class="itemqty">
              <form action="cart.php" method="POST">
              <input type="hidden" name="action" value="replace">
              <input type="hidden" name="productid" value="{$v->code}">
              <input type="hidden" name="name" value="{$v->name}">
              <input type="hidden" name="price" value="{$v->price}">
              <select name="quantity">

ITEM;
      // Set the quantity picker to the current selected quantity
      for ($i = 0; $i <= 25; $i++)
      {
        if ($v->quantity == $i) // This is the matching quantity so mark as selected
        {
          echo "              <option value=\"$i\" selected=\"selected\">$i items</option>\n";
        }
        else // Other quantity values are included but not selected
        {
          echo "              <option value=\"$i\">$i items</option>\n";
        }
      }

      // Allow the user to change the item quantity, or to delete the item from the cart
      echo <<<ITEM
              </select>
              <div id="updatediv"><input type="submit" id="update" name="update" value="Update"></div>
              <div id="deletediv"><input type="submit" id="delete" name="delete" value="Delete"></div>
              </form>
            </div>
          </div>

ITEM;
    } // end foreach loop for printing basket items

    // Print out the subtotal and total quantity we just calculated from all items in cart
    echo <<<SUBTOTAL
        </div>
        <div id="subtotal">
          <div class="itemimg"></div>
          <div class="iteminfo">Subtotal</div>
          <div class="itemprice price">\${$subtotal}</div>
          <div class="itemqty">$quantity items</div>
        </div>
      </div>
      <div id="rightcol">
        <div>Subtotal ($quantity items): <span class="price">\${$subtotal}</span></div>
        <form method="POST" action="userInfo.php">

SUBTOTAL;

    // Don't enable checkout button if there is nothing in the cart
    if ($quantity > 0)
      echo "        <input type=\"submit\" id=\"checkout\" value=\"Checkout\"/>\n";
    else
      echo "        <input type=\"submit\" id=\"checkoutdisabled\" value=\"Checkout\" disabled/>\n";
echo <<<ENDFORM
        </form>
      </div>
    </div>

ENDFORM;
  }

  /*
   * Display the contents of our basket statically for the finalOrder.php page
   */
  function toStaticHTML() {
    global $session_basket;

    $subtotal = "0.00"; // For calculating the subtotal of all items in the cart so far
    $quantity = 0; // For adding up the number of total items in the cart

    // Output the heading part of the basket display
    echo <<<HEADING
    <div id="cart">
      <div id="carttitlediv">
        <span id="carttitle">Shopping Cart </span><img id="carttitleimg" src="Images/shoppingcart.png"/>
      </div>
      <div id="cartlist">
        <div id="header">
          <div class="itemimg"></div>
          <div class="iteminfo">Description</div>
          <div class="itemprice">Price</div>
          <div class="itemqty">Quantity</div>
        </div>
        <div id="items">

HEADING;

    // Go through each item in the basket, add to the subtotal and quantity
    // Display each item in a separate row with its picture, name, price and quantity
    foreach ($session_basket as $k => $v) {
      $quantity += $v->quantity;
      $subtotal += $v->quantity * $v->price;
      echo <<<ITEM
          <div class="item" id="product{$v->code}">
            <div class="itemimg"><img src="Images/product{$v->code}_small.png" alt="{$v->name}"></div>
            <div class="iteminfo"><a href="product{$v->code}.html">{$v->name}</a></div>
            <div class="itemprice price">\${$v->price}</div>
            <div class="itemqty">{$v->quantity} items</div>
          </div>

ITEM;
    } // end item foreach loop

    // Compute and format our final total from the subtotal + tax + shipping
    $subtotal = number_format($subtotal, 2);
    $tax = number_format($subtotal * .08, 2);
    $shipping = number_format(2.00, 2);
    $total = number_format($subtotal + $tax + $shipping, 2);

    // Display the subtotal, tax, shipping and final total
    echo <<<TOTAL
        </div>
        <div id="subtotal">
          <div class="itemimg"></div>
          <div class="iteminfo">Subtotal</div>
          <div class="itemprice price">\${$subtotal}</div>
          <div class="itemqty">$quantity items</div>
        </div>
        <div id="tax">
          <div class="itemimg"></div>
          <div class="iteminfo">Tax (8.00%)</div>
          <div class="itemprice price">\${$tax}</div>
          <div class="itemqty"></div>
        </div>
        <div id="shipping">
          <div class="itemimg"></div>
          <div class="iteminfo">Shipping</div>
          <div class="itemprice price">\${$shipping}</div>
          <div class="itemqty"></div>
        </div>
        <div id="total">
          <div class="itemimg"></div>
          <div class="iteminfo"><b>Total</b></div>
          <div class="itemprice price"><b>\${$total}</b></div>
          <div class="itemqty"></div>
        </div>
      </div>

TOTAL;
  }
}