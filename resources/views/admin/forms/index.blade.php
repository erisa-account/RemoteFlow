




@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="w-full max-w-md mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4 mt-6">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-2">
    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Date range selector</h2>
    <!--<select id="preset" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option value="yesterday">Yesterday</option>
      <option value="7">Last 7 days</option>
      <option value="30">Last 30 days</option>
      <option value="last_week">Last week</option>
      <option value="last_month">Last month</option>
      <option value="last_year">Last year</option>
      <option value="custom">Custom</option>
    </select>-->
    <select id="preset" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option value="yesterday">Yesterday</option>
      <option value="7">Last 7 days</option>
      <option value="30">Last 30 days</option>
      <option value="last_week">Last week</option>
      <option value="last_month">Last month</option>
      <option value="last_year">Last year</option>
      <option value="custom">Custom</option>
    </select>
  </div>

  <!-- Date inputs -->
  <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 mb-4">
    <div class="flex flex-col w-full sm:w-1/2">
      <label for="start_date" class="text-sm text-gray-600 dark:text-gray-300 mb-1">Start date</label>
      <input id="start_date" type="date" value="2024-10-17" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
    </div>

    <span class="text-gray-500 dark:text-gray-400 self-center hidden sm:block">→</span>

    <div class="flex flex-col w-full sm:w-1/2">
      <label for="end_date" class="text-sm text-gray-600 dark:text-gray-300 mb-1">End date</label>
      <input id="end_date" type="date" value="2024-10-23" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
    </div>
  </div>


  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-2">
  <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option>All</option>
      <option>Remote</option>
      <option>On-site</option>
      <option>Me leje</option>
    </select>
  </div>
    

  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-2">
  <select class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option>User</option>
      <option>Remote</option>
      <option>On-site</option>
      <option>Me leje</option>
    </select>
  </div>
    



  
  <!-- Button -->
  <div class="flex justify-end">
    <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500">
      Apply
    </button>
  </div>
</div>

<script>
  // Disable compare select if checkbox is not checked
  /*const compareCheckbox = document.getElementById('compare');
  const compareSelect = document.getElementById('compare_select');

  compareSelect.disabled = !compareCheckbox.checked;

  compareCheckbox.addEventListener('change', () => {
    compareSelect.disabled = !compareCheckbox.checked;
  });*/

  
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

</script>

@endsection