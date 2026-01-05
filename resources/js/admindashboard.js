import Swal from 'sweetalert2';


   document.addEventListener('DOMContentLoaded', () => {
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
    
    //const daysBetween = (a,b) => Math.max(1, Math.round((new Date(b) - new Date(a))/86400000) + 1);

    // ---------- METRICS ----------
    function renderMetrics(m) {
      const host = document.getElementById('metrics'); host.innerHTML='';
      const items = [
        {label:'Total Employees', value:m.totalEmployees ?? 0, icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`, badge:'bg-brand-50 text-brand-600'},
        {label:'Approved Requests', value:m.approved ?? 0, icon:`<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>`, badge:'bg-emerald-50 text-emerald-600'},
        {label:'Pending Requests', value:m.pending ?? 0, icon:`<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>`, badge:'bg-amber-50 text-amber-600'},
        //{label:'Total Days Off', value:m.daysOff ?? 0, icon:`<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>`, badge:'bg-neutral-100 text-neutral-600'}
      ];
      items.forEach(it=>{
        const card=document.createElement('div');
        card.className='bg-white dark:bg-gray-800 rounded-2xl border dark:border-neutral-800 shadow-soft';
        card.innerHTML=`
          <div class="p-5">
            <div class="text-xs uppercase tracking-wide text-neutral-500 dark:text-gray-200">${it.label}</div>
            <div class="mt-2 flex items-center justify-between">
              <div class="text-3xl font-semibold dark:text-gray-200">${it.value}</div>
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
        <div class="flex items-center justify-between text-xs text-neutral-500 mb-1 dark:text-gray-200"><span>Usage</span><span>${p}%</span></div>
        <div class="h-2 w-full bg-neutral-100 rounded-full overflow-hidden">
          <div class="h-full bg-brand-500 rounded-full dark:text-gray-200" style="width:${p}%;"></div>
        </div>
      </div>`;
    }
    function employeeCard(e){
      const remain = Math.max(0,(e.totalDays||0)-(e.usedDays||0));
      const el=document.createElement('div');
      el.className='rounded-2xl border  border-neutral-200 bg-white dark:bg-gray-700 dark:border-gray-600 shadow-soft';
      el.innerHTML=`
        <div class="p-5">
          <div class="flex items-start gap-3">
            <span class="h-10 w-10 rounded-full bg-brand-50 text-brand-700 flex items-center justify-center text-sm font-semibold">${initials(e.name)}</span>
            <div class="min-w-0">
              <div class="font-medium dark:text-gray-200">${e.name}</div>
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
              <div class="text-[10px] uppercase text-neutral-500 dark:text-gray-200">Total Days</div>
              <div class="text-lg font-semibold dark:text-gray-200">${e.totalDays||0}</div>
            </div>
            <div>
              <div class="text-[10px] uppercase text-neutral-500 dark:text-gray-200">Remaining</div>
              <div class="text-lg font-semibold text-emerald-600 dark:text-gray-200">${remain}</div>
            </div>
          </div>

          ${usageBar(e.usedDays||0, e.totalDays||0)}

          <div class="mt-2 grid grid-cols-2 gap-3 text-xs">
            <div class="rounded-xl border border-neutral-200 p-3 dark:border-gray-600">
              <div class="text-neutral-500 dark:text-gray-200">Used</div>
              <div class="mt-1 font-semibold dark:text-gray-200">${e.usedDays||0}</div>
            </div>
            <div class="rounded-xl border border-neutral-200 p-3 dark:border-gray-600">
              <div class="text-neutral-500 dark:text-gray-200">Available</div>
              <div class="mt-1 font-semibold dark:text-gray-200">${remain}</div>
            </div>
          </div>

          <button class="mt-4 w-full rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm hover:bg-neutral-50 hidden">View Details</button>
        </div>`;
      return el;
    }

    function attachRequestButtons() {
  // Approve buttons
  
  document.querySelectorAll('.approve-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.dataset.id;
      
      try {
        const res = await fetch(`/admin/leaves/${id}/approve`, {
          method: 'POST',
          credentials: 'include',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ comment: '' }) // optional
          
        });
        if (!res.ok)  { 
            const text = await res.text();
        console.error('Approve API failed:', res.status, text);
        throw new Error('Failed to approve');
        
      } 
       const data = await res.json();
       
       Swal.fire({
        icon: 'success',
        title: 'Approved',
        text: data.message,
        timer: 2000,
        showConfirmButton: false,
       });

      //alert(data.message);
      loadDashboardData();
      
    }
      catch (err) {
        console.error(err);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error approving leave',
        });
        //alert('Error approving leave');
      }
    });
  });

  // Reject buttons
  document.querySelectorAll('.reject-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.dataset.id;
     
      const {value : reason} = await Swal.fire({
        title: 'Reason for rejection',
        input: 'text',
        inputLabel: 'Please provide a reason for rejection',
        inputPlaceholder: 'Type rejection reason here',
        showCancelButton: true,
        confirmButtonText: 'Reject',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => {
          if (!value) {
            return 'You need to enter a reason.';
          }
        }
      });

      //const reason = prompt('Reason for rejection?') || '';

      if(!reason) return;

      try {
        const res = await fetch(`/admin/leaves/${id}/reject`, {
          method: 'POST',
          credentials: 'include',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ reason })
          
        });
        if (!res.ok) 
            throw new Error('Failed to reject');
        const data = await res.json();

        Swal.fire({
          icon: 'warning',
          title: 'Rejected',
          text: data.message,
          timer: 2000,
          showConfirmButton: false,
        });

        //alert(data.message);
        loadDashboardData(); // refresh

      } catch (err) {
        console.error(err);
        //alert('Error rejecting leave');

        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error rejecting leave',
        });
      }
    });
  });
}
    function renderEmployees(arr){
      const grid=document.getElementById('employeeGrid'); grid.innerHTML='';
      arr.forEach(e=>grid.appendChild(employeeCard(e)));
      //const list = document.getElementById('requestList');
        //list.innerHTML = '';
        //arr.forEach(r => list.appendChild(requestItem(r)));

        //[...list.children].forEach((c,i) => { if(i%2===1) c.classList.add('bg-neutral-50'); });

        //attachRequestButtons(); // attach events
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
    const TYPE_LABEL = {1:'vacation', 2:'sick', personal:'personal', 3:'other', 4:'replacment'}; 
    const TYPE_BADGE = {
      1:'bg-brand-50 text-brand-700 border border-brand-200',
      2:'bg-rose-50 text-rose-700 border border-rose-200',
      personal:'bg-violet-50 text-violet-700 border border-violet-200',
      3:'bg-amber-50 text-amber-800 border border-amber-200'
    };
    const STATUS_BADGE = {
      approved:'bg-emerald-50 text-emerald-700 border border-emerald-200',
      pending:'bg-amber-50 text-amber-800 border border-amber-200',
      rejected:'bg-rose-50 text-rose-700 border border-rose-200'
    };
    function requestItem(r){
      //const days = daysBetween(r.start, r.end);
      const el=document.createElement('div');
      el.className='rounded-xl border border-neutral-200 bg-white dark:bg-gray-700 dark:border-gray-600 px-4 py-3';

    


      el.innerHTML=`
        <div class="flex flex-col gap-2">
          <div class="flex items-start gap-3">
            <span class="h-9 w-9 rounded-full bg-neutral-100 text-neutral-600 flex items-center justify-center text-xs font-semibold">${initials(r.employee?.name||'')}</span>
            <div class="min-w-0 grow">
              <div class="flex flex-wrap items-center justify-start gap-6">
                <div class="min-w-0">
                  <div class="font-medium truncate dark:text-gray-200">${r.employee?.name||''}</div>
                  <div class="text-xs text-neutral-500 truncate dark:text-gray-200">${r.employee?.role||''}</div>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-[11px] px-2 py-1 rounded-md ${TYPE_BADGE[r.type]||'bg-neutral-100 text-neutral-700 border'} capitalize">${TYPE_LABEL[r.type]||r.type}</span>
                  <span class="text-[11px] px-2 py-1 rounded-md ${STATUS_BADGE[r.status]||'bg-neutral-100 text-neutral-700 border'} capitalize">${r.status}</span>
                 ${r.medical_certificate_path ? `
                  <a href="${r.medical_certificate_path}" target="_blank"
                    class="text-blue-600 flex-shrink-0 inline-flex items-center gap-1 ml-1"
                    aria-label="Open medical certificate" title="Open medical certificate">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 block"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                      <path d="M14 2v6h6" />
                      <path d="M8 10h8" />
                      <path d="M8 13h8" />
                      <path d="M8 16h5" />
                    </svg>
                  </a>
                ` : ''}
                </div>
              </div>

              <div class="mt-2 flex flex-wrap items-center gap-4 text-sm">
                <span class="inline-flex items-center gap-2 text-neutral-600 dark:text-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 2v4M16 2v4M3 10h18M4 6h16a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"/></svg>
                  ${fmtRange(r.start,r.end)}
                </span>
                <span class="inline-flex items-center gap-2 text-neutral-600 dark:text-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                 ${r.days} days
                </span>
              </div>

              

              <div class="mt-3 flex justify-end gap-2">
              <button class="approve-btn rounded-xl bg-emerald-600 text-white text-sm px-3 py-1.5 hover:bg-emerald-700 ${r.status === 'approved' ? 'hidden' : ''}" data-id="${r.id}">
    Approve
  </button>
                <button class="reject-btn rounded-xl bg-rose-600 text-white text-sm px-3 py-1.5 hover:bg-rose-700  ${r.status === 'rejected' ? 'hidden' : ''}" data-id="${r.id}">Reject</button>
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
      attachRequestButtons();
    }
    
    // ---------- PUBLIC ENTRY ----------
    // Call this with your backend JSON
    function renderAdminAdminDashboard({metrics, employees, requests}){
      renderMetrics(metrics||{});
      renderEmployees(employees||[]);
      setupEmployeeFilters(employees||[]);
      renderRequests(requests||[]);
    }

   


    async function loadDashboardData() {
  try {
    const response = await fetch('/api/admin/leaves'); // <-- your Laravel route
    if (!response.ok) throw new Error('Failed to fetch dashboard data');

    const data = await response.json();

    // your API probably returns something like: { data: [...] }
    // adapt structure to match renderAdminAdminDashboard()
    const requests = data.data || [];
    console.log('API request' , requests);

    // extract employees + metrics
    const employeesMap = new Map();
    let approved = 0, pending = 0, daysOff = 0;

    requests.forEach(req => {
      // --- requests stats ---
      if (req.status === 'approved') approved++;
      if (req.status === 'pending') pending++;
      daysOff += req.days || 0;

      // --- employees ---
      const e = req.employee;
      if (e) {
        if (!employeesMap.has(e.id)) {
          employeesMap.set(e.id, {
            id: e.id,
            name: e.name,
            role: e.role,
            email: e.email,
            department: e.department || '',
            totalDays: e.totalDays || 0,
            usedDays: e.usedDays || 0,
          });
        }
      }
    });

    const employees = Array.from(employeesMap.values());
    const metrics = {
      totalEmployees: employees.length,
      approved,
      pending,
      daysOff,
    };

    renderAdminAdminDashboard({
      metrics,
      employees,
      requests
    });

  } catch (err) {
    console.error('Error loading dashboard:', err);
    document.getElementById('requestList').innerHTML =
      `<div class="text-sm text-rose-600">Error loading dashboard data.</div>`;
  }
}

// load automatically when the page loads
loadDashboardData();



});