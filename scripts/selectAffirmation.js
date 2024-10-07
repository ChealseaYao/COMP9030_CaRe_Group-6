
console.log("selectAffirmation.js loaded successfully");

document.addEventListener('DOMContentLoaded', function() {
  
    console.log("DOM fully loaded and parsed");

    
    const radios = document.querySelectorAll('input[name="affirmation"]');
    
    
    if (radios.length === 0) {
        console.error("No affirmation radio buttons found");
        return;  
    }
    

    const patientId = document.body.getAttribute('data-patient-id');
    if (!patientId) {
        console.error("No patient ID found on the page");
        return;  
    }

   
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
           
            const label = this.parentNode;
            const selectedText = label.textContent.trim();
            const affirmation = this.value;

           
            console.log("Affirmation selected: ", affirmation);
            console.log("Patient ID: ", patientId);

        
            const form = document.getElementById('affirmationForm');
            form.innerHTML = `<p style="margin: 0; padding: 40px; color: #102e5d; font-size: 1.8rem; font-family: Comic Sans MS, cursive;">${selectedText}</p>`;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'saveAffirmation.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            
            console.log("Sending AJAX request...");

            xhr.send('affirmation=' + encodeURIComponent(affirmation) + '&patient_id=' + encodeURIComponent(patientId));

           
            console.log("AJAX request sent");

     
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                
                        console.log('Success: ', xhr.responseText);
                    } else {
                    
                        console.error('Error: ', xhr.responseText);
                    }
                }
            };
        });
    });
});
