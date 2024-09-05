document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); 

            const username = document.getElementById('Username').value;
            const password = document.getElementById('password').value;
            const role = document.getElementById('role').value;

            
            const users = [
                { username: 'Vivian Harper', password: 'therapist123', role: 'therapist' },
                { username: 'Kage', password: 'patient123', role: 'patient' }
            ];

           
            const user = users.find(user => user.username === username && user.password === password && user.role === role);

            if (user) {
                if (user.role === 'therapist') {
                    window.location.href = '/therapist/therapistDashboard.html'; 
                } else if (user.role === 'patient') {
                    window.location.href = '/patient/patientDashboard.html'; 
                }
            } else {
                alert('Invalid username, password or role!');
            }
        });
    } else {
        console.error('Login form not found');
    }
});
