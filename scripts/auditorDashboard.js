document.addEventListener('DOMContentLoaded', function () {
    const therapistRows = document.querySelectorAll('#therapist-list tr');

    therapistRows.forEach(row => {
        row.addEventListener('click', function () {
            const therapistId = this.getAttribute('data-therapist-id');
            const therapistName = this.children[0].textContent;

            // 更新右侧表头
            document.getElementById('therapist-detail-header').textContent = 'Therapist Consultation Detail of ' + therapistName;

            // 发起 AJAX 请求获取咨询细节
            fetch(`getConsultationDetail.php?therapist_id=${therapistId}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#therapist-detail tbody');
                    tbody.innerHTML = '';  // 清空之前的表格内容

                    if (data.length === 0) {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td colspan="3">No consultations available</td>`;
                        tbody.appendChild(row);
                    } else {
                        // 生成序号 P1, P2, P3 ...
                        data.forEach((consultation, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>P${index + 1}</td>  <!-- 用 P1, P2, P3 代替患者名字 -->
                                <td>${consultation.case_types}</td>
                                <td>${consultation.total_minutes}</td>
                            `;
                            tbody.appendChild(row);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching consultation details:', error);
                });
        });
    });
});
