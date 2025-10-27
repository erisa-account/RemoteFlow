import Swal from 'sweetalert2'; 




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
                      $('#remotiveTable').DataTable().clear().destroy();
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
            
             $('#remotiveTable').DataTable({
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
});





  const table = document.querySelector('#remotiveTable');
  const tableBody = table.querySelector('tbody');




  // 🔹 Hide table initially
  table.style.display = 'none';    



document.getElementById('apply').addEventListener('click', async function () {
    

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
        $('#remotiveTable').DataTable().destroy(); // destroy old instance
    }
    
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
        $('#remotiveTable').DataTable({
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


  // ---- EXPORT FUNCTIONS ----

  // CSV
  function exportTableToCSV(filename) {
    const table = document.getElementById("remotiveTable");
    let csv = [];
    const rows = table.querySelectorAll("tr");

    rows.forEach(row => {
      const cols = row.querySelectorAll("td, th");
      const rowData = Array.from(cols).map(col => col.textContent.trim());
      csv.push(rowData.join(","));
    });

    const blob = new Blob([csv.join("\n")], { type: "text/csv" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
  }

  // Excel
  function exportTableToExcel(filename) {
    const table = document.getElementById("remotiveTable").outerHTML;
    const blob = new Blob([table], { type: "application/vnd.ms-excel" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
  }

  // PDF
  function exportTableToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text("Remotive Data", 10, 10);

    const rows = [];
    document.querySelectorAll("#remotiveTable tbody tr").forEach(tr => {
      const cols = tr.querySelectorAll("td");
      rows.push(Array.from(cols).map(td => td.innerText));
    });

    let y = 20;
    rows.forEach(row => {
      doc.text(row.join(" | "), 10, y);
      y += 10;
    });

    doc.save("remotive_data.pdf");
  }

  // SQL
  function exportTableToSQL(filename) {
    const table = document.getElementById("remotiveTable");
    const rows = table.querySelectorAll("tbody tr");
    const columns = Array.from(table.querySelectorAll("thead th")).map(th => th.textContent.trim());
    
    let sql = `CREATE TABLE remotive (${columns.join(", ")});\n`;
    rows.forEach(row => {
      const values = Array.from(row.querySelectorAll("td"))
        .map(td => `'${td.textContent.trim()}'`);
      sql += `INSERT INTO remotive (${columns.join(", ")}) VALUES (${values.join(", ")});\n`;
    });

    const blob = new Blob([sql], { type: "text/sql" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
  }

   //JSON
  function exportTableToJSON(filename) {
  const table = document.getElementById("remotiveTable");
  const rows = table.querySelectorAll("tbody tr");
  const headers = Array.from(table.querySelectorAll("thead th")).map(th => th.textContent.trim());

  const jsonData = [];
  rows.forEach(row => {
    const cells = row.querySelectorAll("td");
    const obj = {};
    cells.forEach((cell, i) => {
      obj[headers[i]] = cell.textContent.trim();
    });
    jsonData.push(obj);
  });

  const blob = new Blob([JSON.stringify(jsonData, null, 2)], { type: "application/json" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);
}


  //TXT
function exportTableToTXT(filename) {
  const table = document.getElementById("remotiveTable");
  const rows = table.querySelectorAll("tr");
  let txtContent = "";

  rows.forEach(row => {
    const cols = row.querySelectorAll("td, th");
    const rowData = Array.from(cols).map(col => col.textContent.trim());
    txtContent += rowData.join(" | ") + "\n";
  });

  const blob = new Blob([txtContent], { type: "text/plain" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = filename;
  a.click();
  URL.revokeObjectURL(url);
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

