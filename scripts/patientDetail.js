
document.addEventListener("DOMContentLoaded", function() {
    
    const journalData = {
        '2024-09-03': [{ state: '✔', content: 'Today I felt more in control of my emotions. I managed to calm myself down when I started to feel anxious.', star: false }],
        '2024-09-02': [{ state: '✔', content: 'Discussed my progress in therapy today. Feeling hopeful about learning new coping mechanisms.', star: false }],
        '2024-09-01': [{ state: '✔', content: 'Had a great day today. I was able to go outside and walk around without feeling overwhelmed.', star: true }],
        '2024-08-31': [{ state: '✔', content: 'Started my new routine with mindfulness exercises in the morning. It helped me stay calm throughout the day.', star: false }],
        '2024-08-30': [{ state: '●', content: 'Feeling overwhelmed by work today. Struggled to focus and felt a lot of tension in my body.', star: false }],
        '2024-08-29': [{ state: '✔', content: 'I managed to talk to my boss about reducing some of my workload. It was hard, but I feel relieved.', star: false }],
        '2024-08-28': [{ state: '✔', content: 'Had a good session today. My therapist suggested a new breathing technique, which I will try this week.', star: true }],
        '2024-08-27': [{ state: '●', content: 'Today was hard. I felt like I was losing control again. Couldn’t stop the racing thoughts.', star: false }],
        '2024-08-26': [{ state: '✔', content: 'I went grocery shopping today without feeling anxious. This was a big step for me.', star: true }],
        '2024-08-25': [{ state: '●', content: 'Had another sleepless night. I kept replaying past conversations in my head.', star: false }],
        '2024-08-24': [{ state: '✔', content: 'Practiced yoga in the morning. It really helped clear my mind for the day.', star: false }],
        '2024-08-23': [{ state: '●', content: 'Anxiety levels were really high today. I couldn’t concentrate on my work and had to take a break.', star: true }],
        '2024-08-22': [{ state: '✔', content: 'Had a long talk with a friend today. It was nice to feel understood and not judged.', star: false }],
        '2024-08-21': [{ state: '●', content: 'Another difficult day. I kept overthinking everything, especially past mistakes.', star: false }],
        '2024-08-20': [{ state: '✔', content: 'Had a peaceful day. I stayed at home and watched a movie to relax.', star: false }],
        '2024-08-19': [{ state: '●', content: 'Felt really down today. I kept questioning my worth and couldn’t shake off the negativity.', star: false }],
        '2024-08-18': [{ state: '✔', content: 'Therapy session went well today. I feel like I’m making progress, slowly but surely.', star: true }],
        '2024-08-17': [{ state: '●', content: 'Had a panic attack while driving. It came out of nowhere, and I had to pull over.', star: false }],
        '2024-08-16': [{ state: '✔', content: 'Tried journaling my feelings today. It helped me understand where my anxiety is coming from.', star: true }],
        '2024-08-15': [{ state: '●', content: 'Couldn’t sleep last night. My mind was racing with worries about work and relationships.', star: false }]
    };

    
    function displayLatestJournals() {
        const sortedDates = Object.keys(journalData).sort((a, b) => new Date(b) - new Date(a));
        const latestDates = sortedDates.slice(0, 5);  

        renderTable(latestDates);
    }

    
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');  
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    
    function renderTable(dates) {
        const tableBody = document.querySelector(".journalLists-table tbody");
        tableBody.innerHTML = ''; 

        dates.forEach(date => {
            journalData[date].forEach(journal => {
                const formattedDate = formatDate(date);  
                const rowHTML = `
                    <tr>
                        <td><a href="/patient/journal.html">${journal.content}</a></td>
                        <td>${formattedDate}</td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', rowHTML);
            });
        });
    }

    
    displayLatestJournals();
});
