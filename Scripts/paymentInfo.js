// helper function for below functions
// takes a DOM element that represents a form input being validated and some text for the error message
function doError(el, txt) {
  alert(txt); // popup error with message text passed in
  el.focus(); // set focus on the errant element
}

// validation function called when the payment form is submitted
// verifies all required inputs are correctly formatted
// returns false if there is a validation error
function validatePaymentInfo() {
  // Card number must be 16 digits long (no other letters or characters)
  if (document.paymentinfo.cardnumber.value.length != 16 ||
      isNaN(document.paymentinfo.cardnumber.value)) {
    doError(document.paymentinfo.cardnumber, "Card Number must be 16 digits");
    return false;
  }

  // Expiration date cannot be in the past (form only allows current or later year so only have to check if month in past if current year set)
  var date = new Date(); // Create a date to get current date
  if (parseInt(document.paymentinfo.cardexpmonth.value) < date.getMonth()+1 && parseInt(document.paymentinfo.cardexpyear.value) == date.getFullYear()) { // If current year set make sure month is not in past
    doError(document.paymentinfo.cardexpmonth, "Card Expiration month is before current month");
    return false;
  }

  // Card CVV must be 3 digits for Visa/MC/Discover (no other letters or characters)
  if ((document.paymentinfo.cardcvv.value.length != 3 && document.paymentinfo.cardtype.value != "AMEX") || // First check if not AMEX and CVV is not 3 characters
      (document.paymentinfo.cardcvv.value.length == 3 && isNaN(document.paymentinfo.cardcvv.value))) { // Then check if 3 digits (not other characters)
    doError(document.paymentinfo.cardcvv, "Card CVV for " + document.paymentinfo.cardtype.value + " must be 3 digits");
    return false;
  }

  // Card CVV must be 4 digits for AMEX (no other letters or characters)
  if ((document.paymentinfo.cardcvv.value.length != 4 && document.paymentinfo.cardtype.value == "AMEX") || // First check if AMEX but CVV is not 4 characters
      (document.paymentinfo.cardcvv.value.length == 4 && isNaN(document.paymentinfo.cardcvv.value))) { // Then check if 4 digits (not other characters)
      doError(document.paymentinfo.cardcvv, "Card CVV for AMEX must be 4 digits");
      return false;
  }

  // Card Name cannot be empty
  if (document.paymentinfo.cardname.value.length == 0) {
    doError(document.paymentinfo.cardname, "Name on Card cannot be empty");
    return false;
  }

  return true;
}