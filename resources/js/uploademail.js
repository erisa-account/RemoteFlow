document.getElementById('file').addEventListener('change', function (event) {
    const fileListContainer = document.getElementById('fileListContainer');
    
    Array.from(event.target.files).forEach((file) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'rounded-md bg-[#F5F7FB] py-4 px-8';

        fileItem.innerHTML = `
            <div class="flex items-center justify-between">
                <span class="truncate pr-3 text-base font-medium text-[#07074D]">${file.name}</span>
                <button type="button" class="removeFile text-[#07074D]">✕</button>
            </div>
            <div class="relative mt-5 h-[6px] w-full rounded-lg bg-[#E2E5EF]">
                <div class="progress absolute left-0 top-0 h-full w-[0%] rounded-lg bg-[#6A64F1]"></div>
            </div>
        `;

        // Remove button
        fileItem.querySelector('.removeFile').addEventListener('click', () => {
            fileItem.remove();
        });

        fileListContainer.appendChild(fileItem);

        // Simulated progress animation
        let progress = 0;
        const interval = setInterval(() => {
            progress += 10;
            fileItem.querySelector('.progress').style.width = progress + '%';
            if (progress >= 100) clearInterval(interval);
        }, 200);
    });
});
document.getElementById('sendemail').addEventListener('click', async (e) => {
    e.preventDefault();

    const form = document.getElementById('uploadform');
    const formData = new FormData(form);

    try {
        const response = await fetch('/api/send-email', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                //'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        console.log('Response status:', response.status);
        //console.log('Raw response object:', response);
        console.log('Parsed JSON:', data);
        if (response.ok) {
            alert('✅ ' + data.message);
        } else {
            alert('❌ ' + (data.message || 'Something went wrong'));
        }
    } catch (error) {
        console.error(error);
        alert('⚠️ Error sending email');
    }
});