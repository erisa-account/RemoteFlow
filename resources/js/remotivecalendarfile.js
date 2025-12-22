import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function () {

  
  const calendarEl = document.getElementById('calendar');

  const calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: window.innerWidth < 640 ? 'timeGridDay' : 'dayGridMonth',
    dayMaxEventRows: true,
    moreLinkClick: 'popover',
    height: 'auto',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title', 
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    buttonText: { today:'Today', month:'Month', week:'Week', day:'Day' },

    // If your endpoint returns { data: [...] }, use this instead:
    events: function(fetchInfo, success, failure) {
        fetch('/api/statusesnotonsite')
        .then(res => res.json()) 
        .then(json => success(Array.isArray(json.data) ? json.data : json))
        .catch(failure);
    },
        
    // Let multiple employees stack in the same day
    //dayMaxEventRows: true,   // shows “+ n more” when crowded
    //moreLinkClick: 'popover', // nice UX for overflow

    // Tailwind “badges” for each event
   eventContent: function(arg) {
    const container = document.createElement('div');
    container.className = 'text-[10px] sm:text-xs leading-tight break-words';

    const badge = document.createElement('div');
    badge.className = 'px-1 py-0.5 rounded font-medium bg-opacity-10 truncate whitespace-normal sm:whitespace-nowrap';
    badge.style.borderLeft = `3px solid ${arg.event.backgroundColor || arg.event.color || '#6b7280'}`;
    badge.innerText = arg.event.title;

    container.appendChild(badge);  

    // Minimal click popup for mobile
    container.addEventListener('click', (e) => {
        e.stopPropagation(); // prevent other click events
        // Remove existing popups
        const existing = document.querySelector('.event-tooltip');
        if (existing) existing.remove();

        // Position popup above the badge
        const rect = badge.getBoundingClientRect();
        const popup = document.createElement('div');
        popup.textContent = arg.event.title;
        popup.className = 'event-tooltip absolute z-50 p-2 bg-white dark:bg-gray-200 border rounded shadow text-xs';
        popup.style.position = 'absolute';
        popup.style.left = `${rect.left + window.scrollX}px`;
        popup.style.top = `${rect.top + window.scrollY - 30}px`;
        popup.style.whiteSpace = 'nowrap';

        document.body.appendChild(popup);
        setTimeout(() => popup.remove(), 2000); // remove after 2s
    });

    return { domNodes: [container] };
},


    datesSet: function() {
      // Tailwind polish for FullCalendar chrome
      document.querySelectorAll('.fc-button').forEach(btn => {
        btn.classList.add(
          'bg-blue-500','hover:bg-blue-600','text-white','font-medium',
          'py-1','px-2','sm:px-3','rounded-md','transition','duration-150','ease-in-out','mx-0.5'
        );
      });
      document.querySelectorAll('.fc-toolbar-title').forEach(t => {
        t.classList.add('text-xl','font-semibold', 'dark:text-gray-400');
      }); 
      document.querySelectorAll('.fc-daygrid-day').forEach(cell => {
        cell.classList.add('hover:bg-blue-50','transition','duration-100');
      });
      document.querySelectorAll('.fc-scrollgrid').forEach(grid => {
      grid.classList.add('rounded-lg');
      });
       const thead = document.querySelector("thead[role='rowgroup']");
      if (thead) {
        thead.classList.add("custom-thead-bg");
      }
      
    }
  });

  calendar.render();
});



