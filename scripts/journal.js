/*---Star bage---Start*/
var starIcon = document.getElementById("starIcon");

starIcon.addEventListener("click", function() {
  if (starIcon.textContent === "☆") {
    starIcon.textContent = "★"; // Change to full star
  } else {
    starIcon.textContent = "☆"; // Change back to empty star
  }
});
/*---Star bage---End*/

/*---Popup---Start*/
// Show the popup window
function showPopup() {
  document.getElementById('popupOverlay').style.display = 'block';
  document.getElementById('popup').style.display = 'block';
}

// Hide the popup window
function hidePopup() {
  document.getElementById('popupOverlay').style.display = 'none';
  document.getElementById('popup').style.display = 'none';
}

// Execute the delete request and process the response
function confirmDelete() {
  const journal_id = new URLSearchParams(window.location.search).get('journal_id'); // Get the journal_id of URL
  if (!journal_id) {
    alert("No journal selected for deletion.");
    return;
  }

  // Use AJAX send delete request
  const xhr = new XMLHttpRequest();
  xhr.open("POST", `journal.php?journal_id=${journal_id}`, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // Handle response when request completes
  xhr.onload = function () {
    console.log("Server response:", xhr.responseText); // Output server response
    if (xhr.status === 200) {
      try {
        const response = JSON.parse(xhr.responseText);
        if (response.status === 'success') {
          alert(response.message);
          window.location.href = "viewHistoryRecord.php"; 
        } else {
          alert("Failed to delete journal: " + response.message);
        }
      } catch (error) {
        console.error("Failed to parse JSON response:", error);
        alert("Failed to delete journal due to a response parsing error.");
      }
    } else {
      alert("An error occurred while trying to delete the journal.");
    }
  };

  // Pass delete request parameters
  xhr.send("delete_journal=true");

  // Hide pop-up window
  hidePopup();
}
/*---Popup---End*/


