


document.addEventListener('DOMContentLoaded', () => {

  const state = {
     
      current: new Date(),
      viewDate: new Date(),
      selected: null,
      leaves: {} // 'YYYY-MM-DD': 'vacation' | 'sick' | 'personal' | 'unpaid'
    };

    fetch('/api/leave-summary')
    .then(response => response.json())
    .then(data =>
      {
        document.getElementById('totalDays').textContent = data.data.total_days;
        document.getElementById('usedDays').textContent = data.data.used_days;
        document.getElementById('remainingDays').textContent = data.data.remaing_days;
      }
      )
    .catch(error => console.error('Error', error ));

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
    const fileInput = document.getElementById('medical_certificate');
    const fileBadge = document.getElementById('fileBadge');
    const dropzone = document.getElementById('dropzone');

    function updateMedicalVisibility(){
      if(typeSelect.value === '2'){ medicalGroup.classList.remove('hidden'); }
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
      
      
      const dt = new DataTransfer();
      dt.items.add(file);
      fileInput.files = dt.files;
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

    form.addEventListener('submit', async (e)=>{
      e.preventDefault();
      hideError();

      
      const type = typeSelect.value;
      const start = startInput.value;
      const end = endInput.value;
      const reason = reasonInput.value.trim();

      if(!type || !start || !end || !reason){ showError('Please complete all required fields.'); return; }
      const d1 = parseISO(start), d2 = parseISO(end);
      if(d2 < d1){ showError('End date cannot be earlier than start date.'); return; }
      //if(type === '2' && (!fileInput.files || !fileInput.files[0])){ showError('Medical certificate (PDF) is required for Sick Leave.'); return; }

      
      

       
       
      const formData = new FormData(form);

    formData.append('leave_type_id', type);
    formData.append('start_date', start);
    formData.append('end_date', end);
    formData.append('reason', reason);
    if(fileInput.files[0]) formData.append('medical_certificate', fileInput.files[0]);

    try {
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const response = await fetch('/leave-request', {
            method: 'POST',
            headers: { 
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
            body: formData
        });

        const data = await response.json();


        if(!response.ok){
            if(data.errors){
                showError(Object.values(data.errors).flat().join(' '));
            } 
             else if(data.message) {
                    showError(data.message);

            } else {
                showError('An unexpected error occurred.');
            }
            return;
        }

        

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


      } catch(err){
        console.error(err);
        showError('Failed to submit leave request. Please try again.');
    }

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




});