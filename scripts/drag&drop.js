document.addEventListener("DOMContentLoaded", function () {
  // Allow dropping
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

      // Get the user ID from the data attribute
      const userId = patient.getAttribute("data-user-id");

      // Send the updated status to the server via AJAX
      updatePatientStatus(userId, status);
    });
  });

  // Function to send the updated status to the server
  function updatePatientStatus(userId, status) {
    // Use Fetch API to send an AJAX request to the server
    fetch("updatePatientStatus.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ user_id: userId, status: status }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log("Patient status updated successfully");
        } else {
          console.error("Failed to update patient status");
        }
      })
      .catch((error) => console.error("Error:", error));
  }
});
