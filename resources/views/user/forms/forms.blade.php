


@extends ('layouts.user')
@section('content')
    
    
<!-- 

<div class="flex items-center justify-center">
    Author: FormBold Team -->
     
    <div class="mx-auto w-full max-w-[550px] bg-white">
        <form class="py-4 px-9 pt-10" id="checkin-form">
           

            <!--<label for="hs-hidden-select" class="sr-only">Label</label>-->
            <label for="status" class="block mb-1 text-sm font-medium text-neutral-500">Select status</label>
<select id="status" name="status_id" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-400 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
  <option selected="" value="">Select a status</option>

  <option value=""></option>

</select>
            <div class="mb-6 pt-4">
                <!--<label class="mb-5 block text-xl font-semibold text-[#07074D]">
                    Upload File
                </label>

                <div class="mb-8">
                    <input type="file" name="file" id="file" class="sr-only" />
                    <label for="file"
                        class="relative flex min-h-[200px] items-center justify-center rounded-md border border-dashed border-[#e0e0e0] p-12 text-center">
                        <div>
                            <span class="mb-2 block text-xl font-semibold text-[#07074D]">
                                Drop files here
                            </span>
                            <span class="mb-2 block text-base font-medium text-[#6B7280]">
                                Or
                            </span>
                            <span
                                class="inline-flex rounded border border-[#e0e0e0] py-2 px-7 text-base font-medium text-[#07074D]">
                                Browse
                            </span>
                        </div>
                    </label>
                </div>-->

                
<div x-data="{
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
      datePickerPreviousMonth(){
        if (this.datePickerMonth == 0) { 
            this.datePickerYear--; 
            this.datePickerMonth = 12; 
        } 
        this.datePickerMonth--;
        this.datePickerCalculateDays();
      },
      datePickerNextMonth(){
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
        return this.datePickerValue === this.datePickerFormatDate(d) ? true : false;
      },
      datePickerIsToday(day) {
        const today = new Date();
        const d = new Date(this.datePickerYear, this.datePickerMonth, day);
        return today.toDateString() === d.toDateString() ? true : false;
      },
      datePickerCalculateDays() {
        let daysInMonth = new Date(this.datePickerYear, this.datePickerMonth + 1, 0).getDate();
        // find where to start calendar day of week
        let dayOfWeek = new Date(this.datePickerYear, this.datePickerMonth).getDay();
        let blankdaysArray = [];
        for (var i = 1; i <= dayOfWeek; i++) {
            blankdaysArray.push(i);
        }
        let daysArray = [];
        for (var i = 1; i <= daysInMonth; i++) {
            daysArray.push(i);
        }
        this.datePickerBlankDaysInMonth = blankdaysArray;
        this.datePickerDaysInMonth = daysArray;
      },
      datePickerFormatDate(date) {
        let formattedDay = this.datePickerDays[date.getDay()];
        let formattedDate = ('0' + date.getDate()).slice(-2); // appends 0 (zero) in single digit date
        let formattedMonth = this.datePickerMonthNames[date.getMonth()];
        let formattedMonthShortName = this.datePickerMonthNames[date.getMonth()].substring(0, 3);
        let formattedMonthInNumber = ('0' + (parseInt(date.getMonth()) + 1)).slice(-2);
        let formattedYear = date.getFullYear();

        if (this.datePickerFormat === 'M d, Y') {
          return `${formattedMonthShortName} ${formattedDate}, ${formattedYear}`;
        }
        if (this.datePickerFormat === 'MM-DD-YYYY') {
          return `${formattedMonthInNumber}-${formattedDate}-${formattedYear}`;
        }
        if (this.datePickerFormat === 'DD-MM-YYYY') {
          return `${formattedDate}-${formattedMonthInNumber}-${formattedYear}`;
        }
        if (this.datePickerFormat === 'YYYY-MM-DD') {
          return `${formattedYear}-${formattedMonthInNumber}-${formattedDate}`;
        }
        if (this.datePickerFormat === 'D d M, Y') {
          return `${formattedDay} ${formattedDate} ${formattedMonthShortName} ${formattedYear}`;
        }
        
        return `${formattedMonth} ${formattedDate}, ${formattedYear}`;
      },
    }" x-init="
    let currentDate = new Date();

    // if a value already exists, parse it instead of resetting
    if (datePickerValue) {
        currentDate = new Date(Date.parse(datePickerValue));
    } else {
        datePickerValue = datePickerFormatDate(currentDate);
    }

    datePickerMonth = currentDate.getMonth();
    datePickerYear = currentDate.getFullYear();
    datePickerDay = currentDate.getDate();  

    datePickerCalculateDays();
"
    x-cloak>
    <div class="container  mx-auto md:py-2">
        <div class="mb-5">
            <label for="datepicker" class="block mb-1 text-sm font-medium text-neutral-500">Select Date</label>
            <div class="relative w-full">  
                <input x-ref="datePickerInput" type="text" id="datepicker" name="date"  @click="datePickerOpen=!datePickerOpen" x-model="datePickerValue" x-on:keydown.escape="datePickerOpen=false" class="flex px-3 py-2 w-full h-10 text-sm bg-white rounded-md border text-neutral-600 border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" placeholder="Select date" readonly />
                <div @click="datePickerOpen=!datePickerOpen; if(datePickerOpen){ $refs.datePickerInput.focus() }" class="absolute top-0 right-0 px-3 py-2 cursor-pointer text-neutral-400 hover:text-neutral-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <div  
                    x-show="datePickerOpen"
                    x-transition
                    @click.away="datePickerOpen = false" 
                    class="absolute top-0 left-0 z-50 max-w-lg p-4 mt-12 antialiased bg-white border rounded-lg shadow w-[17rem] border-neutral-200/70">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <span x-text="datePickerMonthNames[datePickerMonth]" class="text-lg font-bold text-gray-800"></span>
                            <span x-text="datePickerYear" class="ml-1 text-lg font-normal text-gray-600"></span>
                        </div>
                        <div>
                            <button @click="datePickerPreviousMonth()" type="button" class="inline-flex p-1 rounded-full transition duration-100 ease-in-out cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                                <svg class="inline-flex w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            </button>
                            <button @click="datePickerNextMonth()" type="button" class="inline-flex p-1 rounded-full transition duration-100 ease-in-out cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                                <svg class="inline-flex w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 mb-3">
                        <template x-for="(day, index) in datePickerDays" :key="index">
                            <div class="px-0.5">
                                <div x-text="day" class="text-xs font-medium text-center text-gray-800"></div>
                            </div>
                        </template>
                    </div>
                    <div class="grid grid-cols-7">
                        <template x-for="blankDay in datePickerBlankDaysInMonth">
                            <div class="p-1 text-sm text-center border border-transparent"></div>
                        </template>
                        <template x-for="(day, dayIndex) in datePickerDaysInMonth" :key="dayIndex">
                            <div class="px-0.5 mb-1 aspect-square">
                                <div 
                                    x-text="day"
                                    @click="datePickerDayClicked(day)" 
                                    :class="{
                                        'bg-neutral-200': datePickerIsToday(day) == true, 
                                        'text-gray-600 hover:bg-neutral-200': datePickerIsToday(day) == false && datePickerIsSelectedDate(day) == false,
                                        'bg-neutral-800 text-white hover:bg-neutral-800/70': datePickerIsSelectedDate(day) == true
                                    }" 
                                    class="flex justify-center items-center w-7 h-7 text-sm leading-none text-center rounded-full cursor-pointer"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 












            <div>
                <button 
                    type="submit" class="hover:shadow-form w-full rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none">
                    Check in
                </button>
            </div>
        </form>
        <div id="error-messages" class="text-red-500 mt-2"></div>
        <div id="success-message" class="text-green-500 mt-2"></div>
    </div>
</div> -->





<script>
document.getElementById('checkin-form').addEventListener('submit', async function(e) {
    e.preventDefault(); // prevent normal form submission

    // clear previous messages
    document.getElementById('error-messages').innerHTML = '';
    document.getElementById('success-message').innerHTML = '';

    const status_id = document.getElementById('status').value;
    const date = document.getElementById('datepicker').value;

    try {
        const response = await fetch('{{ route("checkin.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status_id: status_id,
                date: date
            })
        });

        const data = await response.json();

        if (!response.ok) {
            // show validation errors
            if (data.errors) {
                for (const key in data.errors) {
                    document.getElementById('error-messages').innerHTML += data.errors[key].join('<br>') + '<br>';
                }
            } else if (data.message) {
                document.getElementById('error-messages').innerText = data.message;
            }
        } else {
            document.getElementById('success-message').innerText = data.message;
        }

    } catch (error) {
        document.getElementById('error-messages').innerText = 'Something went wrong. Please try again.';
        console.error(error);
    }
});
</script>
 
@endsection 


    





