




@extends('layouts.admin')
@section('content')

<head>
    @vite(['resources/css/app.css', 'resources/js/status.js', 'resources/js/adminform.js'])

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwind.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">




<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://unpkg.com/flowbite@latest/dist/flowbite.js"></script> 

</head> 

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
  <select id="status" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option selected="" value="">Select the status</option>
      <option value=""></option>
    </select>
  </div> 
    

  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-2">
  <select id="users" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3  text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option selected="" value="">Select the user</option>
      <option value=""></option>
    </select>
  </div> 
    



  
  <!-- Button -->
  <div class="flex justify-end">
    <button id="apply" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500">
      Apply
    </button>
  </div>
</div>


<div id="tablewrap"> 

<div id="exportButton" class="relative inline-block text-left z-50 overflow-visible">
  <!-- Export Button -->
  <button id="exportDropdownButton" type="button"
    class=" flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-indigo-700 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
    Export as
    <svg class="-me-0.5 ms-1.5 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
      height="24" fill="none" viewBox="0 0 24 24">
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="m19 9-7 7-7-7" />
    </svg>
  </button>

  <!-- Dropdown Menu --> 
  <div id="exportDropdownMenu"
    class="hidden  right-0 mt-2 w-40 origin-top-right rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
      <li>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700" data-export="csv">Export CSV</a>
      </li>
      <li>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700" data-export = "pdf" >Export PDF</a>
      </li>
      <li>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700" data-export="excel">Export Excel</a>
      </li>
      <li>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700" data-export="txt">Export TXT</a>
      </li>
      <li>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700" data-export="json">Export JSON</a>
      </li>
      <li>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700" data-export="sql">Export SQL</a>
      </li>
      <li>
        <a  href="{{ route('admin.admin.remotive.exportStatusCalendar') }}"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 backend-export" id="exportCustomExcel">Export Excel customised</a>
      </li>
    </ul>
  </div>
</div>



<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<table id="remotiveTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
    <thead class="bg-gray-50 dark:bg-gray-700">
        <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">User</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Created At</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Updated At</th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
        <!-- Rows will be injected here -->
    </tbody>
</table>

</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwind.min.js"></script>





@endsection 