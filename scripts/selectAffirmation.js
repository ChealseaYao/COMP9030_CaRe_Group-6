document.addEventListener('DOMContentLoaded', function() {
  const radios = document.querySelectorAll('input[name="affirmation"]');

  radios.forEach(radio => {
    radio.addEventListener('change', function() {
      const label = this.parentNode;  // 获取当前 radio 的父元素，即 label
      const selectedText = label.textContent.trim();  // 从 label 获取整个文本
      const form = document.getElementById('affirmationForm');
      form.innerHTML = `<p style="margin: 0; padding: 40px; color: #102e5d; font-size: 1.8rem; font-family: Comic Sans MS, cursive;">${selectedText}</p>`;  // 替换表单内容
    });
  });
});
