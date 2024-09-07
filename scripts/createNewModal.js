// modal.js

// Get the modal
var modal = document.getElementById("createGroupModal");
// Get the button that opens the modal
var btn = document.querySelector(".create-new");
// Get the cancel button
var cancelButton = document.getElementById("cancelButton");
// Get the confirm button
var confirmButton = document.getElementById("confirmButton");
// Get the input field for the group name
var groupNameInput = document.getElementById("groupName");
// Get the group container where the new group will be added
var groupContainer = document.getElementById("groupContainer");

// When the user clicks the button, open the modal
btn.onclick = function () {
  modal.style.display = "flex";
};

// When the user clicks on cancel button, close the modal
cancelButton.onclick = function () {
  modal.style.display = "none";
  groupNameInput.value = "";
};

// Optional: When the user clicks on confirm button, do something and close the modal
confirmButton.onclick = function () {
  var newGroupName = groupNameInput.value.trim(); // Get and trim the input value

  // Validate the input to make sure it's not empty
  if (newGroupName) {
    // Create a new div element for the group
    var newGroupItem = document.createElement("div");
    newGroupItem.classList.add("group-item"); // Add the appropriate class
    newGroupItem.textContent = newGroupName; // Set the text to the new group name

    // Append the new group item to the group container
    groupContainer.appendChild(newGroupItem);

    // Close the modal and clear the input field
    modal.style.display = "none";
    groupNameInput.value = ""; // Clear input after adding
  } else {
    alert("Please enter a group name."); // Alert if the input is empty
  }
};

// Close the modal when clicking outside of the modal content
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
    groupNameInput.value = ""; 
  }
};
