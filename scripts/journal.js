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
function showPopup() {
    document.getElementById('popupOverlay').style.display = 'block';
    document.getElementById('popup').style.display = 'block';
}

function hidePopup() {
    document.getElementById('popupOverlay').style.display = 'none';
    document.getElementById('popup').style.display = 'none';
}

function confirmDelete() {
    hidePopup();
    alert('Journal deleted!');
}
/*---Popup---End*/


