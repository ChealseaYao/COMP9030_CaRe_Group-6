document.addEventListener("DOMContentLoaded", function () {
  console.log("DOMContentLoaded event fired");

  // Allow dropping
  function allowDrop(event) {
    event.preventDefault(); // Necessary to allow the drop event
  }

  // Enable dragging of status badges
  const badges = document.querySelectorAll(".badge-item");
  console.log("Badges found:", badges.length); // Log number of badges
  badges.forEach((badge) => {
    badge.addEventListener("dragstart", function (e) {
      const status = e.target.getAttribute("data-status");
      console.log("Dragging status:", status);
      e.dataTransfer.setData("status", status);
    });
  });

  // Allow dropping on patients
  const patients = document.querySelectorAll(".patient-item");
  console.log("Patients found:", patients.length); // Log number of patients
  patients.forEach((patient) => {
    patient.addEventListener("dragover", allowDrop);

    patient.addEventListener("drop", function (e) {
      e.preventDefault();
      const status = e.dataTransfer.getData("status");
      console.log("Dropped status:", status);

      // Remove any existing status
      const statusContainer = patient.querySelector(".status-container");
      statusContainer.innerHTML = ""; // Clear existing status

      // Add the new status span
      const span = document.createElement("span");
      span.classList.add("status", status);
      statusContainer.appendChild(span);
      console.log("Updated status span:", span.className);

      // Get the user ID from the data attribute
      const userId = patient.getAttribute("data-user-id");
      console.log("Updating patient ID:", userId, "with status:", status);

      // Map the drag-and-drop value to the correct database status value
      let dbStatus;
      if (status === "good") {
        dbStatus = "good status";
      } else if (status === "bad") {
        dbStatus = "bad status";
      } else if (status === "danger") {
        dbStatus = "danger status";
      } else {
        console.error("Unknown status:", status);
        return;
      }

      // Send the updated status to the server via AJAX
      updatePatientStatus(userId, dbStatus);
    });
  });

  // Function to send the updated status to the server
  function updatePatientStatus(userId, status) {
    console.log(
      "Sending AJAX request with user ID:",
      userId,
      "and status:",
      status
    );

    // Use Fetch API to send an AJAX request to the server
    fetch("../therapist/patientListPage.php", {
      // Adjust the path if necessary
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ user_id: userId, status: status }),
    })
      .then((response) => {
        console.log("Response status:", response.status);
        if (!response.ok) {
          console.error("Failed to send request:", response.statusText);
          return null;
        }
        return response.json();
      })
      .then((data) => {
        if (data && data.success) {
          console.log("Patient status updated successfully:", data);
        } else if (data && data.error) {
          console.error("Server Error:", data.error);
        } else {
          console.error("Unexpected response:", data);
        }
      })
      .catch((error) => console.error("Error:", error));
  }
});
