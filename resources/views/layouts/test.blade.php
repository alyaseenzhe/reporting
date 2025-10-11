<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>شركة الياسين الزراعية</title>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet"/>
{{--    @yield('css-scripts')--}}
    @livewireStyles
</head>
<body
    id="body-content"
    class="font-inter antialiased  text-gray-600"

>

<div class="flex h-screen overflow-hidden">

    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

        <main style="direction: rtl">
{{--            <div class="px-4 sm:px-6 lg:px-8 py-8 w-full mx-auto">--}}
{{--                <div class="sm:flex sm:justify-between sm:items-center mb-8">--}}
{{--                    --}}{{--                    here title--}}

{{--                    <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">--}}
{{--                        <div class="table-items-action hidden">--}}
{{--                            <div class="flex items-center">--}}
{{--                                <div class="hidden xl:block text-sm italic mr-2 whitespace-nowrap"><span--}}
{{--                                        class="table-items-count"></span> items selected--}}
{{--                                </div>--}}
{{--                                <button--}}
{{--                                    class="btn bg-white border-gray-200 hover:border-gray-300 text-red-500 hover:text-red-600">--}}
{{--                                    Delete--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="mb-4 sm:mb-0"><h1 class="text-2xl md:text-3xl text-gray-800 font-bold">@yield('title')</h1>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    @yield('title-btn')--}}
{{--                </div>--}}
{{--                <div class="bg-white shadow-lg rounded-sm border border-gray-200 p-6">--}}
                    <!-- content here -->
                    {{$slot}}
{{--                </div>--}}


{{--            </div>--}}
        </main>
    </div>
</div>
@livewireScripts
<script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>
