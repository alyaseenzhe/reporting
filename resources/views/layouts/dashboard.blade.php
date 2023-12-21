<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>شركة الياسين الزراعية</title>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet"/>
    @yield('css-scripts')
    @livewireStyles
</head>
<body
    id="body-content"
    class="font-inter antialiased bg-gray-100 text-gray-600"
    :class="{ 'sidebar-expanded': sidebarExpanded }"
    x-data="{ page: 'ecommerce-customers', sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }"
    x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))"
>
<script>
    if (localStorage.getItem("sidebar-expanded") == "true") {
        document.querySelector("body").classList.add("sidebar-expanded");
    } else {
        document.querySelector("body").classList.remove("sidebar-expanded");
    }
</script>
<div class="flex h-screen overflow-hidden">
    <div>
        <div class="fixed inset-0 bg-gray-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
             :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>
{{--        <div--}}
{{--            id="sidebar"--}}
{{--            class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 transform h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-gray-800 p-4 transition-all duration-200 ease-in-out"--}}
{{--            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"--}}
{{--            @click.outside="sidebarOpen = false"--}}
{{--            @keydown.escape.window="sidebarOpen = false"--}}
{{--            x-cloak="lg"--}}
{{--        >--}}
{{--            <div class="flex justify-center mb-10 pr-3 sm:px-2">--}}
{{--                <button class="lg:hidden text-gray-500 hover:text-gray-400" @click.stop="sidebarOpen = !sidebarOpen"--}}
{{--                        aria-controls="sidebar" :aria-expanded="sidebarOpen">--}}
{{--                    <span class="sr-only">Close sidebar</span>--}}
{{--                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">--}}
{{--                        <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z"/>--}}
{{--                    </svg>--}}
{{--                </button>--}}
{{--                <a class="block" href="{{route('dashboard')}}">--}}
{{--                    <img src="{{asset('images/logo-small.png')}}" alt="شركة الياسين الزراعية">--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="space-y-8">--}}
{{--                <div>--}}
{{--                    <ul class="mt-3">--}}
{{--                        <li class="px-3 py-2 rounded-sm mb-4 last:mb-0 {{ request()->routeIs('dashboard') ? 'bg-gray-900' : '' }}">--}}
{{--                            <a class="block text-gray-200 hover:text-white truncate transition duration-150"--}}
{{--                               href="{{ route('dashboard') }}">--}}
{{--                                <div class="flex items-center">--}}
{{--                                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">--}}
{{--                                        <path class="fill-current text-gray-400"--}}
{{--                                              :class="page === 'dashboard' && '!text-indigo-500'"--}}
{{--                                              d="M12 0C5.383 0 0 5.383 0 12s5.383 12 12 12 12-5.383 12-12S18.617 0 12 0z"/>--}}
{{--                                        <path class="fill-current text-gray-600"--}}
{{--                                              :class="page === 'dashboard' && 'text-indigo-600'"--}}
{{--                                              d="M12 3c-4.963 0-9 4.037-9 9s4.037 9 9 9 9-4.037 9-9-4.037-9-9-9z"/>--}}
{{--                                        <path--}}
{{--                                            class="fill-current text-gray-400"--}}
{{--                                            :class="page === 'dashboard' && 'text-indigo-200'"--}}
{{--                                            d="M12 15c-1.654 0-3-1.346-3-3 0-.462.113-.894.3-1.285L6 6l4.714 3.301A2.973 2.973 0 0112 9c1.654 0 3 1.346 3 3s-1.346 3-3 3z"--}}
{{--                                        />--}}
{{--                                    </svg>--}}
{{--                                    <span--}}
{{--                                        class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">الصفحة الرئيسية</span>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}

{{--                        <li class="px-3 py-2 rounded-sm mb-4 last:mb-0 {{ request()->routeIs('list.levels') || request()->routeIs('edit.level') || request()->routeIs('create.level') ? 'bg-gray-900' : '' }}">--}}
{{--                            <a class="block text-gray-200 hover:text-white truncate transition duration-150"--}}
{{--                               href="{{ route('list.levels') }}">--}}
{{--                                <div class="flex items-center">--}}
{{--                                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">--}}
{{--                                        <path class="fill-current text-gray-600"--}}
{{--                                              :class="page === 'analytics' && 'text-indigo-500'" d="M0 20h24v2H0z"/>--}}
{{--                                        <path--}}
{{--                                            class="fill-current text-gray-400"--}}
{{--                                            :class="page === 'analytics' && 'text-indigo-300'"--}}
{{--                                            d="M4 18h2a1 1 0 001-1V8a1 1 0 00-1-1H4a1 1 0 00-1 1v9a1 1 0 001 1zM11 18h2a1 1 0 001-1V3a1 1 0 00-1-1h-2a1 1 0 00-1 1v14a1 1 0 001 1zM17 12v5a1 1 0 001 1h2a1 1 0 001-1v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1z"--}}
{{--                                        />--}}
{{--                                    </svg>--}}
{{--                                    <span--}}
{{--                                        class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">الدرجات</span>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="px-3 py-2 rounded-sm mb-4 last:mb-0 {{ request()->routeIs('list.sports') || request()->routeIs('edit.sport') || request()->routeIs('create.sport') ? 'bg-gray-900' : '' }}">--}}
{{--                            <a class="block text-gray-200 hover:text-white truncate transition duration-150"--}}
{{--                               href="{{ route('list.sports') }}">--}}
{{--                                <div class="flex items-center">--}}
{{--                                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">--}}
{{--                                        <path--}}
{{--                                            class="fill-current text-gray-600"--}}
{{--                                            d="M20 7a.75.75 0 01-.75-.75 1.5 1.5 0 00-1.5-1.5.75.75 0 110-1.5 1.5 1.5 0 001.5-1.5.75.75 0 111.5 0 1.5 1.5 0 001.5 1.5.75.75 0 110 1.5 1.5 1.5 0 00-1.5 1.5A.75.75 0 0120 7zM4 23a.75.75 0 01-.75-.75 1.5 1.5 0 00-1.5-1.5.75.75 0 110-1.5 1.5 1.5 0 001.5-1.5.75.75 0 111.5 0 1.5 1.5 0 001.5 1.5.75.75 0 110 1.5 1.5 1.5 0 00-1.5 1.5A.75.75 0 014 23z"--}}
{{--                                        />--}}
{{--                                        <path--}}
{{--                                            class="fill-current text-gray-400"--}}
{{--                                            :class="page === 'campaigns' && 'text-indigo-300'"--}}
{{--                                            d="M17 23a1 1 0 01-1-1 4 4 0 00-4-4 1 1 0 010-2 4 4 0 004-4 1 1 0 012 0 4 4 0 004 4 1 1 0 010 2 4 4 0 00-4 4 1 1 0 01-1 1zM7 13a1 1 0 01-1-1 4 4 0 00-4-4 1 1 0 110-2 4 4 0 004-4 1 1 0 112 0 4 4 0 004 4 1 1 0 010 2 4 4 0 00-4 4 1 1 0 01-1 1z"--}}
{{--                                        />--}}
{{--                                    </svg>--}}
{{--                                    <span--}}
{{--                                        class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">الرياضات</span>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="px-3 py-2 rounded-sm mb-4 last:mb-0 {{ request()->routeIs('list.seasons') || request()->routeIs('edit.season') || request()->routeIs('create.season') || request()->routeIs('show.season') ? 'bg-gray-900' : '' }}">--}}
{{--                            <a class="block text-gray-200 hover:text-white truncate transition duration-150"--}}
{{--                               href="{{ route('list.seasons') }}">--}}
{{--                                <div class="flex items-center">--}}
{{--                                    <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24"><path class="fill-current text-gray-600" :class="page === 'calendar' &amp;&amp; 'text-indigo-500'" d="M1 3h22v20H1z"></path><path class="fill-current text-gray-400" :class="page === 'calendar' &amp;&amp; 'text-indigo-300'" d="M21 3h2v4H1V3h2V1h4v2h10V1h4v2Z"></path></svg>--}}
{{--                                    <span--}}
{{--                                        class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">المواسم الرياضية</span>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">--}}
{{--                <div class="px-3 py-2">--}}
{{--                    <button @click="sidebarExpanded = !sidebarExpanded">--}}
{{--                        <span class="sr-only">Expand / collapse sidebar</span>--}}
{{--                        <svg class="w-6 h-6 fill-current sidebar-expanded:rotate-180" viewBox="0 0 24 24">--}}
{{--                            <path class="text-gray-400"--}}
{{--                                  d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z"/>--}}
{{--                            <path class="text-gray-600" d="M3 23H1V1h2z"/>--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
        <header class="sticky top-0 bg-white border-b border-gray-200 z-30">
            <div class="p-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 -mb-px">
{{--                    <div class="flex">--}}
{{--                        <button class="text-gray-500 hover:text-gray-600 lg:hidden"--}}
{{--                                @click.stop="sidebarOpen = !sidebarOpen" aria-controls="sidebar"--}}
{{--                                :aria-expanded="sidebarOpen">--}}
{{--                            <span class="sr-only">Open sidebar</span>--}}
{{--                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                <rect x="4" y="5" width="16" height="2"/>--}}
{{--                                <rect x="4" y="11" width="16" height="2"/>--}}
{{--                                <rect x="4" y="17" width="16" height="2"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                    </div>--}}
                    <div class="flex">
                        <a class="block" href="{{route('dashboard')}}">
                            <img src="{{asset('images/logo-small.png')}}" alt="شركة الياسين الزراعية">
                        </a>
                    </div>
                    <div class="flex text-2xl font-bold">
                        @yield('fixed-title')
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="relative inline-flex" x-data="{ open: false }">

                            <div
                                class="origin-top-right z-10 absolute top-full right-0 min-w-44 bg-white border border-gray-200 py-1.5 rounded shadow-lg overflow-hidden mt-1"
                                @click.outside="open = false"
                                @keydown.escape.window="open = false"
                                x-show="open"
                                x-transition:enter="transition ease-out duration-200 transform"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-out duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                x-cloak
                            >
                            </div>
                        </div>
                        <hr class="w-px h-6 bg-gray-200"/>
                        <div class="relative inline-flex" x-data="{ open: false }">
                            <button class="flex items-center justify-center" aria-haspopup="true"
                                    @click.prevent="open = !open" :aria-expanded="open">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="icon icon-tabler icon-tabler-user w-6 h-6 shrink-0 ml-1 fill-current text-gray-400 bg-gray-100 hover:bg-gray-200 rounded-full"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="7" r="4"/>
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                </svg>
                                <div class="flex items-center truncate">
                                    <span
                                        class="truncate ml-2 text-sm font-medium group-hover:text-gray-800">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </button>
                            <div
                                class="origin-top-right z-10 absolute top-full right-0 min-w-44 bg-white border border-gray-200 py-1.5 rounded shadow-lg overflow-hidden mt-1"
                                style="direction: rtl"
                                @click.outside="open = false"
                                @keydown.escape.window="open = false"
                                x-show="open"
                                x-transition:enter="transition ease-out duration-200 transform"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-out duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                x-cloak
                            >
                                <div class="pt-0.5 pb-2 px-3 mb-1 border-b border-gray-200">
                                    <div
                                        class="font-medium text-gray-800">{{ \Illuminate\Support\Facades\Auth::user()->name }}</div>
                                </div>
                                <ul>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="font-medium text-sm text-indigo-500 hover:text-indigo-600 flex items-center py-1 px-3"
                                               href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                                تسجيل خروج
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main style="direction: rtl">
            <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
                <div class="sm:flex sm:justify-between sm:items-center mb-8">
                    {{--                    here title--}}

                    <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                        <div class="table-items-action hidden">
                            <div class="flex items-center">
                                <div class="hidden xl:block text-sm italic mr-2 whitespace-nowrap"><span
                                        class="table-items-count"></span> items selected
                                </div>
                                <button
                                    class="btn bg-white border-gray-200 hover:border-gray-300 text-red-500 hover:text-red-600">
                                    Delete
                                </button>
                            </div>
                        </div>
                        <div class="mb-4 sm:mb-0"><h1 class="text-2xl md:text-3xl text-gray-800 font-bold">@yield('title')</h1>
                        </div>
                    </div>
                    @yield('title-btn')
                </div>
                <div class="bg-white shadow-lg rounded-sm border border-gray-200 p-6">
                    <!-- content here -->
                    {{$slot}}
                </div>
                <script>
                    document.addEventListener("alpine:init", () => {
                        Alpine.data("handleSelect", () => ({
                            selectall: !1,
                            selectAction() {
                                (countEl = document.querySelector(".table-items-action")),
                                countEl &&
                                ((checkboxes = document.querySelectorAll("input.table-item:checked")),
                                    (document.querySelector(".table-items-count").innerHTML = checkboxes.length),
                                    checkboxes.length > 0 ? countEl.classList.remove("hidden") : countEl.classList.add("hidden"));
                            },
                            toggleAll() {
                                (this.selectall = !this.selectall),
                                    (checkboxes = document.querySelectorAll("input.table-item")),
                                    [...checkboxes].map((e) => {
                                        e.checked = this.selectall;
                                    }),
                                    this.selectAction();
                            },
                            uncheckParent() {
                                (this.selectall = !1), (document.getElementById("parent-checkbox").checked = !1), this.selectAction();
                            },
                        }));
                    });
                </script>

            </div>
        </main>
    </div>
</div>
@livewireScripts
<script src="{{ asset('js/custom.js') }}"></script>
@yield('scripts')
</body>
</html>
