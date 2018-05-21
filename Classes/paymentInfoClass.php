<?php

$cardtypes = array(
  'Visa'=>'Visa',
  'MasterCard'=>'MasterCard',
  'Discover'=>'Discover',
  'AMEX'=>'AMEX'
);

$months = array(
  '01'=>'January',
  '02'=>'February',
  '03'=>'March',
  '04'=>'April',
  '05'=>'May',
  '06'=>'June',
  '07'=>'July',
  '08'=>'August',
  '09'=>'September',
  '10'=>'October',
  '11'=>'November',
  '12'=>'December'
);

$years = array(
  '2018'=>'2018',
  '2019'=>'2019',
  '2020'=>'2020',
  '2021'=>'2021',
  '2022'=>'2022',
  '2023'=>'2023',
  '2024'=>'2024',
  '2025'=>'2025',
  '2026'=>'2026',
  '2027'=>'2027',
  '2028'=>'2028'
);

/*
 * The paymentInfo class stores the shoppers provided payment info data in the session
 * It prints out a form view for the payment.php page as well as a static view for the finalOrder.php page
 */
class paymentInfo
{
  var $cardtype;
  var $cardnumber;
  var $cardexpmonth;
  var $cardexpyear;
  var $cardcvv;
  var $cardname;

  function toArray() {
    global $session_paymentinfo;

    $a = array(
      'cardtype' => $session_paymentinfo->cardtype,
      'cardnumber' => $session_paymentinfo->cardnumber,
      'cardexpmonth' => $session_paymentinfo->cardexpmonth,
      'cardexpyear' => $session_paymentinfo->cardexpyear,
      'cardcvv' => $session_paymentinfo->cardcvv,
      'cardname' => $session_paymentinfo->cardname
    );

    return $a;
  }

  // constructor
  function paymentInfo() {
    $this->sessionStart();
  }

  // start a session if not started yet
  // retrieve stored session data for paymentinfo if it exists
  // otherwise start a new session variable
  function sessionStart() {
    global $session_paymentinfo;

    session_start();

    if(isset($_SESSION['session_paymentinfo']))
    {
      $session_paymentinfo = $_SESSION['session_paymentinfo'];
    }
    else
    {
      $session_paymentinfo = $this;

      //store in SESSION variables
      $_SESSION['session_paymentinfo'] = $session_paymentinfo;
    }
  }

  /*
   * Add card info to session
   */
  function addCardInfo($cardtype, $cardnumber, $cardexpmonth, $cardexpyear, $cardcvv, $cardname) {
    global $session_paymentinfo;

    $session_paymentinfo->cardtype = $cardtype;
    $session_paymentinfo->cardnumber = $cardnumber;
    $session_paymentinfo->cardexpmonth = $cardexpmonth;
    $session_paymentinfo->cardexpyear = $cardexpyear;
    $session_paymentinfo->cardcvv = $cardcvv;
    $session_paymentinfo->cardname = $cardname;

    //store in SESSION variables
    $_SESSION['session_paymentinfo'] = $session_paymentinfo;
  }

  /*
   * Did the form already collect payment data?
   */
  function hasCardInfo() {
    global $session_paymentinfo;

    // Check if required payment field is filled
    return strlen($session_paymentinfo->cardnumber) == 16;
  }

  /*
   * Generate a HTML form for the payment.php page
   */
  function toFormHTML() {
    global $session_paymentinfo;
    global $cardtypes;
    global $months;
    global $years;

    // For security hide all but last 4 digits of card number if it exists in session
    $cardnumberlastfour = $session_paymentinfo->cardnumber ? ("************" . substr($session_paymentinfo->cardnumber, 12, 4)) : "";

    // Begin to print out payment form
    // Submit event will validate inputs using validatePaymentInfo JS function
    echo <<<FORM
    <div id="main">
      <form method="POST" action="finalOrder.php" name="paymentinfo" onsubmit="return validatePaymentInfo();">
        <div id="paymentinfo">
          <div class="heading">Payment Information</div>
          <div class="otherformitem">We accept Visa/MasterCard/Discover/AMEX</div>
          <br/>
          <div class="formitem">
            <label class="inlinelabel" for="cardtype">*Card Type:</label>
            <select name="cardtype">

FORM;

    // List all card type options in dropdown
    foreach ($cardtypes as $k => $v) {
      if ($session_paymentinfo->cardtype == $k) {
        echo "              <option value=\"$k\" selected>$v</option>\n";
      }
      else {
        echo "              <option value=\"$k\">$v</option>\n";
      }
    }

    echo <<<FORM
            </select>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="cardnumber">*Card Number:</label>
            <input type="text" name="cardnumber" id="cardnumber" maxlength="16" value="{$cardnumberlastfour}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="cardexpmonth">*Card Expiration:</label>
            <select name="cardexpmonth">

FORM;

    // List all month options in dropdown
    foreach ($months as $k => $v) {
      if ($session_paymentinfo->cardexpmonth == $k) {
        echo "              <option value=\"$k\" selected>$v</option>\n";
      }
      else {
        echo "              <option value=\"$k\">$v</option>\n";
      }
    }

    echo <<<FORM
            </select>
            <select name="cardexpyear">

FORM;

    // List all year options in dropdown
    foreach ($years as $k => $v) {
      if ($session_paymentinfo->cardexpyear == $k) {
        echo "              <option value=\"$k\" selected>$v</option>\n";
      }
      else {
        echo "              <option value=\"$k\">$v</option>\n";
      }
    }

    // Print the final form fields and submit button
    echo <<<FORM
            </select>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="cardcvv">*CVV:</label>
            <input type="text" name="cardcvv" id="cardcvv" maxlength="4" size="4" value="{$session_paymentinfo->cardcvv}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="cardname">*Name on Card:</label>
            <input type="text" name="cardname" id="cardname" value="{$session_paymentinfo->cardname}"/>
          </div>
          <br/>
          <div class="otherformitem">
            <i>(*) indicates a Required Field</i>
          </div>
          <br/>
          <div class="otherformitem">
            <input type="submit" id="continuebtn" value="Continue"/>
          </div>
        </div>
      </form>
    </div>

FORM;
  }

  /*
   * Print data to html without form (for finalOrder page)
   */
  function toStaticHTML() {
    global $months;
    global $session_paymentinfo;

    // For security hide all but last 4 digits of card number if it exists in session
    $cardnumberlastfour = substr($session_paymentinfo->cardnumber, 12, 4);

    echo <<<DATA
    <div id="main">
      <div id="paymentinfo">
        <div class="heading">Payment Information</div>
        <div class="formitem"><span class="inlinelabel">Card Type: </span>{$session_paymentinfo->cardtype}</div>
        <div class="formitem"><span class="inlinelabel">Card Number: </span>************{$cardnumberlastfour}</div>
        <div class="formitem"><span class="inlinelabel">Card Expiration: </span>{$months[$session_paymentinfo->cardexpmonth]} {$session_paymentinfo->cardexpyear}</div>
        <div class="formitem"><span class="inlinelabel">Card CVV: </span>{$session_paymentinfo->cardcvv}</div>
        <div class="formitem"><span class="inlinelabel">Card Name: </span>{$session_paymentinfo->cardname}</div>
      </div>
    </div>

DATA;
  }
}
?>