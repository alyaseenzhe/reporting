<div>
{{--    Tabs --}}
    <!-- Tabs Component -->
    <div class="w-full">
        <div class="flex border-b border-gray-200 mb-5">
            <button id="calendarTab" class="flex-1 px-4 py-2 text-center text-blue-600 border-b-2 border-blue-600 font-medium flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                وضع التقويم
            </button>
            <button id="listTab" class="flex-1 px-4 py-2 text-center text-gray-600 hover:text-blue-600 font-medium flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                </svg>
                وضع الجدول
            </button>







        </div>

        <!-- Tab Panels -->
        <div id="calendarPanel" class="w-full">
            <div wire:ignore id='calendar'></div>
        </div>
        <div id="listPanel" class="p-4 hidden">
            <div class="overflow-x-auto">
                    @if(count($visits) > 0 )
                        <table class="table-auto w-full border text-center">
                            <thead class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                            <tr>
                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">#</div>
                                </th>
                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">النوع</div>
                                </th>
                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">العنوان</div>
                                </th>
                                <th class="border p-2 whitespace-nowrap">
                                    <div class="text-sm">المكان</div>
                                </th>
{{--                                <th class="border p-2 whitespace-nowrap">--}}
{{--                                    <div class="text-sm">الموظف</div>--}}
{{--                                </th>--}}
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
                                <tr style="@if($visit['status'] == 0) background-color:/*#fffddc*/ #dceeff; @elseif($visit['status'] == 1) background-color: #edffe9; @elseif($visit['status'] == 2) background-color: #fff0f8; @elseif($visit['status'] == 3) background-color: #dadada; @endif">
                                    <td class="border p-2 whitespace-nowrap">
                                        <div class="text-center text-gray-800 text-sm">{{ $visit["id"] }}</div>
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        <div>
                                            @php $visit_type = $visit['emps_requester'][0]['user_id'] == \Illuminate\Support\Facades\Auth::id() ? "outgoing" : "ingoing"  @endphp
                                            <div class="text-center text-gray-800 text-sm">
                                                @if($visit_type == "outgoing")
                                                    صادرة
                                                @else
                                                    واردة
                                                @endif
                                            </div>
                                        </div>
                                    </td>
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
{{--                                    <td class="border p-2 whitespace-nowrap">--}}
{{--                                        <div>--}}
{{--                                            <div class="text-center text-gray-800 text-sm">--}}
{{--                                                @if($visit_type == "outgoing")--}}
{{--                                                    {{ $visit["recipient_name"] }}--}}
{{--                                                @else--}}
{{--                                                    {{ $visit["requester_name"] }}--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
                                    <td class="border p-2 whitespace-nowrap">
                                        <div>
                                            <div class="text-center text-gray-800 text-sm">{{ \Carbon\Carbon::parse($visit["start"])->format('Y-m-d')}} - {{ \Carbon\Carbon::parse($visit["end"])->addDays(-1)->format('Y-m-d') }}</div>
                                        </div>
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        <div>
                                            <div class="text-center text-gray-800 text-sm">{{ \Carbon\Carbon::parse($visit["start"])->format('h:i A') }}</div>
                                        </div>
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        <div class="text-center text-gray-800 text-sm">
                                            @if($visit["status"] == 0)
                                                تحت الإجراء
                                            @elseif($visit["status"] == 1)
                                                مقبولة
                                            @elseif($visit["status"] == 2)
                                                مرفوضة
                                            @elseif($visit["status"] == 3)
                                                مغلقة
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
{{--                                        <div class="m-1.5">--}}
{{--                                            <a href="{{ route('edit.user', ['id' => $visit["id"]]) }}"--}}
{{--                                               class="btn border-gray-200 hover:border-gray-300">--}}
{{--                                                <svg class="w-4 h-4 fill-current text-gray-500 shrink-0" viewBox="0 0 16 16">--}}
{{--                                                    <path--}}
{{--                                                        d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM4.6 14H2v-2.6l6-6L10.6 8l-6 6zM12 6.6L9.4 4 11 2.4 13.6 5 12 6.6z"></path>--}}
{{--                                                </svg>--}}
{{--                                            </a>--}}
{{--                                        </div>--}}
{{--                                        <div class="m-1.5">--}}
{{--                                            <div x-data="{ modalOpen: false }">--}}
{{--                                                <button class="btn border-gray-200 hover:border-gray-300"--}}
{{--                                                        @click.prevent="modalOpen = true" aria-controls="danger-modal">--}}
{{--                                                    <svg class="w-4 h-4 fill-current text-red-500 shrink-0" viewBox="0 0 16 16">--}}
{{--                                                        <path--}}
{{--                                                            d="M5 7h2v6H5V7zm4 0h2v6H9V7zm3-6v2h4v2h-1v10c0 .6-.4 1-1 1H2c-.6 0-1-.4-1-1V5H0V3h4V1c0-.6.4-1 1-1h6c.6 0 1 .4 1 1zM6 2v1h4V2H6zm7 3H3v9h10V5z"></path>--}}
{{--                                                    </svg>--}}
{{--                                                </button>--}}
{{--                                                <div class="fixed inset-0 bg-gray-900 bg-opacity-30 z-50 transition-opacity"--}}
{{--                                                     x-show="modalOpen" x-transition:enter="transition ease-out duration-200"--}}
{{--                                                     x-transition:enter-start="opacity-0"--}}
{{--                                                     x-transition:enter-end="opacity-100"--}}
{{--                                                     x-transition:leave="transition ease-out duration-100"--}}
{{--                                                     x-transition:leave-start="opacity-100"--}}
{{--                                                     x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak></div>--}}
{{--                                                <div id="danger-modal"--}}
{{--                                                     class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center transform px-4 sm:px-6"--}}
{{--                                                     role="dialog" aria-modal="true" x-show="modalOpen"--}}
{{--                                                     x-transition:enter="transition ease-in-out duration-200"--}}
{{--                                                     x-transition:enter-start="opacity-0 translate-y-4"--}}
{{--                                                     x-transition:enter-end="opacity-100 translate-y-0"--}}
{{--                                                     x-transition:leave="transition ease-in-out duration-200"--}}
{{--                                                     x-transition:leave-start="opacity-100 translate-y-0"--}}
{{--                                                     x-transition:leave-end="opacity-0 translate-y-4" x-cloak>--}}
{{--                                                    <div--}}
{{--                                                        class="bg-white rounded shadow-lg overflow-auto max-w-lg w-full max-h-full"--}}
{{--                                                        @click.outside="modalOpen = false"--}}
{{--                                                        @keydown.escape.window="modalOpen = false">--}}
{{--                                                        <div class="p-5 flex space-x-4">--}}
{{--                                                            <div--}}
{{--                                                                class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-red-100">--}}
{{--                                                                <svg class="w-4 h-4 shrink-0 fill-current text-red-500"--}}
{{--                                                                     viewBox="0 0 16 16">--}}
{{--                                                                    <path--}}
{{--                                                                        d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z"/>--}}
{{--                                                                </svg>--}}
{{--                                                            </div>--}}
{{--                                                            <div>--}}
{{--                                                                <div class="mb-8 mr-4 mt-2">--}}
{{--                                                                    <div class="text-lg font-semibold text-gray-800">--}}
{{--                                                                        هل تريد بالفعل حذف هذه الزيارة؟--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                                <div class="flex flex-wrap justify-start gap-4">--}}
{{--                                                                    <button--}}
{{--                                                                        class="btn-sm border-gray-200 hover:border-gray-300 text-gray-600"--}}
{{--                                                                        @click="modalOpen = false">لا--}}
{{--                                                                    </button>--}}
{{--                                                                    <button wire:click.prevent="delete({{$visit["id"]}})"--}}
{{--                                                                            class="btn-sm bg-red-500 hover:bg-red-600 text-white">--}}
{{--                                                                        نعم--}}
{{--                                                                    </button>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
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
        /*.swal2-confirm,*/
        /*.swal2-cancel {*/
        /*    opacity: 1 !important;*/
        /*    visibility: visible !important;*/
        /*    color: inherit !important;*/
        /*    background: inherit !important;*/
        /*}*/

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

        /* Ensure input stretches correctly */
        .swal2-input,
        .swal2-textarea {
            width: 100% !important;
            box-sizing: border-box;
        }

        .select-font-size {
            font-size: 0.875rem; /* 14px */
            line-height: 1.25rem; /* 20px */
        }

        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection--single {
            border-radius: 0 !important;



        }
        .swal2-popup .select2-container .select2-selection--single {
            background-color: #eef5ff !important;
            border: 1px solid #007bff !important;
            border-radius: 8px !important;
        }
        .select2-container--default{
            border-width: 1px !important;
            --tw-bg-opacity: 1 !important;
            background-color: rgb(255 255 255/var(--tw-bg-opacity)) !important;
            font-size: .875rem !important;
            line-height: 1.5715 !important;
            --tw-text-opacity: 1 !important;
            color: rgb(30 41 59/var(--tw-text-opacity)) !important;
        }
        .select2-search__field{
            border-width: 1px !important;
            --tw-bg-opacity: 1 !important;
            background-color: rgb(255 255 255/var(--tw-bg-opacity)) !important;
            font-size: .875rem !important;
            line-height: 1.5715 !important;
            --tw-text-opacity: 1 !important;
            color: rgb(30 41 59/var(--tw-text-opacity)) !important;

        }

        .select2-container--default .select2-dropdown {
            border-radius: 0 !important;
        }
        .select2-container .select2-selection--single {

        }



    </style>
@stop
@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

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


            const visits = {!! json_encode($visits) !!}; // Outputs as valid JavaScript object, NOT string
            const currentUserId = {{ \Illuminate\Support\Facades\Auth::id()  }};

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                events: visits.map(formatVisit),
                eventDidMount: function (info) {
                    const status = info.event.extendedProps.status;
                    let textColor = '#000000';

                    if (status === '0') {
                        // info.el.style.backgroundColor = '#fef3c7'; // yellow-ish
                        info.el.style.backgroundColor = '#dceeff';
                        info.el.style.color = '#000';
                    } else if (status === '1') {
                        info.el.style.backgroundColor = '#d1fae5'; // green-ish
                        info.el.style.color = '#000';
                    } else if (status === '2') {
                        info.el.style.backgroundColor = '#fecaca'; // red-ish
                        info.el.style.color = '#000';
                    }
                },
                // select : function (info) {
                //     console.log(info);
                //     var title = prompt('Enter Event Name:');
                //     console.log(title);
                //
                //     if(title) {
                //         Livewire.emit('addVisit', {title: title, start: info.startStr, end: info.endStr});
                //     }
                // },
                select: function (info) {

                    let disabledEmployees = [];

                    Swal.fire({
                        title: 'إضافة زيارة جديدة',
                        html: `
                  <div style="direction: rtl; max-width: 100%; width: 100%;">
  <!-- عنوان الزيارة -->
  <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <label for="event-title" style="min-width: 120px;">عنوان الزيارة</label>
    <input type="text" id="event-title" class="swal2-input form-input w-full" style="flex: 1;">
  </div>

  <!-- سبب الزيارة -->
  <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <label for="visit-reason" style="min-width: 120px;">سبب الزيارة</label>
    <input type="text" id="visit-reason" class="swal2-input form-input w-full" style="flex: 1;">
  </div>

  <!-- أهداف الزيارة -->
  <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <label for="visit-goals" style="min-width: 120px;">أهداف الزيارة</label>
    <textarea id="visit-goals" class="swal2-textarea form-textarea w-full"></textarea>

<!--style="flex: 1; height: 150px; resize: none;-->
<!--                 border: 1px solid #64748b;-->
<!--                 padding: 0.625em;-->
<!--                 border-radius: 0em;-->
<!--                 font-family: inherit;-->
<!--                 font-size: 10pt;"-->


  </div>

  <!-- مكان الزيارة -->
  <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <label for="branch-select" style="min-width: 120px;">مكان الزيارة</label>
    <select id="branch-select" class="swal2-select form-select w-full" style="flex: 1; appearance: auto;">
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
      <option value="0201">مزرعة الدالوة</option>
      <option value="0202">مزرعة الفضول</option>
      <option value="0203">مزرعة الدلم</option>
    </select>
  </div>

    <!-- الموظفين -->
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
      <label for="employee-select" style="min-width: 120px;">الموظفين</label>
      <select id="employee-select" class="swal2-select form-input w-full" multiple style="flex: 1; appearance: auto;"></select>
    </div>


    <!-- وقت الزيارة -->
    <!-- وقت الزيارة -->
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
  <label for="visit-time" style="min-width: 120px;">وقت الزيارة</label>
  <select id="visit-time" class="swal2-select form-select w-full" style="flex: 1; appearance: auto;">
    <option value="" disabled selected dir="rtl" style="text-align: right;">اختر الوقت</option>
    <!-- Time options below -->
    <option value="08:00 AM">08:00 AM</option>
    <option value="08:30 AM">08:30 AM</option>
    <option value="09:00 AM">09:00 AM</option>
    <option value="09:30 AM">09:30 AM</option>
    <option value="10:00 AM">10:00 AM</option>
    <option value="10:30 AM">10:30 AM</option>
    <option value="11:00 AM">11:00 AM</option>
    <option value="11:30 AM">11:30 AM</option>
    <option value="12:00 PM">12:00 PM</option>
    <option value="12:30 PM">12:30 PM</option>
    <option value="01:00 PM">01:00 PM</option>
    <option value="01:30 PM">01:30 PM</option>
    <option value="02:00 PM">02:00 PM</option>
    <option value="02:30 PM">02:30 PM</option>
    <option value="03:00 PM">03:00 PM</option>
    <option value="03:30 PM">03:30 PM</option>
    <option value="04:00 PM">04:00 PM</option>
    <option value="04:30 PM">04:30 PM</option>
    <option value="05:00 PM">05:00 PM</option>
    <option value="05:30 PM">05:30 PM</option>
    <option value="06:00 PM">06:00 PM</option>
    <option value="06:30 PM">06:30 PM</option>
    <option value="07:00 PM">07:00 PM</option>
    <option value="07:30 PM">07:30 PM</option>
    <option value="08:00 PM">08:00 PM</option>
    <option value="08:30 PM">08:30 PM</option>
    <option value="09:00 PM">09:00 PM</option>
    <option value="09:30 PM">09:30 PM</option>
    <option value="10:00 PM">10:00 PM</option>
  </select>
</div>

<!-- خدمات إضافية -->
<div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
  <label style="min-width: 120px;">خدمات إضافية</label>
  <div style="display: flex; flex-direction: column; gap: 5px; flex: 1;">
    <label><input type="checkbox" name="extra-services" value="hotel"> حجز فندق</label>
    <label><input type="checkbox" name="extra-services" value="flight"> حجز طيران</label>
    <label><input type="checkbox" name="extra-services" value="train"> حجز قطار</label>
  </div>
</div>

</div>

                `,
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

                                disabledEmployees = []; // Reset list

                                // Clear previous options
                                $(employeeSelect).empty();

                                employees.forEach(emp => {
                                    const isGroup8 = emp.group == 8;
                                    const option = new Option(emp.name, emp.id, isGroup8, isGroup8);
                                    if (isGroup8) {
                                        option.disabled = true;
                                        disabledEmployees.push(emp.id.toString());
                                    }
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
                        preConfirm: () => {
                            const title = document.getElementById('event-title').value;
                            const reason = document.getElementById('visit-reason').value;
                            const goals = document.getElementById('visit-goals').value;
                            const branch = document.getElementById('branch-select').value;
                            const visitTime = document.getElementById('visit-time').value;
                            // const selectedEmployees = $('#employee-select').val(); // returns an array of selected employee IDs
                            let selectedEmployees = $('#employee-select').val() || [];
                            disabledEmployees.forEach(id => {
                                if (!selectedEmployees.includes(id)) {
                                    selectedEmployees.push(id); // Ensure they're included
                                }
                            });

                            if (!title.trim() || !reason.trim() || !goals.trim() || !branch || !visitTime || !selectedEmployees.length) {
                                Swal.showValidationMessage('الرجاء تعبئة جميع الحقول');
                                return false;
                            }

                            const startDateTime = combineDateAndTime(info.startStr, visitTime);

                            const extraServices = Array.from(document.querySelectorAll('input[name="extra-services"]:checked'))
                                .map(cb => cb.value);

                            return {
                                title,
                                reason,
                                goals,
                                branch,
                                employees: selectedEmployees,
                                start: startDateTime,
                                end: info.endStr,
                                extra_services: extraServices
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emit('addVisit', {
                                title: result.value.title,
                                start: result.value.start,
                                end: info.endStr,
                                reason: result.value.reason,
                                goals: result.value.goals,
                                branch: result.value.branch,
                                employees: result.value.employees,
                                extra_services: result.value.extra_services
                            });
                        }
                    });
                },
                // When an existing event is clicked
                // eventClick: function(info) {
                //     var newTitle = prompt('Edit Event Title:', info.event.title);
                //     if (newTitle) {
                //         // Update event on calendar
                //         info.event.setProp('title', newTitle);
                //
                //         // Send update to Livewire
                //         Livewire.emit('updateVisit', {
                //             id: info.event.id,
                //             title: newTitle,
                //             start: info.event.startStr,
                //             end: info.event.endStr
                //         });
                //     }
                // }
                eventClick: function (info) {
                    data_requester =info.event.extendedProps.emps_requester;
                    data_recipient =info.event.extendedProps.emps_recipients;
                    // // console.log((info.event.extendedProps.emps_recipients).find(item => item.user_id === currentUserId));
                    // console.log((data.find(item => item.user_id)).user_id);
                    // console.log(((data.find(item => item.user_id)).user_id == currentUserId) == true);
                    // console.log(info.event.extendedProps.emps_requester);
                    // console.log(info.event.extendedProps.emps_recipients);
                    //////////// approve or reject
                    const isRecipient = data_recipient.some(item => item.user_id == currentUserId) == true;
                    const isOwner = data_requester.some(item => item.user_id == currentUserId) == true;
                    const isDeleted = info.event.extendedProps.is_deleted;
                    const status = info.event.extendedProps.status;
                    const viewRouteBase = @json(route('show.visit', ['id' => 'VISIT_ID']));
                    const visitId = info.event.id;
                    const viewUrl = viewRouteBase.replace('VISIT_ID', visitId);

                    let buttonsHtml = '';


                    if (isRecipient && status == 0 && isDeleted == 0) {
                        // alert('coco');
                        buttonsHtml = `
    <div style="display: flex; justify-content: center; margin-top: 20px;">
      <button id="approve-btn" class="swal2-styled" style="background-color: #2f9d58; color: white; border-radius: 5px;"  >موافقة</button>
      <button id="reject-btn" class="swal2-styled" style="background-color: #b91818; color: white; border-radius: 5px;">رفض</button>
    </div>
  `;

                    }
                    //////////// end of approve or reject section


                    Swal.fire({
                        title: 'معلومات الزيارة',
                        html: `<style>
  .visit-info-container {
    direction: rtl;
    text-align: right;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    max-width: 700px;
    margin: 0 auto;
  }

  .visit-info-item {
    display: flex;
    flex-direction: column;
  }

  .full-span {
    grid-column: span 2;
  }

  @media (max-width: 768px) {
    .visit-info-container {
      grid-template-columns: 1fr;
    }
    .full-span {
      grid-column: span 1;
    }
  }
</style>

<div class="visit-info-container">
  <div class="visit-info-item">
    <label><strong>عنوان الزيارة:</strong></label>
    <p style="color: #08089d">${info.event.title || 'N/A'}</p>
  </div>

  <div class="visit-info-item">
    <label><strong>سبب الزيارة:</strong></label>
    <p style="color: #08089d">${info.event.extendedProps.reason || 'N/A'}</p>
  </div>

  <div class="visit-info-item full-span">
    <label><strong>أهداف الزيارة:</strong></label>
    <p style="white-space: pre-wrap; color: #08089d">${info.event.extendedProps.goals || 'N/A'}</p>
  </div>

  <div class="visit-info-item">
    <label><strong>مكان الزيارة:</strong></label>
    <p style="color: #08089d">${branchMap[info.event.extendedProps.branch] || 'N/A'}</p>
  </div>

  <div class="visit-info-item">
    <label><strong>مقدم الطلب:</strong></label>
    <p style="color: #08089d">
      ${(() => {
                            const requester = (info.event.extendedProps.emps_requester || []).find(e => e.type === 'requester');
                            return requester?.user?.name || 'N/A';
                        })()}
    </p>
  </div>

  <div class="visit-info-item full-span">
    <label><strong>المستلمون:</strong></label>
    <p style="color: #08089d">
      ${(() => {
                            const recipients = info.event.extendedProps.emps_recipients || [];
                            if (recipients.length === 0) return 'N/A';
                            return recipients.map(r => r.user?.name).filter(Boolean).join(', ');
                        })()}
    </p>
  </div>

  <div class="visit-info-item">
    <label><strong>تاريخ بداية الزيارة:</strong></label>
    <p style="color: #08089d">${info.event.startStr ? new Date(info.event.startStr).toLocaleDateString('en-GB') : 'N/A'}</p>
  </div>

  <div class="visit-info-item">
    <label><strong>تاريخ نهاية الزيارة:</strong></label>
    <p style="color: #08089d">
      ${info.event.endStr
                            ? new Date(new Date(info.event.endStr).setDate(new Date(info.event.endStr).getDate() - 1)).toLocaleDateString('en-GB')
                            : 'N/A'}
    </p>
  </div>

  <div class="visit-info-item">
    <label><strong>وقت الزيارة:</strong></label>
    <p style="color: #08089d">${info.event.startStr ? new Date(info.event.startStr).toLocaleTimeString('en-US', {
                            hour: 'numeric',
                            minute: 'numeric',
                            hour12: true
                        }) : 'N/A'}</p>
  </div>

<div class="visit-info-item full-span">
  <label><strong>الخدمات الإضافية:</strong></label>
  <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 10px;">
    ${(() => {
                            let raw = info.event.extendedProps.extra_services;
                            console.log(raw);

                            const serviceMap = {
                                hotel: { label: 'حجز فندق', icon: '🏨', color: '#6293ff' },
                                flight: { label: 'حجز طيران', icon: '✈️', color: '#47d377' },
                                train: { label: 'حجز قطار', icon: '🚆', color: '#c48341' }
                            };

                            // 🔍 Handle JSON string or array
                            let services = [];

                            try {
                                if (typeof raw === 'string') {
                                    // Sometimes it's double-encoded, e.g. "\"[\\\"hotel\\\",\\\"flight\\\"]\""
                                    raw = raw.trim();
                                    if (raw.startsWith('"[') || raw.startsWith('[{')) {
                                        raw = JSON.parse(raw); // unquote the string
                                    }
                                    const parsed = JSON.parse(raw);
                                    if (Array.isArray(parsed)) {
                                        services = parsed;
                                    }
                                } else if (Array.isArray(raw)) {
                                    services = raw;
                                }
                            } catch (e) {
                                // If parsing fails, fallback to comma-separated string
                                services = typeof raw === 'string' ? raw.split(',') : [];
                            }

                            if (!services || services.length === 0) {
                                return `<li style="color: #08089d;">لا يوجد</li>`;
                            }

                            return services.map(s => {
                                const key = s.trim().replace(/['"]+/g, ''); // remove quotes
                                const svc = serviceMap[key] || { label: key, icon: '🔹', color: '#6b7280' };
                                return `
          <li style="
            background-color: ${svc.color};
            color: white;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
          ">
            <span>${svc.icon}</span>
            <span>${svc.label}</span>
          </li>
        `;
                            }).join('');
                        })()}
  </ul>
</div>


  <div></div> <!-- Spacer for layout balance -->
</div>
`,
                        showCancelButton: true,
                        showDenyButton: isOwner && status == 0 && isDeleted == 0,
                        showConfirmButton: isOwner && status == 0 && isDeleted == 0,
                        confirmButtonText: 'تعديل',
                        cancelButtonText: 'إغلاق',
                        denyButtonText: 'حذف',
                        didOpen: () => {

                            if (isRecipient && status == 0 && isDeleted == 0) {
                                // Approve button
                                const approveBtn = document.createElement('button');
                                approveBtn.innerText = 'موافقة';
                                approveBtn.className = 'swal2-styled';
                                approveBtn.style.backgroundColor = '#2f9d58';
                                approveBtn.style.color = '#fff';
                                approveBtn.style.marginLeft = '10px';
                                approveBtn.style.borderRadius = '5px';
                                approveBtn.addEventListener('click', () => {
                                    // your approve logic here
                                    Swal.fire({
                                        title: 'الموافقة',
                                        html: `
<label for="approve-reason" style="min-width: 120px;">يرجى كتابة الملاحظات إن وجد</label>
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <textarea id="approve-reason" class="swal2-textarea" style="flex: 1; height: 150px; resize: none; direction: rtl;
                 border: 1px solid #64748b;
                 padding: 0.625em;
                 border-radius: 0em;
                 font-family: inherit;
                 font-size: 10pt;"></textarea>
    </div>`,
                                        preConfirm: () => {
                                            const reason = document.getElementById('approve-reason').value.trim();
                                            return reason;
                                        },
                                        showCancelButton: true,
                                        confirmButtonText: 'تأكيد الموافقة',
                                        cancelButtonText: 'إلغاء',
                                    }).then((res) => {
                                        if (res.isConfirmed) {
                                            Livewire.emit('approveVisit', {
                                                id: info.event.id,
                                                status_notice: res.value
                                            });

                                            Swal.fire({
                                                title: 'تمت الموافقة!',
                                                icon: 'success',
                                                timer: 2000,
                                                showConfirmButton: false,
                                                timerProgressBar: true
                                            });
                                        }
                                    });
                                });


                                // Reject button
                                let canApprove = @json($can_approve);
                                console.log(canApprove);
                                if (canApprove) {
                                    const rejectBtn = document.createElement('button');
                                    rejectBtn.innerText = 'رفض';
                                    rejectBtn.className = 'swal2-styled';
                                    rejectBtn.style.backgroundColor = '#b91818';
                                    rejectBtn.style.color = '#fff';
                                    rejectBtn.style.marginLeft = '10px';
                                    rejectBtn.style.borderRadius = '5px';
                                    rejectBtn.addEventListener('click', () => {
                                        // your reject logic here
                                        Swal.fire({
                                            title: 'سبب الرفض',
                                            html: `
<label for="reject-reason" style="min-width: 120px;">يرجى إدخال سبب رفض الزيارة</label>
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <textarea id="reject-reason" class="swal2-textarea" style="flex: 1; height: 150px; resize: none; direction: rtl;
                 border: 1px solid #64748b;
                 padding: 0.625em;
                 border-radius: 0em;
                 font-family: inherit;
                 font-size: 10pt;"></textarea>
    </div>`,
                                            preConfirm: () => {
                                                const reason = document.getElementById('reject-reason').value.trim();
                                                if (!reason) {
                                                    Swal.showValidationMessage('يرجى كتابة سبب الرفض');
                                                    return false;
                                                }
                                                return reason;
                                            },
                                            showCancelButton: true,
                                            confirmButtonText: 'تأكيد الرفض',
                                            cancelButtonText: 'إلغاء',
                                        }).then((res) => {
                                            if (res.isConfirmed) {
                                                Livewire.emit('rejectVisit', {
                                                    id: info.event.id,
                                                    status_notice: res.value
                                                });

                                                Swal.fire({
                                                    title: 'تم الرفض!',
                                                    icon: 'info',
                                                    timer: 2000,
                                                    showConfirmButton: false,
                                                    timerProgressBar: true
                                                });
                                            }
                                        });
                                    });

                                    // Add buttons to Swal actions
                                    Swal.getActions().appendChild(rejectBtn);
                                    Swal.getActions().appendChild(approveBtn);
                                }
                            }
                            /* View Button */
                            const viewBtn = document.createElement('button');
                            viewBtn.innerText = 'عرض';
                            viewBtn.className = 'swal2-styled custom-view-btn';
                            viewBtn.style.backgroundColor = '#4caf50';
                            viewBtn.style.color = '#fff';
                            viewBtn.style.marginLeft = '10px';
                            viewBtn.style.padding = '0.625em 1.2em';
                            viewBtn.style.borderRadius = '0.25em';
                            viewBtn.style.fontWeight = 'bold';
                            viewBtn.style.border = 'none';

                            viewBtn.addEventListener('click', () => {
                                window.location.href = viewUrl;
                            });

                            Swal.getActions().appendChild(viewBtn);
                            /* End of View Button*/

                            /*
                            if (isRecipient && status == 0 && isDeleted == 0) {
                                document.getElementById('approve-btn').addEventListener('click', () => {
                                    // Livewire.emit('approveVisit', info.event.id);
                                    // Swal.fire('تمت الموافقة!', '', 'success');

                                    Swal.fire({
                                        title: 'الموافقة',
                                        html: `
<label for="approve-reason" style="min-width: 120px;">يرجى كتابة الملاحظات إن وجد</label>
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <textarea id="approve-reason" class="swal2-textarea" style="flex: 1; height: 150px; resize: none; direction: rtl;
                 border: 1px solid #64748b;
                 padding: 0.625em;
                 border-radius: 0em;
                 font-family: inherit;
                 font-size: 10pt;"></textarea>
    </div>`,
                                        preConfirm: () => {
                                            const reason = document.getElementById('approve-reason').value.trim();
                                            return reason;
                                        },
                                        showCancelButton: true,
                                        confirmButtonText: 'تأكيد الموافقة',
                                        cancelButtonText: 'إلغاء',
                                    }).then((res) => {
                                        if (res.isConfirmed) {
                                            Livewire.emit('approveVisit', {
                                                id: info.event.id,
                                                status_notice: res.value
                                            });

                                            Swal.fire({
                                                title: 'تمت الموافقة!',
                                                icon: 'success',
                                                timer: 2000,
                                                showConfirmButton: false,
                                                timerProgressBar: true
                                            });
                                        }
                                    });


                                });

                                document.getElementById('reject-btn').addEventListener('click', () => {
                                    Swal.fire({
                                        title: 'سبب الرفض',
                                        html: `
<label for="reject-reason" style="min-width: 120px;">يرجى إدخال سبب رفض الزيارة</label>
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <textarea id="reject-reason" class="swal2-tex:tarea" style="flex: 1; height: 150px; resize: none; direction: rtl;
                 border: 1px solid #64748b;
                 padding: 0.625em;
                 border-radius: 0em;
                 font-family: inherit;
                 font-size: 10pt;"></textarea>
    </div>`,
                                        preConfirm: () => {
                                            const reason = document.getElementById('reject-reason').value.trim();
                                            if (!reason) {
                                                Swal.showValidationMessage('يرجى كتابة سبب الرفض');
                                                return false;
                                            }
                                            return reason;
                                        },
                                        showCancelButton: true,
                                        confirmButtonText: 'تأكيد الرفض',
                                        cancelButtonText: 'إلغاء',
                                    }).then((res) => {
                                        if (res.isConfirmed) {
                                            Livewire.emit('rejectVisit', {
                                                id: info.event.id,
                                                status_notice: res.value
                                            });

                                            Swal.fire({
                                                title: 'تم الرفض!',
                                                icon: 'info',
                                                timer: 2000,
                                                showConfirmButton: false,
                                                timerProgressBar: true
                                            });
                                        }
                                    });
                                });
                            }
                            */
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Edit event - open SweetAlert2 edit form
                            Swal.fire({
                                title: 'تعديل الزيارة',
                                html:`<style>
  .edit-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    direction: rtl;
    width: 100%;
    box-sizing: border-box;
  }

  .edit-form-group {
    display: flex;
    flex-direction: column;
  }

  .edit-form-group label {
    font-weight: bold;
    margin-bottom: 6px;
    font-size: 14px;
  }

  .edit-form-group input,
  .edit-form-group select,
  .edit-form-group textarea {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-family: inherit;
    box-sizing: border-box;
    width: 100%;
  }

  .edit-form-group textarea {
    resize: none;
    height: 120px;
  }

  /* Mobile: force single column */
  @media (max-width: 600px) {
    .edit-form {
      grid-template-columns: 1fr !important;
    }

    .edit-form-group[style*="grid-column"] {
      grid-column: span 1 !important;
    }
  }
</style>

<div class="edit-form">
  <div class="edit-form-group">
    <label for="edit-title">عنوان الزيارة</label>
    <input type="text" id="edit-title" value="${info.event.title || ''}">
  </div>

  <div class="edit-form-group">
    <label for="edit-reason">سبب الزيارة</label>
    <input type="text" id="edit-reason" value="${info.event.extendedProps.reason || ''}">
  </div>

  <div class="edit-form-group" style="grid-column: span 2;">
    <label for="edit-goals">أهداف الزيارة</label>
    <textarea id="edit-goals">${info.event.extendedProps.goals || ''}</textarea>
  </div>

  <div class="edit-form-group">
    <label for="edit-branch">مكان الزيارة</label>
    <select id="edit-branch" disabled>
      <option value="" disabled>اختر المكان</option>
      ${Object.entries(branchMap).map(([key, name]) =>
                                    `<option value="${key}" ${info.event.extendedProps.branch === key ? 'selected' : ''}>${name}</option>`
                                ).join('')}
    </select>
  </div>

  <div class="edit-form-group">
    <label for="edit-employees">الموظفين</label>
    <select id="edit-employees" multiple></select>
  </div>

  <div class="edit-form-group">
    <label for="edit-visit-time">وقت الزيارة</label>
    <select id="edit-visit-time">
      <option value="" disabled>اختر الوقت</option>
      ${timeOptions.map(time =>
                                    `<option value="${time}" ${formatTime(info.event.start) === time ? 'selected' : ''}>${time}</option>`
                                ).join('')}
    </select>
  </div>

    <div class="edit-form-group" style="grid-column: span 2;">
  <label style="text-align: right">خدمات إضافية</label>
  <ul style="list-style: none; padding: 0; margin: 0;">
    ${(() => {
                                    const extraServices = info.event.extendedProps.extra_services || [];
                                    const servicesList = [
                                        { key: 'hotel', label: 'حجز فندق' },
                                        { key: 'flight', label: 'حجز طيران' },
                                        { key: 'train', label: 'حجز قطار' },
                                    ];

                                    let servicesArray = [];
                                    if (typeof extraServices === 'string') {
                                        try {
                                            servicesArray = JSON.parse(extraServices);
                                        } catch {
                                            servicesArray = extraServices.split(',');
                                        }
                                    } else if (Array.isArray(extraServices)) {
                                        servicesArray = extraServices;
                                    }

                                    return servicesList.map(s => {
                                        const isChecked = servicesArray.includes(s.key) ? 'checked' : '';
                                        return `
          <li style="margin-bottom: 8px;">
            <label style="
              cursor: pointer;
              display: inline-flex;
              align-items: center;
              gap: 6px;
              white-space: nowrap;
            ">
              <input type="checkbox" name="edit-extra-services" value="${s.key}" ${isChecked} style="flex-shrink: 0; width: 16px; height: 16px;">
              <span>${s.label}</span>
            </label>
          </li>
        `;
                                    }).join('');
                                })()}
  </ul>
</div>


</div>
`,
                                focusConfirm: false,
                                showCancelButton: true,
                                confirmButtonText: 'تحديث',
                                cancelButtonText: 'إلغاء',
                                reverseButtons: true,
                                didOpen: () => {
                                    const branchSelect = document.getElementById('edit-branch');
                                    const employeeSelect = document.getElementById('edit-employees');

                                    $(employeeSelect).select2({
                                        dir: "rtl",
                                        dropdownCssClass: "select-font-size",
                                        dropdownParent: document.querySelector('.swal2-popup'),
                                        placeholder: "اختر الموظفين"
                                    });

                                    const populateEmployees = (branchId, selected = []) => {
                                        const employees = employeesByBranch[branchId] || [];
                                        $(employeeSelect).empty();
                                        disabledEmployees = []; // Reset

                                        employees.forEach(emp => {
                                            const isGroup8 = emp.group == 8;
                                            const shouldBeSelected = selected.includes(emp.id.toString()) || isGroup8; // SELECT if previously selected OR group 8

                                            const option = new Option(emp.name, emp.id, shouldBeSelected, shouldBeSelected);

                                            if (isGroup8) {
                                                option.disabled = true;
                                                disabledEmployees.push(emp.id.toString());
                                            }

                                            $(employeeSelect).append(option);
                                        });

                                        $(employeeSelect).trigger('change');
                                    };


                                    const initialBranchId = branchSelect.value;
                                    const selectedEmpIds = (info.event.extendedProps.emps_recipients || []).map(emp => emp.user_id.toString());

                                    console.log('======= employees =======')
                                    console.log(info.event.extendedProps.employees);
                                    console.log(info.event);

                                    populateEmployees(initialBranchId, selectedEmpIds);

                                    branchSelect.addEventListener('change', () => {
                                        const newBranchId = branchSelect.value;
                                        populateEmployees(newBranchId);
                                    });

                                    $(employeeSelect).on('select2:unselecting', function (e) {
                                        const id = e.params.args.data.id;
                                        const option = $(this).find(`option[value="${id}"]`);
                                        if (option.prop('disabled')) {
                                            e.preventDefault();
                                        }
                                    });
                                },
                                preConfirm: () => {
                                    const title = document.getElementById('edit-title').value;
                                    const reason = document.getElementById('edit-reason').value;
                                    const goals = document.getElementById('edit-goals').value;
                                    const branch = document.getElementById('edit-branch').value;
                                    const visitTime = document.getElementById('edit-visit-time').value;
                                    // const selectedEmployees = $('#edit-employees').val();
                                    let selectedEmployees = $('#edit-employees').val() || [];
                                    disabledEmployees.forEach(id => {
                                        if (!selectedEmployees.includes(id)) {
                                            selectedEmployees.push(id);
                                        }
                                    });

                                    if (
                                        !title.trim() ||
                                        !reason.trim() ||
                                        !goals.trim() ||
                                        !branch ||
                                        !visitTime ||
                                        !selectedEmployees ||
                                        selectedEmployees.length === 0
                                    ) {
                                        Swal.showValidationMessage('الرجاء تعبئة جميع الحقول');
                                        return false;
                                    }

                                    const startDateTime = combineDateAndTime(info.event.startStr, visitTime);

                                    const extraServiceCheckboxes = document.querySelectorAll('input[name="edit-extra-services"]:checked');
                                    const extraServices = Array.from(extraServiceCheckboxes).map(cb => cb.value);

                                    return {
                                        title,
                                        reason,
                                        goals,
                                        branch,
                                        employees: selectedEmployees,
                                        extra_services: extraServices,
                                        start: startDateTime,
                                        end: info.event.endStr
                                    };
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Livewire.emit('updateVisit', {
                                        id: info.event.id,
                                        title: result.value.title,
                                        reason: result.value.reason,
                                        goals: result.value.goals,
                                        branch: result.value.branch,
                                        employees: result.value.employees,
                                        extra_services: result.value.extra_services,
                                        start: result.value.start,
                                        end: result.value.end
                                    });
                                }
                            });


                        } else if (result.isDenied) {
                            // Delete confirmation
                            Swal.fire({
                                title: 'هل متأكد من ذلك؟',
                                text: "سوف يتم حذف هذه الزيارة للأبد وإبلاغ الشخص المسؤول بمكان الزيارة",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'نعم، احذف',
                                cancelButtonText: 'إلغاء'
                            }).then((delResult) => {
                                if (delResult.isConfirmed) {
                                    // Livewire.emit('deleteVisit', info.event.id);
                                    // Swal.fire({
                                    //     title: 'تم الحذف!',
                                    //     text: 'هذه الزيارة تم حذفها',
                                    //     icon: 'success',
                                    //     timer: 2000,
                                    //     showConfirmButton: false,
                                    //     timerProgressBar: true
                                    // });

                                    Swal.fire({
                                        title: 'سبب الحذف',
                                        html: `
<label for="delete-reason" style="min-width: 120px;">يرجى إدخال سبب حذف الزيارة</label>
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <textarea id="delete-reason" class="swal2-textarea" style="flex: 1; height: 150px; resize: none; direction: rtl;
                 border: 1px solid #64748b;
                 padding: 0.625em;
                 border-radius: 0em;
                 font-family: inherit;
                 font-size: 10pt;"></textarea>
    </div>
  `,
                                        focusConfirm: false,
                                        showCancelButton: true,
                                        confirmButtonText: 'حذف',
                                        cancelButtonText: 'إلغاء',
                                        customClass: {
                                            popup: 'responsive-modal'
                                        },
                                        preConfirm: () => {
                                            const reason = document.getElementById('delete-reason').value.trim();
                                            if (!reason) {
                                                Swal.showValidationMessage('يجب إدخال السبب قبل الحذف');
                                                return false;
                                            }
                                            return reason;
                                        }
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const reason = result.value;

                                            Livewire.emit('deleteVisit', {
                                                id: info.event.id,
                                                delete_reason: reason
                                            });

                                            Swal.fire({
                                                title: 'تم الحذف!',
                                                text: 'هذه الزيارة تم حذفها',
                                                icon: 'success',
                                                timer: 2000,
                                                showConfirmButton: false,
                                                timerProgressBar: true
                                            });
                                        }
                                    });


                                }
                            });
                        }
                        // If Cancel: do nothing (close details)
                    });
                }

            });

            calendar.render();

            Livewire.on('visitsLoaded', (visits) => {
                calendar.removeAllEvents();
                calendar.addEventSource(visits.map(formatVisit)); // Apply formatting again
                console.log(visits);
            });

            function combineDateAndTime(dateStr, timeStr) {
                const [year, month, day] = dateStr.split("T")[0].split("-").map(Number);
                const [time, modifier] = timeStr.split(" ");
                let [hours, minutes] = time.split(":").map(Number);

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
                let backgroundColor;
                let textColor = '#000000';

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
                    default:
                        backgroundColor = '#e5e7eb';
                }

                return {
                    ...visit,
                    backgroundColor,
                    textColor,
                    borderColor: 'transparent'
                };
            }

            const calendarTab = document.getElementById('calendarTab');
            const listTab = document.getElementById('listTab');
            const calendarPanel = document.getElementById('calendarPanel');
            const listPanel = document.getElementById('listPanel');

            calendarTab.addEventListener('click', () => {
                calendarTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                listTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                calendarPanel.classList.remove('hidden');
                listPanel.classList.add('hidden');
            });

            listTab.addEventListener('click', () => {
                listTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                calendarTab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                listPanel.classList.remove('hidden');
                calendarPanel.classList.add('hidden');
            });


        });

    </script>
@stop
