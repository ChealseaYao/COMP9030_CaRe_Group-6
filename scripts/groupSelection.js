// groupSelection.js
// Select all group items
var groupItems = document.querySelectorAll(".group-item");

var groupNameDisplay = document.getElementById("currentGroupName");

// Add click event listener to each group item
groupItems.forEach(function (item) {
  item.addEventListener("click", function () {
    // Remove 'selected' class from all group items
    groupItems.forEach(function (group) {
      group.classList.remove("selected");
    });

    // Add 'selected' class to the clicked group item
    item.classList.add("selected");

    groupNameDisplay.textContent = item.textContent;
  });
});

