// groupSelection.js

// Select all group items
var groupItems = document.querySelectorAll(".group-item");

// Select the <p> element that displays the current group name
var groupNameDisplay = document.getElementById("currentGroupName");

// Select the container where members will be displayed
var membersContainer = document.getElementById("membersContainer");

function handleGroupClick(item) {
  // Remove 'selected' class from all group items
  var allGroups = document.querySelectorAll(".group-item");
  allGroups.forEach(function (group) {
    group.classList.remove("selected");
  });

  // Add 'selected' class to the clicked group item
  item.classList.add("selected");

  // Update the content of the groupNameDisplay <p> with the clicked group's name
  groupNameDisplay.textContent = item.textContent;

  // Get the group ID from a data attribute (you can set this dynamically in PHP)
  var groupId = item.getAttribute("data-group-id");

  // Send an AJAX request to fetch members for the selected group
  fetchGroupMembers(groupId);
}

// Add click event listener to each existing group item
groupItems.forEach(function (item) {
  item.addEventListener("click", function () {
    handleGroupClick(item);
  });
});

// Function to send an AJAX request to fetch group members
function fetchGroupMembers(groupId) {
  // Clear the existing members
  membersContainer.innerHTML = "";

  // Use Fetch API to send an AJAX request
  fetch("../therapist/patientListPage.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ group_id: groupId }),
  })
    .then((response) => response.json())
    .then((data) => {
      // Update the members list with the fetched members
      updateMembersList(data.members);
    })
    .catch((error) => console.error("Error fetching group members:", error));
}

// Function to update members list based on the fetched data
function updateMembersList(members) {
  // Clear the existing members
  membersContainer.innerHTML = "";

  // Loop through each member and add them to the members container
  members.forEach(function (member) {
    var memberItem = document.createElement("div");
    memberItem.classList.add("member-item");
    memberItem.textContent = member.name; // Use the name from the AJAX response

    // delete icon
    var deleteIcon = document.createElement("span");
    deleteIcon.classList.add("delete-icon");
    deleteIcon.textContent = "🗑️";
    memberItem.appendChild(deleteIcon);

    membersContainer.appendChild(memberItem);
  });
  attachDeleteListeners();
}

// groupSelection.js

function attachDeleteListeners() {
  var deleteButtons = document.querySelectorAll(".delete-icon");

  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      var memberItem = button.parentElement;
      var memberName = memberItem.textContent.replace("🗑️", "").trim(); // Get the member's name

      // Get the group ID from the currently selected group
      var groupId = document
        .querySelector(".group-item.selected")
        .getAttribute("data-group-id");

      // Send an AJAX request to delete the member from the group
      deleteGroupMember(groupId, memberName, memberItem);
    });
  });
}

// Function to send an AJAX request to delete a group member
function deleteGroupMember(groupId, memberName, memberItem) {
  fetch("../therapist/patientListPage.php", {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ group_id: groupId, member_name: memberName }), // Send group_id and member_name
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Remove the member from the UI
        memberItem.remove();
        console.log("Member deleted successfully");
      } else {
        console.error("Failed to delete member:", data.error);
      }
    })
    .catch((error) => console.error("Error deleting member:", error));
}
