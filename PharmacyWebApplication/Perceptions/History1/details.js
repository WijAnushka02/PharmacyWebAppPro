document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const prescriptionId = urlParams.get('id');

    if (prescriptionId) {
        document.getElementById('prescription-id').textContent = `Prescription #${prescriptionId}`;

        // Fetch prescription data from the PHP API
        fetch(`api.php?id=${prescriptionId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Prescription not found');
                }
                return response.json();
            })
            .then(data => {
                // Update Patient Information
                document.getElementById('patient-name').textContent = data.patientName;
                document.getElementById('patient-dob').textContent = data.dob;
                document.getElementById('patient-address').textContent = data.address;

                // Update Scanned Prescription Image
                document.getElementById('scanned-prescription-image').style.backgroundImage = `url(${data.image})`;

                // Update Prescribed Medicines table
                const medicineTableBody = document.getElementById('medicine-table-body');
                medicineTableBody.innerHTML = ''; // Clear existing rows
                data.medicines.forEach(medicine => {
                    const row = document.createElement('tr');
                    row.className = 'border-b border-gray-200';
                    row.innerHTML = `
                        <td class="px-6 py-4 font-medium text-[var(--text-primary)]">${medicine.medicine}</td>
                        <td class="px-6 py-4 text-[var(--text-secondary)]">${medicine.dosage}</td>
                        <td class="px-6 py-4 text-[var(--text-secondary)]">${medicine.quantity}</td>
                        <td class="px-6 py-4 text-[var(--text-secondary)]">${medicine.refills}</td>
                    `;
                    medicineTableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error fetching prescription details:', error);
                document.getElementById('prescription-id').textContent = `Prescription Not Found`;
                // Optionally, hide other sections or display an error message on the page
            });
    }
});