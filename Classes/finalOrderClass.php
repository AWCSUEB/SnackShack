<?php

require_once 'HTTP/Request2.php';

class finalOrder
{
  var $basket;
  var $userInfo;
  var $paymentInfo;

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

  function sessionEnd() {
    session_unset();
    session_destroy();
  }

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

  function toStaticHTML() {
    if (isset($_POST["ordersubmitted"])) {
      $a = array(
        'basket' => $this->basket->toArray(),
        'userinfo' => $this->userInfo->toArray(),
        'paymentinfo' => $this->paymentInfo->toArray()
      );
      //$req = new HTTP_Request2('http://boiling-coast-46544.herokuapp.com/storeData');
      $req = new HTTP_Request2('http://blooming-cove-71969.herokuapp.com/storeData');
      //$req = new HTTP_Request2('http://localhost:3000/storeData');
      $req->setMethod(HTTP_Request2::METHOD_POST);
      $req->addPostParameter('order', json_encode($a, JSON_PRETTY_PRINT));
      $res = $req->send();

      echo $res->getBody();
      return $res->getStatus();
    }
  }
}

?>