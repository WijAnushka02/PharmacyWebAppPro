document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('prescription-table-body');
    const searchInput = document.querySelector('input[placeholder="Search by patient name or Rx ID..."]');
    const priorityFilter = document.getElementById('priority-filter');

    let allPrescriptions = []; // Store the full list of prescriptions

    // Function to fetch data from the server
    const fetchPrescriptions = () => {
        fetch('api.php')
            .then(response => response.json())
            .then(data => {
                allPrescriptions = data; // Store the fetched data
                renderTable(allPrescriptions); // Render the initial table
            })
            .catch(error => console.error('Error fetching prescriptions:', error));
    };

    // Function to render the table with the provided data
    const renderTable = (prescriptions) => {
        if (!tableBody) return;
        tableBody.innerHTML = ''; // Clear existing table rows

        prescriptions.forEach(prescription => {
            const row = document.createElement('tr');
            row.className = 'bg-white border-b hover:bg-gray-50';

            const priorityClass = `priority-${prescription.priority.toLowerCase()}`;

            row.innerHTML = `
                <td class="px-6 py-4 font-medium text-[var(--text-primary)] whitespace-nowrap">${prescription.prescriptionId}</td>
                <td class="px-6 py-4">${prescription.patientName}</td>
                <td class="px-6 py-4">${prescription.issueDate}</td>
                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 text-xs font-medium rounded-full ${priorityClass}">${prescription.priority}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <a class="font-medium text-[var(--primary-color)] hover:underline" href="details_view.html?id=${prescription.prescriptionId}">Verify</a>
                </td>
            `;
            tableBody.appendChild(row);
        });
    };

    // Function to filter the data
    const filterTable = () => {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedPriority = priorityFilter.value.toLowerCase();

        const filteredPrescriptions = allPrescriptions.filter(prescription => {
            const matchesSearch = prescription.prescriptionId.toLowerCase().includes(searchTerm) || prescription.patientName.toLowerCase().includes(searchTerm);
            const matchesPriority = selectedPriority === 'all priorities' || prescription.priority.toLowerCase() === selectedPriority;
            return matchesSearch && matchesPriority;
        });

        renderTable(filteredPrescriptions);
    };

    // Add event listeners for both search and priority filter
    if (searchInput && priorityFilter) {
        searchInput.addEventListener('input', filterTable);
        priorityFilter.addEventListener('change', filterTable);
    }

    // Initial data fetch
    fetchPrescriptions();
});