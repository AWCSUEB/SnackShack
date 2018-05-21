<?php
/*
 * userInfo Form to collect shipping and billing info
 * cart data should already be in session
 * billing info form will be hidden depending on the state of checkbox
 */
  require "Classes/basketClass.php";
  require "Classes/userInfoClass.php";

  // Create the basket and userinfo objects to retrieve session data
  $basket = new basket();
  $userInfo = new userInfo();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>User Information</title>
    <link type="text/css" rel="stylesheet" href="Styles/nav.css"/>
    <link type="text/css" rel="stylesheet" href="Styles/userinfo.css"/>
    <script type="text/javascript" src="Scripts/userInfo.js"></script>
  </head>
  <body onload="toggleBillingInfo(document.getElementById('billingsameasshippinginfo'));">
    <div id="nav">
      <img src="Images/strawhut.jpg" id="navimage"/>
      <span id="navtitle">The Snack Shack</span>
      <span class="navlink" onclick="location.href='index.html'">Home</span>
      <span class="navlink" onclick="location.href='pages.html'">Product Pages</span>
      <span class="navlink" onclick="location.href='cart.php'">Cart</span>
    </div>
<?php
  if ($basket->basketSize() > 0) { // Only gather info if something is in basket
    $userInfo->toFormHTML(); // Display the userInfo HTML form
   } else { // Tell user to checkout from shopping cart again if there is missing session data (in case page is navigated to prematurely)
    echo "<div style='font-size: 2em; font-family: Arial, Helvetica, sans-serif'>Error! Checkout process incomplete. Please checkout from shopping cart.</div>";
  }
?>
  </body>
</html>