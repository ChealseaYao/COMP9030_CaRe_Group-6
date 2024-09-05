document.addEventListener("DOMContentLoaded", function() {
    // 模拟的 journalData 数据
    const journalData = {
        '2024-09-03': [{ content: 'Today I felt more in control of my emotions. I managed to calm myself down when I started to feel anxious.', star: false }],
        '2024-09-02': [{ content: 'Discussed my progress in therapy today. Feeling hopeful about learning new coping mechanisms.', star: false }],
        '2024-09-01': [{ content: 'Had a great day today. I was able to go outside and walk around without feeling overwhelmed.', star: true }],
        '2024-08-31': [{ content: 'Started my new routine with mindfulness exercises in the morning. It helped me stay calm throughout the day.', star: false }],
        '2024-08-30': [{ content: 'Feeling overwhelmed by work today. Struggled to focus and felt a lot of tension in my body.', star: false }],
        '2024-08-29': [{ content: 'I managed to talk to my boss about reducing some of my workload. It was hard, but I feel relieved.', star: false }],
        '2024-08-28': [{ content: 'Had a good session today. My therapist suggested a new breathing technique, which I will try this week.', star: true }],
        '2024-08-27': [{ content: 'Today was hard. I felt like I was losing control again. Couldn’t stop the racing thoughts.', star: false }],
        '2024-08-26': [{ content: 'I went grocery shopping today without feeling anxious. This was a big step for me.', star: true }],
        '2024-08-25': [{ content: 'Had another sleepless night. I kept replaying past conversations in my head.', star: false }],
        '2024-08-24': [{ content: 'Practiced yoga in the morning. It really helped clear my mind for the day.', star: false }],
        '2024-08-23': [{ content: 'Anxiety levels were really high today. I couldn’t concentrate on my work and had to take a break.', star: true }],
        '2024-08-22': [{ content: 'Had a long talk with a friend today. It was nice to feel understood and not judged.', star: false }],
        '2024-08-21': [{ content: 'Another difficult day. I kept overthinking everything, especially past mistakes.', star: false }],
        '2024-08-20': [{ content: 'Had a peaceful day. I stayed at home and watched a movie to relax.', star: false }],
        '2024-08-19': [{ content: 'Felt really down today. I kept questioning my worth and couldn’t shake off the negativity.', star: false }],
        '2024-08-18': [{ content: 'Therapy session went well today. I feel like I’m making progress, slowly but surely.', star: true }],
        '2024-08-17': [{ content: 'Had a panic attack while driving. It came out of nowhere, and I had to pull over.', star: false }],
        '2024-08-16': [{ content: 'Tried journaling my feelings today. It helped me understand where my anxiety is coming from.', star: true }],
        '2024-08-15': [{ content: 'Couldn’t sleep last night. My mind was racing with worries about work and relationships.', star: false }],
        '2024-08-14': [{ content: 'Felt more at peace today. I spent some time reading and practicing mindfulness.', star: false }],
        '2024-08-13': [{ content: 'This is an example of a very long journal that might include many internal thoughts.', star: true }],
        '2024-08-12': [{ content: 'Discussed boundaries in therapy today. I think it’s something I need to work on.', star: false }],
        '2024-08-11': [{ content: 'Still feeling anxious about upcoming events. I can’t seem to stop worrying about them.', star: false }],
        '2024-08-10': [{ content: 'Had a good day overall. Managed to do my chores without feeling overwhelmed.', star: false }]
    };

    // 渲染表格函数
    function renderTable(data) {
        const tableBody = document.querySelector(".historyJournal-table tbody");
        tableBody.innerHTML = ''; // 清空表格内容

        if (data.length > 0) {
            data.forEach(item => {
                const rowHTML = `
                    <tr>
                        <td class="star">${item.star ? '★' : ''}</td>
                        <td>${item.content}</td>
                        <td>${item.date}</td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', rowHTML);
            });
        } else {
            const noDataHTML = `<tr><td colspan="4">No journals found.</td></tr>`;
            tableBody.insertAdjacentHTML('beforeend', noDataHTML);
        }
    }

    // 搜索函数
    function searchJournals(keyword) {
        const results = [];

        // 遍历 journalData 查找匹配关键词的条目
        Object.keys(journalData).forEach(date => {
            journalData[date].forEach(journal => {
                if (journal.content.toLowerCase().includes(keyword.toLowerCase())) {
                    results.push({
                        ...journal,
                        date: date
                    });
                }
            });
        });

        return results;
    }

    // 默认展示所有数据
    const allJournals = Object.keys(journalData).reduce((acc, date) => {
        journalData[date].forEach(journal => {
            acc.push({ ...journal, date });
        });
        return acc;
    }, []);
    renderTable(allJournals);

    // 搜索框逻辑
    const searchInput = document.querySelector('input[name="search"]');
    const searchButton = document.querySelector('button[type="submit"]');

    searchButton.addEventListener('click', function(event) {
        event.preventDefault();
        const keyword = searchInput.value.trim();

        if (keyword) {
            const filteredJournals = searchJournals(keyword);
            renderTable(filteredJournals);
        } else {
            // 如果搜索框为空，则显示所有数据
            renderTable(allJournals);
        }
    });

    // 处理日历点击
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
