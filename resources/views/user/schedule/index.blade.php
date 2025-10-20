@extends ('layouts.user')
@section('content')

    
    <head>
         @vite(['resources/css/app.css', 'resources/js/status.js', 'resources/js/remotivecalendarfile.js']) 
</head>

<div class="max-w-full w-full bg-white rounded-2xl shadow-lg p-4 sm:p-6 dark:bg-gray-800 dark:text-gray-400 transition-colors duration-300">
  <h1 class="text-2xl font-semibold text-gray-800 mb-4 text-center dark:bg-gray-800 dark:text-gray-400 transition-colors duration-300">📅 Kalendari C7</h1>
  <div id="calendar" class="w-full overflow-hidden text-[10px] sm:text-xs md:text-sm"></div>
</div>







@endsection 
