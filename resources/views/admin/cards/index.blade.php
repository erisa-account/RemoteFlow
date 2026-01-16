@extends('layouts.admin')
@section('content')


<head>
    @vite(['resources/css/app.css', 'resources/js/admindashboard.js']) 
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Admin Dashboard • Time Off</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            brand: {50:'#eef6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8'}
          }, 
          boxShadow: {
            soft:'0 1px 2px rgba(16,24,40,.05), 0 1px 3px rgba(16,24,40,.08)',
            card:'0 1px 3px rgba(16,24,40,.08), 0 10px 20px rgba(16,24,40,.06)'
          }
        }
      }
    }
  </script>
</head>
<body class="h-full bg-neutral-50 text-neutral-800">

  <!-- Top strip -->
  <header class="sticky top-0 z-10 bg-white/90 border-b border-neutral-200 backdrop-blur dark:border-neutral-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between hidden">
      <div>
        <p class="text-sm font-medium">Admin Dashboard</p>
        <p class="text-xs text-neutral-500 -mt-0.5">Manage employee time off</p>
      </div>
      <span class="inline-flex items-center gap-1 rounded-md border border-neutral-200 bg-neutral-100 px-2.5 py-1 text-[11px] text-neutral-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-7 9a7 7 0 1 1 14 0v1H5v-1Z"/></svg>
        Administrator
      </span>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Overview title -->
    <section>
      <h2 class="text-xl font-semibold dark:text-white">Overview</h2>
      <p class="text-sm text-neutral-500 dark:text-white/70">Monitor vacation usage and leave requests</p>
    </section>

    <!-- Metrics -->
    <section id="metrics" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></section>

    <!-- Employees -->
    <section class="bg-white dark:bg-gray-800 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-soft">
      <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
        <h3 class="text-sm font-semibold dark:text-gray-200">Employee Overview</h3>
        <p class="text-xs text-neutral-500 dark:text-gray-200">View vacation balances for all employees</p>
      </div>

      <!-- Toolbar -->
      <div class="px-5 py-4 flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
        <div class="w-full md:max-w-md">
          <div class="relative">
            <input id="empSearch" type="text" placeholder="Search employees…" class="w-full rounded-xl border border-neutral-200 bg-white dark:bg-gray-700 dark:border-gray-600 px-3.5 py-2 text-sm shadow-soft dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-400">
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
          </div>
        </div>
        <div class="flex gap-3 hidden">
          <div class="relative">
            <select id="empDept" class="appearance-none rounded-xl border border-neutral-200 bg-white dark:bg-gray-700 dark:border-gray-600 px-3.5 py-2 pr-9 text-sm shadow-soft dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-400">
              <option value="">All Departments</option>
            </select>
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
            </span>
          </div>
          <div class="relative">
            <select id="empSort" class="appearance-none rounded-xl border border-neutral-200 bg-white dark:bg-gray-700 dark:border-gray-600 px-3.5 py-2 pr-9 text-sm shadow-soft dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-400">
              <option value="name">Name</option>
              <option value="remaining">Remaining days</option>
              <option value="total">Total days</option>
            </select>
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
            </span>
          </div>
        </div>
      </div>

      <!-- Cards -->
      <div id="employeeGrid" class="px-5 pb-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5"></div>
    </section>

    <!-- Leave Requests -->
    <section class="bg-white dark:bg-gray-800 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-soft">
      <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center gap-2 text-sm font-semibold dark:text-gray-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M8 2v4M16 2v4M3 10h18M4 6h16a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"/>
          </svg>
          Leave Requests
        </div>
        <p class="text-xs text-neutral-500 dark:text-gray-200">Review and manage employee leave requests</p>

        <div class="flex gap-3 ">
          <div class="relative">
            <select id="empStatus" class="appearance-none rounded-xl border border-neutral-200 bg-white dark:bg-gray-700 dark:border-gray-600 px-3.5 py-2 pr-9 text-sm shadow-soft dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-400">
              <option value="">Filter based on status</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>


            
          </div>
          <div class="relative">
            <select id="empName" class="appearance-none rounded-xl border border-neutral-200 bg-white dark:bg-gray-700 dark:border-gray-600 px-3.5 py-2 pr-9 text-sm shadow-soft dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-400">
              <option value="">Filter based on user name</option>
              <option value=""></option>
            </select>

            
          </div>

          <div class="relative hidden">
            <select id="empTime" class="appearance-none rounded-xl border border-neutral-200 bg-white dark:bg-gray-700 dark:border-gray-600 px-3.5 py-2 pr-9 text-sm shadow-soft dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-400">
              <option value="">Filter based on time</option>
              <option value=""></option>
            </select>
          </div>

          <div x-data="{ open: false, startingDate: '', endingDate: '' }" @click.outside="open = false" class="relative appearance-none rounded-xl border border-neutral-200  
          bg-white dark:bg-gray-700 dark:border-gray-600 px-3.5 py-2  text-sm shadow-soft dark:text-gray-200 focus:outline-none 
          focus:ring-2 focus:ring-brand-400 w-full md:w-96">
    <!-- Display current start date -->
    <div class="flex justify-between items-center w-full">
        <span class="flex items-center space-x-2 flex-1 ">
            <span>Filter based on time</span>
            <span
            x-html="
        startingDate && endingDate 
        ? `<span class='font-semibold'>${startingDate}</span> to <span class='font-semibold'>${endingDate}</span>` 
        : 'Not set'
    "></span>
        </span>
        <!-- Edit button -->
        <button @click="open = true" class="px-2 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600 ml-2">
            Edit
        </button>
    </div>

    <!-- Inline form, shown when editing -->
    <div x-show="open" x-transition=""  class="absolute top-full left-0 mt-1 bg-white text-black rounded shadow p-3 w-full z-10" style="display: none;">
        <form  @submit.prevent="console.log(startingDate, endingDate)" id="filtertime">
                        
            <label class="block text-sm font-medium mb-1" for="starting_date">From</label>
            <input type="date" name="starting_date" id="starting_date" x-ref="starting_date" x-model="startingDate"  class="border rounded p-2 w-full mb-3">

             
            
            <label class="block text-sm font-medium mb-1" for="ending_date">To</label>
            <input type="date" name="ending_date" id="ending_date" x-ref="ending_date" x-model="endingDate"  class="border rounded p-2 w-full mb-3">
                   
            <div class="flex justify-end">
                <button type="button" id="clearBtn"  @click="open = false"
                class="px-3 py-1 mr-2 text-sm border rounded hover:bg-gray-100">
                    Clear
                </button>
            </div>
        </form>
    </div>
</div>

        </div>
        

      </div>
      
      

      <div id="requestList" class="p-5 space-y-3"></div>

      <!-- Footer actions for bulk approve/reject if you want -->
      <!-- <div class="px-5 pb-5 flex justify-end gap-2">
        <button class="rounded-xl bg-emerald-600 text-white text-sm px-4 py-2 shadow-soft">Approve All</button>
        <button class="rounded-xl bg-rose-600 text-white text-sm px-4 py-2 shadow-soft">Reject All</button>
      </div> -->
    </section>
  </main>

  
</body>

@endsection