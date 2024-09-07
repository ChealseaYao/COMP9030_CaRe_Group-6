document.addEventListener("DOMContentLoaded", function () {
  // allow dropping
  function allowDrop(event) {
    event.preventDefault(); // Necessary to allow the drop event
  }
  // Enable dragging of status badges
  const badges = document.querySelectorAll(".badge-item");
  badges.forEach((badge) => {
    badge.addEventListener("dragstart", function (e) {
      e.dataTransfer.setData("status", e.target.getAttribute("data-status"));
    });
  });
  // Allow dropping on patients
  const patients = document.querySelectorAll(".patient-item");
  patients.forEach((patient) => {
    patient.addEventListener("dragover", allowDrop);

    patient.addEventListener("drop", function (e) {
      e.preventDefault();
      const status = e.dataTransfer.getData("status");

      // Remove any existing status
      const statusContainer = patient.querySelector(".status-container");
      statusContainer.innerHTML = ""; // Clear existing status

      // Add the new status span
      const span = document.createElement("span");
      span.classList.add("status", status);
      statusContainer.appendChild(span);
    });
  });

  // Enable dragging of patient items
  const patientItems = document.querySelectorAll(".patient-item");
  patientItems.forEach((patient) => {
    patient.setAttribute("draggable", true); // Make patient items draggable

    patient.addEventListener("dragstart", function (e) {
      // Set the patient name (assuming it's inside a <strong> tag)
      const patientName = patient.querySelector("strong").textContent;
      e.dataTransfer.setData("text", patientName);
    });
  });

  // Enable dropping on the members container
  const membersContainer = document.getElementById("membersContainer");
  membersContainer.addEventListener("dragover", allowDrop);

  membersContainer.addEventListener("drop", function (e) {
    e.preventDefault();
    const patientName = e.dataTransfer.getData("text");

    // Check if the patient is already a member
    const existingMembers = [...membersContainer.querySelectorAll(".member-item")];
    const isAlreadyMember = existingMembers.some(member => member.textContent.includes(patientName));

    if (!isAlreadyMember) {
      // Create a new member item for the dropped patient
      const memberItem = document.createElement("div");
      memberItem.classList.add("member-item");
      memberItem.textContent = patientName;

      // Add delete icon
      const deleteIcon = document.createElement('span');
      deleteIcon.classList.add('delete-icon');
      deleteIcon.textContent = '🗑️';
      deleteIcon.style.cursor = "pointer";
      memberItem.appendChild(deleteIcon);

      // Append the new member item to the members container
      membersContainer.appendChild(memberItem);
      attachDeleteListeners();
    }
  });

});
