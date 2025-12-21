

<div   wire:init="$set('activePanel', 'calendar')"
       x-data="{ panel: @entangle('activePanel')}"
         x-on:togglePanel.window="panel = $event.detail.panel"
         class="w-full">
{{--    Tabs --}}
    <!-- Tabs Component -->
    <div class="w-full">

        <div class="flex border-b border-gray-200 mb-5">


            <button @click="panel = 'calendar'"
                    :class="panel === 'calendar'
                ? 'text-blue-600 border-b-2 border-blue-600'
                : 'text-gray-600'"
                    class="flex-1 px-4 py-2 text-center  font-medium flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                 التقويم
            </button>
            <button  @click="panel = 'list'"
                     :class="panel === 'list'
                ? 'text-blue-600 border-b-2 border-blue-600'
                : 'text-gray-600'"
                     class="flex-1 px-4 py-2 text-center text-gray-600 hover:text-blue-600 font-medium flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                </svg>
              بحث
            </button>







        </div>

        <div class="w-full bg-white border border-gray-200 rounded-xl p-4 mb-6
            flex flex-wrap items-center gap-4 shadow-sm">

            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-3 h-3 rounded-full " style="background-color: rgb(220, 238, 255); "></span>
                <span>تحت الموافقة</span>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-3 h-3 rounded-full " style="background-color: rgb(209, 250, 229);"></span>
                <span>مقبولة</span>
            </div>

            <div class="flex items-center gap-2 text-sm " >
                <span class="w-3 h-3 rounded-full bg-gray-400" style="background-color:  rgb(254, 202, 202);"></span>
                <span>مرفوضة</span>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-3 h-3 rounded-full" style="background-color: rgb(218, 218, 218);"></span>
                <span>التقارير تحت الإجراء</span>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-3 h-3 rounded-full " style="background-color: rgb(185, 240, 234);"></span>
                <span>التقارير تامة</span>
            </div>

            <div class="flex items-center gap-2 text-sm text-gray-700">
                <span class="w-3 h-3 rounded-full" style="background-color: #ffd7b5;"></span>
                <span>ملغية</span>
            </div>

        </div>


        <!-- Tab Panels -->

        <div  class="w-full"  x-show="panel === 'calendar'">
            <div wire:ignore id='calendar'></div>
        </div>
        <div  x-show="panel === 'list'" class="p-4 ">
            <div id="branch-container" class="mb-6 mt-6">
                <div style="background-color:#f0f8ff" class="p-5 flex flex-col gap-4">
                    <div class="w-full flex flex-col sm:flex-row gap-4">

{{--                        @php--}}
{{--                            $uniqueRequesters = collect($visits)--}}
{{--                                ->flatMap(function ($visit) {--}}
{{--                                    return $visit['emps_requester'];--}}
{{--                                })--}}
{{--                                ->unique(fn($r) => $r['user']['id']);--}}
{{--                        @endphp--}}
                       <div class="w-full">
                           <label class="block font-bold mb-2">اسم الزائر

                           </label>

                           <select class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500  shadow-sm sm:text-sm border-gray-300 rounded-md" wire:model.lazy="user">
{{--                               @foreach($visits as $visit)--}}
                               <option value="all">الكل</option>
                               @foreach($uniqueRequesters as $requester)
                                   <option value="{{$requester['user']['id']}}" >{{$requester['user']['name']}}</option>
                               @endforeach
{{--
@endforeach--}}
                           </select>
                       </div>


                        <div class="w-full">
                            <label class="block font-bold mb-2">التاريخ بداية الزيارة من

                            </label>
                            <div>
                            <input class="form-input w-full" type="date" wire:model.lazy="start"/>

                            </div>
                        </div>
                        <div class="w-full">
                            <label class="block font-bold mb-2">التاريخ بداية الزيارة الى

                            </label>
                            <div>
                            <input class="form-input w-full" type="date" wire:model.lazy="end"/>

                            </div>
                        </div>
                        <div class="w-full">
                            <label class="block font-bold mb-2">الفرع
                            </label>
                            <select wire:model="branch" name="branch" id="branch"
                                    class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            >

                                <option value="all">الكل</option>

                                <option value="0101">الأحساء</option>
                                <option value="0102">جدة</option>
                                <option value="0103">الرياض</option>
                                <option value="0104">وادي الدواسر</option>
                                <option value="0105">الجوف</option>
                                <option value="0106">الدمام</option>
                                <option value="0107">الخرج</option>
                                <option value="0108">نجران</option>
                                <option value="0109">حائل</option>
                                <option value="0110">تبوك</option>
                                <option value="0111">القصيم</option>
                                <option value="0112">ساجر</option>

                            </select>
                            @error('branch') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="w-full">
                            <label class="block font-bold mb-2">الحالة
                            </label>
                            <select wire:model.lazy="status" name="status" id="status"
                                    class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <option value="all">الكل</option>
                                <option value="0">تحت الموافقة</option>
                                <option value="1">مقبوله</option>
                                <option value="2">مرفوضة</option>
                                <option value="3">التقارير تحت الإجراء</option>
                                <option value="5">التقارير تامة</option>
                                <option value="4">ملغية</option>


                            </select>
                            @error('status') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-8 text-center w-full">
                            <button
                                wire:click="search"
                                id="gen-report" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                    <span class="mr-2 font-bold" wire:loading.remove wire:target="search">
                        <span></span>
                        <span>بحث</span>
                    </span>
                                <span class="mr-2 font-bold" wire:loading wire:target="search">
                    <span></span>
                    <span>الرجاء الانتظار</span>
                    </span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                      @if(count($visits) > 0 )

                        <table class="table-auto w-full border text-center">
                            <thead class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                            <tr>
                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">#</div>
                                </th>


                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">الزائر</div>
                                </th>
                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">العنوان</div>
                                </th>
                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">المكان</div>
                                </th>

                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">التاريخ</div>
                                </th>
                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">الوقت</div>
                                </th>
                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">الحالة</div>
                                </th>
                                <th class="border p-2 whitespace-nowrap">
                                    <div class="font-semibold"></div>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">


                            @forelse($visits as $visit)
                                <tr style="@if($visit['status'] == 0) background-color:/*#fffddc*/ #dceeff; @elseif($visit['status'] == 1) background-color: #edffe9; @elseif($visit['status'] == 2) background-color: #fff0f8; @elseif($visit['status'] == 3) background-color: #dadada; @elseif($visit['status'] == 4) background-color:#ffd7b5; @elseif($visit['status'] == 5) background-color:#b9f0ea; @endif">

                                    <td class="border p-2 whitespace-nowrap">
                                        <div class="text-center text-gray-800 text-sm">{{ $visit["id"] }}</div>
                                    </td>

                                    <td class="border p-2 whitespace-nowrap">
                                        <div class="text-center text-gray-800 text-sm">{{ $visit['emps_requester'][0]['user']['name']?? null }}</div>
                                    </td>
{{--                                    <td class="border p-2 whitespace-nowrap">--}}
{{--                                        <div>--}}
{{--                                            @dd($visit['emps_requester'] )--}}
{{--                                            @php $visit_type = $visit['emps_requester'][0]['user_id'] == \Illuminate\Support\Facades\Auth::id() ? "outgoing" : "ingoing"  @endphp--}}
{{--                                            <div class="text-center text-gray-800 text-sm">--}}
{{--                                                @if($visit_type == "outgoing")--}}
{{--                                                    صادرة--}}
{{--                                                @else--}}
{{--                                                    واردة--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
                                    <td class="border p-2 whitespace-nowrap">
                                        <div>
                                            <div class="text-center text-gray-800 text-sm">{{ $visit["title"] }}</div>
                                        </div>
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        <div>
                                            <div class="text-center text-gray-800 text-sm">
                                                @if($visit["branch"] == "0101")
                                                    فرع الاحساء
                                                @elseif($visit["branch"] == "0102")
                                                    فرع جدة
                                                @elseif($visit["branch"] == "0103")
                                                    فرع الرياض
                                                @elseif($visit["branch"] == "0104")
                                                    فرع وادي الدواسر
                                                @elseif($visit["branch"] == "0105")
                                                    فرع الجوف
                                                @elseif($visit["branch"] == "0106")
                                                    فرع الدمام
                                                @elseif($visit["branch"] == "0107")
                                                    فرع الخرج
                                                @elseif($visit["branch"] == "0108")
                                                    فرع نجران
                                                @elseif($visit["branch"] == "0109")
                                                    فرع حائل
                                                @elseif($visit["branch"] == "0110")
                                                    فرع تبوك
                                                @elseif($visit["branch"] == "0111")
                                                    فرع القصيم
                                                @elseif($visit["branch"] == "0112")
                                                    فرع ساجر
                                                @elseif($visit["branch"] == "0201")
                                                    مزرعة الدالوة
                                                @elseif($visit["branch"] == "0202")
                                                    مزرعة الفضول
                                                @elseif($visit["branch"] == "0203")
                                                    مزرعة الدلم
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="border p-2 whitespace-nowrap">
                                        <div>

                                            <div class="text-center text-gray-800 text-sm">{{ \Carbon\Carbon::parse($visit["start"]?? null)->format('Y-m-d')}} - {{ \Carbon\Carbon::parse($visit["end"])->addDays(-1)->format('Y-m-d') }}</div>
                                        </div>
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        <div>

                                            <div class="text-center text-gray-800 text-sm">{{ \Carbon\Carbon::parse($visit["start"]?? null)->format('h:i A') }}</div>

                                        </div>
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        <div class="text-center text-gray-800 text-sm">
                                            @if($visit["status"] == 0)
                                                تحت الموافقة
                                            @elseif($visit["status"] == 1)
                                                مقبولة
                                            @elseif($visit["status"] == 2)
                                                مرفوضة
                                            @elseif($visit["status"] == 3)
                                              التقارير تحت الإجراء
                                            @elseif($visit["status"] == 4)
                                                ملغية
                                            @elseif($visit["status"] == 5)
                                                التقارير تامة
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap sm:flex justify-center">
                                        <div class="m-1.5">
                                            <a
                                                {{--                                        href="{{ route('show.daily-report', ['id' => $record->id]) }}"--}}
                                                href="{{ route('show.visit', ['id' => $visit["id"]]) }}"
                                                class="btn border-gray-200 hover:border-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 shrink-0"
                                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none"
                                                     stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <circle cx="12" cy="12" r="2"/>
                                                    <path
                                                        d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/>
                                                </svg>

                                            </a>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="border text-center p-6 text-lg font-bold">لا يوجد زيارات حتى الآن
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    @else
                        <div>
                            <div class="border text-center p-6 text-lg font-bold bg-gray-50">لا يوجد زيارات حتى الآن</div>
                        </div>
                    @endif
            </div>
        </div>
    </div>
{{--    Ends of Tabs--}}
</div>

@section('css-scripts')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.21.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>


        .swal2-confirm {
            background-color: #5b53b5 !important; /* or any color you like */
            color: white !important;
            border: none !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .swal2-deny {
            background-color: #dc3741 !important; /* or any color you like */
            color: white !important;
            border: none !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .swal2-cancel {
            background-color: #6e7881 !important; /* or any color you like */
            color: white !important;
            border: none !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Responsive SweetAlert2 modal */
        .responsive-modal {
            width: 90vw !important;
            max-width: 600px !important;
            box-sizing: border-box;
            padding: 1rem;
        }
        </style>

@stop
@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        async function checkDuplicate(branch, visitDate) {
            return await @this.call('checkDuplicate', branch, visitDate);
        }

        const branchMap = {
            "0101": "فرع الاحساء",
            "0102": "فرع جدة",
            "0103": "فرع الرياض",
            "0104": "فرع وادي الدواسر",
            "0105": "فرع الجوف",
            "0106": "فرع الدمام",
            "0107": "فرع الخرج",
            "0108": "فرع نجران",
            "0109": "فرع حائل",
            "0110": "فرع تبوك",
            "0111": "فرع القصيم",
            "0112": "فرع ساجر",
            "0201": "مزرعة الدالوة",
            "0202": "مزرعة الفضول",
            "0203": "مزرعة الدلم"
        };

        const employeesByBranch = @json($emps);

        const timeOptions = [
            "08:00 AM", "08:30 AM", "09:00 AM", "09:30 AM", "10:00 AM", "10:30 AM", "11:00 AM", "11:30 AM",
            "12:00 PM", "12:30 PM", "01:00 PM", "01:30 PM", "02:00 PM", "02:30 PM", "03:00 PM", "03:30 PM",
            "04:00 PM", "04:30 PM", "05:00 PM", "05:30 PM", "06:00 PM", "06:30 PM", "07:00 PM", "07:30 PM",
            "08:00 PM", "08:30 PM", "09:00 PM", "09:30 PM", "10:00 PM"
        ];



        document.addEventListener('DOMContentLoaded', function () {



            const visit = '';
            @if(auth()->user()->group == 7)
            {{--visits = {!! json_encode($visits) !!}; // Outputs as valid JavaScript object, NOT string--}}
            visits = {!! json_encode($calendarVisit) !!}; // Outputs as valid JavaScript object, NOT string

            @else
            visits = {!! json_encode($showAllVisits) !!}; // Outputs as valid JavaScript object, NOT string
            {{--visits = {!! json_encode($calendarVisit) !!}; // Outputs as valid JavaScript object, NOT string--}}

            @endif

            const currentUserId = {{ \Illuminate\Support\Facades\Auth::id()  }};

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                firstDay: 6, // Saturday
                selectable: true,
                 events: visits.map(formatVisit),
                // events:            [ {
                //     title: 'Accepted Order',
                //     start: '2025-11-04',
                //     url: '/show-visit/88' // 👈 your route link here
                // }],
                eventDidMount: function (info) {

                    const status = info.event.extendedProps.status;
                    let textColor = '#000000';

                    if (status === '0') {
                        // info.el.style.backgroundColor = '#fef3c7'; // yellow-ish
                        info.el.style.backgroundColor = '#dceeff';  // blue-ish
                        info.el.style.color = '#000';
                    } else if (status === '1') {
                        info.el.style.backgroundColor = '#d1fae5'; // green-ish
                        info.el.style.color = '#000';
                    } else if (status === '2') {
                        info.el.style.backgroundColor = '#fecaca'; // red-ish
                        info.el.style.color = '#000';
                    } else if (status === '3') {
                        info.el.style.backgroundColor = '#dadada'; // grey-ish
                        info.el.style.color = '#000';
                    } else if (status === '5') {
                        info.el.style.backgroundColor = '#b9f0ea';
                        info.el.style.color = '#000';
                    }
                },


                select : function (info) {

                //     console.log(info);
                //     var title = prompt('Enter Event Name:');
                //     console.log(title);
                //
                //     if(title) {
                //         Livewire.emit('addVisit', {title: title, start: info.startStr, end: info.endStr});
                //     }
                // },
                // dateClick: function (info) {


                    let selectedDate = info.startStr; // 👉 selected date
                    let selectedEnd = info.endStr;
                    if (!canOpenModal) {
                        return; // User is not allowed, do nothing
                    }

                    let disabledEmployees = [];

                    Swal.fire({
                        html: `
                  <div style="direction: rtl; max-width: 100%; width: 100%;">
  <!-- عنوان الزيارة -->
  <div style=" align-items: center; gap: 10px; margin-bottom: 10px;">
    <label for="event-title" style="min-width: 120px;text-align:right;" class="text-sm font-bold">موضوع الزيارة<span class="mx-1 text-red-500">*</span></label>
    <input type="text" id="event-title" class=" form-input w-full" style="flex: 1;" value="${visit.title || ''}">
  </div>

  <!-- سبب الزيارة -->
  <div style="align-items: center; gap: 10px; margin-bottom: 10px;">
    <label for="visit-reason" style="min-width: 120px;text-align:right;" class="text-sm font-bold">سبب الزيارة<span class="mx-1 text-red-500">*</span></label>
    <input type="text" id="visit-reason" class=" form-input w-full" style="flex: 1;">
  </div>

  <!-- أهداف الزيارة -->
  <div style="align-items: center; gap: 10px; margin-bottom: 10px;">
    <label for="visit-goals" style="min-width: 120px;text-align:right;" class="text-sm font-bold">التحضيرات المطلوبة</label>
    <input id="visit-goals" class=" form-input w-full text-right">

<!--style="flex: 1; height: 150px; resize: none;-->
<!--                 border: 1px solid #64748b;-->
<!--                 padding: 0.625em;-->
<!--                 border-radius: 0em;-->
<!--                 font-family: inherit;-->
<!--                 font-size: 10pt;"-->


  </div>

  <!-- مكان الزيارة -->
  <div style="align-items: center; gap: 10px; margin-bottom: 10px;">
    <label for="branch-select" style="min-width: 120px;text-align:right;" class="text-sm font-bold">مكان الزيارة<span class="mx-1 text-red-500">*</span></label>
    <select id="branch-select" class=" form-select w-full" style="flex: 1; ">
      <option value="" disabled selected>اختر المكان</option>
      <option value="0101">فرع الاحساء</option>
      <option value="0102">فرع جدة</option>
      <option value="0103">فرع الرياض</option>
      <option value="0104">فرع وادي الدواسر</option>
      <option value="0105">فرع الجوف</option>
      <option value="0106">فرع الدمام</option>
      <option value="0107">فرع الخرج</option>
      <option value="0108">فرع نجران</option>
      <option value="0109">فرع حائل</option>
      <option value="0110">فرع تبوك</option>
      <option value="0111">فرع القصيم</option>
      <option value="0112">فرع ساجر</option>
<!--      <option value="0201">مزرعة الدالوة</option>-->
<!--      <option value="0202">مزرعة الفضول</option>-->
<!--      <option value="0203">مزرعة الدلم</option>-->
    </select>
  </div>

    <!-- الموظفين -->
    <div style=" align-items: center; gap: 10px; margin-bottom: 10px;">
      <label for="employee-select" style="min-width: 120px;text-align:right;" class="text-sm font-bold">ابلاغ الموظفين<span class="mx-1 text-red-500">*</span></label>
      <select id="employee-select" class=" form-input w-full" multiple style="flex: 1; "></select>
    </div>

<div >
<div class="flex w-full gap-2">
    <div class="w-full" style=" align-items: center; gap: 10px; margin-bottom: 10px;">
     <label class="text-sm font-bold" style="min-width: 120px;">تاريخ البداية<span class="mx-1 text-red-500">*</span></label>

 <input type="date" id="start" value="${selectedDate}" class="form-input w-full" >
   </div>
    <!-- وقت الزيارة -->
    <!-- وقت الزيارة -->
<div style="align-items: center; gap: 10px; margin-bottom: 10px;">
  <label class="text-sm font-bold" for="visit-time" style="min-width: 120px; text-align:right;">وقت الزيارة</label>
  <select id="visit-time" class=" form-select w-full" style="flex: 1; ">
    <option value="" disabled selected dir="rtl" style="text-align: right;">اختر الوقت</option>
    <!-- Time options below -->
 ${timeOptions.map(t => `<option value="${t}">${t}</option>`).join('')}

  </select>
</div>
</div>
<div class="flex gap-2">
     <div class="w-full" style="align-items: center; gap: 10px; margin-bottom: 10px;">
     <label class="text-sm font-bold" style="min-width: 120px;">تاريخ النهاية<span class="mx-1 text-red-500">*</span></label>
 <input type="date"  id="end" value="${selectedDate}" class="form-input w-full">
   </div>



<div style=" align-items: center; gap: 10px; margin-bottom: 10px;">
  <label class="text-sm font-bold" for="end-time" style="min-width: 120px; text-align:right;">وقت الإنتهاء</label>
  <select id="end-time" class=" form-select w-full" style="flex: 1; ">
    <option value="" disabled selected dir="rtl" style="text-align: right;">اختر الوقت</option>
    <!-- Time options below -->
 ${timeOptions.map(t => `<option value="${t}">${t}</option>`).join('')}
</select>
</div>
</div>
</div>
<!-- المرافقون -->
<div style=" align-items: flex-start; gap: 10px; margin-bottom: 10px;">
  <label class="text-sm font-bold"  style="min-width: 120px;text-align:right;">المرافقون</label>
<input type="text" id="attendants" class=" form-input w-full" style="flex: 1;">
<!--  <div style="display: flex; flex-direction: column; gap: 5px; flex: 1;">-->
<!--    <label><input class="form-checkbox" type="checkbox" name="extra-services" value="hotel"> حجز فندق</label>-->
<!--    <label><input class="form-checkbox" type="checkbox" name="extra-services" value="flight"> حجز طيران</label>-->
<!--    <label><input class="form-checkbox" type="checkbox" name="extra-services" value="train"> حجز قطار</label>-->
<!--  </div>-->
</div>

</div>

                `,
                        title: 'إضافة زيارة جديدة',
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: 'إضافة',
                        cancelButtonText: 'إلغاء',
                        reverseButtons: true,
                        customClass: {
                            popup: 'responsive-modal'
                        },


                        didOpen: () => {
                            const branchSelect = document.getElementById('branch-select');
                            const employeeSelect = document.getElementById('employee-select');

                            // Initialize Select2
                            $(employeeSelect).select2({
                                dir: "rtl",
                                dropdownCssClass: "select-font-size form-select",
                                class:"from-input",
                                dropdownParent: document.querySelector('.swal2-popup'),
                                placeholder: "اختر الموظفين"
                            });



                            branchSelect.addEventListener('change', () => {
                                const selectedBranchId = branchSelect.value;
                                const employees = employeesByBranch[selectedBranchId] || [];

                                // Reset Select2
                                $(employeeSelect).off('select2:select');
                                $(employeeSelect).empty();

                                // Add "All" option (always present)
                                const allOption = new Option("الكل", "all", false, false);
                                $(employeeSelect).append(allOption);

                                // Add employee options
                                employees.forEach(emp => {
                                    const option = new Option(emp.name, emp.id, false, false);
                                    $(employeeSelect).append(option);
                                });

                                $(employeeSelect).trigger('change.select2');

                                // Handle selection
                                $(employeeSelect).on('select2:select', function(e) {
                                    const selectedId = e.params.data.id;

                                    if (selectedId === "all") {
                                        // Clear all employees and select only "all"
                                        $(employeeSelect).val(["all"]).trigger('change.select2');
                                    } else {
                                        // If user selects any employee, remove "all" from selection but keep in dropdown
                                        let currentSelected = $(employeeSelect).val().filter(id => id !== "all");
                                        $(employeeSelect).val(currentSelected).trigger('change.select2');
                                    }
                                });
                            });


                            branchSelect.addEventListener('change', () => {
                                const selectedBranchId = branchSelect.value;
                                const employees = employeesByBranch[selectedBranchId] || [];

                                disabledEmployees = []; // Reset list

                                // Clear previous options
                                $(employeeSelect).empty();

                                // Add "All" option
                                const allOption = new Option("الكل", "all", true, true);
                                $(employeeSelect).append(allOption);

                                employees.forEach(emp => {
                                    const isGroup8 = emp.group == 8;
                                    const option = new Option(emp.name, emp.id);
                                    // const option2 = new Option(emp.name, emp.id, isGroup8, isGroup8);
                                    // if (isGroup8) {
                                    //     // option.disabled = true;
                                    //     disabledEmployees.push(emp.id.toString());
                                    // }
                                     $(employeeSelect).append(option);
                                });



                                // Trigger change to refresh Select2 UI
                                $(employeeSelect).trigger('change');


                            });

                            $(employeeSelect).on('select2:unselecting', function (e) {

                                const id = e.params.args.data.id;

                                // Check if the option is disabled (group 8)
                                const option = $(this).find(`option[value="${id}"]`);
                                if (option.prop('disabled')) {
                                    e.preventDefault(); // prevent unselect
                                }
                            });
                        },

                        preConfirm: async() => {
                            const title = document.getElementById('event-title').value;
                            const reason = document.getElementById('visit-reason').value;
                            const goals = document.getElementById('visit-goals').value;
                            const branch = document.getElementById('branch-select').value;
                            const visitTime = document.getElementById('visit-time').value;
                            const endTime = document.getElementById('end-time').value;
                            const attendants = document.getElementById('attendants').value;
                            const start = document.getElementById('start').value;
                            const end = document.getElementById('end').value;

                            // const selectedEmployees = $('#employee-select').val(); // returns an array of selected employee IDs
                            let selectedEmployees = $('#employee-select').val() || [];
                            disabledEmployees.forEach(id => {
                                if (!selectedEmployees.includes(id)) {
                                    selectedEmployees.push(id); // Ensure they're included
                                }
                            });

                            // if (!title.trim() || !reason.trim() || !goals.trim() || !branch || !visitTime || !selectedEmployees.length) {

                            // Call Livewire method
                            const visitDate = info.startStr; // YYYY-MM-DD

                            const exists = await checkDuplicate(branch, visitDate);


                                if (!title.trim() || !reason.trim() || !branch   || !selectedEmployees.length) {
                                    Swal.showValidationMessage('الرجاء تعبئة الحقول المطلوبة');
                                    return false;
                                }

                                if (start > end) {
                                    Swal.showValidationMessage('يجب أن يكون تاريخ النهاية بعد تاريخ البداية');
                                    return false;
                                }



                            if (exists) {
                                const result = await Swal.fire({
                                    icon: 'warning',
                                    title: 'تنبيه',
                                    text: 'يوجد زيارة بنفس التاريخ والفرع',
                                    showCancelButton: true,
                                    confirmButtonText: 'نعم، متابعة',
                                    cancelButtonText: 'إلغاء',
                                    reverseButtons: true
                                });

                                // المستخدم ضغط إلغاء
                                if (!result.isConfirmed) {
                                    return null; // ⛔ مهم جدًا
                                }
                            }

                            // const startDateTime = combineDateAndTime(info.startStr, visitTime);
                                const startDateTime = combineDateAndTime(start, visitTime);
                                const endDateTime = combineDateAndTime(end, endTime, isEnd= true);

                                const extraServices = Array.from(document.querySelectorAll('input[name="extra-services"]:checked'))
                                    .map(cb => cb.value);

                                return {
                                    title,
                                    reason,
                                    goals,
                                    branch,
                                    attendants,
                                    employees: selectedEmployees,
                                     start: startDateTime,
                                    // start,
                                     end: endDateTime,
                                   // end,
                                    extra_services: extraServices,
                                    requester: @json(Auth::user()->name)
                                };


                            }


                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emit('addVisit', {
                                title: result.value.title,
                                start: result.value.start,
                                // end: info.endStr,
                                end:  result.value.end,
                                reason: result.value.reason,
                                goals: result.value.goals,
                                branch: result.value.branch,
                                employees: result.value.employees,
                                attendants: result.value.attendants,
                                extra_services: result.value.extra_services,
                                requester: @json(Auth::user()->name)

                            });
                        }
                          // window.location.reload()


                    });

                },
                eventClick: function(info) {
                    // document.getElementById('start').value = info.dateStr;
                    info.jsEvent.preventDefault(); // prevent default link behavior

                    const visit = info.event.extendedProps;
                    const authUserId = {{auth()->user()->id}}
                    // 👇 Example condition:
                    // Replace this with your actual logic
                    const isAllowed = visit.emps.some(emp => emp.user_id === authUserId); // for example, something you send from backend
                     console.log(visit)
                    if (isAllowed || canViewAll) {
                        // ✅ Go to the URL
                        window.location.href = `/show-visit/${info.event.id}`;
                    } else {
                        // 🚫 Show alert or popup
                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه',
                            text: 'لا يمكنك الدخول إلى هذه الزيارة',
                            confirmButtonText: 'حسنًا'
                        });
                    }
                }


            });

            calendar.render();


            Livewire.on('visitsLoaded', (visits) => {

                // window.location.reload();

                   calendar.removeAllEvents();
                // calendar.getEventById(visit.id)?.remove();
                // calendar.addEvent(formatVisit(visit));
                   calendar.addEventSource(visits.map(formatVisit)); // Apply formatting again
                console.log(visits.emps);


            });

            function combineDateAndTime(dateStr, timeStr, isEnd= false) {

                const [year, month, day] = dateStr.split("T")[0].split("-").map(Number);
                const [time, modifier] = timeStr.split(" ");
                let [hours, minutes] = time.split(":").map(Number);

                // If timeStr is missing
                if (!timeStr) {
                    const pad = n => String(n).padStart(2, '0');
                    return isEnd
                        ? `${year}-${pad(month)}-${pad(day)}T00:00:00`  // End of day
                        : `${year}-${pad(month)}-${pad(day)}T00:00:00`; // Start of day
                }
                if (modifier === "PM" && hours !== 12) hours += 12;
                if (modifier === "AM" && hours === 12) hours = 0;

                const localDate = new Date(year, month - 1, day, hours, minutes);

                // Instead of .toISOString(), format it manually to keep local time
                const pad = n => String(n).padStart(2, '0');
                const localDateTimeString = `${year}-${pad(month)}-${pad(day)}T${pad(hours)}:${pad(minutes)}:00`;

                return localDateTimeString;
            }


            function formatTime(dateObj) {
                if (!dateObj) return '';
                return new Date(dateObj).toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }

            function formatVisit(visit) {

                const authUserId ={{auth()->user()->id}};
                const branchName = branchMap[visit.branch] || 'فرع غير معروف';
                const requester = visit.requester?.name || visit.title

                let backgroundColor;
                let textColor = '#000000';
                let eventUrl = `visit-calendar`;

                // visit.emps = [
                //     { id: 1, name: "Zahra", type: "requester" },
                //     { id: 2, name: "Ali", type: "recipient" },
                // ];

                if (Array.isArray(visit.emps) && visit.emps.some(emp => emp.user_id === authUserId)) {
                    eventUrl = `/show-visit/${visit.id}`;
                }


                switch (visit.status) {
                    case '0':
                        backgroundColor = '#fef3c7';
                        break;
                    case '1':
                        backgroundColor = '#d1fae5';
                        break;
                    case '2':
                        backgroundColor = '#fecaca';
                        break;
                    case '5':
                        backgroundColor = '#b9f0ea'
                        break;
                    default:
                        backgroundColor = '#e5e7eb';
                }

                return {
                     ...visit,
                    backgroundColor,
                    textColor,
                    borderColor: 'transparent',
                    // url: eventUrl,
                    // url: `/show-visit/${visit.id}`,
                    // title: `${visit.requester?.name}  - ${branchName}` || 'لايوجد',
                    title: `${requester}  - ${branchName}` || 'لايوجد',
                    allDay: true,

                };
            }


            //
            // listTab.addEventListener('click', () => {
            //     listTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
            //     calendarTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
            //     listPanel.classList.remove('hidden');
            //     calendarPanel.classList.add('hidden');
            // });


        });


        let canOpenModal = @json(
        (Auth::user()->user_group->visits && in_array('create-visit', json_decode(Auth::user()->user_group->visits, true)))
        || Auth::user()->role == 'a'
    );
        let canViewAll = @json(
        (Auth::user()->user_group->visits && in_array('view-all-visits', json_decode(Auth::user()->user_group->visits, true)))
        || Auth::user()->role == 'a'
    );

    </script>
@stop
