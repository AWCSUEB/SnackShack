<?php
/*
 * finalOrder page to review all data entered before submission
 * if payment data posted to page save it to session
 */
require "Classes/basketClass.php";
require "Classes/userInfoClass.php";
require "Classes/paymentInfoClass.php";
require "Classes/finalOrderClass.php";

// Create objects for all 4 classes used (basket, userinfo, paymentinfo, finalorder)
// The basket, userinfo, and paymentinfo classes will retrieve their saved data from the session
$basket = new basket();
$userInfo = new userInfo();
$paymentInfo = new paymentInfo();
$finalOrder = new finalOrder($basket, $userInfo, $paymentInfo); // finalOrder class will call the toHTML of the other 3 classes passed in

?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
<?php
  if (isset($_POST["ordersubmitted"])) { // If there is an ordersubmitted form variable that means the order was submitted already so we should display the confirmation
    echo "  <title>Final Order Confirmation</title>";
  }
  else { // No ordernumber form variable means we are displaying the finalorder summary
    echo "  <title>Final Order Summary</title>";
  }
?>
  <link type="text/css" rel="stylesheet" href="Styles/nav.css">
  <link type="text/css" rel="stylesheet" href="Styles/cart.css">
  <link type="text/css" rel="stylesheet" href="Styles/userinfo.css">
  <link type="text/css" rel="stylesheet" href="Styles/paymentinfo.css">
  <link type="text/css" rel="stylesheet" href="Styles/finalorder.css">
</head>
<body>
<div id="nav">
  <img src="Images/strawhut.jpg" id="navimage"/>
  <span id="navtitle">The Snack Shack</span>
  <span class="navlink" onclick="location.href='index.html'">Home</span>
  <span class="navlink" onclick="location.href='pages.html'">Product Pages</span>
  <span class="navlink" onclick="location.href='cart.php'">Cart</span>
</div>
<?php
  if (isset($_POST["ordersubmitted"])) { // If there is an ordersubmitted form variable that means the order was submitted already so we should display the confirmation page
    $status = $finalOrder->toStaticHTML(); // Generate the static order summary information HTML
    if ($status == 200)
      $finalOrder->sessionEnd(); // Clear the session when the order is successfully finished
  }
  else if ($basket->basketSize() > 0 &&
           $userInfo->hasShippingInfo() &&
           $paymentInfo->hasCardInfo()) { // No ordernumber form variable means we are displaying the finalorder summary (but also have to check if the session variables were correctly loaded)
    $finalOrder->toFormHTML();// Generate the final order summary HTML form
  } else { // Tell user to checkout from shopping cart again if there is missing session data (in case page is navigated to prematurely)
    echo "<div style='font-size: 2em; font-family: Arial, Helvetica, sans-serif'>Error! Checkout process incomplete. Please checkout from shopping cart.</div>";
  }
?>
</body>
</html>