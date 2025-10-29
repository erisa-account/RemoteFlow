import Swal from 'sweetalert2'; 
let remotiveTable;


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
            if ($.fn.DataTable.isDataTable('#remotiveTable')) {
                      remotiveTable.clear().destroy();
                  } 
            tableBody.innerHTML = '';
      
            data.data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.id}</td>
                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.user_name}</td>
                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.status_name}</td>
                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.date}</td>
                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.created_at}</td>
                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.updated_at}</td>
                `;
                tableBody.appendChild(row);
            });
            
             remotiveTable = $('#remotiveTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                language: {
                search: "",
                searchPlaceholder: "Search...",
                paginate: {
                  previous: "‹",
                  next: "›"
                }
              },
              });

            })  
        .catch(error => {
         console.error('Error fetching remotive data:', error);
    });  
    document.getElementById('tablewrap').classList.add('hidden');

});





  const table = document.querySelector('#remotiveTable');
  const tableBody = table.querySelector('tbody');




  // 🔹 Hide table initially
  table.style.display = 'none';    



document.getElementById('apply').addEventListener('click', async function () {
  document.getElementById('tablewrap').classList.remove('hidden');
  document.getElementById('exportButton').classList.remove('hidden');
      document.getElementById('remotiveTable_length').classList.remove('hidden');
      document.getElementById('remotiveTable_filter').classList.remove('hidden');
      document.getElementById('remotiveTable_info').classList.remove('hidden');
      document.getElementById('remotiveTable_paginate').classList.remove('hidden');



  const userSelect = document.getElementById('users');
  const statusSelect = document.getElementById('status');
  const presetSelect = document.getElementById('preset');
  
  const startDateInput = document.getElementById('start_date');
  const endDateInput = document.getElementById('end_date');

  const user_id = userSelect.value; 
  const status_id = statusSelect.value;
  const preset = presetSelect.value; 

  // Also get the readable text (not just IDs)
  const user_name = userSelect.options[userSelect.selectedIndex]?.text || '';
  const status_name = statusSelect.options[statusSelect.selectedIndex]?.text || '';
  
   const presetMap = {
    'yesterday': 'Dje',
    '7': '7 ditët e fundit',
    '30': '30 ditët e fundit',
    'last_week': 'Java e kaluar',
    'last_month': 'Muaji i kaluar',
    'last_year': 'Viti i kaluar'                          
  };

  //  Handle preset name dynamically
  let preset_name = presetMap[preset] || '';

   if (preset === 'custom') {
    const start = startDateInput.value;
    const end = endDateInput.value;
    if (start && end) {
      preset_name = `${start} deri më ${end}`;
    } else if (start) {
      preset_name = `${start}`;
    } else if (end) {
      preset_name = `${end}`;
    } else {
      preset_name = 'datë e zgjedhur';
    }
  }

  // 🧾 Include date range in request if custom
  const params = new URLSearchParams({
  user_id,
  status_id,
  preset
  });

  // Only send start/end dates if custom preset
  if (preset === 'custom') { 
  function formatDateToISO(dateStr) {
  if (!dateStr) return '';
  const parts = dateStr.split(/[\/\-]/); // handle both / and -
  if (parts[2].length === 4) {
    // format is DD/MM/YYYY or DD-MM-YYYY
    return `${parts[2]}-${parts[1]}-${parts[0]}`;
  }
  return dateStr; // already correct
  }
  
  const start = formatDateToISO(startDateInput.value);
  const end = formatDateToISO(endDateInput.value);
  if (start) params.append('start_date', start);
  if (end) params.append('end_date', end); 
  }
  console.log('🔍 Sending params:', params.toString());

    try {
    const response = await fetch('/api/remotive-table/filter?' + params.toString());
    const data = await response.json();

    //const table = document.querySelector('#remotiveTable');
    //const tableBody = table.querySelector('tbody');
    //const tableHeader = table.querySelector('thead');

    tableBody.innerHTML = ''; // clear old rows

      if (!data.data || data.data.length === 0) {
        tableBody.innerHTML = '';
      
      let message = 'Nuk ka të dhëna për filtrimin që keni bërë.';

      // 👇 Build a smarter message based on selected filters
      if (preset && status_name && user_name) {
        message = `Nuk ka përdorues me emrin "${user_name}" me statusin "${status_name}" në datën "${preset_name}".`;
      } else if (preset && status_name) {
        message = `Nuk ka status "${status_name}" në datën "${preset_name}".`;
      } else if (preset && user_name) {
        message = `Nuk ka të dhëna për përdoruesin "${user_name}" në datën "${preset_name}".`;
      } else if (status_name && user_name) {
        message = `Nuk ka të dhëna për përdoruesin "${user_name}" me status "${status_name}".`;
      } else if (status_name) {
        message = `Nuk ka të dhëna për statusin "${status_name}".`;
      } else if (user_name) {
        message = `Nuk ka të dhëna për përdoruesin "${user_name}".`;
      } else if (preset_name) {
        message = `Nuk ka të dhëna për datën "${preset_name}".`;
      }
      
      table.style.display = 'none';
        
      document.getElementById('exportButton').classList.add('hidden');
      document.getElementById('remotiveTable_length').classList.add('hidden');
      document.getElementById('remotiveTable_filter').classList.add('hidden');
      document.getElementById('remotiveTable_info').classList.add('hidden');
      document.getElementById('remotiveTable_paginate').classList.add('hidden');

      await Swal.fire({
        title: 'Pa të dhëna!',
        text: message,
        icon: 'info',
        confirmButtonText: 'OK'
      });
      return;
    }

    table.style.display = 'table';

    if ($.fn.DataTable.isDataTable('#remotiveTable')) {
        remotiveTable.clear().destroy();
    }
    tableBody.innerHTML = "";
     data.data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.id}</td>
                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.user_name}</td>
                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.status_name}</td>
                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.date}</td>
                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.created_at}</td>
                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">${item.updated_at}</td>
            `;
             tableBody.appendChild(row);
        });
        remotiveTable = $('#remotiveTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
      });
          }
    catch(error) {console.error('Error fetching data:', error);
      await Swal.fire({
      title: 'Gabim!',
      text: 'Gabim gjatë marrjes së të dhënave nga serveri.',
      icon: 'error',
      confirmButtonText: 'OK'
    });
  }
}); 





const exportBtn = document.getElementById('exportDropdownButton');
  const exportMenu = document.getElementById('exportDropdownMenu');

  exportBtn.addEventListener('click', () => {
    exportMenu.classList.toggle('hidden');
  });

  document.addEventListener('click', (event) => {
    if (!exportBtn.contains(event.target) && !exportMenu.contains(event.target)) {
      exportMenu.classList.add('hidden');
    }
  });

$('#exportDropdownMenu a').on('click', async function(e) {
   if ($(this).hasClass('backend-export')) return; 
    e.preventDefault(); // prevent # navigation
    const format = $(this).data('export');

    if (!remotiveTable || remotiveTable.rows().count() === 0) {
        await Swal.fire({
            title: 'Pa të dhëna për eksport',
            text: 'Nuk ka të dhëna për t\'u eksportuar me filtrat aktualë.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
        exportMenu.classList.add('hidden'); // close dropdown anyway
        return;
    }


    switch(format) {
        case 'csv': exportTableToCSV('remotive_data.csv'); break;
        case 'excel': exportTableToExcel('remotive_data.xls'); break;
        case 'pdf': exportTableToPDF(); break;
        case 'txt': exportTableToTXT('remotive_data.txt'); break;
        case 'json': exportTableToJSON('remotive_data.json'); break;
        case 'sql': exportTableToSQL('remotive_data.sql'); break;
    }

    exportMenu.classList.add('hidden'); // close dropdown after click
});
  
// --- Export functions (using jQuery DataTable instance) ---
function downloadBlob(content, filename, type) {
    const blob = new Blob([content], { type });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = filename;
    a.click();
    URL.revokeObjectURL(a.href);
}

function exportTableToCSV(filename) {
    const rows = remotiveTable.rows({ search: 'applied' }).nodes();
    const csv = [];
    $(rows).each(function() {
        const rowData = [];
        $(this).find('td').each(function() {
            rowData.push($(this).text().trim());
        });
        csv.push(rowData.join(','));
    });
    downloadBlob(csv.join('\n'), filename, 'text/csv');
}

function exportTableToTXT(filename) {
    const rows = remotiveTable.rows({ search: 'applied' }).nodes();
    const txt = [];
    $(rows).each(function() {
        const rowData = [];
        $(this).find('td').each(function() {
            rowData.push($(this).text().trim());
        });
        txt.push(rowData.join(' | '));
    });
    downloadBlob(txt.join('\n'), filename, 'text/plain');
}

function exportTableToJSON(filename) {
    const rows = remotiveTable.rows({ search: 'applied' }).nodes();
    const headers = $('#remotiveTable thead th').map(function(){ return $(this).text().trim(); }).get();
    const data = [];
    $(rows).each(function() {
        const obj = {};
        $(this).find('td').each(function(i) { obj[headers[i]] = $(this).text().trim(); });
        data.push(obj);
    });
    downloadBlob(JSON.stringify(data, null, 2), filename, 'application/json');
}

function exportTableToExcel(filename) {
    const tableHtml = remotiveTable.table().node().outerHTML;
    downloadBlob(tableHtml, filename, 'application/vnd.ms-excel');
}

function exportTableToSQL(filename) {
    const rows = remotiveTable.rows({ search: 'applied' }).nodes();
    const columns = $('#remotiveTable thead th').map(function(){ return $(this).text().trim(); }).get();
    let sql = `CREATE TABLE remotive (${columns.join(", ")});\n`;
    $(rows).each(function() {
        const values = $(this).find('td').map(function(){ return `'${$(this).text().trim().replace(/'/g,"''")}'`; }).get();
        sql += `INSERT INTO remotive (${columns.join(", ")}) VALUES (${values.join(", ")});\n`;
    });
    downloadBlob(sql, filename, 'text/sql');
}

// PDF export requires jsPDF and AutoTable
function exportTableToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const rows = remotiveTable.rows({ search: 'applied' }).nodes();
    const data = [];
    $(rows).each(function() {
        data.push($(this).find('td').map(function(){ return $(this).text().trim(); }).get());
    });
    const columns = $('#remotiveTable thead th').map(function(){ return $(this).text().trim(); }).get();
    doc.autoTable({ head: [columns], body: data });
    doc.save('remotive_data.pdf');
}




window.exportTableToCSV = exportTableToCSV;
window.exportTableToExcel = exportTableToExcel;
window.exportTableToPDF = exportTableToPDF;
window.exportTableToSQL = exportTableToSQL;
window.exportTableToJSON = exportTableToJSON;
window.exportTableToTXT = exportTableToTXT;
 


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

