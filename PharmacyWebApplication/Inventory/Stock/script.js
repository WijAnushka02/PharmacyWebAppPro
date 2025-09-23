document.addEventListener('DOMContentLoaded', () => {
    const inventoryTableBody = document.getElementById('inventory-table-body');
    const medicineForm = document.getElementById('medicine-form');
    const formTitle = document.getElementById('form-title');
    const medicineIdInput = document.getElementById('medicine-id');
    const medicineNameInput = document.getElementById('medicine-name');
    const medicineDosageInput = document.getElementById('medicine-dosage');
    const medicineQuantityInput = document.getElementById('medicine-quantity');
    const medicineExpiryInput = document.getElementById('medicine-expiry');
    const formSubmitBtn = document.getElementById('form-submit-btn');

    const fetchMedicines = async () => {
        try {
            const response = await fetch('api.php');
            const medicines = await response.json();
            inventoryTableBody.innerHTML = '';
            medicines.forEach(item => {
                const row = document.createElement('tr');
                const statusClass = item.quantity === 0 ? 'out-of-stock' : (new Date(item.expiry) < new Date() ? 'expired' : (item.quantity <= 15 ? 'low-stock' : 'in-stock'));
                const statusText = item.quantity === 0 ? 'Out of Stock' : (new Date(item.expiry) < new Date() ? 'Expired' : (item.quantity <= 15 ? 'Low Stock' : 'In Stock'));
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.dosage}</td>
                    <td>${item.quantity}</td>
                    <td>${item.expiry}</td>
                    <td><span class="status-label ${statusClass}">${statusText}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="edit-btn" data-id="${item.id}">Edit</button>
                            <button class="delete-btn" data-id="${item.id}">Delete</button>
                        </div>
                    </td>
                `;
                inventoryTableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    };

    const editMedicine = async (id, data) => {
        try {
            const response = await fetch('api.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            console.log(result.message);
            fetchMedicines();
        } catch (error) {
            console.error('Error updating medicine:', error);
        }
    };

    const deleteMedicine = async (id) => {
        try {
            const response = await fetch(`api.php?id=${id}`, {
                method: 'DELETE'
            });
            const result = await response.json();
            console.log(result.message);
            fetchMedicines();
        } catch (error) {
            console.error('Error deleting medicine:', error);
        }
    };

    inventoryTableBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('edit-btn')) {
            const id = e.target.dataset.id;
            const row = e.target.closest('tr');
            const cells = row.querySelectorAll('td');

            medicineIdInput.value = cells[0].textContent;
            medicineNameInput.value = cells[1].textContent;
            medicineDosageInput.value = cells[2].textContent;
            medicineQuantityInput.value = cells[3].textContent;
            medicineExpiryInput.value = cells[4].textContent;

            formTitle.textContent = 'Edit Medicine';
            formSubmitBtn.textContent = 'Update';

        } else if (e.target.classList.contains('delete-btn')) {
            const id = e.target.dataset.id;
            if (confirm('Are you sure you want to delete this medicine?')) {
                deleteMedicine(id);
            }
        }
    });

    medicineForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const id = medicineIdInput.value;
        const data = {
            id: id,
            name: medicineNameInput.value,
            dosage: medicineDosageInput.value,
            quantity: medicineQuantityInput.value,
            expiry: medicineExpiryInput.value
        };

        if (formSubmitBtn.textContent === 'Update') {
            editMedicine(id, data);
        } else {
            // Assuming you have an add function here
            // addMedicine(data);
        }
        medicineForm.reset();
        formTitle.textContent = 'Add New Medicine';
        formSubmitBtn.textContent = 'Add';
    });

    fetchMedicines();
});