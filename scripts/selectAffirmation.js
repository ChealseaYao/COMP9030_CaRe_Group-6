// 确认文件加载成功
console.log("selectAffirmation.js loaded successfully");

document.addEventListener('DOMContentLoaded', function() {
    // 确认 DOM 完全加载
    console.log("DOM fully loaded and parsed");

    // 获取页面中的所有肯定句的 radio 按钮
    const radios = document.querySelectorAll('input[name="affirmation"]');
    
    // 确认是否找到了肯定句的 radio 按钮
    if (radios.length === 0) {
        console.error("No affirmation radio buttons found");
        return;  // 如果没有找到，则不继续执行
    }
    
    // 获取患者的 ID
    const patientId = document.body.getAttribute('data-patient-id');
    if (!patientId) {
        console.error("No patient ID found on the page");
        return;  // 如果没有找到患者ID，也不继续执行
    }

    // 为每个 radio 按钮绑定事件监听器
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            // 获取选中的肯定句的值
            const label = this.parentNode;
            const selectedText = label.textContent.trim();
            const affirmation = this.value;

            // 输出调试信息，确认用户选择了肯定句并正确获取到了患者ID
            console.log("Affirmation selected: ", affirmation);
            console.log("Patient ID: ", patientId);

            // 更新页面显示的肯定句
            const form = document.getElementById('affirmationForm');
            form.innerHTML = `<p style="margin: 0; padding: 40px; color: #102e5d; font-size: 1.8rem; font-family: Comic Sans MS, cursive;">${selectedText}</p>`;

            // 通过 AJAX 将选中的肯定句发送到服务器
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'saveAffirmation.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // 调试输出，确认 AJAX 请求已准备好发送
            console.log("Sending AJAX request...");

            // 发送 POST 请求
            xhr.send('affirmation=' + encodeURIComponent(affirmation) + '&patient_id=' + encodeURIComponent(patientId));

            // 调试输出，确认 AJAX 请求已发送
            console.log("AJAX request sent");

            // 处理服务器的响应
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // 成功时输出服务器的响应
                        console.log('Success: ', xhr.responseText);
                    } else {
                        // 如果请求失败，输出错误信息
                        console.error('Error: ', xhr.responseText);
                    }
                }
            };
        });
    });
});
