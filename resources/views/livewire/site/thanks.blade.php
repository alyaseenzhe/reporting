<x-guest-layout>
<div>
<div class="w-full">
    <img src="{{asset('images/banner.png')}}" width="100%" />
</div>
{{--@livewireScripts--}}


<div class="grid grid-cols-2 gap-2 bg-white p-5  max-w-9xl mx-auto my-8">

    <div class="mx-auto flex justify-center">
       <div>
           <div class="mx-auto flex justify-center">
        <svg fill="none" width="150px" height="150px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <!-- Circle path -->
            <path
                d="M20.94,11A8.26,8.26,0,0,1,21,12a9,9,0,1,1-9-9,8.83,8.83,0,0,1,4,1"
                stroke="green"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                fill="none"
            >
                <animate
                    attributeName="stroke-dasharray"
                    from="0, 100"
                    to="56.5, 0"
                    dur="1s"
                    fill="freeze"
                />
            </path>

            <!-- Check mark -->
            <polyline
                points="21 5 12 14 8 10"
                stroke="green"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                fill="none"
                stroke-dasharray="20"
                stroke-dashoffset="20"
            >
                <animate
                    attributeName="stroke-dashoffset"
                    from="20"
                    to="0"
                    dur="0.6s"
                    begin="1s"
                    fill="freeze"
                />
            </polyline>
        </svg>
           </div>

        <h1 class="text-3xl text-center">
            نشكرك على رسالتك وسيتم التواصل معك قريبا
        </h1>

       </div>

     </div>

</div>



</div>
</x-guest-layout>
