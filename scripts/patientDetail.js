document.addEventListener("DOMContentLoaded", function() {
    // 模拟后端数据
    const journalData = {
        '2024-09-03': [{ state: '✔', title: 'Journal Title 15' }],
        '2024-09-01': [{ state: '✔', title: 'Journal Title 14' }],
        '2024-08-31': [{ state: '●', title: 'Journal Title 13' }],
        '2024-08-29': [{ state: '✔', title: 'Journal Title 12' }],
        '2024-08-27': [{ state: '✔', title: 'Journal Title 11' }],
        '2024-08-25': [{ state: '●', title: 'Journal Title 10' }],
        '2024-08-23': [{ state: '✔', title: 'Journal Title 9' }],
        '2024-08-21': [{ state: '✔', title: 'Journal Title 8' }],
        '2024-08-19': [{ state: '✔', title: 'Journal Title 7' }],
        '2024-08-17': [{ state: '●', title: 'Journal Title 6' }],
        '2024-08-13': [{ state: '●', title: 'Journal Title 1' }],
        '2024-08-11': [{ state: '✔', title: 'Journal Title 2' }],
        '2024-08-09': [{ state: '✔', title: 'Journal Title 3' }],
        '2024-08-07': [{ state: '✔', title: 'Journal Title 4' }],
        '2024-08-06': [{ state: '✔', title: 'Journal Title 5' }],
        '2024-08-04': [{ state: '✔', title: 'Journal Title 16' }],
        '2024-08-02': [{ state: '●', title: 'Journal Title 17' }],
        '2024-08-01': [{ state: '✔', title: 'Journal Title 18' }],
        '2024-07-30': [{ state: '✔', title: 'Journal Title 19' }],
        '2024-07-28': [{ state: '✔', title: 'Journal Title 20' }],
        '2024-07-25': [{ state: '●', title: 'Journal Title 21' }],
        '2024-07-23': [{ state: '✔', title: 'Journal Title 22' }],
        '2024-07-21': [{ state: '✔', title: 'Journal Title 23' }],
        '2024-07-19': [{ state: '✔', title: 'Journal Title 24' }],
        '2024-07-17': [{ state: '●', title: 'Journal Title 25' }],
        '2024-07-15': [{ state: '✔', title: 'Journal Title 26' }],
        '2024-07-13': [{ state: '✔', title: 'Journal Title 27' }],
        '2024-07-11': [{ state: '✔', title: 'Journal Title 28' }],
        '2024-07-09': [{ state: '✔', title: 'Journal Title 29' }],
        '2024-07-07': [{ state: '✔', title: 'Journal Title 30' }]
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
                        <td>${journal.state}</td>
                        <td>${journal.title}</td>
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
