
document.addEventListener("DOMContentLoaded", function () {
    fetch('/api/statuses')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('status');
            select.innerHTML = '<option value="">Zgjidh një status</option>';

            data.forEach(status => {
                const option = document.createElement('option');
                option.value = status.id;
                option.textContent = status.status; // emri i statusit
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Gabim gjatë marrjes së statuseve:', error);
        });
});


// resources/js/status.js


// Define your component
function datePickerComponent() {
  return {
    datePickerOpen: false,
    datePickerValue: '',
    datePickerFormat: 'M d, Y',
    datePickerMonth: '',
    datePickerYear: '',
    datePickerDay: '',
    datePickerDaysInMonth: [],
    datePickerBlankDaysInMonth: [],
    datePickerMonthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    datePickerDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    datePickerDayClicked(day) {
      let selectedDate = new Date(this.datePickerYear, this.datePickerMonth, day);
      this.datePickerDay = day;
      this.datePickerValue = this.datePickerFormatDate(selectedDate);
      this.datePickerIsSelectedDate(day);
    },
    datePickerPreviousMonth() {
      if (this.datePickerMonth == 0) {
        this.datePickerYear--;
        this.datePickerMonth = 12;
      }
      this.datePickerMonth--;
      this.datePickerCalculateDays();
    },
    datePickerNextMonth() {
      if (this.datePickerMonth == 11) {
        this.datePickerMonth = 0;
        this.datePickerYear++;
      } else {
        this.datePickerMonth++;
      }
      this.datePickerCalculateDays();
    },
    datePickerIsSelectedDate(day) {
      const d = new Date(this.datePickerYear, this.datePickerMonth, day);
      return this.datePickerValue === this.datePickerFormatDate(d);
    },
    datePickerIsToday(day) {
      const today = new Date();
      const d = new Date(this.datePickerYear, this.datePickerMonth, day);
      return today.toDateString() === d.toDateString();
    },
    datePickerCalculateDays() {
      let daysInMonth = new Date(this.datePickerYear, this.datePickerMonth + 1, 0).getDate();
      let dayOfWeek = new Date(this.datePickerYear, this.datePickerMonth).getDay();
      let blankdaysArray = [];
      for (let i = 1; i <= dayOfWeek; i++) blankdaysArray.push(i);
      let daysArray = [];
      for (let i = 1; i <= daysInMonth; i++) daysArray.push(i);
      this.datePickerBlankDaysInMonth = blankdaysArray;
      this.datePickerDaysInMonth = daysArray;
    },
    datePickerFormatDate(date) {
      let formattedDay = this.datePickerDays[date.getDay()];
      let formattedDate = ('0' + date.getDate()).slice(-2);
      let formattedMonth = this.datePickerMonthNames[date.getMonth()];
      let formattedMonthShort = formattedMonth.substring(0, 3);
      let formattedMonthNum = ('0' + (date.getMonth() + 1)).slice(-2);
      let formattedYear = date.getFullYear();

      switch (this.datePickerFormat) {
        case 'M d, Y': return `${formattedMonthShort} ${formattedDate}, ${formattedYear}`;
        case 'MM-DD-YYYY': return `${formattedMonthNum}-${formattedDate}-${formattedYear}`;
        case 'DD-MM-YYYY': return `${formattedDate}-${formattedMonthNum}-${formattedYear}`;
        case 'YYYY-MM-DD': return `${formattedYear}-${formattedMonthNum}-${formattedDate}`;
        case 'D d M, Y': return `${formattedDay} ${formattedDate} ${formattedMonthShort} ${formattedYear}`;
        default: return `${formattedMonth} ${formattedDate}, ${formattedYear}`;
      }
    },
  };
}

// Optional: start Alpine if you haven't already somewhere else

window.datePickerComponent = datePickerComponent;