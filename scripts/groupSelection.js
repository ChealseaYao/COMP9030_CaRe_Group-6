// groupSelection.js
// Define group members
var groupMembers = {
  "Tuesday 3pm Session": ["John Smith", "Jane Doe", "Mark Johnson"],
  "Friday Special": ["Lucy Liu", "Tom Hanks", "Will Smith"],
  "Anxiety Group": ["Emma Watson", "Chris Evans", "Robert Downey Jr."],
  "Avengers": ["Iron Man", "Captain America", "Thor"],
  "Revengers": ["Loki", "Valkyrie", "Hulk"],
  "Justice League": ["Superman", "Wonder Woman", "Batman"]
};

// Select all group items
var groupItems = document.querySelectorAll(".group-item");

// Select the <p> element that displays the current group name
var groupNameDisplay = document.getElementById("currentGroupName");

// Select the container where members will be displayed
var membersContainer = document.getElementById("membersContainer");

// Add click event listener to each group item
groupItems.forEach(function (item) {
  item.addEventListener("click", function () {
    // Remove 'selected' class from all group items
    groupItems.forEach(function (group) {
      group.classList.remove("selected");
    });

    // Add 'selected' class to the clicked group item
    item.classList.add("selected");

    // Update the content of the groupNameDisplay <p> with the clicked group's name
    groupNameDisplay.textContent = item.textContent;

    // Get the group name
    var selectedGroup = item.textContent;

    // Update the members list for the selected group
    updateMembersList(selectedGroup);
  });
});

// Function to update members list based on the selected group
function updateMembersList(group) {
  // Clear the existing members
  membersContainer.innerHTML = '';

  // Get the members for the selected group from the groupMembers object
  var members = groupMembers[group] || [];

  // Create and append each member to the members container
  members.forEach(function (member) {
    var memberItem = document.createElement('div');
    memberItem.classList.add('member-item');
    memberItem.textContent = member;

    // delete icon 
    var deleteIcon = document.createElement('span');
    deleteIcon.classList.add('delete-icon');
    deleteIcon.textContent = '🗑️';
    memberItem.appendChild(deleteIcon);

    membersContainer.appendChild(memberItem);
  });
  attachDeleteListeners();
}
