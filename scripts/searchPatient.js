document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.querySelector("input[name='search']");
  const patientListContainer = document.querySelector(".tableContainer");

  // Listen to the input event on the search bar
  searchInput.addEventListener("input", function (e) {
    const query = e.target.value.trim();

    if (query.length > 0) {
      // Send the search query to the server via AJAX
      searchPatients(query);
    } else {
      // If the search bar is empty, fetch all patients
      fetchAllPatients();
    }
  });

  // Function to search patients by name
  function searchPatients(query) {
    fetch("../therapist/patientListPage.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ action: "search_patient", search_query: query }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.patients) {
          renderPatientList(data.patients);
        } else {
          console.error("No patients found.");
          patientListContainer.innerHTML = "<p>No patients found.</p>";
        }
      })
      .catch((error) => console.error("Error searching patients:", error));
  }

  // Function to render the patient list
  function renderPatientList(patients) {
    // Clear the patient list container
    patientListContainer.innerHTML = "";

    // Loop through each patient and append it to the patient list container
    patients.forEach((patient) => {
      const patientItem = document.createElement("div");
      patientItem.classList.add("patient-item");
      patientItem.setAttribute("data-user-id", patient.user_id);

      patientItem.innerHTML = `
          <div class="left-section">
            <div class="patient-icon" draggable="true">☰</div>
            <div>
              <strong>${patient.full_name}</strong><br>
              Age: ${patient.age}
            </div>
          </div>
          <div class="right-section">
            <div class="status-container">
              <span class="status ${getStatusClass(patient.badge)}"></span>
            </div>
            <a href="patientDetail.html"><button class="details">Details</button></a>
          </div>
        `;

      // Append the patient item to the container
      patientListContainer.appendChild(patientItem);
    });
  }

  // Helper function to get the appropriate status class
  function getStatusClass(badge) {
    if (badge === "good status") return "good";
    if (badge === "bad status") return "bad";
    if (badge === "danger status") return "danger";
    return "";
  }

  // Function to fetch all patients
  function fetchAllPatients() {
    fetch("../therapist/patientListPage.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ action: "fetch_all_patients" }), // Fetch all patients
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.patients) {
          renderPatientList(data.patients);
        } else {
          console.error("No patients found.");
          patientListContainer.innerHTML = "<p>No patients found.</p>";
        }
      })
      .catch((error) => console.error("Error fetching patients:", error));
  }

  // Fetch all patients on initial load
  fetchAllPatients();
});
