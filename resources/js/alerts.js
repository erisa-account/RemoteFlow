import Swal from 'sweetalert2';

window.addEventListener('load', () => {
    

    const form = document.getElementById('checkin-form');

    if(!form) return;
    
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
            statusError.textContent = 'Please select a status:';
            hasError = true;
        }

        if (!date) {
            dateError.textContent = 'Please choose a date.';
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
                title: 'Check-in exists!',
                text: result.message + '\nDo you want to update it with the new status?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update.',
                cancelButtonText: 'No, cancel.',
                reverseButtons: true
               });

            if (confirmResult.isConfirmed) {
            await updateCheckin(result.data.id, status_id);
              } else {
                errorMessage.textContent = 'The action was canceled by the user.';
               }
            } 
            
             else {
                errorMessage.textContent = result.message;
                successMessage.textContent = '';
            }
        } catch (err) {
            console.error("Fetch error:", err);
            errorMessage.textContent = 'Error while sending.';
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
                title: 'Updated!',
                text: 'You have updated your status!',
                icon: 'success'
            });
            errorMessage.textContent = '';
            successMessage.textContent = '';
            form.reset();
            } else {
                errorMessage.textContent = result.message || 'The update failed.';
            }
        } catch (err) {
            console.error("Update error:", err);
            errorMessage.textContent = 'Error during update.';
        }
    }


    const infoBtn = document.getElementById('statusInfoBtn');

    if(infoBtn) {
        infoBtn.addEventListener('click', () =>{
            Swal.fire({
                icon: 'info',
                title: '',
                text: `You can select a status for any day you choose.Once selected, the status will be applied to the chosen day.
                You cannot select a past date for your status.If you don't check in today, your status will be saved on the site.`,
                showCloseButton: true,
                
            })
        })
    }
}); 


  

  