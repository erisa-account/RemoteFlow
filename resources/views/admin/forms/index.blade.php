




@extends('layouts.admin')
@section('content')

<head>
    @vite(['resources/css/app.css', 'resources/js/status.js', 'resources/js/adminform.js'])
</head>
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
  <select id="status" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option selected="" value="">Select a status</option>
      <option value=""></option>
    </select>
  </div>
    

  <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-2">
  <select id="users" class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3  text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option selected="" value="">Zgjidhni perdoruesin</option>
      <option value=""></option>
    </select>
  </div>
    



  
  <!-- Button -->
  <div class="flex justify-end">
    <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-500">
      Apply
    </button>
  </div>
</div>



<div class="w-full overflow-hidden rounded-lg shadow-xs max-w-7xl mx-auto my-6 px-6">
      <div class="w-full overflow-x-auto">
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
</div>
</div>

@endsection