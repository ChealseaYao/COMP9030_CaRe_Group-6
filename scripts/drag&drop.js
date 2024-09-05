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
});
