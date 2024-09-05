document.addEventListener("DOMContentLoaded", function() {
    // 模拟后端数据
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
        '2024-08-15': [{ state: '●', content: 'Couldn’t sleep last night. My mind was racing with worries about work and relationships.', star: false }],
        '2024-08-14': [{ state: '✔', content: 'Felt more at peace today. I spent some time reading and practicing mindfulness.', star: false }],
        '2024-08-13': [{ state: '●', content: 'This is an example of a very long journal that might include many internal thoughts.', star: true }],
        '2024-08-12': [{ state: '✔', content: 'Discussed boundaries in therapy today. I think it’s something I need to work on.', star: false }],
        '2024-08-11': [{ state: '●', content: 'Still feeling anxious about upcoming events. I can’t seem to stop worrying about them.', star: false }],
        '2024-08-10': [{ state: '✔', content: 'Had a good day overall. Managed to do my chores without feeling overwhelmed.', star: false }]
    };
    

    // 显示默认最新的5条记录
    function displayLatestJournals() {
        const sortedDates = Object.keys(journalData).sort((a, b) => new Date(b) - new Date(a));
        const latestDates = sortedDates.slice(0, 5);

        renderTable(latestDates);
    }

    // 渲染表格内容
    function renderTable(dates) {
        const tableBody = document.querySelector(".journalLists-table tbody");
        tableBody.innerHTML = ''; // 清空表格

        dates.forEach(date => {
            journalData[date].forEach(journal => {
                const rowHTML = `
                    <tr>
                        <td>${journal.content}</td>
                        <td>${date}</td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', rowHTML);
            });
        });
    }

    // 处理日历图标点击，过滤选择的日期
    const confirmBtn = document.getElementById('confirm-btn');
    confirmBtn.addEventListener('click', function() {
        const year = document.getElementById('year').value;
        const month = document.getElementById('month').value.padStart(2, '0');
        const day = document.getElementById('day').value.padStart(2, '0');
        
        const selectedDate = `${year}-${month}-${day}`;
        
        if (journalData[selectedDate]) {
            renderTable([selectedDate]); // 只显示选择的日期的数据
        } else {
            alert("No journals found for the selected date.");
        }
    });

    // 初始化日期选择器，年、月、日
    function initDatePicker() {
        const currentYear = new Date().getFullYear();
        const yearSelect = document.getElementById('year');
        const monthSelect = document.getElementById('month');
        const daySelect = document.getElementById('day');
        
        // 年份选择
        for (let i = currentYear; i >= 2020; i--) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            yearSelect.appendChild(option);
        }
        
        // 月份选择
        for (let i = 1; i <= 12; i++) {
            const option = document.createElement('option');
            option.value = i.toString().padStart(2, '0');
            option.textContent = i;
            monthSelect.appendChild(option);
        }
        
        // 日期选择（动态变化）
        monthSelect.addEventListener('change', updateDays);
        yearSelect.addEventListener('change', updateDays);
        
        function updateDays() {
            const year = parseInt(yearSelect.value);
            const month = parseInt(monthSelect.value);
            const daysInMonth = new Date(year, month, 0).getDate(); // 获取该月的天数
            daySelect.innerHTML = ''; // 清空日期选择
            
            for (let i = 1; i <= daysInMonth; i++) {
                const option = document.createElement('option');
                option.value = i.toString().padStart(2, '0');
                option.textContent = i;
                daySelect.appendChild(option);
            }
        }
        
        // 初始化时更新日期
        updateDays();
    }

    // 初始化日期选择器和显示最新记录
    initDatePicker();
    displayLatestJournals();
});
