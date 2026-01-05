
@extends ('layouts.user')
@section('content')

    
    
         @vite(['resources/css/app.css', 'resources/js/status.js', 'resources/js/alerts.js']) 


     
    <div class="mx-auto w-full max-w-[550px] mt-12">


     <div
    class="relative rounded-3xl border border-slate-200/70 bg-white/80 p-6 shadow-xl backdrop-blur-sm dark:border-slate-700/60 dark:bg-[#7a7ea830] bg-gradient-to-b from-indigo-200/30 to-transparent dark:from-indigo-500/10 ">
    

     
    <div class="flex items-center justify-center">
        <span class="text-xl font-semibold tracking-tight text-slate-900 dark:text-white">Select your status for your day</span>
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
    
    
    <form action="{{ route('user.checkin.store') }}" class="py-4 px-9 pt-10" id="checkin-form" method="POST">
    @csrf

           
            
            <label for="status" class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-300">Select status</label>

           <div class="relative">
  <!-- 👇 The icon -->
  <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 dark:text-slate-500"
       viewBox="0 0 24 24" fill="currentColor">
    <path d="M12 14a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-4.43 0-8 1.79-8 4v1h16v-1c0-2.21-3.57-4-8-4Z" />
  </svg>

            <select id="status" name="status_id" class=" ps-10 py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-400 dark:text-neutral-400 dark:placeholder-neutral-500 dark:bg-[#e5e6ef30] dark:focus:ring-neutral-600">
            <option selected="" value="">Select the status</option>
            <option value=""></option>
 
            </select>
</div>

            <span id="status-error" class="text-red-500 text-sm mt-1"></span>

            <div class="mb-6 pt-4">
                
                
<div 
  x-data="datePickerComponent()" 
  x-init="
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
  x-cloak
>
    <div class="container  mx-auto md:py-2">
        <div class="mb-5">
            <label for="datepicker" class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-300">Select Date</label>
            <div class="relative w-full">  
                <input x-ref="datePickerInput" type="text" id="datepicker" name="date"  @click="datePickerOpen=!datePickerOpen" x-model="datePickerValue" x-on:keydown.escape="datePickerOpen=false" class="flex px-3 py-2 w-full h-10 text-sm bg-white rounded-md border border-neutral-300 ring-offset-background dark:bg-[#e5e6ef30] dark:text-neutral-400 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed disabled:opacity-50" placeholder="Select date" readonly />
                <div @click="datePickerOpen=!datePickerOpen; if(datePickerOpen){ $refs.datePickerInput.focus() }" class="absolute top-0 right-0 px-3 py-2 cursor-pointer text-neutral-400 hover:text-neutral-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <div @click="datePickerOpen = !datePickerOpen; if(datePickerOpen){ $refs.datePickerInput.focus() }"
                    class="absolute top-0 right-0 px-3 py-2 cursor-pointer text-neutral-400 hover:text-neutral-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
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
         <span id="date-error" class="text-red-500 text-sm mt-1 "></span>

            <div>
                   <button 
                    type="submit" 
                    class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-3 text-center text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:from-indigo-500 hover:to-violet-500 focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-500/40 active:scale-[.99]">
                    Check in 
                </button>
            </div>
        </form>
    </div>
        
        
        <div id="error-messages" class="text-red-500 mt-2"></div>
        <div id="success-message" class="text-green-500 mt-2"></div>
    </div>
</div> 




 
@endsection 


    





