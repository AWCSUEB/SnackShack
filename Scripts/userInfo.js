// this function shows/hides the billing info form area depending on if the user selects the checkbox to make billing info same as shipping info
function toggleBillingInfo(checkbox) {
  if (checkbox.checked) { // If checked that means hide the billing part of the form
    document.getElementById("billing").style.visibility = "hidden";
  }
  else { // If checked that means show the billing part of the form
    document.getElementById("billing").style.visibility = "visible";
  }
}

// helper function for below functions
// takes a DOM element that represents a form input being validated and some text for the error message
function doError(el, txt) {
    alert(txt); // popup error with message text passed in
    el.focus(); // set focus on the errant element
}

// common validation function to validate if a string field has at least one character
// returns false if in error
function validateNotEmpty(el, typestr, fieldstr) {
  if (el.value.length == 0) { // Error if field is empty
    doError(el, typestr + " Information: " + fieldstr + " is missing");
    return false;
  }

  return true;
}

// common validation function to validate a zip is 5 digits
// takes an HTML DOM object representing the zip form field, and a string indicating if its for shipping or billing
// returns false if in error
function validateZip(el, typestr) {
  if (el.value.length != 5 || // Check if 5 character zip code
      isNaN(el.value) || // Check if form value is a number
      parseInt(el.value) < 0) { // Check if the number is negative
    doError(el, typestr + " Information: 5 digit Zip Code is invalid or missing");
    return false;
  }

  return true;
}

// common validation function to validate a email is at least of the form a@b.c
// takes an HTML DOM object representing the zip form field, and a string indicating if its for shipping or billing
// returns false if in error
function validateEmail(el, typestr) {
  if (!el.value.includes("@") || // Must include @ symbol
      !el.value.includes(".") || // Must include . symbol
      el.value.length < 5) { // Must be minimum of 5 characters
    doError(el, typestr + " Information: Email is missing or invalid");
    return false;
  }

  return true;
}

// common validation function to validate a phone number has 10 digits with or without dashes
// takes an HTML DOM object representing the zip form field, and a string indicating if its for shipping or billing
// returns false if in error
function validatePhone(el, typestr) {
  var phonenodash = el.value.split("-").join(""); // If phone number like ###-###-#### remove the dashes
  if (phonenodash.length != 10 || // phone number must be 10 characters
      isNaN(phonenodash)) { // phone number must be all digits
    doError(el, typestr + " Information: 10 digit phone number (###-###-####) required");
    return false;
  }

  return true;
}

// validation function called when the userinfo form is submitted
// verifies all required inputs are correctly formatted
// returns false if there is a validation error
function validateUserInfo() {
  var shippingstr = "Shipping"; // this variable just used to pass in the string Shipping to validation function

  // First name required
  if (!validateNotEmpty(document.userinfo.shipfirstname, shippingstr, "First Name"))
    return false;

  // Last name required
  if (!validateNotEmpty(document.userinfo.shiplastname, shippingstr, "Last Name"))
    return false;

  // First address field required (2nd field optional)
  if (!validateNotEmpty(document.userinfo.shipaddress1, shippingstr, "Address 1"))
    return false;

  // City required
  if (!validateNotEmpty(document.userinfo.shipcity, shippingstr, "City"))
    return false;

  // Zip required and must be 5 digits
  if (!validateZip(document.userinfo.shipzip, shippingstr))
    return false;

  // Email required and must be at least like a@b.c (5 characters)
  if (!validateEmail(document.userinfo.shipemail, shippingstr))
    return false;

  // Phone without dashes must be 10 digits
  if (!validatePhone(document.userinfo.shipphone, shippingstr))
    return false;

  // If billing different than shipping, validate billing info
  if (!document.userinfo.billingsameasshippinginfo.checked) {
    var billingstr = "Billing"; // this variable just used to pass in the string Billing to validation function

    // First name required
    if (!validateNotEmpty(document.userinfo.billfirstname, billingstr, "First Name"))
        return false;

    // Last name required
    if (!validateNotEmpty(document.userinfo.billlastname, billingstr, "Last Name"))
        return false;

    // First address field required (2nd field optional)
    if (!validateNotEmpty(document.userinfo.billaddress1, billingstr, "Address 1"))
        return false;

    // City required
    if (!validateNotEmpty(document.userinfo.billcity, billingstr, "City"))
        return false;

    // Zip required and must be 5 digits
    if (!validateZip(document.userinfo.billzip, billingstr))
        return false;

    // Email required and must be at least like a@b.c (5 characters)
    if (!validateEmail(document.userinfo.billemail, billingstr))
        return false;

    // Phone without dashes must be 10 digits
    if (!validatePhone(document.userinfo.billphone, billingstr))
        return false;
  }

  return true;
}