<?php

// Include our PEAR HTTP_Request2 method
require_once 'HTTP/Request2.php';

class finalOrder
{
  // Local copy of session data variables
  var $basket;
  var $userInfo;
  var $paymentInfo;

  /*
   * Constructor to take in a copy of session variables
   */
  function finalOrder($basket, $userInfo, $paymentInfo) {
    session_start();

    $this->basket = $basket;
    $this->userInfo = $userInfo;
    $this->paymentInfo = $paymentInfo;

    if (isset($_POST["cardnumber"])) { // POST data from payment form should be saved to session
      $this->paymentInfo->addCardInfo(
        $_POST["cardtype"],
        $_POST["cardnumber"],
        $_POST["cardexpmonth"],
        $_POST["cardexpyear"],
        $_POST["cardcvv"],
        $_POST["cardname"]
      );
    }
  }

  /*
   * Remove all session variables
   */
  function sessionEnd() {
    session_unset();
    session_destroy();
  }

  /*
   * Generate a single submit button form to allow the order to be submitted
   */
  function toFormHTML() {
    $this->basket->toStaticHTML();
    $this->userInfo->toStaticHTML();
    $this->paymentInfo->toStaticHTML();

    echo <<<FORM
    <br/>
    <div id="finalOrder">
      <form name="submitOrder" method="POST" action="finalOrder.php">
        <input type="hidden" name="ordersubmitted" value="true"/>
        <input type="submit" name="submitbtn" id="submitbtn" value="Submit Order"/>
      </form>
    </div>
FORM;
  }

  /*
   * Makes a POST request to our NodeJS app, sends all order data as a JSON array of arrays all session data, then displays the rendered NodeJS view result
   */
  function toStaticHTML() {
    if (isset($_POST["ordersubmitted"])) { // Only show this if we clicked the previous order submit button
      $a = array( // consolidate all of our session data into this array
        'basket' => $this->basket->toArray(),
        'userinfo' => $this->userInfo->toArray(),
        'paymentinfo' => $this->paymentInfo->toArray()
      );

      // Make the remote request to NodeJS to this URL
      $req = new HTTP_Request2('http://boiling-coast-46544.herokuapp.com/storeData');
      $req->setMethod(HTTP_Request2::METHOD_POST); // It will be a POST request
      $req->addPostParameter('order', json_encode($a, JSON_PRETTY_PRINT)); // Encode our array of data to JSON
      $res = $req->send(); // Send the POST request

      echo $res->getBody(); // Write the rendered body return from NodeJS into our page
      return $res->getStatus(); // We'll use the returned status to determine if the order was successful, which will be an indicator to clear the session data
    }
  }
}

?>