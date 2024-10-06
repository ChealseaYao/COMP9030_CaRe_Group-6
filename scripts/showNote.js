document.addEventListener("DOMContentLoaded", function() {

    let currentRow = null;
    let currentDate = null;
    let currentNote = null;
    let currentNoteId = null; // Declare currentNoteId

    // notesData and patientName are already available from the embedded PHP
    console.log("Fetched notes data:", notesData);
    console.log("Patient ID:", patient_id);


    // Populate the patient name in the input field
    const patientNameInput = document.getElementById('patient-name');
    patientNameInput.value = patientName;

    // Function to convert date from 'yyyy-MM-dd' to 'dd/MM/yyyy'
    function formatDate(dateString) {
        const dateParts = dateString.split('-');
        return `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
    }

    // Initialize and display the latest three notes
    function displayLatestNotes() {
        const tableBody = document.querySelector(".note-history-note tbody");
        tableBody.innerHTML = '';

        // Get the latest three records sorted by date
        const latestNotes = Object.keys(notesData)
            .sort((a, b) => new Date(b) - new Date(a)) 
            .slice(0, 3); 

        latestNotes.forEach(date => {
            notesData[date].forEach(note => {
                const newRowHTML = `
                    <tr data-date="${date}" data-note-id="${note.note_id}">
                        <td>${formatDate(date)}</td>
                        <td>${note.note_content}</td>
                        <td><button class="delete-button">Delete</button></td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', newRowHTML);
            });
        });

        bindDeleteButtons();
    }

    // Bind the event for the date picker confirmation button
    const confirmBtn = document.getElementById('confirm-btn');
    confirmBtn.addEventListener('click', function() {
        // Get the selected year, month, and day, and pad the values
        const year = document.getElementById('year').value;
        const month = document.getElementById('month').value.padStart(2, '0');
        const day = document.getElementById('day').value.padStart(2, '0'); 

        // Construct the date string
        const selectedDate = `${year}-${month}-${day}`;
        console.log("Selected Date:", selectedDate);

        // Retrieve notes for the selected date
        const noteContentArray = notesData[selectedDate];

        if (noteContentArray) {
            const tableBody = document.querySelector(".note-history-note tbody");

            // Clear previous notes
            tableBody.innerHTML = '';

            // Display notes for the selected date
            noteContentArray.forEach(noteContent => {
                const newRowHTML = `
                    <tr data-date="${selectedDate}">
                        <td>${formatDate(selectedDate)}</td>
                        <td>${noteContent.note_content}</td>
                        <td><button class="delete-button">Delete</button></td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', newRowHTML);
            });

            bindDeleteButtons();
        } else {
            alert("No notes found for the selected date.");
        }

        // Hide the date picker popup
        const calendarPopup = document.querySelector('.calendar-popup');
        calendarPopup.style.display = 'none';
    });

     // Bind delete button event (after modification, a modal box pops up)
     function bindDeleteButtons() {
        const deleteButtons = document.querySelectorAll(".delete-button");
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                currentRow = event.target.closest('tr'); 
                currentDate = currentRow.getAttribute('data-date'); 
                currentNote = currentRow.querySelector('td:nth-child(2)').textContent; 
                currentNoteId = currentRow.getAttribute('data-note-id'); // Get the note_id from the row's data attribute

                const deleteModal = document.getElementById('note-deleteModal');
                deleteModal.style.display = 'block'; 
            });
        });
    }

    // Bind the cancel and confirm buttons in the modal
    const cancelBtn = document.getElementById('note-cancelBtn');
    const deConfirmBtn = document.getElementById('note-confirmBtn');
    
    // Hide modal when cancel button is clicked
    cancelBtn.addEventListener('click', function() {
        const deleteModal = document.getElementById('note-deleteModal');
        deleteModal.style.display = 'none'; 
    });

    // Delete note when confirm button is clicked
    deConfirmBtn.addEventListener('click', async function() {
        if (!currentNoteId || !patient_id) {
            alert("Missing data to perform delete operation.");
            return;
        }

        const response = await fetch('../therapist/deleteNote.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `note_id=${currentNoteId}&patient_id=${patient_id}`
        });

        const result = await response.json();
        if (result.success) {
            notesData[currentDate] = notesData[currentDate].filter(noteObj => noteObj.note_id !== currentNoteId);
            if (notesData[currentDate].length === 0) {
                delete notesData[currentDate];
            }
            currentRow.remove();
        } else {
            alert("Failed to delete note.");
        }

        const deleteModal = document.getElementById('note-deleteModal');
        deleteModal.style.display = 'none';
    });


     // Handle save button click event and show success modal
     const saveBtn = document.getElementById('note-save');
     const saveModal = document.getElementById('saveModal');
     const saveConfirmBtn = document.getElementById('saveConfirmBtn');
 
     saveBtn.addEventListener('click', async function() {
        const newNoteContent = document.getElementById('new-note').value.trim(); 
        if (newNoteContent === '') {
            alert("Please enter a note."); 
            return;
        }

        const response = await fetch('../therapist/addNote.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `note_content=${encodeURIComponent(newNoteContent)}&patient_id=${patient_id}`
        });

        const result = await response.json();
        if (result.success) {
            const formattedDate = new Date().toISOString().split('T')[0];
            if (!notesData[formattedDate]) {
                notesData[formattedDate] = [];
            }
            notesData[formattedDate].push({ note_id: result.note_id, note_content: newNoteContent });
            displayLatestNotes();
            document.getElementById('new-note').value = '';
            saveModal.style.display = 'block';
        } else {
            alert("Failed to add note.");
        }
    });
 
     // Close the success modal when confirm button is clicked
     saveConfirmBtn.addEventListener('click', function() {
         saveModal.style.display = 'none'; 
     });

    // Initialize and display the notes once the page has loaded
    displayLatestNotes();
});
