// memberDeletion.js

// Get the modal
var deleteModal = document.getElementById("confirmDeleteModal");

// Get the cancel and confirm buttons in the modal
var cancelDeleteButton = document.getElementById("cancelDeleteButton");
var confirmDeleteButton = document.getElementById("confirmDeleteButton");

// Variable to keep track of which member to delete
var memberToDelete = null;


function attachDeleteListeners() {

  var deleteButtons = document.querySelectorAll(".delete-icon");
// When the user clicks the bin button, open the modal
deleteButtons.forEach(function (button) {
  button.addEventListener("click", function () {
    // Store the member element to delete
    memberToDelete = button.closest(".member-item");
    deleteModal.style.display = "flex";
  });
});
}

// When the user clicks on cancel button, close the modal
cancelDeleteButton.onclick = function () {
  deleteModal.style.display = "none";
};

// When the user clicks on confirm button, delete the member and close the modal
confirmDeleteButton.onclick = function () {
  if (memberToDelete) {
    memberToDelete.remove(); // Remove the member item
    memberToDelete = null; // Reset the reference
  }
  deleteModal.style.display = "none";
};

// Close the modal when clicking outside of the modal content
window.onclick = function (event) {
  if (event.target == deleteModal) {
    deleteModal.style.display = "none";
  }
};
