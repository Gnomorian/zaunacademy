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
  return true;
}
