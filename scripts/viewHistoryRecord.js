document.addEventListener("DOMContentLoaded", function() {

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
                    <td><a href="journal.php?journal_id=${journal.journal_id}" style="text-decoration: none; color: inherit;">${journal.content.length > 50 ? journal.content.substring(0, 50) + '...' : journal.content}</a></td>
                    <td>${formatDate(journal.date)}</td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', rowHTML);
        });
    }

    // Get all journal data with formatted date
    function getAllJournals() {
        return journalData.map(journal => ({
            journal_id: journal.journal_id,
            content: journal.content,
            date: journal.date
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
        const filteredData = getAllJournals().filter(journal => journal.date === selectedDate);


        // Re-render table
        renderTable(filteredData);
    });
});

