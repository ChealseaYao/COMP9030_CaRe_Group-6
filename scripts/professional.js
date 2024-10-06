document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('patientTableBody');
    let tableData = Array.from(tableBody.getElementsByTagName('tr'));

    let sortDirections = {
        patient_id: 'asc',
        age: 'asc',
        height: 'asc',
        weight: 'asc'
    };
    let genderFilter = 'all';

    window.sortTable = function (column) {
        const direction = sortDirections[column];
        tableData.sort((a, b) => {
            const aValue = a.querySelector(`td:nth-child(${getColumnIndex(column)})`).innerText;
            const bValue = b.querySelector(`td:nth-child(${getColumnIndex(column)})`).innerText;
            
            if (column === 'age' || column === 'patient_id' || column === 'height' || column === 'weight') {
                return direction === 'asc' ? aValue - bValue : bValue - aValue;
            } else {
                return direction === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
            }
        });

        // Toggle the sorting direction
        sortDirections[column] = direction === 'asc' ? 'desc' : 'asc';

        renderTable(tableData);
    };

    window.filterByGender = function () {
        if (genderFilter === 'all') {
            genderFilter = 'male';
        } else if (genderFilter === 'male') {
            genderFilter = 'female';
        } else {
            genderFilter = 'all';
        }

        let filteredData;
        if (genderFilter === 'all') {
            filteredData = tableData;
        } else {
            filteredData = tableData.filter(row => row.querySelector(`td:nth-child(${getColumnIndex('gender')})`).innerText === genderFilter);
        }

        renderTable(filteredData);
    };

    function renderTable(data) {
        tableBody.innerHTML = '';
        data.forEach(row => tableBody.appendChild(row));
    }

    function getColumnIndex(column) {
        switch (column) {
            case 'patient_id': return 1;
            case 'age': return 2;
            case 'gender': return 3;
            case 'height': return 4;
            case 'weight': return 5;
        }
    }
});
