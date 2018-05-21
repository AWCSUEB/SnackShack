<?php
/*
 * payment form to collect credit card information
 * if userinfo form data posted to page it should be added to session
 * cart data should already be in session
 */
require "Classes/userInfoClass.php";
require "Classes/paymentInfoClass.php";

// Create necessary class objects to retrieve/save session data
$userInfo = new userInfo();
$paymentInfo = new paymentInfo();

// Check for a field from the userInfo form in the POST to be sure the data is available for saving
if (isset($_POST["shipemail"])) {
  $userInfo->addShippingInfo( // Save Shipping info - this will always be same regardless of sameasbillinginfo
    $_POST["shipfirstname"],
    $_POST["shiplastname"],
    $_POST["shipaddress1"],
    $_POST["shipaddress2"],
    $_POST["shipcity"],
    $_POST["shipstate"],
    $_POST["shipzip"],
    $_POST["shipemail"],
    $_POST["shipphone"]
  );
  if (isset($_POST["billingsameasshippinginfo"])) { // Billing data will be same as shipping data so save shipping data into the billing data fields
    $userInfo->addBillingInfo(
      $_POST["shipfirstname"],
      $_POST["shiplastname"],
      $_POST["shipaddress1"],
      $_POST["shipaddress2"],
      $_POST["shipcity"],
      $_POST["shipstate"],
      $_POST["shipzip"],
      $_POST["shipemail"],
      $_POST["shipphone"],
      true
    );
  } else { // Billing data is different from shipping data so save separate billing info
    $userInfo->addBillingInfo(
      $_POST["billfirstname"],
      $_POST["billlastname"],
      $_POST["billaddress1"],
      $_POST["billaddress2"],
      $_POST["billcity"],
      $_POST["billstate"],
      $_POST["billzip"],
      $_POST["billemail"],
      $_POST["billphone"],
      false
    );
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <title>Payment Information</title>
  <link type="text/css" rel="stylesheet" href="Styles/nav.css"/>
  <link type="text/css" rel="stylesheet" href="Styles/paymentinfo.css"/>
  <script type="text/javascript" src="Scripts/paymentInfo.js"></script>
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
  if ($userInfo->hasShippingInfo()) { // Verify session has saved userinfo data
    $paymentInfo->toFormHTML(); // Display payment form
  } else { // Tell user to checkout from shopping cart again if there is missing session data (in case page is navigated to prematurely)
    echo "<div style='font-size: 2em; font-family: Arial, Helvetica, sans-serif'>Error! Checkout process incomplete. Please checkout from shopping cart.</div>";
  }
?>
</body>
</html>