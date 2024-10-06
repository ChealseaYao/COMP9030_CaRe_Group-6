// memberDeletion.js

// Get the modal
var deleteModal = document.getElementById("confirmDeleteModal");

// Check if the modal and buttons exist before accessing them
if (deleteModal) {
  // Get the cancel and confirm buttons in the modal
  var cancelDeleteButton = document.getElementById("cancelDeleteButton");
  var confirmDeleteButton = document.getElementById("confirmDeleteButton");

  // Variable to keep track of which member to delete
  var memberToDelete = null;
  var groupIdToDeleteFrom = null; // Keep track of the group ID

  // Attach event listeners to the delete icons
  function attachDeleteListeners() {
    var deleteButtons = document.querySelectorAll(".delete-icon");

    // When the user clicks the bin button, open the modal
    deleteButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        // Store the member element to delete
        memberToDelete = button.closest(".member-item");
        // Get the group ID from the selected group (this assumes groupId is stored as a data attribute)
        groupIdToDeleteFrom = document
          .querySelector(".group-item.selected")
          .getAttribute("data-group-id");

        if (memberToDelete && groupIdToDeleteFrom) {
          deleteModal.style.display = "flex"; // Open the modal
        } else {
          console.error("Member item or group ID not found.");
        }
      });
    });
  }

  // Make sure the cancel button exists
  if (cancelDeleteButton) {
    // When the user clicks on cancel button, close the modal
    cancelDeleteButton.onclick = function () {
      deleteModal.style.display = "none";
      memberToDelete = null; // Reset the reference
      groupIdToDeleteFrom = null;
    };
  } else {
    console.error("Cancel button not found in the DOM.");
  }

  // Make sure the confirm button exists
  if (confirmDeleteButton) {
    // When the user clicks on confirm button, delete the member and close the modal
    confirmDeleteButton.onclick = function () {
      if (memberToDelete && groupIdToDeleteFrom) {
        // Get the member's name to send for deletion
        var memberName = memberToDelete.textContent.replace("🗑️", "").trim();

        // Send AJAX request to delete the member from the group
        deleteGroupMember(groupIdToDeleteFrom, memberName);

        // Remove the member from the UI
        memberToDelete.remove();
        memberToDelete = null; // Reset the reference
        groupIdToDeleteFrom = null;
      }
      deleteModal.style.display = "none";
    };
  } else {
    console.error("Confirm button not found in the DOM.");
  }

  // Close the modal when clicking outside of the modal content
  window.onclick = function (event) {
    if (event.target === deleteModal) {
      deleteModal.style.display = "none";
      memberToDelete = null; // Reset the reference
      groupIdToDeleteFrom = null;
    }
  };
} else {
  console.error("Delete confirmation modal not found in the DOM.");
}

// Function to send an AJAX request to delete a group member
function deleteGroupMember(groupId, memberName) {
  fetch("../therapist/patientListPage.php", {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ group_id: groupId, member_name: memberName }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        console.log("Member deleted successfully");
      } else {
        console.error("Failed to delete member:", data.error);
      }
    })
    .catch((error) => console.error("Error deleting member:", error));
}
