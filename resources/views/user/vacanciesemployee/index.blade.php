@extends('layouts.user')
@section('content')

<head>
    @vite(['resources/css/app.css']) 

  
<head>
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
  <header class="sticky top-0 z-10 bg-white/90 dark:bg-neutral-950/80 backdrop-blur border-b border-neutral-200 dark:border-neutral-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
      <div>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 leading-tight">Vacation Management</p>
        <p class="text-xs text-neutral-400 dark:text-neutral-500 -mt-0.5">Welcome, John Doe</p>
      </div>

      <div class="flex items-center gap-2">
        <!-- Theme toggle -->
        <button id="themeToggle" class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-2.5 py-2 shadow-soft hover:bg-neutral-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-brand-400" title="Toggle dark mode" aria-label="Toggle dark mode">
          <span id="themeIcon" class="inline-block">
             <!--moon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden dark:block" viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79z"/></svg>
             <!--sun -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 dark:hidden" viewBox="0 0 24 24" fill="currentColor"><path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Zm0 4a1 1 0 0 1-1-1v-1a1 1 0 1 1 2 0v1a1 1 0 0 1-1 1Zm0-18a1 1 0 0 1-1-1V2a1 1 0 1 1 2 0v1a1 1 0 0 1-1 1Zm10 9a1 1 0 0 1-1-1h-1a1 1 0 1 1 0-2h1a1 1 0 1 1 2 0 1 1 0 0 1-1 1ZM4 13a1 1 0 0 1 0-2H3a1 1 0 1 1 0-2h1a1 1 0 1 1 0 2H3a1 1 0 1 1 0 2h1Zm13.657 6.243a1 1 0 0 1-1.414 1.414l-.707-.707a1 1 0 0 1 1.414-1.414l.707.707ZM7.464 5.05A1 1 0 0 1 6.05 6.464l-.707-.707A1 1 0 1 1 6.757 4.05l.707.707Zm10.193-1a1 1 0 1 1 1.415 1.413l-.708.707A1 1 0 0 1 17.65 4.05l.707-.707ZM5.343 18.657l.707-.707A1 1 0 0 1 7.464 19.364l-.707.707a1 1 0 1 1-1.414-1.414Z"/></svg>
          </span>
        </button>

        <button id="requestLeaveBtn"
          class="inline-flex items-center gap-2 rounded-xl bg-brand-600 px-3.5 py-2 text-white text-sm font-medium shadow-soft hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
          Request Leave
        </button>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <section>
      <h2 class="text-lg font-semibold">Your Vacation Balance</h2>
      <p class="text-sm text-neutral-500 dark:text-neutral-400">Track your time off for 2025</p>
    </section>

    <!-- KPIs -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div class="bg-white dark:bg-neutral-950 rounded-2xl p-5 shadow-soft border border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Total Days</p>
          <div class="h-9 w-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center dark:bg-brand-500/10 dark:text-brand-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4M16 2v4M3 10h18M4 6h16a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"/></svg>
          </div>
        </div>
        <p id="totalDays" class="mt-3 text-3xl font-bold tracking-tight">20</p>
      </div>

      <div class="bg-white dark:bg-neutral-950 rounded-2xl p-5 shadow-soft border border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Used Days</p>
          <div class="h-9 w-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center dark:bg-rose-500/10 dark:text-rose-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
          </div>
        </div>
        <p id="usedDays" class="mt-3 text-3xl font-bold tracking-tight">0</p>
      </div>

      <div class="bg-white dark:bg-neutral-950 rounded-2xl p-5 shadow-soft border border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">Remaining Days</p>
          <div class="h-9 w-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center dark:bg-emerald-500/10 dark:text-emerald-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17l6-6 4 4 7-7"/><path d="M14 5h7v7"/></svg>
          </div>
        </div>
        <p id="remainingDays" class="mt-3 text-3xl font-bold tracking-tight">20</p>
      </div>
      <div class="hidden lg:block"></div>
    </section>

    <!-- Calendar + Sidebar -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Calendar card -->
      <div class="lg:col-span-2 bg-white dark:bg-neutral-950 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-soft">
        <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
          <h3 class="text-sm font-semibold">Vacation Calendar</h3>
          <p class="text-xs text-neutral-500 dark:text-neutral-400">View your scheduled time off</p>
        </div>

        <div class="px-5 py-4 flex items-center justify-between">
          <span id="monthLabel" class="text-sm font-medium">October 2025</span>
          <div class="flex items-center gap-1">
            <div class="hidden sm:flex rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
              <button class="px-2 py-1.5 text-xs hover:bg-neutral-50 dark:hover:bg-neutral-800 focus:outline-none" data-range="year">Year</button>
              <button class="px-2 py-1.5 text-xs bg-neutral-100 dark:bg-neutral-800/60 font-medium" data-range="month">Month</button>
              <button class="px-2 py-1.5 text-xs hover:bg-neutral-50 dark:hover:bg-neutral-800" data-range="week">Week</button>
            </div>
            <div class="flex rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
              <button id="prevBtn" class="px-2 py-1.5 hover:bg-neutral-50 dark:hover:bg-neutral-800 focus:outline-none" aria-label="Previous">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
              </button>
              <button id="nextBtn" class="px-2 py-1.5 hover:bg-neutral-50 dark:hover:bg-neutral-800 focus:outline-none" aria-label="Next">
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
            <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-brand-500"></span>Vacation</div>
            <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>Sick Leave</div>
            <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>Personal</div>
            <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>Unpaid</div>
            <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full border border-brand-500"></span>Today</div>
          </div>
        </div>
</div>



      <!-- Sidebar -->
      <aside class="space-y-6">
        <div class="bg-white dark:bg-neutral-950 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-soft">
          <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
            <h4 class="text-sm font-semibold">Leave Types</h4>
          </div>
          <div class="p-4 space-y-3 text-sm">
            <div class="flex items-center justify-between"><div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-brand-500"></span>Vacation</div><span class="text-[10px] rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5">Paid</span></div>
            <div class="flex items-center justify-between"><div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>Sick Leave</div><span class="text-[10px] rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5">Paid</span></div>
            <div class="flex items-center justify-between"><div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>Personal</div><span class="text-[10px] rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5">Paid</span></div>
            <div class="flex items-center justify-between"><div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>Unpaid</div><span class="text-[10px] rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5">Unpaid</span></div>
          </div>
        </div>

        <div class="bg-white dark:bg-neutral-950 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-soft">
          <div class="p-5 border-b border-neutral-200 dark:border-neutral-800">
            <h4 class="text-sm font-semibold">Usage Progress</h4>
          </div>
          <div class="p-5 space-y-4">
            <div>
              <div class="flex items-center justify-between text-xs text-neutral-500 dark:text-neutral-400 mb-1">
                <span>2025 Usage</span><span id="usagePct">0%</span>
              </div>
              <div class="h-2 w-full bg-neutral-100 dark:bg-neutral-800 rounded-full overflow-hidden">
                <div id="usageBar" class="h-full bg-brand-500 rounded-full" style="width:0%"></div>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3 text-xs">
              <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 p-3">
                <p class="text-neutral-500 dark:text-neutral-400">Days used</p>
                <p class="mt-1 font-semibold"><span id="usedDaysSmall">0</span> days</p>
              </div>
              <div class="rounded-xl border border-neutral-200 dark:border-neutral-800 p-3">
                <p class="text-neutral-500 dark:text-neutral-400">Days remaining</p>
                <p class="mt-1 font-semibold"><span id="remainingDaysSmall">20</span> days</p>
              </div>
            </div>
          </div>
        </div>
      </aside>

      <!-- Leave History -->
 
   <section class="mt-6 bg-white lg:col-span-2 dark:bg-neutral-950 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-soft">
  <div class="px-5 pt-5">
    <div class="flex items-center gap-2 text-sm font-semibold">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-500 dark:text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
        class="w-full max-w-2xl rounded-2xl bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 shadow-modal">
        <!-- header -->
        <div class="flex items-start justify-between p-5 border-b border-neutral-200 dark:border-neutral-800">
          <div>
            <h3 id="modalTitle" class="text-lg font-semibold">Request Time Off</h3>
            <p class="text-xs text-neutral-500 dark:text-neutral-400">Submit a new leave request for approval</p>
          </div>
          <button id="closeModal" class="rounded-lg p-2 hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- body -->
        <form id="leaveForm" class="p-5 space-y-4">
          <!-- Leave type -->
          <div>
            <label class="text-sm font-medium">Leave Type <span class="text-rose-600">*</span></label>
            <div class="relative mt-1">
              <select id="leaveType" class="w-full appearance-none rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3.5 py-2.5 pr-9 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400">
                <option value="vacation">Vacation</option>
                <option value="sick">Sick Leave</option>
                <option value="personal">Personal</option>
                <option value="unpaid">Unpaid</option>
              </select>
              <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
              </span>
            </div>
          </div>

          <!-- Dates -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium">Start Date <span class="text-rose-600">*</span></label>
              <div class="relative mt-1">
                <input id="startDate" type="date" placeholder="dd/mm/yyyy"
                  class="w-full rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3.5 py-2.5 pr-9 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400"/>
                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4M16 2v4M3 10h18M4 6h16a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"/></svg>
                </span>
              </div>
            </div>
            <div>
              <label class="text-sm font-medium">End Date <span class="text-rose-600">*</span></label>
              <div class="relative mt-1">
                <input id="endDate" type="date" placeholder="dd/mm/yyyy"
                  class="w-full rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3.5 py-2.5 pr-9 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400"/>
                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4M16 2v4M3 10h18M4 6h16a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"/></svg>
                </span>
              </div>
            </div>
          </div>

          <!-- Reason -->
          <div>
            <label class="text-sm font-medium">Reason <span class="text-rose-600">*</span></label>
            <textarea id="reason" rows="4" placeholder="Briefly describe the reason for your leave request..."
              class="mt-1 w-full rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 px-3.5 py-2.5 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400"></textarea>
          </div>

          <!-- Medical certificate (conditional) -->
          <div id="medicalGroup" class="hidden">
            <label class="text-sm font-medium">Medical Certificate <span class="text-rose-600">*</span></label>
            <div id="dropzone"
              class="mt-1 rounded-2xl border-2 border-dashed border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900/40 p-6 text-center hover:bg-neutral-100 dark:hover:bg-neutral-800 transition cursor-pointer">
              <input id="medicalFileInput" type="file" accept="application/pdf" class="hidden"/>
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
              class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-4 py-2 text-sm shadow-soft hover:bg-neutral-50 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-brand-400">Cancel</button>
            <button type="submit" id="submitBtn"
              class="rounded-xl bg-brand-600 px-4 py-2 text-sm font-medium text-white shadow-soft hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-400 disabled:opacity-60 disabled:cursor-not-allowed">Submit Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script>
    // -------- State --------
    const state = {
      totalDays: 20,
      usedDays: 0,
      current: new Date(),
      viewDate: new Date(),
      selected: null,
      leaves: {} // 'YYYY-MM-DD': 'vacation' | 'sick' | 'personal' | 'unpaid'
    };

    // -------- Helpers --------
    const pad2 = n => String(n).padStart(2,'0');
    const ymd = d => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;
    const monthLabel = d => d.toLocaleString(undefined,{month:'long',year:'numeric'});
    const daysInMonth = (y,m) => new Date(y, m+1, 0).getDate();
    const parseISO = s => { const [Y,M,D] = s.split('-').map(Number); return new Date(Y, M-1, D); };

    // -------- UI Updates --------
    function renderKpis(){
      const remaining = Math.max(0, state.totalDays - state.usedDays);
      document.getElementById('totalDays').textContent = state.totalDays;
      document.getElementById('usedDays').textContent = state.usedDays;
      document.getElementById('remainingDays').textContent = remaining;
      document.getElementById('usedDaysSmall').textContent = state.usedDays;
      document.getElementById('remainingDaysSmall').textContent = remaining;
      const pct = Math.min(100, Math.round((state.usedDays/state.totalDays)*100)) || 0;
      document.getElementById('usagePct').textContent = pct + '%';
      document.getElementById('usageBar').style.width = pct + '%';
    }

    function renderCalendar(){
      const grid = document.getElementById('calendarGrid'); grid.innerHTML='';
      const view = new Date(state.viewDate.getFullYear(), state.viewDate.getMonth(), 1);
      document.getElementById('monthLabel').textContent = monthLabel(view);

      const y = view.getFullYear(), m = view.getMonth();
      const first = new Date(y, m, 1).getDay();
      const total = daysInMonth(y, m);

      // leading blanks
      for(let i=0;i<first;i++){
        const c = document.createElement('div');
        c.className = 'aspect-square rounded-xl bg-neutral-50 dark:bg-neutral-900 border border-dashed border-neutral-200 dark:border-neutral-800';
        grid.appendChild(c);
      }

      // day cells
      for(let d=1; d<=total; d++){
        const date = new Date(y,m,d);
        const key = ymd(date);
        const cell = document.createElement('button');
        cell.type = 'button';
        cell.className = 'relative aspect-square rounded-xl border bg-white dark:bg-neutral-950 border-neutral-200 dark:border-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-900 transition focus:outline-none focus:ring-2 focus:ring-brand-400';
        const number = document.createElement('span');
        number.className = 'absolute top-2 left-2 text-sm font-medium';
        number.textContent = d;
        cell.appendChild(number);

        const type = state.leaves[key];
        if(type){
          const color = {vacation:'bg-brand-500', sick:'bg-rose-500', personal:'bg-violet-500', unpaid:'bg-amber-500'}[type] || 'bg-neutral-300';
          const dot = document.createElement('span');
          dot.className = `absolute bottom-2 left-2 h-2.5 w-2.5 rounded-full ${color}`;
          cell.appendChild(dot);
        }

        if(ymd(date) === ymd(state.current)){
          const ring = document.createElement('span');
          ring.className = 'absolute inset-1 rounded-lg border-2 border-brand-500/50 pointer-events-none';
          cell.appendChild(ring);
        }

        if(state.selected && ymd(date) === ymd(state.selected)){
          cell.classList.add('ring-2','ring-brand-500');
        }

        cell.addEventListener('click', ()=>{ state.selected = date; renderCalendar(); });
        grid.appendChild(cell);
      }
    }

    // -------- Modal Logic --------
    const overlay = document.getElementById('modalOverlay');
    const openBtn = document.getElementById('requestLeaveBtn');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelModal');

    function openModal(){ overlay.classList.remove('hidden'); document.getElementById('leaveType').focus(); }
    function closeModal(){ overlay.classList.add('hidden'); resetForm(); }
    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', (e)=>{ if(e.target===overlay) closeModal(); });
    window.addEventListener('keydown', (e)=>{ if(!overlay.classList.contains('hidden') && e.key==='Escape') closeModal(); });

    // Conditional medical certificate
    const typeSelect = document.getElementById('leaveType');
    const medicalGroup = document.getElementById('medicalGroup');
    const fileInput = document.getElementById('medicalFileInput');
    const fileBadge = document.getElementById('fileBadge');
    const dropzone = document.getElementById('dropzone');

    function updateMedicalVisibility(){
      if(typeSelect.value === 'sick'){ medicalGroup.classList.remove('hidden'); }
      else { medicalGroup.classList.add('hidden'); fileInput.value=''; fileBadge.classList.add('hidden'); fileBadge.textContent=''; }
    }
    typeSelect.addEventListener('change', updateMedicalVisibility);

    // Drag & Drop
    ;['dragenter','dragover'].forEach(ev => dropzone.addEventListener(ev, e=>{ e.preventDefault(); dropzone.classList.add('ring-2','ring-brand-400'); }));
    ;['dragleave','drop'].forEach(ev => dropzone.addEventListener(ev, e=>{ e.preventDefault(); dropzone.classList.remove('ring-2','ring-brand-400'); }));
    dropzone.addEventListener('click', ()=> fileInput.click());
    dropzone.addEventListener('drop', (e)=>{
      const file = e.dataTransfer.files?.[0];
      if(file) handleFile(file);
    });
    fileInput.addEventListener('change', (e)=>{ const file=e.target.files?.[0]; if(file) handleFile(file); });

    function handleFile(file){
      if(file.type !== 'application/pdf'){ showError('Please upload a PDF file.'); return; }
      if(file.size > 5*1024*1024){ showError('File is too large (max 5MB).'); return; }
      fileInput.files = new DataTransfer().files; // keep default
      // show badge
      fileBadge.textContent = file.name;
      fileBadge.classList.remove('hidden');
      hideError();
    }

    // Validation + submit
    const form = document.getElementById('leaveForm');
    const startInput = document.getElementById('startDate');
    const endInput = document.getElementById('endDate');
    const reasonInput = document.getElementById('reason');
    const errorBox = document.getElementById('formError');

    function showError(msg){ errorBox.textContent = msg; errorBox.classList.remove('hidden'); }
    function hideError(){ errorBox.textContent = ''; errorBox.classList.add('hidden'); }

    form.addEventListener('submit', (e)=>{
      e.preventDefault();
      hideError();

      const type = typeSelect.value;
      const start = startInput.value;
      const end = endInput.value;
      const reason = reasonInput.value.trim();

      if(!type || !start || !end || !reason){ showError('Please complete all required fields.'); return; }
      const d1 = parseISO(start), d2 = parseISO(end);
      if(d2 < d1){ showError('End date cannot be earlier than start date.'); return; }
      if(type === 'sick' && (!fileInput.files || !fileInput.files[0])){ showError('Medical certificate (PDF) is required for Sick Leave.'); return; }

      // Add to calendar + KPI
      const days = Math.round((d2 - d1) / 86400000) + 1; // inclusive
      for(let i=0;i<days;i++){
        const cur = new Date(d1); cur.setDate(cur.getDate() + i);
        state.leaves[ymd(cur)] = type;
      }
      state.usedDays = Math.min(state.totalDays, state.usedDays + days);
      renderKpis();
      renderCalendar();
      closeModal();
    });

    function resetForm(){
      form.reset();
      updateMedicalVisibility();
      hideError();
    }

    // -------- Theme toggle --------
    const themeBtn = document.getElementById('themeToggle');
    const root = document.documentElement;
    function applyTheme(t){ if(t==='dark') root.classList.add('dark'); else root.classList.remove('dark'); }
    function currentTheme(){ return localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'); }
    applyTheme(currentTheme());
    themeBtn.addEventListener('click', ()=>{
      const t = root.classList.contains('dark') ? 'light' : 'dark';
      applyTheme(t); localStorage.setItem('theme', t);
    });

    // -------- Calendar navigation --------
    document.getElementById('prevBtn').addEventListener('click', ()=>{
      state.viewDate = new Date(state.viewDate.getFullYear(), state.viewDate.getMonth()-1, 1);
      renderCalendar();
    });
    document.getElementById('nextBtn').addEventListener('click', ()=>{
      state.viewDate = new Date(state.viewDate.getFullYear(), state.viewDate.getMonth()+1, 1);
      renderCalendar();
    });
    document.querySelectorAll('[data-range]').forEach(b=>{
      b.addEventListener('click', ()=>{
        document.querySelectorAll('[data-range]').forEach(x=>x.classList.remove('bg-neutral-100','dark:bg-neutral-800/60','font-medium'));
        b.classList.add('bg-neutral-100','dark:bg-neutral-800/60','font-medium');
      });
    });

    // -------- Boot --------
    renderKpis(); renderCalendar(); updateMedicalVisibility();




    
// Public API:
//   showLeaveHistorySkeleton()
//   hideLeaveHistorySkeleton()
//   renderLeaveHistory('leaveHistory', itemsArray)
//
// itemsArray shape:
// [
//   { type:'vacation'|'sick'|'personal'|'unpaid', status:'approved'|'pending'|'rejected',
//     start:'YYYY-MM-DD', end:'YYYY-MM-DD' }
// ]

const TYPE_LABEL = { vacation:'Vacation', sick:'Sick Leave', personal:'Personal', unpaid:'Unpaid' };
const TYPE_BADGE = {
  vacation: 'bg-brand-50 text-brand-700 border-brand-200 dark:bg-brand-500/10 dark:text-brand-300 dark:border-brand-900/40',
  sick:     'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:border-rose-900/40',
  personal: 'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-500/10 dark:text-violet-300 dark:border-violet-900/40',
  unpaid:   'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:border-amber-900/40',
};
const STATUS_BADGE = {
  approved: 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-900/40',
  pending:  'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:border-amber-900/40',
  rejected: 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:border-rose-900/40',
};

function fmtRange(isoStart, isoEnd) {
  const s = new Date(isoStart), e = new Date(isoEnd);
  const opts = { month:'short', day:'numeric', year:'numeric' };
  return `${s.toLocaleDateString(undefined, opts)} - ${e.toLocaleDateString(undefined, opts)}`;
}
function inclusiveDays(isoStart, isoEnd) {
  const d1 = new Date(isoStart), d2 = new Date(isoEnd);
  return Math.max(1, Math.round((d2 - d1) / 86400000) + 1);
}
function pluralize(n, w){ return `${n} ${w}${n===1?'':'s'}`; }

function showLeaveHistorySkeleton() {
  document.getElementById('leaveHistorySkeleton')?.classList.remove('hidden');
  document.getElementById('leaveHistory')?.classList.add('hidden');
  document.getElementById('leaveHistoryEmpty')?.classList.add('hidden');
}
function hideLeaveHistorySkeleton() {
  document.getElementById('leaveHistorySkeleton')?.classList.add('hidden');
}

function renderLeaveHistory(containerId, items) {
  const root = document.getElementById(containerId);
  if (!root) return;

  hideLeaveHistorySkeleton();
  root.innerHTML = '';

  if (!items || !items.length) {
    root.classList.add('hidden');
    document.getElementById('leaveHistoryEmpty')?.classList.remove('hidden');
    return;
  }
  root.classList.remove('hidden');
  document.getElementById('leaveHistoryEmpty')?.classList.add('hidden');

  items.forEach(item => {
    const tLabel = TYPE_LABEL[item.type] || item.type;
    const tClass = TYPE_BADGE[item.type] || 'bg-neutral-100 text-neutral-700 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700';
    const sClass = STATUS_BADGE[item.status] || 'bg-neutral-100 text-neutral-700 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700';
    const days = inclusiveDays(item.start, item.end);

    const el = document.createElement('div');
    el.className = 'rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 px-4 py-3';
    el.innerHTML = `
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
          <span class="text-[11px] px-2 py-1 rounded-md border ${tClass} capitalize">${tLabel}</span>
          <span class="text-[11px] px-2 py-1 rounded-md border ${sClass} capitalize">${item.status}</span>
        </div>
      </div>

      <div class="mt-2 flex flex-wrap items-center gap-4 text-sm">
        <div class="flex items-center gap-2 text-neutral-600 dark:text-neutral-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M8 2v4M16 2v4M3 10h18M4 6h16a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"/>
          </svg>
          <span>${fmtRange(item.start, item.end)}</span>
        </div>
        <div class="flex items-center gap-2 text-neutral-600 dark:text-neutral-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>
          </svg>
          <span>${pluralize(days,'day')}</span>
        </div>
      </div>
    `;
    root.appendChild(el);
  });
}


  </script>
</body>
 
@endsection 