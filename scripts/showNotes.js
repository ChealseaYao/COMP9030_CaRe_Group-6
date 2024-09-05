document.addEventListener("DOMContentLoaded", function() {
    let notesData = {
        '2024-07-20': [
            'Patient discussed concerns about managing stress at work.',
            'Follow-up session: Patient reported better sleep patterns.',
            'Patient started a new exercise routine as recommended.',
            'Patient discussed concerns about managing stress at work.',
            'Follow-up session: Patient reported better sleep patterns.',
            'Patient started a new exercise routine as recommended.',
            'Patient discussed concerns about managing stress at work.',
            'Follow-up session: Patient reported better sleep patterns.',
            'Patient started a new exercise routine as recommended.',
            'Patient discussed concerns about managing stress at work.',
            'Follow-up session: Patient reported better sleep patterns.',
            'Patient started a new exercise routine as recommended.'
        ],
        '2024-07-30': [
            'Patient reported feeling anxious in social settings.'
        ],
        '2024-08-01': [
            'Patient reported improvement in sleep patterns.'
        ],
        '2024-08-03': [
            'Initial session: Established treatment goals. Patient expressed concerns about managing work-related stress.'
        ],
        '2024-08-05': [
            'Patient reported a decrease in anxiety after practicing mindfulness exercises.'
        ],
        '2024-08-06': [
            'Discussed progress with cognitive behavioral therapy. Patient is noticing gradual improvement in mood.'
        ],
        '2024-08-07': [
            'Patient reported feeling anxious in social settings. Suggested practicing deep breathing exercises.'
        ],
        '2024-08-10': [
            'Patient discussed challenges with balancing work and personal life.'
        ],
        '2024-08-12': [
            'Patient expressed concern over persistent sleep issues despite improvement in other areas.'
        ],
        '2024-08-14': [
            'Follow-up: Reviewed progress with new sleep strategies. Patient reports some improvement.'
        ],
        '2024-08-17': [
            'Patient is noticing improvement in overall mood, but still struggling with work-related stress.'
        ],
        '2024-08-19': [
            'Discussed new techniques for managing stress at work.'
        ],
        '2024-08-21': [
            'Patient reported a significant improvement in mood after starting a new hobby.'
        ],
        '2024-08-23': [
            'Patient reported occasional anxiety attacks, but with less frequency and intensity.'
        ],
        '2024-08-25': [
            'Patient discussed the importance of maintaining a work-life balance.'
        ],
        '2024-08-27': [
            'Follow-up session: Patient reported better sleep patterns and improved mood.'
        ],
        '2024-08-29': [
            'Patient started a new diet plan and reported positive effects on overall energy levels.'
        ],
        '2024-08-31': [
            'Patient reported feeling overwhelmed with work but is managing stress better with recent techniques.'
        ],
        '2024-09-01': [
            'Initial session: Patient expressed concerns about managing work-related stress and sleep issues.'
        ],
        '2024-09-03': [
            'Follow-up: Patient reports significant improvement in managing stress, but still struggles with sleep.'
        ]
    };

    // Initialize and display the latest three contents
    function displayLatestNotes() {
        const tableBody = document.querySelector(".note-history-note tbody");
        tableBody.innerHTML = '';

        // Get the latest three records, sorted by date
        const latestNotes = Object.keys(notesData)
            .sort((a, b) => new Date(b) - new Date(a)) 
            .slice(0, 3); 

        
        latestNotes.forEach(date => {
            notesData[date].forEach(note => {
                const newRowHTML = `
                    <tr data-date="${date}">
                        <td>${date}</td>
                        <td>${note}</td>
                        <td><button class="delete-button">Delete</button></td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', newRowHTML);
            });
        });

       
        bindDeleteButtons();
    }

    // Bind the event of the date picker confirmation button
    const confirmBtn = document.getElementById('confirm-btn');
    confirmBtn.addEventListener('click', function() {
       
        const year = document.getElementById('year').value;
        const month = document.getElementById('month').value.padStart(2, '0');
        const day = document.getElementById('day').value.padStart(2, '0'); 

        
        const selectedDate = `${year}-${month}-${day}`;
        console.log("Selected Date:", selectedDate);

        
        const noteContentArray = notesData[selectedDate];

        if (noteContentArray) {
            const tableBody = document.querySelector(".note-history-note tbody");

            
            tableBody.innerHTML = '';

            
            noteContentArray.forEach(noteContent => {
                const newRowHTML = `
                    <tr data-date="${selectedDate}">
                        <td>${selectedDate}</td>
                        <td>${noteContent}</td>
                        <td><button class="delete-button">Delete</button></td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', newRowHTML);
            });

            
            bindDeleteButtons();
        } else {
            alert("No notes found for the selected date.");
        }

        
        const calendarPopup = document.querySelector('.calendar-popup');
        calendarPopup.style.display = 'none';
    });

    // 
    // function bindDeleteButtons() {
    //     const deleteButtons = document.querySelectorAll(".delete-button");
    //     deleteButtons.forEach(button => {
    //         button.addEventListener('click', function(event) {
    //             const row = event.target.closest('tr');
    //             if (row) {
    //                 const date = row.getAttribute('data-date');
    //                 const note = row.querySelector('td:nth-child(2)').textContent;
                    
    //                 // 
    //                 notesData[date] = notesData[date].filter(n => n !== note);
    //                 if (notesData[date].length === 0) {
    //                     delete notesData[date];
    //                 }
                    
    //                 // 
    //                 row.remove();
    //             }
    //         });
    //     });
    // 

    // Function to bind the delete button event (after modification, a modal box pops up)
    function bindDeleteButtons() {
        const deleteButtons = document.querySelectorAll(".delete-button");
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                currentRow = event.target.closest('tr'); 
                currentDate = currentRow.getAttribute('data-date'); 
                currentNote = currentRow.querySelector('td:nth-child(2)').textContent; 

                
                const deleteModal = document.getElementById('note-deleteModal');
                deleteModal.style.display = 'block'; 
            });
        });
    }

    // Bind the cancel and confirm button events of the modal box
    const cancelBtn = document.getElementById('note-cancelBtn');
    const deConfirmBtn = document.getElementById('note-confirmBtn');
    
    // Hide the modal when the cancel button is clicked
    cancelBtn.addEventListener('click', function() {
        const deleteModal = document.getElementById('note-deleteModal');
        deleteModal.style.display = 'none'; 
    });

    
    deConfirmBtn.addEventListener('click', function() {
       
        notesData[currentDate] = notesData[currentDate].filter(note => note !== currentNote);
        if (notesData[currentDate].length === 0) {
            delete notesData[currentDate]; // If all notes for the current date are deleted, delete that date
        }

        
        if (currentRow) {
            currentRow.remove();
        }

        
        const deleteModal = document.getElementById('note-deleteModal');
        deleteModal.style.display = 'none';
    });

    // Process the save button click event and display a modal box indicating successful saving
    const saveBtn = document.getElementById('note-save');
    const saveModal = document.getElementById('saveModal');
    const saveConfirmBtn = document.getElementById('saveConfirmBtn');

    saveBtn.addEventListener('click', function() {
        // 1. Get the new note content entered by the user
        const newNoteContent = document.getElementById('new-note').value.trim(); // Get the text box content and remove extra spaces

        if (newNoteContent === '') {
            alert("Please enter a note."); 
            return;
        }

        // 2. Get the current date in the format of 'yyyy-MM-dd'
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0]; 

        // 3. Add a new note to the notesData data structure
        if (!notesData[formattedDate]) {
            notesData[formattedDate] = []; 
        }
        notesData[formattedDate].push(newNoteContent); 

        // 4. Dynamically update the page to show newly added notes
        const tableBody = document.querySelector(".note-history-note tbody");
        const newRowHTML = `
            <tr data-date="${formattedDate}">
                <td>${formattedDate}</td>
                <td>${newNoteContent}</td>
                <td><button class="delete-button">Delete</button></td>
            </tr>`;
        tableBody.insertAdjacentHTML('beforeend', newRowHTML); 

        // 5. Rebind the delete button event to ensure that the newly added delete button also has functionality
        bindDeleteButtons();

        // 6. Displays a modal box showing successful saving
        document.getElementById('new-note').value = '';

        // 7. Displays a modal box showing successful saving
        saveModal.style.display = 'block';
    });

    
    saveConfirmBtn.addEventListener('click', function() {
        saveModal.style.display = 'none'; 
    });



    // Initialize and display the latest three items
    displayLatestNotes();
});


