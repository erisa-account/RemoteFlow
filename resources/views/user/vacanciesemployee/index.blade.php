@extends('layouts.user')
@section('content')

<head>
     
    @vite(['resources/css/app.css', 'resources/js/leaverequest.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
  <title>Vacation Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            brand: {50:'#eef6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8'},
          },
          boxShadow: {
            soft: '0 1px 2px rgba(16,24,40,.05), 0 1px 3px rgba(16,24,40,.08)',
            modal: '0 40px 120px rgba(0,0,0,.25), 0 12px 32px rgba(0,0,0,.18), 0 2px 6px rgba(0,0,0,.12)'
          }
        }
      }
    }
  </script>
</head>
<body class="h-full bg-neutral-50 text-neutral-800 dark:bg-neutral-900 dark:text-neutral-100">
  <!-- Header -->
  <header class=" top-0 bg-white/90 dark:bg-gray-800 backdrop-blur border-b border-neutral-200 dark:border-neutral-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
      <div>
        
      </div>

      <div class="flex items-center gap-2">
        <!-- Theme toggle -->
        <button id="themeToggle" class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-2.5 py-2 shadow-soft hover:bg-neutral-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-brand-400 hidden " title="Toggle dark mode" aria-label="Toggle dark mode">
          <span id="themeIcon" class="inline-block">
             <!--moon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden dark:block" viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79z"/></svg>
             <!--sun -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 dark:hidden" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Zm0 4a1 1 0 0 1-1-1v-1a1 1 0 1 1 2 0v1a1 1 0 0 1-1 1Zm0-18a1 1 0 0 1-1-1V2a1 1 0 1 1 2 0v1a1 1 0 0 1-1 1Zm10 9a1 1 0 0 1-1-1h-1a1 1 0 1 1 0-2h1a1 1 0 1 1 2 0 1 1 0 0 1-1 1ZM4 13a1 1 0 0 1 0-2H3a1 1 0 1 1 0-2h1a1 1 0 1 1 0 2H3a1 1 0 1 1 0 2h1Zm13.657 6.243a1 1 0 0 1-1.414 1.414l-.707-.707a1 1 0 0 1 1.414-1.414l.707.707ZM7.464 5.05A1 1 0 0 1 6.05 6.464l-.707-.707A1 1 0 1 1 6.757 4.05l.707.707Zm10.193-1a1 1 0 1 1 1.415 1.413l-.708.707A1 1 0 0 1 17.65 4.05l.707-.707ZM5.343 18.657l.707-.707A1 1 0 0 1 7.464 19.364l-.707.707a1 1 0 1 1-1.414-1.414Z"/></svg>
          </span>
        </button>

         <div class="flex items-center justify-center">
        <button
          type="button"
          id="statusInfoBtn"
          class="inline-flex items-center justify-center w-6 h-6 ml-2
                text-sm font-bold text-white
                bg-green-500 rounded-full
                hover:bg-green-600 focus:outline-none"
          aria-label="Status information"
        >
          i
        </button>
            </div>


<div x-data="{ open: false, startingDate: '{{ auth()->user()->leaveBalance?->starting_date?->format('Y-m-d') }}' }" 
     class="relative inline-flex items-center gap-2 rounded-xl bg-brand-400 px-3.5 py-2 text-white text-sm font-medium dark:text-gray-200 shadow-soft hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-400 w-80">

    <!-- Display current start date -->
    <div class="flex justify-between items-center w-full">
        <span>
            <strong>Company Start Date:</strong>
            <span x-text="startingDate ? startingDate : 'Not set'"></span>
        </span>
        <!-- Edit button -->
        <button @click="open = true" class="px-2 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
            Edit
        </button>
    </div>

    <!-- Inline form, shown when editing -->
    <div x-show="open" x-transition class="absolute top-full left-0 mt-1 bg-white text-black rounded shadow p-3 w-full z-10">
        <form 
            method="POST"
            action="{{ route('user.starting-date') }}"
            @submit="open = false"
        >
            @csrf
            <label class="block text-sm font-medium mb-1" for="starting_date">Start Date</label>
            <input type="date" name="starting_date" x-model="startingDate" 
            value="{{ auth()->user()->leaveBalance?->starting_date?->format('Y-m-d') }}"
                   class="border rounded p-2 w-full mb-3">
                   
            <div class="flex justify-end">
                <button type="button" @click="open = false" 
                        class="px-3 py-1 mr-2 text-sm border rounded hover:bg-gray-100">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-3 py-1 text-sm text-white bg-green-500 rounded hover:bg-green-600">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

        <button id="requestLeaveBtn"
          class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-3.5 py-2 text-white text-sm font-medium dark:text-gray-200 shadow-soft hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
          Request Leave
        </button>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <section>
      <h2 class="text-lg font-semibold dark:text-gray-200">Your Vacation Balance</h2>
      <p class="text-sm text-neutral-500 dark:text-neutral-400">Track your time off</p>
    </section>

    <!-- KPIs -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-soft border border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Total Days</p>
          <div class="h-9 w-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center dark:bg-brand-500/10 dark:text-brand-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4M16 2v4M3 10h18M4 6h16a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"/></svg>
          </div>
        </div>
        <p id="totalDays" class="mt-3 text-3xl font-bold tracking-tight dark:text-gray-200"></p>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-soft border border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Used Days</p>
          <div class="h-9 w-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center dark:bg-rose-500/10 dark:text-rose-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
          </div>
        </div>
        <p id="usedDays" class="mt-3 text-3xl font-bold tracking-tight dark:text-gray-200"></p>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-soft border border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Remaining Days</p>
          <div class="h-9 w-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center dark:bg-emerald-500/10 dark:text-emerald-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17l6-6 4 4 7-7"/><path d="M14 5h7v7"/></svg>
          </div>
        </div>
        <p id="remainingDays" class="mt-3 text-3xl font-bold tracking-tight dark:text-gray-200"></p>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-soft border border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Forwarded days</p>
          <div class="flex items-center justify-center">
        <button type="button" id="statusInfoBtnForwarded" class="inline-flex items-center justify-center w-6 h-6 ml-2
                text-sm font-bold text-white
                bg-green-500 rounded-full
                hover:bg-green-600 focus:outline-none" aria-label="Status information">
          i
        </button>
            </div>
          <div class="h-9 w-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center dark:bg-emerald-500/10 dark:text-emerald-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17l6-6 4 4 7-7"/><path d="M14 5h7v7"/></svg>
          </div>
        </div>
        <p id="forwardedDays" class="mt-3 text-3xl font-bold tracking-tight dark:text-gray-200"></p>
      </div>

       
      <div class="hidden lg:block"></div>
    </section>

    <!-- Calendar + Sidebar -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Calendar card -->
      <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-soft">
        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
          <h3 class="text-sm font-semibold dark:text-gray-200">Vacation Calendar</h3>
          <p class="text-xs text-neutral-500 dark:text-neutral-400">View your scheduled time off</p>
        </div>

        <div class="px-5 py-4 flex items-center justify-between">
          <span id="monthLabel" class="text-sm font-medium dark:text-gray-200">October 2025</span>
          <div class="flex items-center gap-1">
            <div class="hidden sm:flex rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
              <button class="px-2 py-1.5 text-xs hover:bg-neutral-50 dark:hover:bg-neutral-800 focus:outline-none dark:text-gray-200" data-range="year">Year</button>
              <button class="px-2 py-1.5 text-xs bg-neutral-100 dark:bg-neutral-800/60 font-medium dark:text-gray-200" data-range="month">Month</button>
              <button class="px-2 py-1.5 text-xs hover:bg-neutral-50 dark:hover:bg-neutral-800 dark:text-gray-200" data-range="week">Week</button>
            </div>
            <div class="flex rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
              <button id="prevBtn" class="px-2 py-1.5 hover:bg-neutral-50 dark:hover:bg-neutral-800 focus:outline-none dark:text-gray-200" aria-label="Previous">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
              </button>
              <button id="nextBtn" class="px-2 py-1.5 hover:bg-neutral-50 dark:hover:bg-neutral-800 focus:outline-none dark:text-gray-200" aria-label="Next">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Weekdays -->
        <div class="grid grid-cols-7 gap-px px-5 text-center text-xs font-medium text-neutral-500 dark:text-neutral-400">
          <div class="py-2">SUN</div><div class="py-2">MON</div><div class="py-2">TUE</div>
          <div class="py-2">WED</div><div class="py-2">THU</div><div class="py-2">FRI</div><div class="py-2">SAT</div>
        </div>

        <!-- Days -->
        <div id="calendarGrid" class="grid grid-cols-7 gap-2 p-5 pt-3"></div>

        <!-- Legend -->
        <div class="px-5 pb-5">
          <div class="flex flex-wrap items-center gap-5 text-xs">
            <div class="flex items-center gap-2 dark:text-gray-200"><span class="h-2.5 w-2.5 rounded-full bg-brand-500"></span>Vacation</div>
            <div class="flex items-center gap-2 dark:text-gray-200"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>Sick Leave</div>
            <div class="flex items-center gap-2 dark:text-gray-200"><span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>Replacement</div>
            <div class="flex items-center gap-2 dark:text-gray-200"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>Other</div>
            <div class="flex items-center gap-2 dark:text-gray-200"><span class="h-2.5 w-2.5 rounded-full border border-brand-500"></span>Today</div>
          </div>
        </div>
</div>



      <!-- Sidebar -->
      <aside class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-soft">
          <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
            <h4 class="text-sm font-semibold dark:text-gray-200">Leave Types</h4>
          </div>
          <div class="p-4 space-y-3 text-sm">
            <div class="flex items-center justify-between"><div class="flex items-center gap-2 dark:text-gray-200"><span class="h-2.5 w-2.5 rounded-full bg-brand-500 "></span>Vacation</div><span class="text-[10px] rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 dark:text-gray-200">Paid</span></div>
            <div class="flex items-center justify-between"><div class="flex items-center gap-2 dark:text-gray-200"><span class="h-2.5 w-2.5 rounded-full bg-rose-500 "></span>Sick Leave</span></div><span class="text-[10px] rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 dark:text-gray-200">85% Paid</span></div>
            <div class="flex items-center justify-between"><div class="flex items-center gap-2 dark:text-gray-200"><span class="h-2.5 w-2.5 rounded-full bg-violet-500 "></span>Replacement</div><span class="text-[10px] rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 dark:text-gray-200">Unpaid</span></div>
            <div class="flex items-center justify-between"><div class="flex items-center gap-2 dark:text-gray-200"><span class="h-2.5 w-2.5 rounded-full bg-amber-500 "></span>Other</div><span class="text-[10px] rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 dark:text-gray-200">Unpaid</span></div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-soft">
          <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
            <h4 class="text-sm font-semibold dark:text-gray-200">Usage Progress</h4>
          </div>
          <div class="p-5 space-y-4">
            <div>
              <div class="flex items-center justify-between text-xs text-neutral-500 dark:text-neutral-400 mb-1">
                <span>2025 Usage</span><span id="usagePct">0%</span>
              </div>
              <div class="h-2 w-full bg-neutral-100 dark:bg-white rounded-full overflow-hidden">
                <div id="usageBar" class="h-full bg-brand-500 rounded-full" style="width:0%"></div>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3 text-xs">
              <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 p-3 dark:bg-gray-700">
                <p class="text-neutral-500 dark:text-neutral-400">Days used</p>
                <p class="mt-1 font-semibold dark:text-gray-200"><span id="usedDaysSmall">0</span> days</p>
              </div>
              <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 p-3 dark:bg-gray-700">
                <p class="text-neutral-500 dark:text-neutral-400">Days remaining</p>
                <p class="mt-1 font-semibold dark:text-gray-200"><span id="remainingDaysSmall">20</span> days</p>
              </div>
            </div>
          </div>
        </div>
      </aside>

      <!-- Leave History -->
 
   <section class="mt-6 bg-white lg:col-span-2 dark:bg-gray-800 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-soft">
  <div class="px-5 pt-5">
    <div class="flex items-center gap-2 text-sm font-semibold dark:text-gray-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-500 dark:text-gray-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M7 21h10a2 2 0 0 0 2-2V8l-5-5H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z"/><path d="M14 3v5h5"/>
      </svg>
      Leave History
    </div>
    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Your recent leave requests and approvals</p>
  </div>

  <!-- Items will be injected here -->
  <div id="leaveHistory" class="p-5 pt-3 space-y-3"></div>

  <!-- Empty state (auto-shown by JS if list is empty) -->
  <div id="leaveHistoryEmpty" class="hidden p-6 pt-3 text-sm text-neutral-500 dark:text-neutral-400">
    No leave requests yet.
  </div>

  <!-- Skeleton (use while you load from API; call showLeaveHistorySkeleton/hide...) -->
  <div id="leaveHistorySkeleton" class="hidden p-5 pt-3 space-y-3">
    <div class="animate-pulse rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 h-20"></div>
    <div class="animate-pulse rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 h-20"></div>
  </div>
</section>

    </section>
  </main>

  <!-- Modal -->
  <div id="modalOverlay" class="fixed inset-0 z-50 hidden">
    <!-- backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" aria-hidden="true"></div>

    <!-- dialog -->
    <div class="relative h-full w-full flex items-start sm:items-center justify-center p-4">
      <div role="dialog" aria-modal="true" aria-labelledby="modalTitle"
        class="w-full max-w-2xl rounded-2xl bg-white dark:bg-gray-800 border border-neutral-200 dark:border-neutral-800 shadow-modal">
        <!-- header -->
        <div class="flex items-start justify-between p-5 border-b border-neutral-200 dark:border-neutral-800">
          <div>
            <h3 id="modalTitle" class="text-lg font-semibold dark:text-gray-200">Request Time Off</h3>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">Submit a new leave request for approval</p>
          </div>
          <button id="closeModal" class="rounded-lg p-2 hover:bg-neutral-100 dark:hover:bg-neutral-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- body -->
        <form id="leaveForm" class="p-5 space-y-4" method="POST" enctype="multipart/form-data" action="/leave-request">
          <!-- Leave type -->
          <div>
            <label class="text-sm font-medium dark:text-gray-200">Leave Type <span class="text-rose-600">*</span></label>
            <div class="relative mt-1">
              <select id="leaveType" name="leave_type_id" class="w-full appearance-none rounded-xl border border-neutral-200 dark:border-neutral-800 dark:text-gray-200 bg-white dark:bg-gray-700 px-3.5 py-2.5 pr-9 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400">
                <option value="1">Vacation</option>
                <option value="2">Sick Leave</option>
                <option value="3">Other</option>
                <option value="4">Replacement</option>
              </select>
              <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
              </span>
            </div>
          </div>

          <!-- Dates -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="startDate" class="text-sm font-medium dark:text-gray-200">Start Date <span class="text-rose-600">*</span></label>
              <div class="relative mt-1">
                <input id="startDate" name="start_date"  type="date" placeholder="dd/mm/yyyy"
                  class="w-full rounded-xl border border-neutral-200 dark:border-neutral-800 dark:text-gray-200 bg-white dark:bg-gray-700 px-3.5 py-2.5 pr-9 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400"/>
                
              </div>
            </div>
            <div>
              <label for="endDate" class="text-sm font-medium dark:text-gray-200">End Date <span class="text-rose-600">*</span></label>
              <div class="relative mt-1">
                <input id="endDate" name="end_date" type="date" placeholder="dd/mm/yyyy"
                  class="w-full rounded-xl border border-neutral-200 dark:border-neutral-800 dark:text-gray-200 bg-white dark:bg-gray-700 px-3.5 py-2.5 pr-9 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400"/>
                
              </div>
            </div>
          </div>

          <!-- Reason -->
          <div>
            <label class="text-sm font-medium dark:text-gray-200">Reason <span class="text-rose-600"></span></label>
            <textarea id="reason" name="reason" rows="4" placeholder="Briefly describe the reason for your leave request..."
              class="mt-1 w-full rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-gray-700 dark:text-gray-200 px-3.5 py-2.5 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400"></textarea>
          </div>

          <!-- Medical certificate (conditional) -->
          <div id="medicalGroup" class="hidden">
            <label class="text-sm font-medium dark:text-gray-200">Medical Certificate <span class="text-rose-600">*</span></label>
            <div id="dropzone"
              class="mt-1 rounded-2xl border-2 border-dashed border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900/40 p-6 text-center hover:bg-neutral-100 dark:hover:bg-neutral-800 transition cursor-pointer">
              <input id="medical_certificate" name="medical_certificate" type="file" accept="application/pdf" class="hidden"/>
              <div class="flex flex-col items-center gap-2">
                <div class="rounded-xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 p-3 shadow-soft">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 16V4m0 0l-4 4m4-4l4 4"/><path d="M20 20H4"/>
                  </svg>
                </div>
                <div class="text-sm">
                  <p class="font-medium">Upload PDF document</p>
                  <p class="text-xs text-neutral-500 dark:text-neutral-400">Click to browse or drag and drop</p>
                </div>
                <div id="fileBadge" class="hidden text-xs rounded-lg bg-neutral-100 dark:bg-neutral-800 px-2 py-1"></div>
              </div>
            </div>
          </div>

          <!-- Errors -->
          <p id="formError" class="text-rose-600 text-sm hidden"></p>

          <!-- footer -->
          <div class="pt-2 flex items-center justify-end gap-3">
            <button type="button" id="cancelModal"
              class="rounded-xl border border-neutral-200 dark:border-neutral-700 dark:text-gray-200 bg-white dark:bg-gray-700 px-4 py-2 text-sm shadow-soft hover:bg-neutral-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-brand-400">Cancel</button>
            <button type="submit" id="submitBtn"
              class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-medium dark:text-gray-200 text-white shadow-soft hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-400 disabled:opacity-60 disabled:cursor-not-allowed">Submit Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  </body>
 
@endsection 