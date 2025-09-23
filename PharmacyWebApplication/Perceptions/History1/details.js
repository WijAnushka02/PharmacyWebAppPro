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
                document.getElementById('patient-name').textContent = data.patient_name;
                document.getElementById('patient-dob').textContent = data.dob;
                document.getElementById('patient-address').textContent = data.address;
            })
            .catch(error => {
                console.error('Error fetching prescription details:', error);
                document.getElementById('prescription-id').textContent = `Prescription Not Found`;
                // Optionally, hide other sections or display an error message on the page
            });

        // Add event listeners for the action buttons
        const approveButton = document.getElementById('approve-button');
        const rejectButton = document.getElementById('reject-button');

        if (approveButton) {
            approveButton.addEventListener('click', () => {
                processVerification(prescriptionId, 'approved');
            });
        }

        if (rejectButton) {
            rejectButton.addEventListener('click', () => {
                processVerification(prescriptionId, 'rejected');
            });
        }
    }
});

function processVerification(prescriptionId, action) {
    // You can add a prompt for notes here if needed
    // const notes = prompt(`Please add notes for the ${action} action:`);

    fetch('process_verification.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            prescriptionId: prescriptionId,
            action: action,
            // notes: notes
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            // Optional: Redirect back to the main list page
            // window.location.href = 'perciption_verification.html';
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    });
}