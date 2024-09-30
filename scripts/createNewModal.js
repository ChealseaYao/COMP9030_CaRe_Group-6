// createNewModal.js

// Get the modal
var modal = document.getElementById("createGroupModal");

// Check if modal and other elements exist before accessing them
if (modal) {
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

  // Check if the button to open the modal exists
  if (btn) {
    // When the user clicks the button, open the modal
    btn.onclick = function () {
      modal.style.display = "flex";
    };
  } else {
    console.error("Create New button not found in the DOM.");
  }

  // Check if the cancel button exists
  if (cancelButton) {
    // When the user clicks on cancel button, close the modal and clear the input field
    cancelButton.onclick = function () {
      modal.style.display = "none";
      groupNameInput.value = ""; // Clear input field
    };
  } else {
    console.error("Cancel button not found in the DOM.");
  }

  // Check if the confirm button exists
  if (confirmButton) {
    // When the user clicks on confirm button, validate and add the new group
    confirmButton.onclick = function () {
      var newGroupName = groupNameInput.value.trim(); // Get and trim the input value

      // Validate the input to make sure it's not empty
      if (newGroupName) {
        // Call the function to add a new group to the group container
        addNewGroup(newGroupName);

        // Close the modal and clear the input field
        modal.style.display = "none";
        groupNameInput.value = ""; // Clear input after adding
      } else {
        alert("Please enter a group name."); // Alert if the input is empty
      }
    };
  } else {
    console.error("Confirm button not found in the DOM.");
  }

  // Close the modal when clicking outside of the modal content
  window.onclick = function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
      groupNameInput.value = ""; // Clear input field
    }
  };
} else {
  console.error("Create Group modal not found in the DOM.");
}

// Function to add a new group dynamically to the group container
function addNewGroup(groupName) {
  if (groupContainer) {
    // Create a new div element for the group
    var newGroupItem = document.createElement("div");
    newGroupItem.classList.add("group-item");
    newGroupItem.textContent = groupName;

    // Append the new group item to the group container
    groupContainer.appendChild(newGroupItem);

    // Attach a click event listener to the newly created group item
    newGroupItem.addEventListener("click", function () {
      handleGroupClick(newGroupItem);
    });
  } else {
    console.error("Group container not found in the DOM.");
  }
}
