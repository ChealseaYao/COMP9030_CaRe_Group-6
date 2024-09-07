document.addEventListener('DOMContentLoaded', function() {
  const radios = document.querySelectorAll('input[name="affirmation"]');

  radios.forEach(radio => {
    radio.addEventListener('change', function() {
      const label = this.parentNode;  
      const selectedText = label.textContent.trim();  
      const form = document.getElementById('affirmationForm');
      form.innerHTML = `<p style="margin: 0; padding: 40px; color: #102e5d; font-size: 1.8rem; font-family: Comic Sans MS, cursive;">${selectedText}</p>`;  
    });
  });
});
