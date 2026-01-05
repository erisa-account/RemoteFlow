@extends ('layouts.user')
@section('content')

    
    <head>
         @vite(['resources/css/app.css', 'resources/js/status.js', 'resources/js/remotivecalendarfile.js']) 
</head>

<div class="max-w-full w-full rounded-2xl shadow-lg p-4 sm:p-6 dark:bg-gray-800 dark:text-gray-400 transition-colors duration-300">
   <div class="flex items-center justify-center ">
      <h1 class="text-2xl font-semibold text-gray-800 mr-4 text-center dark:bg-gray-800 dark:text-gray-400 transition-colors duration-300">📅 Kalendari C7</h1>

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
  <div id="calendar" class=""></div>
</div> 

   





@endsection 
