document.addEventListener("DOMContentLoaded", function() {
    // Default journal data
    const journalData = {
        '2024-09-03': { content: 'Today I felt more in control of my emotions. I managed to calm myself down when I started to feel anxious.' },
        '2024-09-02': { content: 'Discussed my progress in therapy today. Feeling hopeful about learning new coping mechanisms.' },
        '2024-09-01': { content: 'Had a great day today. I was able to go outside and walk around without feeling overwhelmed.' },
        '2024-08-31': { content: 'Started my new routine with mindfulness exercises in the morning. It helped me stay calm throughout the day.' },
        '2024-08-30': { content: 'Feeling overwhelmed by work today. Struggled to focus and felt a lot of tension in my body.' },
        '2024-08-29': { content: 'I managed to talk to my boss about reducing some of my workload. It was hard, but I feel relieved.' },
        '2024-08-28': { content: 'Had a good session today. My therapist suggested a new breathing technique, which I will try this week.' },
        '2024-08-27': { content: 'Today was hard. I felt like I was losing control again. Couldn’t stop the racing thoughts.' },
        '2024-08-26': { content: 'I went grocery shopping today without feeling anxious. This was a big step for me.' },
        '2024-08-25': { content: 'Had another sleepless night. I kept replaying past conversations in my head.' },
        '2024-08-24': { content: 'Practiced yoga in the morning. It really helped clear my mind for the day.' },
        '2024-08-23': { content: 'Anxiety levels were really high today. I couldn’t concentrate on my work and had to take a break.' },
        '2024-08-22': { content: 'Had a long talk with a friend today. It was nice to feel understood and not judged.' },
        '2024-08-21': { content: 'Another difficult day. I kept overthinking everything, especially past mistakes.' },
        '2024-08-20': { content: 'Had a peaceful day. I stayed at home and watched a movie to relax.' },
        '2024-08-19': { content: 'Felt really down today. I kept questioning my worth and couldn’t shake off the negativity.' },
        '2024-08-18': { content: 'Therapy session went well today. I feel like I’m making progress, slowly but surely.' },
        '2024-08-17': { content: 'Had a panic attack while driving. It came out of nowhere, and I had to pull over.' },
        '2024-08-16': { content: 'Tried journaling my feelings today. It helped me understand where my anxiety is coming from.' },
        '2024-08-15': { content: 'Couldn’t sleep last night. My mind was racing with worries about work and relationships.' },
        '2024-08-14': { content: 'Felt more at peace today. I spent some time reading and practicing mindfulness.' },
        '2024-08-13': { content: 'This is an example of a very long journal that might include many internal thoughts.' },
        '2024-08-12': { content: 'Discussed boundaries in therapy today. I think it’s something I need to work on.' },
        '2024-08-11': { content: 'Still feeling anxious about upcoming events. I can’t seem to stop worrying about them.' },
        '2024-08-10': { content: 'Had a good day overall. Managed to do my chores without feeling overwhelmed.' }
    };

    // Function to format date from 'yyyy-MM-dd' to 'dd/MM/yyyy'
    function formatDate(dateString) {
        const dateParts = dateString.split('-');
        return `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
    }

    // Render table
    function renderTable(filteredData) {
        const tableBody = document.querySelector(".historyJournal-table tbody");
        tableBody.innerHTML = '';  // Clear table

        if (filteredData.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="3">No records found</td></tr>';
            return;
        }

        filteredData.forEach((journal) => {
            const rowHTML = `
                <tr>
                    <td><a href="/patient/journal.html">${journal.content}</a></td>
                    <td>${journal.date}</td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', rowHTML);
        });
    }

    // Get all journal data with formatted date
    function getAllJournals() {
        return Object.keys(journalData).map(date => ({
            content: journalData[date].content,
            date: formatDate(date) // Format the date here
        }));
    }

    renderTable(getAllJournals());

    // Search function
    const searchInput = document.querySelector('.search-bar input[name="search"]');
    const searchButton = document.querySelector('.search-bar button[type="submit"]');

    searchButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent page refresh

        const keyword = searchInput.value.toLowerCase();
        const filteredData = getAllJournals().filter(journal => journal.content.toLowerCase().includes(keyword));

        renderTable(filteredData);
    });

    // Date picker function
    const confirmBtn = document.getElementById('confirm-btn');
    const yearSelect = document.getElementById('year');
    const monthSelect = document.getElementById('month');
    const daySelect = document.getElementById('day');

    confirmBtn.addEventListener('click', function() {
        const selectedDate = `${yearSelect.value}-${monthSelect.value.padStart(2, '0')}-${daySelect.value.padStart(2, '0')}`;
        
        // Filter journalData by selected date
        const filteredData = Object.keys(journalData)
            .filter(date => date === selectedDate)
            .map(date => ({
                no: journalData[date].no,
                content: journalData[date].content,
                date: formatDate(date) // Format the date
            }));

        // Re-render table
        renderTable(filteredData);
    });
});

