@section('title')
    عرض التقرير
@stop
<div>
    <div class="mb-5">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        <span class="mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">الصفحة الرئيسية</span>
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <a href="{{ route('list.employees-daily-reports') }}" class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        <span class="mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقارير الموظفين</span>
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 199.404 199.404"
                             xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">عرض تقرير</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    @if(\Illuminate\Support\Facades\Auth::user()->role == 'a')
        <div wire:ignore class="mt-8 mb-4 text-center">
            <button wire:click.prevent="sendReport" wire:loading.attr="disabled"
                    style="background-color: #026832;" class="btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold" wire:loading.remove wire:target="sendReport">
                            <span></span>
                            <span>ارسال التقرير على الايميل</span>
                        </span>
                <span class="mr-2 font-bold" wire:loading wire:target="sendReport">
                        <span></span>
                        <span>الرجاء الانتظار</span>
                        </span>
            </button>
        </div>
    @endif

    <div>
        <div class="w-full flex sm:flex-row flex-col gap-4 mb-5" style="background-color: #f5f5f5; padding: 20px;">
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">اسم الموظف</label>
                <div style="color: #5222e1">{{$records[0]->user->name}}</div>
            </div>
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">الفترة</label>
                <div style="color: #5222e1">{{$records[0]->start_of_week}} - {{ $records[0]->end_of_week }}</div>
            </div>
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">عدد المهام المنجزة</label>
                <div style="color: #5222e1">{{count($records)}}</div>
            </div>
        </div>
        {{-- table 2 (details) --}}
        <div id="tbl2-container" class="overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th class="whitespace-nowrap">
                        <div class="text-xs">اليوم</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="whitespace-nowrap">
                        <div class="text-xs">نوع النشاط</div>
                    </th>
                    <th>
                        <div class="text-xs">الموقع</div>
                    </th>
                    <th>
                        <div class="text-xs">العميل</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">المرافقون</div>
                    </th>
                    <th>
                        <div class="text-xs">العمل/المنجزات</div>
                    </th>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">

                @foreach($records as $record)
                    <tr>
                        <td style="background-color: #e8f9e8;" class="whitespace-nowrap">
                            @php $trans = \Carbon\Carbon::parse($record->report_date); $trans->locale('ar'); @endphp
                            <div>{{ $trans->translatedFormat('l') }}</div>
                            <div>{{$record->report_date}}</div>
                        </td>
                        <td style="background-color: #e8f9e8; border-left: 2px solid black;" class="whitespace-nowrap">
                            @if($record->report_type == 'work')
                                تقرير عمل
                            @elseif($record->report_type == 'visit')
                                تقرير زيارة
                            @elseif($record->report_type == 'sales')
                                تحصيل/مبيعات
                            @elseif($record->report_type == 'general-rept')
                                تقرير عام عن عميل
                            @elseif($record->report_type == 'meeting')
                                إجتماع
                            @endif
                        </td>
                        <td>
                            {{$record->location2}}
                        </td>
                        <td>
                            {{$record->customer_name}}
                        </td>
                        <td style="border-left: 2px solid black;">
                            {{$record->companion}}
                        </td>
                        <td>
                            {{$record->report_note}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')

    <script src="{{ asset('js/jquery.min.js') }}"></script>
@stop
@section('css-scripts')
    <style>
        table td {
            border: solid 1px black;
            padding: 0.5rem;
        }

        table th{
            border: solid 1px black;
            padding: 0.5rem;
        }

        .hide {
            display: none;
        }

        #report-logo {
            display: none;
        }

        @media print {

            @page {size: A4 landscape}

            html { overflow: hidden; }

            body * {
                visibility: hidden;
                margin:0; padding:0;
                background-color: white;
            }
            .printable * {
                visibility: visible;
            }
            #tbl2 {
                transform: scale(0.7);
                translate: 14%;
            }
            #tbl2-container {
                overflow: hidden;
            }

            #branch-container {
                display: none;
            }

            #body-content {
                background-color: white;
            }

            #report-logo {
                display: unset;
            }

            .sticky {
                display: none;
            }
        }
    </style>

@stop
