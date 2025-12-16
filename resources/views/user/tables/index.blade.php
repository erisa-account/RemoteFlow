


@extends ('layouts.user')
@section ('content')

<head>
    @vite(['resources/css/app.css', 'resources/js/uploademail.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>


<div class="flex items-center justify-center"> 
    <!-- Author: FormBold Team -->
    <div class="mx-auto w-full max-w-[550px]"> 
        <section class="mx-auto max-w-2xl">
        <div
          class="relative overflow-hidden  rounded-xl bg-gradient-to-b from-indigo-200/30 to-transparent dark:from-indigo-500/10 border border-slate-200/70 p-6 mt-7 mb-5 shadow-xl backdrop-blur-sm dark:border-slate-700/60 dark:bg-slate-900/70">
        <!-- subtle gradient glow -->
       
        <form id="uploadform" method="POST" action="{{ route('email.send') }}" enctype="multipart/form-data" class="py-4 px-9">
        @csfr 


       <div class="flex items-center gap-4"> 
      <div
        class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-md">
        <!-- mail icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
          <path
            d="M20 8v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8m16 0-6.553 4.37a3 3 0 0 1-3.294 0L4 8m16 0a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2" />
        </svg>
      </div>
      <div>
        <h2 class="text-xl font-semibold tracking-tight text-slate-900 dark:text-white">Send files via email</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Attach files and send them with your message.</p>
      </div>
       </div>

             

      <div class="space-y-2 mt-6">
        <label for="subject" class="text-sm font-medium text-slate-700 dark:text-slate-300">Send files to this email</label>
        <div class="flex items-center gap-3 rounded-2xl border border-slate-300 bg-white px-4 py-1 shadow-sm focus-within:ring-4 ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900">
         <svg class="h-5 w-5 text-slate-400 transition group-focus-within:text-indigo-500 dark:text-slate-500"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M22 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-15A2.5 2.5 0 0 1 2 17.5v-11A2.5 2.5 0 0 1 4.5 4h15A2.5 2.5 0 0 1 22 6.5Zm-2 .1L12.7 12a1.5 1.5 0 0 1-1.4 0L4 6.6v10.9A1.5 1.5 0 0 0 5.5 19h13a1.5 1.5 0 0 0 1.5-1.5Z" />
          </svg>
        <input type="email" name="email" id="email" placeholder="example@domain.com" required
          class="w-full text-slate-900 outline-none border-0 focus:ring-0 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 bg-white" />
          </div>
          </div>

         
      <div class="space-y-2 mt-6">
        <label for="subject" class="text-sm font-medium text-slate-700 dark:text-slate-300">The subject of email</label>
        <div class="flex items-center gap-3 rounded-2xl border border-slate-300 bg-white px-4 py-1 shadow-sm focus-within:ring-4 ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-900">
         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 13H8M16 17H8"/>
          </svg>
        <input type="text" name="subject" id="subject" placeholder="Remote schedule" required
          class="w-full text-slate-900 outline-none border-0 focus:ring-0 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 bg-white" />
          </div>
          </div>
      

            <div class="space-y-2 mt-6">
        <label for="description" class="text-sm font-medium text-slate-700 dark:text-slate-300">Description</label>
        <textarea name="description" id="description" rows="4" placeholder="Write the text body of email"
          class="w-full resize-y rounded-2xl border border-slate-300 bg-white px-4 py-3 shadow-sm outline-none ring-indigo-500/20 transition focus:ring-4 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"></textarea>
      </div>


            

                 <!-- Upload -->
      <div class="space-y-2 mt-6">
        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Upload File</p>

        <div id="dropzone"
          class="group rounded-3xl border-2 border-dashed border-slate-300 bg-slate-50/60 p-6 text-center transition hover:border-indigo-400 hover:bg-indigo-50/40 dark:border-slate-700 dark:bg-slate-800/40 dark:hover:border-indigo-500/70">
          <input type="file" name="file[]" id="file" multiple class="hidden" />
          <div class="mx-auto grid max-w-sm place-items-center gap-3">
          <div class="flex items-center justify-center h-12 w-12 rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80 dark:bg-slate-900 dark:ring-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V6" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 10l4-4 4 4" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16" />
            </svg>
          </div>
            <p class="text-sm text-slate-600 dark:text-slate-400">
              <span class="font-medium text-slate-800 dark:text-slate-200">Drop files here</span>
              or
              <button type="button" id="browseBtn"
                class="font-semibold text-indigo-600 underline-offset-4 hover:underline dark:text-indigo-400">Browse</button>
            </p>
            <p class="text-xs text-slate-400 dark:text-slate-500">PDF, DOCX, PNG, JPG up to 25MB each</p>
          </div>
        </div>

        <!-- File list -->
        <ul id="fileList" class="space-y-2"></ul>
      </div>

    
            
            <div class="pt-2">
        <button  type="submit" id="sendemail"
          class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-3 font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:from-indigo-500 hover:to-violet-500 focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-500/40 active:scale-[.99]">
          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="m3.4 20.6 17.2-8.27a1 1 0 0 0 0-1.8L3.4 2.26A1 1 0 0 0 2 3.2l2.07 6.11a1 1 0 0 0 .79.67l7.57 1.19-7.57 1.19a1 1 0 0 0-.79.67L2 20.8a1 1 0 0 0 1.4.8Z" />
          </svg>
          <span>Send email</span>
        </button>
      </div>

        </form>
</section>
    </div>
</div>



@endsection