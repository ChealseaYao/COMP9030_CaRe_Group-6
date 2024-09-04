// submitModal.js

// Get the modal
var submitModal = document.getElementById("submitJournalModal");
// Get the cancel and confirm buttons in the modal
var cancelSubmitButton = document.getElementById("cancelSubmitButton");
var confirmSubmitButton = document.getElementById("confirmSubmitButton");

// Function to open the submit confirmation modal
function openSubmitModal() {
  submitModal.style.display = "flex";
}

// When the user clicks on cancel button, close the modal
cancelSubmitButton.onclick = function () {
  submitModal.style.display = "none";
};

// When the user clicks on confirm button, redirect to the success page
confirmSubmitButton.onclick = function () {
  window.location.href = "./successfullySubmitPage.html"; // Redirect to the success page
};

// Close the modal when clicking outside of the modal content
window.onclick = function (event) {
  if (event.target == submitModal) {
    submitModal.style.display = "none";
  }
};
