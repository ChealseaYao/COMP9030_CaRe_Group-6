// drag&dropMember.js

document.addEventListener("DOMContentLoaded", function () {
  // Enable dragging for patient items
  const patients = document.querySelectorAll(".patient-item");

  // Enable dragging for the patient icon
  patients.forEach((patient) => {
    const icon = patient.querySelector(".patient-icon");
    if (icon) {
      icon.setAttribute("draggable", true); // Make icon draggable

      // Add dragstart event to patient icons
      icon.addEventListener("dragstart", function (e) {
        e.dataTransfer.setData(
          "text/plain",
          patient.getAttribute("data-user-id")
        );
        console.log(
          `Dragging patient with user ID: ${patient.getAttribute(
            "data-user-id"
          )}`
        );
      });
    }
  });

  // Allow dropping on the members container of the currently selected group
  const membersContainer = document.getElementById("membersContainer");

  // Handle dragover (allow drop)
  membersContainer.addEventListener("dragover", function (e) {
    e.preventDefault(); // Necessary to allow the drop event
  });

  // Handle drop event
  membersContainer.addEventListener("drop", function (e) {
    e.preventDefault();

    // Get the dragged patient's user ID
    const userId = e.dataTransfer.getData("text/plain");
    console.log(`Dropped patient with user ID: ${userId}`);

    // Get the current group ID
    const selectedGroup = document.querySelector(".group-item.selected");
    const groupId = selectedGroup
      ? selectedGroup.getAttribute("data-group-id")
      : null;

    if (groupId && userId) {
      // Add the dropped patient to the members list visually
      addPatientToGroup(userId, groupId);
    } else {
      console.error("Group or patient ID not found.");
    }
  });

  // Function to visually add the patient to the group member list and send an AJAX request
  function addPatientToGroup(userId, groupId) {
    // Use AJAX to add the patient to the group in the database
    fetch("../therapist/patientListPage.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ group_id: groupId, user_id: userId }), // Send group_id and user_id to the server
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log("Patient added to group successfully");
          // Update the UI by adding the patient to the members list
          updateMembersListUI(data.patient_name); // Assuming the server responds with the patient name
        } else {
          console.error("Failed to add patient to group:", data.error);
        }
      })
      .catch((error) => console.error("Error adding patient to group:", error));
  }

  // Function to update the UI by adding the patient to the members list
  function updateMembersListUI(patientName) {
    const memberItem = document.createElement("div");
    memberItem.classList.add("member-item");
    memberItem.textContent = patientName;

    // Add delete icon
    const deleteIcon = document.createElement("span");
    deleteIcon.classList.add("delete-icon");
    deleteIcon.textContent = "🗑️";
    memberItem.appendChild(deleteIcon);

    // Append the new member to the members container
    membersContainer.appendChild(memberItem);

    // Re-attach delete listeners (if needed)
    attachDeleteListeners();
  }
});
