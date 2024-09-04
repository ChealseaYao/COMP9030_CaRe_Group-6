// modal.js

// Get the modal
var modal = document.getElementById("createGroupModal");
// Get the button that opens the modal
var btn = document.querySelector(".create-new");
// Get the cancel button
var cancelButton = document.getElementById("cancelButton");
// Get the confirm button
var confirmButton = document.getElementById("confirmButton");

// When the user clicks the button, open the modal
btn.onclick = function () {
  modal.style.display = "flex";
};

// When the user clicks on cancel button, close the modal
cancelButton.onclick = function () {
  modal.style.display = "none";
};

// Optional: When the user clicks on confirm button, do something and close the modal
confirmButton.onclick = function () {
  // Do something with the group name (e.g., add it to the list)
  modal.style.display = "none";
};

// Close the modal when clicking outside of the modal content
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
