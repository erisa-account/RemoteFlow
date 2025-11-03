@extends('layouts.admin')
@section('content')

<head>
    @vite(['resources/css/app.css']) 

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
  <header class="sticky top-0 z-10 bg-white/90 border-b border-neutral-200 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
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
      <h2 class="text-xl font-semibold">Overview</h2>
      <p class="text-sm text-neutral-500">Monitor vacation usage and leave requests</p>
    </section>

    <!-- Metrics -->
    <section id="metrics" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"></section>

    <!-- Employees -->
    <section class="bg-white rounded-2xl border border-neutral-200 shadow-soft">
      <div class="p-5 border-b border-neutral-200">
        <h3 class="text-sm font-semibold">Employee Overview</h3>
        <p class="text-xs text-neutral-500">View vacation balances for all employees</p>
      </div>

      <!-- Toolbar -->
      <div class="px-5 py-4 flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
        <div class="w-full md:max-w-md">
          <div class="relative">
            <input id="empSearch" type="text" placeholder="Search employees…" class="w-full rounded-xl border border-neutral-200 bg-white px-3.5 py-2 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400">
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
          </div>
        </div>
        <div class="flex gap-3">
          <div class="relative">
            <select id="empDept" class="appearance-none rounded-xl border border-neutral-200 bg-white px-3.5 py-2 pr-9 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400">
              <option value="">All Departments</option>
            </select>
            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
            </span>
          </div>
          <div class="relative">
            <select id="empSort" class="appearance-none rounded-xl border border-neutral-200 bg-white px-3.5 py-2 pr-9 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-400">
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
    <section class="bg-white rounded-2xl border border-neutral-200 shadow-soft">
      <div class="p-5 border-b border-neutral-200">
        <div class="flex items-center gap-2 text-sm font-semibold">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M8 2v4M16 2v4M3 10h18M4 6h16a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"/>
          </svg>
          Leave Requests
        </div>
        <p class="text-xs text-neutral-500">Review and manage employee leave requests</p>
      </div>
      <div id="requestList" class="p-5 space-y-3"></div>

      <!-- Footer actions for bulk approve/reject if you want -->
      <!-- <div class="px-5 pb-5 flex justify-end gap-2">
        <button class="rounded-xl bg-emerald-600 text-white text-sm px-4 py-2 shadow-soft">Approve All</button>
        <button class="rounded-xl bg-rose-600 text-white text-sm px-4 py-2 shadow-soft">Reject All</button>
      </div> -->
    </section>
  </main>

  <script>
    // ---------- UTIL ----------
    const currency = n => new Intl.NumberFormat().format(n);
    const pct = (a,b) => b ? Math.round((a/b)*100) : 0;
    const pad2 = n => String(n).padStart(2,'0');
    const ymd = d => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;
    const fmtRange = (a,b) => {
      const s = new Date(a), e = new Date(b);
      const o = {month:'short',day:'numeric',year:'numeric'};
      return `${s.toLocaleDateString(undefined,o)} - ${e.toLocaleDateString(undefined,o)}`
    };
    const daysBetween = (a,b) => Math.max(1, Math.round((new Date(b) - new Date(a))/86400000) + 1);

    // ---------- METRICS ----------
    function renderMetrics(m) {
      const host = document.getElementById('metrics'); host.innerHTML='';
      const items = [
        {label:'Total Employees', value:m.totalEmployees ?? 0, icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`, badge:'bg-brand-50 text-brand-600'},
        {label:'Approved Requests', value:m.approved ?? 0, icon:`<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>`, badge:'bg-emerald-50 text-emerald-600'},
        {label:'Pending Requests', value:m.pending ?? 0, icon:`<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>`, badge:'bg-amber-50 text-amber-600'},
        {label:'Total Days Off', value:m.daysOff ?? 0, icon:`<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>`, badge:'bg-neutral-100 text-neutral-600'}
      ];
      items.forEach(it=>{
        const card=document.createElement('div');
        card.className='bg-white rounded-2xl border border-neutral-200 shadow-soft';
        card.innerHTML=`
          <div class="p-5">
            <div class="text-xs uppercase tracking-wide text-neutral-500">${it.label}</div>
            <div class="mt-2 flex items-center justify-between">
              <div class="text-3xl font-semibold">${it.value}</div>
              <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl ${it.badge}">${it.icon}</span>
            </div>
          </div>`;
        host.appendChild(card);
      });
    }

    // ---------- EMPLOYEES ----------
    function initials(name){
      return name.split(' ').map(s=>s[0]).filter(Boolean).slice(0,2).join('').toUpperCase();
    }
    function usageBar(used,total){
      const p = pct(used,total);
      return `<div class="mt-3">
        <div class="flex items-center justify-between text-xs text-neutral-500 mb-1"><span>Usage</span><span>${p}%</span></div>
        <div class="h-2 w-full bg-neutral-100 rounded-full overflow-hidden">
          <div class="h-full bg-brand-500 rounded-full" style="width:${p}%;"></div>
        </div>
      </div>`;
    }
    function employeeCard(e){
      const remain = Math.max(0,(e.totalDays||0)-(e.usedDays||0));
      const el=document.createElement('div');
      el.className='rounded-2xl border border-neutral-200 bg-white shadow-soft';
      el.innerHTML=`
        <div class="p-5">
          <div class="flex items-start gap-3">
            <span class="h-10 w-10 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center text-sm font-semibold">${initials(e.name)}</span>
            <div class="min-w-0">
              <div class="font-medium">${e.name}</div>
              <div class="text-xs text-neutral-500">${e.role||''}</div>
              <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-neutral-600">
                <span class="inline-flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M22 6 12 13 2 6"/></svg>
                  ${e.email||''}
                </span>
                <span class="inline-flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                  ${e.department||''}
                </span>
              </div>
            </div>
          </div>

          <div class="mt-4 grid grid-cols-2 gap-6">
            <div>
              <div class="text-[10px] uppercase text-neutral-500">Total Days</div>
              <div class="text-lg font-semibold">${e.totalDays||0}</div>
            </div>
            <div>
              <div class="text-[10px] uppercase text-neutral-500">Remaining</div>
              <div class="text-lg font-semibold text-emerald-600">${remain}</div>
            </div>
          </div>

          ${usageBar(e.usedDays||0, e.totalDays||0)}

          <div class="mt-2 grid grid-cols-2 gap-3 text-xs">
            <div class="rounded-xl border border-neutral-200 p-3">
              <div class="text-neutral-500">Used</div>
              <div class="mt-1 font-semibold">${e.usedDays||0}</div>
            </div>
            <div class="rounded-xl border border-neutral-200 p-3">
              <div class="text-neutral-500">Available</div>
              <div class="mt-1 font-semibold">${remain}</div>
            </div>
          </div>

          <button class="mt-4 w-full rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm hover:bg-neutral-50">View Details</button>
        </div>`;
      return el;
    }
    function renderEmployees(arr){
      const grid=document.getElementById('employeeGrid'); grid.innerHTML='';
      arr.forEach(e=>grid.appendChild(employeeCard(e)));
    }

    // filters
    function setupEmployeeFilters(employees){
      const deptSel=document.getElementById('empDept');
      // fill departments
      const depts=[...new Set(employees.map(e=>e.department).filter(Boolean))].sort();
      depts.forEach(d=>{
        const o=document.createElement('option'); o.value=d; o.textContent=d; deptSel.appendChild(o);
      });

      const search=document.getElementById('empSearch');
      const sortSel=document.getElementById('empSort');

      function apply(){
        const q=(search.value||'').toLowerCase();
        const d=deptSel.value;
        let list=employees.filter(e=>{
          const inDept=!d || e.department===d;
          const inSearch=!q || (e.name?.toLowerCase().includes(q) || e.email?.toLowerCase().includes(q) || e.role?.toLowerCase().includes(q));
          return inDept && inSearch;
        });
        switch(sortSel.value){
          case 'remaining': list.sort((a,b)=>((b.totalDays-b.usedDays)-(a.totalDays-a.usedDays))); break;
          case 'total': list.sort((a,b)=>b.totalDays-a.totalDays); break;
          default: list.sort((a,b)=>a.name.localeCompare(b.name));
        }
        renderEmployees(list);
      }
      [search,deptSel,sortSel].forEach(el=>el.addEventListener('input',apply));
      apply();
    }

    // ---------- REQUESTS ----------
    const TYPE_LABEL = {vacation:'vacation', sick:'sick', personal:'personal', unpaid:'unpaid'};
    const TYPE_BADGE = {
      vacation:'bg-brand-50 text-brand-700 border border-brand-200',
      sick:'bg-rose-50 text-rose-700 border border-rose-200',
      personal:'bg-violet-50 text-violet-700 border border-violet-200',
      unpaid:'bg-amber-50 text-amber-800 border border-amber-200'
    };
    const STATUS_BADGE = {
      approved:'bg-emerald-50 text-emerald-700 border border-emerald-200',
      pending:'bg-amber-50 text-amber-800 border border-amber-200',
      rejected:'bg-rose-50 text-rose-700 border border-rose-200'
    };
    function requestItem(r){
      const days = daysBetween(r.start, r.end);
      const el=document.createElement('div');
      el.className='rounded-xl border border-neutral-200 bg-white px-4 py-3';
      el.innerHTML=`
        <div class="flex flex-col gap-2">
          <div class="flex items-start gap-3">
            <span class="h-9 w-9 rounded-full bg-neutral-100 text-neutral-600 flex items-center justify-center text-xs font-semibold">${initials(r.employee?.name||'')}</span>
            <div class="min-w-0 grow">
              <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="min-w-0">
                  <div class="font-medium truncate">${r.employee?.name||''}</div>
                  <div class="text-xs text-neutral-500 truncate">${r.employee?.role||''}</div>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-[11px] px-2 py-1 rounded-md ${TYPE_BADGE[r.type]||'bg-neutral-100 text-neutral-700 border'} capitalize">${TYPE_LABEL[r.type]||r.type}</span>
                  <span class="text-[11px] px-2 py-1 rounded-md ${STATUS_BADGE[r.status]||'bg-neutral-100 text-neutral-700 border'} capitalize">${r.status}</span>
                  ${r.type==='sick' ? '<span class="text-[11px] px-2 py-1 rounded-md bg-neutral-100 text-neutral-700 border">📄 Document</span>' : ''}
                </div>
              </div>

              <div class="mt-2 flex flex-wrap items-center gap-4 text-sm">
                <span class="inline-flex items-center gap-2 text-neutral-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 2v4M16 2v4M3 10h18M4 6h16a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"/></svg>
                  ${fmtRange(r.start,r.end)}
                </span>
                <span class="inline-flex items-center gap-2 text-neutral-600">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                  ${days} days
                </span>
              </div>

              ${r.reason ? `<blockquote class="mt-1 text-xs italic text-neutral-500">"${r.reason}"</blockquote>` : ''}

              <div class="mt-3 flex justify-end gap-2">
                <button class="rounded-xl bg-emerald-600 text-white text-sm px-3 py-1.5 hover:bg-emerald-700">Approve</button>
                <button class="rounded-xl bg-rose-600 text-white text-sm px-3 py-1.5 hover:bg-rose-700">Reject</button>
              </div>
            </div>
          </div>
        </div>`;
      return el;
    }
    function renderRequests(arr){
      const list=document.getElementById('requestList'); list.innerHTML='';
      arr.forEach(r=>list.appendChild(requestItem(r)));
      // zebra striping (like screenshot)
      [...list.children].forEach((c,i)=>{ if(i%2===1) c.classList.add('bg-neutral-50'); });
    }

    // ---------- PUBLIC ENTRY ----------
    // Call this with your backend JSON
    function renderAdminAdminDashboard({metrics, employees, requests}){
      renderMetrics(metrics||{});
      renderEmployees(employees||[]);
      setupEmployeeFilters(employees||[]);
      renderRequests(requests||[]);
    }

    // ---------- DEMO (remove; wire your own data) ----------
    const demo = {
      metrics: { totalEmployees:6, approved:0, pending:3, daysOff:0 },
      employees: [
        { name:'John Doe',   role:'Senior Developer', email:'john@company.com', department:'Engineering', totalDays:20, usedDays:0 },
        { name:'Jane Smith', role:'Marketing Manager', email:'jane@company.com', department:'Marketing',  totalDays:22, usedDays:0 },
        { name:'Mike Johnson', role:'Sales Director', email:'mike@company.com', department:'Sales', totalDays:25, usedDays:0 },
      ],
      requests: [
        { type:'vacation', status:'approved', start:'2024-01-05', end:'2024-01-09', reason:'Family vacation', employee:{name:'John Doe', role:'Senior Developer'} },
        { type:'vacation', status:'approved', start:'2024-01-15', end:'2024-01-16', reason:'Weekend getaway', employee:{name:'John Doe', role:'Senior Developer'} },
        { type:'sick', status:'approved', start:'2024-01-12', end:'2024-01-13', reason:'Medical appointment', employee:{name:'John Doe', role:'Senior Developer'} },
        { type:'vacation', status:'pending', start:'2024-02-10', end:'2024-02-12', reason:'Winter break?', employee:{name:'John Doe', role:'Senior Developer'} },
      ]
    };
    renderAdminAdminDashboard(demo);
    // --------- /DEMO ----------
  </script>
</body>

@endsection