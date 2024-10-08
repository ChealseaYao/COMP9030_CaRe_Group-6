
document.addEventListener("DOMContentLoaded", function() {
    const allJournals = journalData;

    
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); 
        const year = date.getFullYear();
        return `${day}/${month}/${year}`; 
    }

 
    function renderTable(data) {
        const tableBody = document.querySelector(".historyJournal-table tbody");
        tableBody.innerHTML = ''; 

        if (data.length > 0) {
            data.forEach(item => {
                const formattedDate = formatDate(item.date); 
                const rowHTML = `
                    <tr>
                        <td class="star">${item.highlight ? '★' : ''}</td>
                        <td><a href="journal.php?journal_id=${item.journal_id}&patient_id=${patient_id}">${item.content.length > 50 ? item.content.substring(0, 50) + '...' : item.content}</a></td>
                        <td>${formattedDate}</td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', rowHTML);
            });
        } else {
            const noDataHTML = `<tr><td colspan="4">No journals found.</td></tr>`;
            tableBody.insertAdjacentHTML('beforeend', noDataHTML);
        }
    }

   
    function searchJournals(keyword) {
        // const results = [];

   
        // Object.keys(journalData).forEach(date => {
        //     journalData[date].forEach(journal => {
        //         if (journal.content.toLowerCase().includes(keyword.toLowerCase())) {
        //             results.push({
        //                 ...journal,
        //                 date: date
        //             });
        //         }
        //     });
        // });

        // return results;
        return allJournals.filter(journal => journal.content.toLowerCase().includes(keyword.toLowerCase()));
    }


    // const allJournals = Object.keys(journalData).reduce((acc, date) => {
    //     journalData[date].forEach(journal => {
    //         acc.push({ ...journal, date });
    //     });
    //     return acc;
    // }, []);
    renderTable(allJournals);


    const searchInput = document.querySelector('input[name="search"]');
    const searchButton = document.querySelector('button[type="submit"]');

    searchButton.addEventListener('click', function(event) {
        event.preventDefault();
        const keyword = searchInput.value.trim();

        if (keyword) {
            const filteredJournals = searchJournals(keyword);
            renderTable(filteredJournals);
        } else {
            renderTable(allJournals);
        }
    });


    const confirmBtn = document.getElementById('confirm-btn');
    confirmBtn.addEventListener('click', function() {
        const year = document.getElementById('year').value;
        const month = document.getElementById('month').value.padStart(2, '0');
        const day = document.getElementById('day').value.padStart(2, '0');

        const selectedDate = `${year}-${month}-${day}`;
        const filteredJournals = allJournals.filter(journal => journal.date === selectedDate);
        renderTable(filteredJournals);
    });

    
});
