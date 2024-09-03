document.addEventListener("DOMContentLoaded", function() {
    const dateIcon = document.getElementById('date-icon');
    const calendarPopup = document.querySelector('.calendar-popup');
    const yearSelect = document.getElementById('year');
    const monthSelect = document.getElementById('month');
    const daySelect = document.getElementById('day');
    const confirmBtn = document.getElementById('confirm-btn');

    // Populate year dropdown
    const currentYear = new Date().getFullYear();
    const startYear = currentYear - 100;
    const endYear = currentYear + 10;
    for (let year = startYear; year <= endYear; year++) {
        let option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    // Populate month dropdown
    for (let month = 1; month <= 12; month++) {
        let option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelect.appendChild(option);
    }

    // Populate day dropdown
    function populateDays() {
        daySelect.innerHTML = '';
        const year = parseInt(yearSelect.value);
        const month = parseInt(monthSelect.value);
        const daysInMonth = new Date(year, month, 0).getDate();
        for (let day = 1; day <= daysInMonth; day++) {
            let option = document.createElement('option');
            option.value = day;
            option.textContent = day;
            daySelect.appendChild(option);
        }
    }

    yearSelect.addEventListener('change', populateDays);
    monthSelect.addEventListener('change', populateDays);

    // Initialize the dropdowns with current date
    yearSelect.value = currentYear;
    monthSelect.value = new Date().getMonth() + 1;
    populateDays();
    daySelect.value = new Date().getDate();

    // Show/Hide the calendar popup
    dateIcon.addEventListener('click', function() {
        calendarPopup.style.display = calendarPopup.style.display === 'block' ? 'none' : 'block';
    });

    // Close the calendar on confirm
    confirmBtn.addEventListener('click', function() {
        const selectedDate = `${yearSelect.value}-${monthSelect.value}-${daySelect.value}`;
        console.log("Selected Date:", selectedDate);
        // Here you can set the selected date to an input field or display it on the page
        // Example:
        // document.getElementById('date-range-input').value = selectedDate;
        calendarPopup.style.display = 'none';
    });

    // Close the calendar if clicked outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.date-picker-container')) {
            calendarPopup.style.display = 'none';
        }
    });

    // Stop propagation to keep calendar open on click
    calendarPopup.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});
