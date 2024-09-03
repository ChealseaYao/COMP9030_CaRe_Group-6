document.addEventListener("DOMContentLoaded", function() {
    // 更新后的笔记数据
    let notesData = {
        '2024-07-20': [
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

    // 初始化显示最新的三条内容
    function displayLatestNotes() {
        const tableBody = document.querySelector(".note-history-note tbody");
        tableBody.innerHTML = ''; // 清空表格内容

        // 获取最新的三条记录，按日期排序
        const latestNotes = Object.keys(notesData)
            .sort((a, b) => new Date(b) - new Date(a)) // 按日期降序排列
            .slice(0, 3); // 获取前三条记录

        // 动态生成表格行
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

        // 绑定删除按钮事件
        bindDeleteButtons();
    }

    // 绑定日期选择器确认按钮的事件
    const confirmBtn = document.getElementById('confirm-btn');
    confirmBtn.addEventListener('click', function() {
        // 获取用户选择的日期
        const year = document.getElementById('year').value;
        const month = document.getElementById('month').value.padStart(2, '0'); // 确保月份为两位数
        const day = document.getElementById('day').value.padStart(2, '0'); // 确保日期为两位数

        // 格式化日期为 yyyy-MM-dd
        const selectedDate = `${year}-${month}-${day}`;
        console.log("Selected Date:", selectedDate);

        // 查找对应的内容
        const noteContentArray = notesData[selectedDate];

        if (noteContentArray) {
            const tableBody = document.querySelector(".note-history-note tbody");

            // 清空表格
            tableBody.innerHTML = '';

            // 生成新的表格行
            noteContentArray.forEach(noteContent => {
                const newRowHTML = `
                    <tr data-date="${selectedDate}">
                        <td>${selectedDate}</td>
                        <td>${noteContent}</td>
                        <td><button class="delete-button">Delete</button></td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', newRowHTML);
            });

            // 重新绑定删除按钮事件
            bindDeleteButtons();
        } else {
            alert("No notes found for the selected date.");
        }

        // 关闭日期选择器
        const calendarPopup = document.querySelector('.calendar-popup');
        calendarPopup.style.display = 'none';
    });

    // 绑定删除按钮事件的函数
    function bindDeleteButtons() {
        const deleteButtons = document.querySelectorAll(".delete-button");
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                const row = event.target.closest('tr');
                if (row) {
                    const date = row.getAttribute('data-date');
                    const note = row.querySelector('td:nth-child(2)').textContent;
                    
                    // 删除数据对象中的对应记录
                    notesData[date] = notesData[date].filter(n => n !== note);
                    if (notesData[date].length === 0) {
                        delete notesData[date];
                    }
                    
                    // 从表格中删除该行
                    row.remove();
                }
            });
        });
    }

    // 初始化显示最新三条内容
    displayLatestNotes();
});
