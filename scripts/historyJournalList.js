
document.addEventListener("DOMContentLoaded", function() {
    const allJournals = journalData;

    // 日期格式转换函数
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // 月份从0开始计数
        const year = date.getFullYear();
        return `${day}/${month}/${year}`; // 转换为 DD/MM/YYYY 格式
    }

    // 渲染表格函数
    function renderTable(data) {
        const tableBody = document.querySelector(".historyJournal-table tbody");
        tableBody.innerHTML = ''; // 清空表格内容

        if (data.length > 0) {
            data.forEach(item => {
                const formattedDate = formatDate(item.date); // 使用 formatDate 转换日期格式
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

    // 搜索函数
    function searchJournals(keyword) {
        // const results = [];

        // // 遍历 journalData 查找匹配关键词的条目
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

    // 默认展示所有数据
    // const allJournals = Object.keys(journalData).reduce((acc, date) => {
    //     journalData[date].forEach(journal => {
    //         acc.push({ ...journal, date });
    //     });
    //     return acc;
    // }, []);
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
