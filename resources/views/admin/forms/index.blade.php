




@extends('layouts.admin')
@section('content')

<head>
    @vite(['resources/css/app.css', 'resources/js/status.js', 'resources/js/adminform.js'])

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://unpkg.com/flowbite@latest/dist/flowbite.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" defer></script>
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
      <option selected="" value="">Zgjidh një status</option>
      <option value=""></option>
    </select>
  </div> 
    

  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-2">
  <select id="users" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3  text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option selected="" value="">Zgjidh një përdorues</option>
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



<div class="relative inline-block text-left z-50 overflow-visible">
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
        <a href="#" onclick="exportTableToCSV('remotive_data.csv')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Export CSV</a>
      </li>
      <li>
        <a href="#"  onclick="exportTableToPDF()" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Export PDF</a>
      </li>
      <li>
        <a href="#" onclick="exportTableToExcel('remotive_data.xls')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Export Excel</a>
      </li>
      <li>
        <a href="#" onclick="exportTableToTXT('remotive_data.txt')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Export TXT</a>
      </li>
      <li>
        <a href="#" onclick="exportTableToJSON('remotive_data.json')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Export JSON</a>
      </li>
      <li>
        <a href="#" onclick="exportTableToSQL('remotive_data.sql')" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Export SQL</a>
      </li>
    </ul>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>





<div class="flex items-center justify-between bg-white px-4 py-3 border-b border-gray-200 rounded-t-lg shadow-sm dark:bg-gray-800"> 
    <!-- Entries per page selector -->
    <div class="flex items-center space-x-2">
      <label for="entries" class="font-semibold text-gray-600 dark:text-gray-400">Show</label>
      <select id="entries"
        class="block w-20 rounded-md text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm dark:bg-gray-800">
        <option value="5" class="font-semibold text-gray-600 dark:text-gray-400">5</option>
        <option value="10" selected class="font-semibold text-gray-600 dark:text-gray-400">10</option>
        <option value="15" class="font-semibold text-gray-600 dark:text-gray-400">15</option>
        <option value="20" class="font-semibold text-gray-600 dark:text-gray-400">20</option>
        <option value="25" class="font-semibold text-gray-600 dark:text-gray-400">25</option>
      </select>
      <span class="font-semibold text-gray-600 dark:text-gray-400">entries per page</span> 
    </div>

    <!-- Optional search box -->
    <div class="relative dark:bg-gray-800">
      <input type="text" placeholder="Search..."
        class="block w-48 rounded-md text-sm pl-8 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm dark:bg-gray-800">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
           stroke-width="1.5" stroke="currentColor"
           class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
      </svg>
    </div>
</div>

  <!--tabela--> 
      <table id="remotiveTable" class="w-full whitespace-no-wrap">
    <thead>
      <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">ID</th>
            <th class="px-4 py-3">User ID</th>
            <th class="px-4 py-3">Status ID</th>
            <th class="px-4 py-3">Date</th>
            <th class="px-4 py-3">Created At</th>
            <th class="px-4 py-3">Updated At</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
    </tbody>
</table>


<!--pagination--> 
<div class="flex flex-col sm:flex-row items-center justify-between bg-white px-4 py-3 border-t border-gray-200 rounded-b-lg shadow-sm dark:bg-gray-800">
  <!-- Info text -->
  <div class="font-semibold text-gray-600 dark:text-gray-400">
    Showing <span class="font-semibold text-gray-600 dark:text-gray-400">1</span> to <span class="font-semibold text-gray-600 dark:text-gray-400">10</span> of <span class="font-semibold text-gray-600 dark:text-gray-400">21</span> entries
  </div>


  <!--pagination-->
<nav class="datatable-pagination">
      <ul class="datatable-pagination-list flex space-x-2">
        <li class="datatable-pagination-list-item datatable-hidden datatable-disabled">
            <button data-page="1" class="datatable-pagination-list-item-link font-semibold text-gray-600 dark:text-gray-400" aria-label="Page 1"><</button>
        </li>
        <li class="datatable-pagination-list-item datatable-active">
            <button data-page="1" class="datatable-pagination-list-item-link font-semibold text-gray-600 dark:text-gray-400" aria-label="Page 1">1</button>
        </li>
        <li class="datatable-pagination-list-item">
            <button data-page="2" class="datatable-pagination-list-item-link font-semibold text-gray-600 dark:text-gray-400" aria-label="Page 2">2</button>
        </li>
        <li class="datatable-pagination-list-item">
        <button data-page="3" class="datatable-pagination-list-item-link font-semibold text-gray-600 dark:text-gray-400" aria-label="Page 3">3</button>
        </li>
        <li class="datatable-pagination-list-item">
        <button data-page="2" class="datatable-pagination-list-item-link font-semibold text-gray-600 dark:text-gray-400" aria-label="Page 2">></button>
       </li>
     </ul>
</nav>





</div>



</div>






@endsection