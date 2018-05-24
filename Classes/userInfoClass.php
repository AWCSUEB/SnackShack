<?php

// Data to populate US state dropdownlist
$statearray = array(
  'AL'=>'Alabama',
  'AK'=>'Alaska',
  'AZ'=>'Arizona',
  'AR'=>'Arkansas',
  'CA'=>'California',
  'CO'=>'Colorado',
  'CT'=>'Connecticut',
  'DE'=>'Delaware',
  'DC'=>'District of Columbia',
  'FL'=>'Florida',
  'GA'=>'Georgia',
  'HI'=>'Hawaii',
  'ID'=>'Idaho',
  'IL'=>'Illinois',
  'IN'=>'Indiana',
  'IA'=>'Iowa',
  'KS'=>'Kansas',
  'KY'=>'Kentucky',
  'LA'=>'Louisiana',
  'ME'=>'Maine',
  'MD'=>'Maryland',
  'MA'=>'Massachusetts',
  'MI'=>'Michigan',
  'MN'=>'Minnesota',
  'MS'=>'Mississippi',
  'MO'=>'Missouri',
  'MT'=>'Montana',
  'NE'=>'Nebraska',
  'NV'=>'Nevada',
  'NH'=>'New Hampshire',
  'NJ'=>'New Jersey',
  'NM'=>'New Mexico',
  'NY'=>'New York',
  'NC'=>'North Carolina',
  'ND'=>'North Dakota',
  'OH'=>'Ohio',
  'OK'=>'Oklahoma',
  'OR'=>'Oregon',
  'PA'=>'Pennsylvania',
  'RI'=>'Rhode Island',
  'SC'=>'South Carolina',
  'SD'=>'South Dakota',
  'TN'=>'Tennessee',
  'TX'=>'Texas',
  'UT'=>'Utah',
  'VT'=>'Vermont',
  'VA'=>'Virginia',
  'WA'=>'Washington',
  'WV'=>'West Virginia',
  'WI'=>'Wisconsin',
  'WY'=>'Wyoming',
);

/*
 * The userInfo class helps to store user information in the current session
 * It also helps to render the forms for entering the user information on the userinfo.php page
 * as well as displaying a static view of the user info on the finalOrder.php page
 */
class userInfo {
  // Shipping info class variables
  var $shipfirstname; // firstname (required)
  var $shiplastname; // lastname (required)
  var $shipaddress1; // address1 (required)
  var $shipaddress2; // address2 (optional)
  var $shipcity; // city (required)
  var $shipstate; // state (required)
  var $shipzip; // zip (required)
  var $shipemail; // email address (required)
  var $shipphone; // phone # (required)
  var $sameasbillinginfo; // did user want to use same info for shipping and billing? (optional)

  // Billing info class variables
  var $billfirstname; // firstname (required)
  var $billlastname; // lastname (required)
  var $billaddress1; // address1 (required)
  var $billaddress2; // address2 (optional)
  var $billcity; // city (required)
  var $billstate; // state (required)
  var $billzip; // zip (required)
  var $billemail; // email address (required)
  var $billphone; // phone # (required)

  /*
   * Generate an array of billing and shipping data that will represent customers and shipping documents
   */
  function toArray() {
    global $session_userinfo;
    
    $a = array(
      'shipinfo' => array (
        'shipfirstname' => $session_userinfo->shipfirstname,
        'shiplastname' => $session_userinfo->shiplastname,
        'shipaddress1' => $session_userinfo->shipaddress1,
        'shipaddress2' => $session_userinfo->shipaddress2,
        'shipcity' => $session_userinfo->shipcity,
        'shipstate' => $session_userinfo->shipstate,
        'shipzip' => $session_userinfo->shipzip,
        'shipemail' => $session_userinfo->shipemail,
        'shipphone' => $session_userinfo->shipphone
      ),
      'billinfo' => array (
        'billfirstname' => $session_userinfo->billfirstname,
        'billlastname' => $session_userinfo->billlastname,
        'billaddress1' => $session_userinfo->billaddress1,
        'billaddress2' => $session_userinfo->billaddress2,
        'billcity' => $session_userinfo->billcity,
        'billstate' => $session_userinfo->billstate,
        'billzip' => $session_userinfo->billzip,
        'billemail' => $session_userinfo->billemail,
        'billphone' => $session_userinfo->billphone
      )
    );
    
    return $a;
  }

  // constructor
  function userInfo() {
    $this->sessionStart();
  }

  /*
   * Start up session if not yet started
   * Get previous session variable for userinfo loaded if exists
   * Otherwise start new session variable for userinfo
   */
  function sessionStart() {
    global $session_userinfo;

    session_start();

    if(isset($_SESSION['session_userinfo']))
    {
      $session_userinfo = $_SESSION['session_userinfo'];
    }
    else
    {
      $session_userinfo = $this;

      //store in SESSION variables
      $_SESSION['session_userinfo'] = $this;
    }
  }

  /*
   * If user provided shipping info call this function to set the session variable with the values input
   */
  function addShippingInfo($shipfirstname, $shiplastname, $shipaddress1, $shipaddress2, $shipcity, $shipstate, $shipzip, $shipemail, $shipphone) {
    global $session_userinfo;

    $session_userinfo->shipfirstname = $shipfirstname;
    $session_userinfo->shiplastname = $shiplastname;
    $session_userinfo->shipaddress1 = $shipaddress1;
    $session_userinfo->shipaddress2 = $shipaddress2;
    $session_userinfo->shipcity = $shipcity;
    $session_userinfo->shipstate = $shipstate;
    $session_userinfo->shipzip = $shipzip;
    $session_userinfo->shipemail = $shipemail;
    $session_userinfo->shipphone = $shipphone;

    //store in SESSION variables
    $_SESSION['session_userinfo'] = $session_userinfo;
  }

  /*
   * If user provided billing info call this function to set the session variable with the values input
   */
  function addBillingInfo($billfirstname, $billlastname, $billaddress1, $billaddress2, $billcity, $billstate, $billzip, $billemail, $billphone, $billingsameasshippinginfo) {
    global $session_userinfo;

    $session_userinfo->billfirstname = $billfirstname;
    $session_userinfo->billlastname = $billlastname;
    $session_userinfo->billaddress1 = $billaddress1;
    $session_userinfo->billaddress2 = $billaddress2;
    $session_userinfo->billcity = $billcity;
    $session_userinfo->billstate = $billstate;
    $session_userinfo->billzip = $billzip;
    $session_userinfo->billemail = $billemail;
    $session_userinfo->billphone = $billphone;
    $session_userinfo->billingsameasshippinginfo = $billingsameasshippinginfo;

    //store in SESSION variables
    $_SESSION['session_userinfo'] = $session_userinfo;
  }

  /*
   * Was user information already collected?
   */
  function hasShippingInfo() {
    global $session_userinfo;

    // Pick any required shipping info field to verify form has data
    return strlen($session_userinfo->shiplastname) > 0;
  }

  // Display a HTML form for inputting shipping and billing info
  function toFormHTML() {
    global $session_userinfo;
    global $statearray; // Access the predeclared US state array for our dropdown

    // Each shipping form element will have a label on the left and an input text or select field on the right
    echo <<<FORM
    <div id="userinfo">
      <form method="POST" action="payment.php" name="userinfo" onsubmit="return validateUserInfo();">
        <div id="shipping">
          <div class="heading">Shipping Information</div>
          <div class="formitem">
            <label class="inlinelabel" for="shipfirstname">*First Name:</label>
            <input type="text" name="shipfirstname" id="shipfirstname" value="{$session_userinfo->shipfirstname}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="shiplastname">*Last Name:</label>
            <input type="text" name="shiplastname" id="shiplastname" value="{$session_userinfo->shiplastname}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="shipaddress1">*Address Line 1:</label>
            <input type="text" name="shipaddress1" id="shipaddress1" value="{$session_userinfo->shipaddress1}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="shipaddress2">Address Line 2:</label>
            <input type="text" name="shipaddress2" id="shipaddress2" value="{$session_userinfo->shipaddress2}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="shipcity">*City:</label>
            <input type="text" name="shipcity" id="shipcity" value="{$session_userinfo->shipcity}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="shipstate">*State:</label>
            <select name="shipstate" id="shipstate">

FORM;

    // Fill the select element with the different states, selecting the option already set in session if applicable
    foreach ($statearray as $k => $v)
    {
      if ($session_userinfo->shipstate == $k) {
        echo "              <option value=\"$k\" selected>$v</option>\n";
      }
      else {
        echo "              <option value=\"$k\">$v</option>\n";
      }
    }

    // Continue displaying remaining form elements for shipping info
    echo <<<FORM
            </select>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="shipzip">*Zip:</label>
            <input type="text" name="shipzip" id="shipzip" value="{$session_userinfo->shipzip}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="shipemail">*Email:</label>
            <input type="text" name="shipemail" id="shipemail" value="{$session_userinfo->shipemail}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="shipphone">*Phone:</label>
            <input type="text" name="shipphone" id="shipphone" value="{$session_userinfo->shipphone}"/>
          </div>
          <br/>
          <div class="otherformitem">
            <i>(*) indicates a Required Field</i>
          </div>
          </br/>
          <div class="otherformitem">
            <label for="billingsameasshippinginfo">Use Shipping Information for Billing?</label>

FORM;

    // Show checkbox for asking user to copy shipping info for billing info
    // This will toggle visibility of the billing info form section
    if ($session_userinfo->billingsameasshippinginfo) {
      echo "            <input type=\"checkbox\" name=\"billingsameasshippinginfo\" id=\"billingsameasshippinginfo\" onchange=\"toggleBillingInfo(this)\" checked/>\n";
    }
    else {
      echo "            <input type=\"checkbox\" name=\"billingsameasshippinginfo\" id=\"billingsameasshippinginfo\" onchange=\"toggleBillingInfo(this)\"/>\n";
    }

    // Display remaining shipping info details
    // Then display the billing form fields
    echo <<<FORM
          </div>
          <br/>
          <div class="otherformitem">
            Standard Shipping: $2.00
          </div>
          <br/>
          <br/>
          <div class="otherformitem">
            <input type="submit" id="continuebtn" value="Continue"/>
          </div>
        </div>
        <div id="billing" style="visibility: hidden;">
          <div class="heading">Billing Information</div>
          <div class="formitem">
            <label class="inlinelabel" for="billfirstname">*First Name:</label>
            <input type="text" name="billfirstname" id="billfirstname" value="{$session_userinfo->billfirstname}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="billlastname">*Last Name:</label>
            <input type="text" name="billlastname" id="billlastname" value="{$session_userinfo->billlastname}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="billaddress1">*Address Line 1:</label>
            <input type="text" name="billaddress1" id="billddress1" value="{$session_userinfo->billaddress1}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="billaddress2">Address Line 2:</label>
            <input type="text" name="billaddress2" id="billaddress2" value="{$session_userinfo->billaddress2}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="billcity">*City:</label>
            <input type="text" name="billcity" id="billcity" value="{$session_userinfo->billcity}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="billstate">*State:</label>
            <select name="billstate" id="billstate">

FORM;
    // add each state to the billing state dropdown, selecting the option currently selected in the session if applicable
    foreach ($statearray as $k => $v)
    {
      if ($session_userinfo->billstate == $k) {
        echo "              <option value=\"$k\" selected>$v</option>\n";
      }
      else {
        echo "              <option value=\"$k\">$v</option>\n";
      }
    }

    // Display the remaining billing form elements
    echo <<<FORM
            </select>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="billzip">*Zip:</label>
            <input type="text" name="billzip" id="billzip" value="{$session_userinfo->billzip}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="billemail">*Email:</label>
            <input type="text" name="billemail" id="billemail" value="{$session_userinfo->billemail}"/>
          </div>
          <div class="formitem">
            <label class="inlinelabel" for="billphone">*Phone:</label>
            <input type="text" name="billphone" id="billphone" value="{$session_userinfo->billphone}"/>
          </div>
        </div>
      </form>
    </div>

FORM;
  }

  /*
   * This function will display a static list of all userinfo data already collected in the session for display on the finalOrder.php page
   */
  function toStaticHTML() {
    global $session_userinfo;

    // Print all shipping info on the left and all billing info on the right
    echo <<<FORM
    <div id="userinfo">
      <div id="shipping">
        <div class="heading">Shipping Information</div>
        <div class="formitem"><span class="inlinelabel">First Name: </span>{$session_userinfo->shipfirstname}</div>
        <div class="formitem"><span class="inlinelabel">Last Name: </span>{$session_userinfo->shiplastname}</div>
        <div class="formitem"><span class="inlinelabel">Address Line 1: </span>{$session_userinfo->shipaddress1}</div>
        <div class="formitem"><span class="inlinelabel">Address Line 2: </span>{$session_userinfo->shipaddress2}</div>
        <div class="formitem"><span class="inlinelabel">City: </span>{$session_userinfo->shipcity}</div>
        <div class="formitem"><span class="inlinelabel">State: </span>{$session_userinfo->shipstate}</div>
        <div class="formitem"><span class="inlinelabel">Zip: </span>{$session_userinfo->shipzip}</div>
        <div class="formitem"><span class="inlinelabel">Email: </span>{$session_userinfo->shipemail}</div>
        <div class="formitem"><span class="inlinelabel">Phone: </span>{$session_userinfo->shipphone}</div>
      </div>
      <div id="billing">
        <div class="heading">Billing Information</div>
        <div class="formitem"><span class="inlinelabel">First Name: </span>{$session_userinfo->billfirstname}</div>
        <div class="formitem"><span class="inlinelabel">Last Name: </span>{$session_userinfo->billlastname}</div>
        <div class="formitem"><span class="inlinelabel">Address Line 1: </span>{$session_userinfo->billaddress1}</div>
        <div class="formitem"><span class="inlinelabel">Address Line 2: </span>{$session_userinfo->billaddress2}</div>
        <div class="formitem"><span class="inlinelabel">City: </span>{$session_userinfo->billcity}</div>
        <div class="formitem"><span class="inlinelabel">State: </span>{$session_userinfo->billstate}</div>
        <div class="formitem"><span class="inlinelabel">Zip: </span>{$session_userinfo->billzip}</div>
        <div class="formitem"><span class="inlinelabel">Email: </span>{$session_userinfo->billemail}</div>
        <div class="formitem"><span class="inlinelabel">Phone: </span>{$session_userinfo->billphone}</div>
      </div>
    </div>
FORM;
  }
}
?>