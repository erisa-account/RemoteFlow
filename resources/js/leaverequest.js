
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {

  const state = {
     
      current: new Date(),
      viewDate: new Date(),
      selected: null,
      leaves: {},  // 'YYYY-MM-DD': 'vacation' | 'sick' | 'personal' | 'unpaid'
      totalDays: 0,
      usedDays: 0,
      replacementLeaves: {},
      replacementDates: {},
      replacementMap: {},
      remainingDays: 0,
      forwardedDays: 0,
      dayMarkers: {},
    };
    

    

    fetch('/user/leave-data', {
      
      method: 'GET',
      credentials: 'same-origin',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      }
    })
 
    .then(response => response.json())
    .then(data =>
      {
        //document.getElementById('totalDays').textContent = data.total_days;
        //document.getElementById('usedDays').textContent = data.used_days;
        //document.getElementById('remainingDays').textContent = data.remaining_days;
        console.log("api result", data);
        
         /*state.totalDays = data.data.total_days;
         state.usedDays = data.data.used_days;
         state.remainingDays = data.data.remaining_days;*/

         state.totalDays = data.total_days;
         state.usedDays = data.used_days;
         state.remainingDays = data.remaining_days;
         state.forwardedDays = data.forwarded_days;

         renderKpis();
         
         
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
      document.getElementById('forwardedDays').textContent = state.forwardedDays;
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
        c.className = 'aspect-square rounded-xl bg-neutral-50 dark:bg-gray-800 border border-dashed border-neutral-200 dark:border-neutral-800';
        grid.appendChild(c);
      }

      // day cells
      for(let d=1; d<=total; d++){
        const date = new Date(y,m,d); 
        const key = ymd(date);
       
        const month = date.getMonth() + 1;
        const day = date.getDate();

        
        const cell = document.createElement('button');
        cell.type = 'button';
        cell.className = 'relative aspect-square rounded-xl border bg-white dark:bg-gray-800 border-neutral-200 dark:border-gray-600 hover:bg-neutral-50 dark:hover:bg-neutral-900 transition focus:outline-none focus:ring-2 focus:ring-brand-400';
        const number = document.createElement('span');
        number.className = 'absolute top-2 left-2 text-sm font-medium dark:text-gray-200';
        number.textContent = d;
        cell.appendChild(number);




    state.holidays?.forEach(h => {
    if (h.month === month && h.day === d) {
        cell.style.backgroundColor = h.color || '#f8b9b9ff';
        
        const hName = document.createElement('span');
        hName.textContent = h.name;
        hName.className = 'absolute bottom-1 left-1 right-1 text-[9px] sm:text-[10px] font-medium text-red-700 dark:text-red-400  leading-tight line-clamp-1 sm:line-clamp-2';

        hName.title = h.name;

       

        cell.appendChild(hName);

        cell.addEventListener('click', () => {
        const rect = cell.getBoundingClientRect(); // get cell position
        const popup = document.createElement('div');
        popup.textContent = h.name;
        popup.className = 'absolute z-50 p-2 bg-white dark:bg-gray-800 dark:text-gray-200 rounded shadow text-xs';
        
        // position popup above the cell
        popup.style.position = 'absolute';
        popup.style.left = `${rect.left + window.scrollX}px`; // align with cell left
        popup.style.top = `${rect.top + window.scrollY - 30}px`; // 30px above cell
        popup.style.whiteSpace = 'nowrap';
        
        document.body.appendChild(popup);                                               
        setTimeout(() => popup.remove(), 2000); // remove after 2s
    });
        

        
    }
  });




        const type = state.leaves[key];
 
        if(type){
           const leave = state.replacementDates[key]; // or any way you store start/end per key
         const formattedStart = new Date(leave?.start || key).toLocaleDateString('en-GB');
        const formattedEnd = new Date(leave?.end || key).toLocaleDateString('en-GB');

          cell.classList.add("relative", "group");
          const color = {vacation:'bg-brand-500', sick:'bg-rose-500', unpaid:'bg-amber-500', replacement:'bg-violet-500'}[type];
          const dot = document.createElement('span');
          dot.className = `absolute top-1 right-2 sm:top-2 sm:right-2 h-2.5 w-2.5 rounded-full ${color}`;
          
         if (type === "replacement") {
           const tooltip = document.createElement("span");
                   tooltip.textContent = `Replacement: ${formattedStart} → ${formattedEnd}`;
          tooltip.className = `
        absolute -top-8 left-1/2 -translate-x-1/2
        whitespace-nowrap rounded-md bg-black text-white text-xs
        px-2 py-1 opacity-0 pointer-events-none
        transition-opacity duration-200
        group-hover:opacity-100
        `;
        cell.appendChild(tooltip);
        }
          cell.appendChild(dot);
        }

          

        // const rType = state.replacementLeaves[key];
        // if (rType){
        //   const dot = document.createElement('span');
        //   dot.className = 'absolute bottom-2 right-2 h-2.5 w-2.5 rounded-full bg-violet-500';
        //   cell.appendChild(dot);
        // }

        if (state.dayMarkers[key]) {
          /*const dot = document.createElement('span');
          dot.className = 'absolute bottom-2 right-2 h-2.5 w-2.5 rounded-full';
          dot.style.backgroundColor = state.dayMarkers[key].color;
          cell.appendChild(dot);*/
        

          cell.style.backgroundColor = state.dayMarkers[key].color;
          cell.classList.remove('bg0white', 'dark:bg-neutral-950');
          //cell.style.color = '#7ec800ff';

          
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

    function updateReplacementUI(){
      const type = String(typeSelect.value);

      const startLabel = document.querySelector('label[for="startDate"]');
      const endLabel = document.querySelector('label[for="endDate"]');

      if (type === '4'){
        startLabel.textContent = 'Choose the date you are replacing:';
        endLabel.textContent = 'Replacement day:';
      }
      else {
        startLabel.textContent = 'Start date';
        endLabel.textContent = 'End date';
      }
    }
    typeSelect.addEventListener('change', updateReplacementUI);



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

      if(!type || !start || !end ){ showError('Please complete all required fields.'); return; }
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
      const days = inclusiveDays(start, end);
      for(let i=0;i<days;i++){
        const cur = new Date(d1); cur.setDate(cur.getDate() + i);
        state.leaves[ymd(cur)] = type;
      }
      state.usedDays = Math.min(state.totalDays, state.usedDays + days);
      renderKpis();
      renderCalendar();
      closeModal();

      await Swal.fire({
        icon: 'success',
        title: "Kerkesa eshte derguar me sukses", 
        confirmButtonText: 'OK',
      });


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

 

async function loadLeaveHistory() {
  showLeaveHistorySkeleton();

try {
  const res = await fetch('/leave-history', {
    headers : {'Accept': 'application/json'}
  });


  const json = await res.json();

  function mapType(name) {
    //if(!unpaid) return 'unpaid';
    name = name.toLowerCase();
    if(name.includes('vacation')) return 'vacation';
    if (name.includes('sick')) return 'sick';
    if (name.includes('unpaid')) return 'unpaid';
    if (name.includes('replacement')) return 'replacement';
    //return 'unpaid';
  }

  const items = json.data.map(item => ({
    type: mapType(item.leave_type_name),
    status: item.status,
    start: item.start_date,
    days: item.days,
    end: item.end_date,
    medical_certificate_path: item.medical_certificate_path,
  }));


  renderLeaveHistory('leaveHistory', items);
populateReplacementDates(items);
  const approvedLeaves = items.filter(item => item.status === 'approved');
  state.leaves = {};
  state.replacementLeaves = {};

  approvedLeaves.forEach (leave =>{
        const start = new Date(leave.start);
        const end = new Date(leave.end);

        if(leave.type === 'replacement') {
          
             state.leaves[ymd(start)] = leave.type;
             state.leaves[ymd(end)] = leave.type;
             /*for (let d = new Date(start); d <= end; d.setDate(d.getDate() +1)) {
              const key = ymd(d);
              state.replacementLeaves[key] = leave.type;
             }*/

              // state.replacementDates[ymd(start)] = 'replacementstart';
              // state.replacementDates[ymd(end)] = 'replacementend';
              
        } 
        else 
          {
        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
          const key = ymd(d);
          state.leaves[key] = leave.type;
         
        }
      }
  });


 
}catch (err) {
  console.error('Error loading leave history:' , err);
  renderLeaveHistory('leaveHistory', []);
}
}
function populateReplacementDates(items) {
  state.replacementDates = {}; // reset

  items.forEach(item => {
    if (item.type === "replacement") {
      const start = new Date(item.start);
      const end   = new Date(item.end);

      // Fill every day in the range
      for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
        const key = d.toISOString().slice(0, 10); // YYYY-MM-DD
        state.replacementDates[key] = { start: item.start, end: item.end };
      }
    }
  });
}




  async function loadHolidays() {
    try{
      const res = await fetch('/holidays', {
        headers: {'Accept': 'application/json'}
      });
      const json = await res.json();
      /*json.data.forEach(h => {
        state.dayMarkers[h.date] = {
          color: h.color,
          name: h.name
        };
      });*/

      state.holidays = json.data.map(h => {
        const parts = h.date.split('-');
        return {
          year: parseInt(parts[0], 10),
          month: parseInt(parts[1], 10),
          day: parseInt(parts[2], 10),
          name: h.name,
          
        };
      });


      renderCalendar();
    }
    catch (err) {
      console.error('Error loding holidays:' , err);
    }
  }

  async function loadWeekendHolidays() {
    try {
      //const year = state.viewDate.getFullYear();
      const res = await fetch('/holidays/weekend',
        {
          headers: {'Accept': 'application/json'} 
        }
      );
      const json = await res.json();

      const weekendHolidays = json.data.map(h => {
        const parts = h.date.split('-');
        return {
          year: parseInt(parts[0], 10),
          month: parseInt(parts[1], 10),
          day: parseInt(parts[2], 10),
          name: h.name,
          color: h.color || '#c8efbcff',
        };
      });


      weekendHolidays.forEach (h => {
        const key = `${h.year}-${String(h.month).padStart(2,'0')}-${String(h.day).padStart(2, '0')}`;
        state.dayMarkers[key] = { name: h.name, color: h.color};
      });

      renderCalendar();
    }
    catch(err) {
      console.error('Error loading weekend holidays:', err);
    }
  }




const TYPE_LABEL = { vacation:'Vacation', sick:'Sick Leave', replacement: 'Replacement', unpaid:'Unpaid' };
const TYPE_BADGE = {
  vacation: 'bg-brand-50 text-brand-700 border-brand-200 dark:bg-brand-500/10 dark:text-brand-300 dark:border-brand-900/40',
  sick:     'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:border-rose-900/40',
  replacement: 'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-500/10 dark:text-violet-300 dark:border-violet-900/40',
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
    el.className = 'rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-gray-700 px-4 py-3';
    el.innerHTML = `
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
          <span class="text-[11px] px-2 py-1 rounded-md border ${tClass} capitalize">${tLabel}</span>
          <span class="text-[11px] px-2 py-1 rounded-md border ${sClass} capitalize">${item.status}</span>  
        ${item.medical_certificate_path ? `
  <a href="${item.medical_certificate_path}" target="_blank"
     class="text-blue-600 inline-flex items-center gap-2 ml-2 flex-shrink-0 relative z-10"
     aria-label="Open medical certificate" title="Open medical certificate">
     <div class="h-9 w-9 rounded-xl text-gray-400 flex items-center justify-center dark:bg-gray-700/20 dark:text-gray-400">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block"
         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round" preserveAspectRatio="xMidYMid meet">
      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
      <path d="M14 2v6h6" />
      <path d="M8 13h8" />
      <path d="M8 10h8" />
      <path d="M8 16h5" />
    </svg>
  </div>
  </a>
` : ''}
        </div>
      </div>

      <div class="mt-2 flex flex-wrap items-center gap-4 text-sm">
        <div class="flex items-center gap-2 text-neutral-600 dark:text-gray-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M8 2v4M16 2v4M3 10h18M4 6h16a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z"/>
          </svg>
          <span>${fmtRange(item.start, item.end)}</span>
        </div>
        <div class="flex items-center gap-2 text-neutral-600 dark:text-gray-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>
          </svg>
          <span>${item.days} days</span>
        </div>
      </div>
    `;

    //if(item.medical_certificate_path){
      //const link = document.createElement('a');
      //link.href = item.medical_certificate_path;
      //link.target = "_blank";
      //link.textContent = "Open PDF:";
      //link.className = "text-blue-600 underline mt-2 block";
      //el.appendChild(link);

    //}

    //link.className = "text-blue-600 mt-2 inline-flex items-center gap-1";

  //Add an SVG document icon
  //link.innerHTML = `
   // <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      //<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
      //<path d="M14 2v6h6"/>
    //</svg>
    
  //`;
    

  //el.appendChild(link);
    
   // }
    root.appendChild(el);
  });
}


const infoBtn = document.getElementById('statusInfoBtn');

    if(infoBtn) {
        infoBtn.addEventListener('click', () =>{
            Swal.fire({
                icon: 'info',
                title: '',
                html: `This page helps you manage your vacations and time off.
Days highlighted in green (like Monday or Tuesday) indicate a weekend holiday.
You can request leave in various categories: <b>Vacation,</b> <b>Sick Leave,</b> <b>Replacement,</b> or <b>Other</b>.
You can also replace days you have already taken as leave.
Check your <b>Leave History</b> to see which requests have been approved or rejected.`,
                showCloseButton: true,
                
            })
        })
    }




    const infoBtnForwarded = document.getElementById('statusInfoBtnForwarded');

    if(infoBtnForwarded) {
        infoBtnForwarded.addEventListener('click', () =>{
            Swal.fire({
                icon: 'info',
                title: '',
                html: `Forwarded days are unused leave days carried over from the previous year.
If you do not use all of your annual leave, the remaining days are forwarded to the next year.
These forwarded days are valid until 31 March of the current year and will expire after that date.
When submitting a leave request, any available forwarded days will be used first.`,
                showCloseButton: true,
                
            })
        })
    }


 // -------- Boot --------
     updateMedicalVisibility(); loadLeaveHistory(); renderCalendar(); loadHolidays(); loadWeekendHolidays();


}); 