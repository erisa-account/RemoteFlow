import Swal from 'sweetalert2';
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('checkin-form');
    const statusInput = document.getElementById('status');
    const dateInput = document.getElementById('datepicker');
    const statusError = document.getElementById('status-error');
    const dateError = document.getElementById('date-error');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-messages');

    let isSubmitting = false;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (isSubmitting) return;
        isSubmitting = true;

        // Clear previous messages
        statusError.textContent = '';
        dateError.textContent = '';
        successMessage.textContent = '';
        errorMessage.textContent = '';

        // Get input values
        const status_id = statusInput.value;
        const date = dateInput.value;

        // Validate
        let hasError = false;
        if (!status_id) {
            statusError.textContent = 'Ju lutem zgjidhni një status.';
            hasError = true;
        }

        if (!date) {
            dateError.textContent = 'Ju lutem zgjidhni një datë.';
            hasError = true;
        }

        if (hasError) {
            isSubmitting = false;
            return;
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ status_id, date })
            });

            const result = await response.json();
            console.log("Response:", result);
            
            if (result.success && !result.existing) {
            // ✅ New check-in — show message in the div
            successMessage.textContent = result.message;
            errorMessage.textContent = '';
            form.reset(); // clear form
            } else if (result.existing) {
             // ❗ Check-in already exists — use SweetAlert2 instead
                const confirmResult = await Swal.fire({
                title: 'Check-in ekziston!',
                text: result.message + '\nDoni ta përditësoni me statusin e ri?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Po, përditësoje',
                cancelButtonText: 'Jo, anuloje',
                reverseButtons: true
               });

            if (confirmResult.isConfirmed) {
            await updateCheckin(result.data.id, status_id);
              } else {
                errorMessage.textContent = 'Veprimi u anulua nga përdoruesi.';
               }
            } 
            
             else {
                errorMessage.textContent = result.message;
                successMessage.textContent = '';
            }
        } catch (err) {
            console.error("Fetch error:", err);
            errorMessage.textContent = 'Gabim gjatë dërgimit.';
        } finally {
            isSubmitting = false;
        }
    });

    // ✅ Update request function
    async function updateCheckin(id, status_id) {
        try {
            const response = await fetch(`/user/checkin/update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ status_id })
            });

            const result = await response.json();
            console.log("Update result:", result);

            if (result.success) {
            // ✅ Show SweetAlert2 only — do NOT use div message
            await Swal.fire({
                title: 'Përditësuar!',
                text: result.message,
                icon: 'success'
            });
            errorMessage.textContent = '';
            successMessage.textContent = '';
            form.reset();
            } else {
                errorMessage.textContent = result.message || 'Përditësimi dështoi.';
            }
        } catch (err) {
            console.error("Update error:", err);
            errorMessage.textContent = 'Gabim gjatë përditësimit.';
        }
    }
});
            
