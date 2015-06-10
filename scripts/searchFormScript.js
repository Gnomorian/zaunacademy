/*
  validation function for the search form to be linked on all pages it apears
  on as to make sure user edited fields arefilled correctly.
*/
function validateForm() {
  // get the currently selected region id and add it to the hidden input variable
  var currentField = document.getElementById("selectRegion");
  var region = currentField.options[currentField.selectedIndex].value;
  document.getElementsByName('region')[0].value = region;

  //check if the user has input a summoner name
  var username = document.getElementsByName('summoner')[0].value
  if(username === "" || username === null) {
    alert("You must type a summoner name!");
    return false;
  }
  // get rid of caps so the api doesnt error out
  document.getElementsByName('summoner')[0].value = document.getElementsByName('summoner')[0].value.toLowerCase();
  return true;
}
