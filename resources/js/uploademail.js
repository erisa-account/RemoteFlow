import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function () {
  const fileInput = document.getElementById('file');
  const dropzone = document.getElementById('dropzone');
  const browseBtn = document.getElementById('browseBtn');
  const fileList = document.getElementById('fileList'); 
  const sendEmailBtn = document.getElementById('sendemail');

  // Browse button opens file picker
  browseBtn.addEventListener('click', () => fileInput.click());

  dropzone.addEventListener('click', () => fileInput.click()); //clicking the zone to upload files 

  // Drag & Drop handlers
  dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('border-indigo-400', 'bg-indigo-50/40');
  });

  dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('border-indigo-400', 'bg-indigo-50/40');
  });


  dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('border-indigo-400', 'bg-indigo-50/40');
    handleFiles(e.dataTransfer.files);
  });

  // File input change
  fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
  });

  // Function to display selected files with progress
  function handleFiles(files) {
    Array.from(files).forEach((file) => {
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

      // remove button
      fileItem.querySelector('.removeFile').addEventListener('click', () => {
        fileItem.remove();
      });

      fileList.appendChild(fileItem);

      // Simulate upload progress
      let progress = 0;
      const interval = setInterval(() => {
        progress += 10;
        fileItem.querySelector('.progress').style.width = progress + '%';
        if (progress >= 100) clearInterval(interval);
      }, 200);
    });
  }

  // Handle Send email button
  sendEmailBtn.addEventListener('click', async (e) => {
    e.preventDefault();

    const form = document.getElementById('uploadform');
    const formData = new FormData(form);

    try {
      const response = await fetch('/api/send-email', {
        method: 'POST',
        body: formData,
        headers: {
          Accept: 'application/json',
          // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
      }); 

      const data = await response.json();
      if (response.ok) {
       
      await Swal.fire({
        icon: 'success',
        title: 'Success',
        text: data.data.message,
        confirmButtonText: 'OK',
      });

      window.location.reload();

      }

      else {
        await Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.data.message || 'Something went wrong',
          confirmButtonText: 'OK',
        });
      }
    } catch (error) {
      console.error(error);
      await Swal.fire({
        icon: 'warning',
        title: 'Network Error',
        text: 'Error sending mail',
        confirmButtonText: 'OK',
      });
    }

  });
});




