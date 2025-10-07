document.addEventListener("DOMContentLoaded", function () {
  fetch('/api/users')
    .then(response => response.json())
    .then(data => {
      const userSelect = document.getElementById('users');
      userSelect.innerHTML = '<option value="">Zgjidh një përdorues</option>';

      // 👇 Access the actual array inside "data"
      data.data.forEach(user => {
        const option = document.createElement('option');
        option.value = user.id;
        option.textContent = user.name;
        userSelect.appendChild(option);
      });
    })
    .catch(error => {
      console.error('Gabim gjatë marrjes së përdoruesve:', error);
    });
});


function formatDate(dateString) {
    const date = new Date(dateString);
    // Format: YYYY-MM-DD HH:mm
    return `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}`;
}

function pad(n) {
    return n.toString().padStart(2, '0');
}
document.addEventListener("DOMContentLoaded", function () {
  fetch('/api/remotive-table')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#remotiveTable tbody');
            data.data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <tr class="text-gray-700 dark:text-gray-400"><td class="px-4 py-3"><p class="text-xs text-gray-600 dark:text-gray-400">${item.id}</p></td></tr>
                    <tr class="text-gray-700 dark:text-gray-400"><td class="px-4 py-3"><p class="text-xs text-gray-600 dark:text-gray-400">${item.user_id}</p></td></tr>
                    <tr class="text-gray-700 dark:text-gray-400"><td class="px-4 py-3"><p class="text-xs text-gray-600 dark:text-gray-400">${item.status_id}</p></td></tr>
                    <tr class="text-gray-700 dark:text-gray-400"><td class="px-4 py-3"><p class="text-xs text-gray-600 dark:text-gray-400">${formatDate(item.date)}<p></td></tr>
                    <tr class="text-gray-700 dark:text-gray-400"><td class="px-4 py-3"><p class="text-xs text-gray-600 dark:text-gray-400">${formatDate(item.created_at)}</p></td></tr>
                    <tr class="text-gray-700 dark:text-gray-400"><td class="px-4 py-3"><p class="text-xs text-gray-600 dark:text-gray-400">${formatDate(item.updated_at)}</p></td></tr>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching remotive data:', error);
    });
  }); 
   
 
 
 const startPicker = flatpickr("#start_date", { dateFormat: "d/m/Y" });
  const endPicker = flatpickr("#end_date", { dateFormat: "d/m/Y" });

  const preset = document.getElementById('preset');

  function formatDateToFlatpickr(date) {
    // Flatpickr can parse Date object directly
    return date;
  }

  function setDates(option) {
    const today = new Date();
    let start = today;
    let end = today;

    switch(option) {
      case 'yesterday':
        start = new Date(today); start.setDate(today.getDate() - 1);
        end = new Date(today); end.setDate(today.getDate() - 1);
        break;
      case '7':
        start = new Date(today); start.setDate(today.getDate() - 6);
        break;
      case '30':
        start = new Date(today); start.setDate(today.getDate() - 29);
        break;
      case 'last_week':
        const dayOfWeek = today.getDay();
        start = new Date(today); start.setDate(today.getDate() - dayOfWeek - 6);
        end = new Date(today); end.setDate(today.getDate() - dayOfWeek);
        break;
      case 'last_month':
        start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        end = new Date(today.getFullYear(), today.getMonth(), 0);
        break;
      case 'last_year':
        start = new Date(today.getFullYear() - 1, 0, 1);
        end = new Date(today.getFullYear() - 1, 11, 31);
        break;
      case 'custom':
        start = null; end = null;
        break;
    }

    if(start) startPicker.setDate(start, true, "d/m/Y");
    if(end) endPicker.setDate(end, true, "d/m/Y");
  }

  // Initialize with Last 7 days
  setDates('7');

  // Change dates on preset change
  preset.addEventListener('change', (e) => {
    setDates(e.target.value);
  });

  