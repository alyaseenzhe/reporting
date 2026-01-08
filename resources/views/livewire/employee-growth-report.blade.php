@section('title')
    16- تقرير نمو الموظفين
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
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 199.404 199.404"
                             xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقرير نمو الموظفين</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    {{--    <div--}}
    {{--        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">--}}
    {{--        <div class="w-full">تقرير نمو الموظفين</div>--}}
    {{--    </div>--}}
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">الفرع
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="area_id" name="area_id" wire:model="area_id"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('item_id') border: solid 1px #fda4af; @enderror">
                        <option value="-1">الرجاء اختيار الفرع</option>
                        @if(in_array("3", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="01">فرع الاحساء</option>
                        @endif
                        @if(in_array("10", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="02">فرع جدة</option>
                        @endif
                        @if(in_array("7", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="03">فرع الرياض</option>
                        @endif
                        @if(in_array("13", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="04">فرع وادي الدواسر</option>
                        @endif
                        @if(in_array("4", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="05">فرع الجوف</option>
                        @endif
                        @if(in_array("6", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="06">فرع الدمام</option>
                        @endif
                        @if(in_array("5", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="07">فرع الخرج</option>
                        @endif
                        @if(in_array("12", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="08">فرع نجران</option>
                        @endif
                        @if(in_array("11", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="09">فرع حائل</option>
                        @endif
                        @if(in_array("9", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="10">فرع تبوك</option>
                        @endif
                        @if(in_array("8", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="11">فرع القصيم</option>
                        @endif
                        @if(in_array("505", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="12">فرع ساجر</option>
                        @endif
                    </select>
                    @error('area_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="date" name="start_date" wire:model="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="date" name="end_date" wire:model="end_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mt-8 text-center w-full">
                    <button wire:click.prevent="generateReport" wire:loading.attr="disabled"
                            style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">
                            <span></span>
                            <span>إنشاء تقرير</span>
                        </span>
                        <span class="mr-2 font-bold" wire:loading wire:target="generateReport">
                        <span></span>
                        <span>الرجاء الانتظار</span>
                        </span>
                    </button>
                </div>
                {{--                <div wire:ignore class="mt-8 text-center w-full">--}}
                {{--                    <button wire:click.prevent="sendReport" wire:loading.attr="disabled"--}}
                {{--                            style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                {{--                        <span class="mr-2 font-bold" wire:loading.remove wire:target="sendReport">--}}
                {{--                            <span></span>--}}
                {{--                            <span>ارسال</span>--}}
                {{--                        </span>--}}
                {{--                        <span class="mr-2 font-bold" wire:loading wire:target="sendReport">--}}
                {{--                        <span></span>--}}
                {{--                        <span>الرجاء الانتظار</span>--}}
                {{--                        </span>--}}
                {{--                    </button>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
    <div id="report-btn" wire:loading.remove wire:target="generateReport" class="hide printable">
        {{-- table 2 (details) --}}
        <div id="tbl2-container" class="overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th class="whitespace-nowrap">
                        <div class="text-xs">#</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="whitespace-nowrap">
                        <div class="text-xs">الاسم</div>
                    </th>
                    {{--                    <th>--}}
                    {{--                        <div class="text-xs">--}}
                    {{--                            <div>#</div>--}}
                    {{--                            <div>عملاء</div>--}}
                    {{--                            <div>مبيعات</div>--}}
                    {{--                        </div>--}}
                    {{--                    </th>--}}
                    <th>
                        <div class="text-xs">
                            <div>مبيعات فترة</div>
                        </div>
                    </th>
                    {{--                    <th style="border-left: 1px solid black;">--}}
                    {{--                        <div class="text-xs">--}}
                    {{--                            <div>%</div>--}}
                    {{--                            <div>كفاءة</div>--}}
                    {{--                        </div>--}}
                    {{--                    </th>--}}
                    {{--                    <th style="border-left: 2px solid black;">--}}
                    {{--                        <div class="text-xs">--}}
                    {{--                            <div>#عملاء</div>--}}
                    {{--                            <div>> 50k</div>--}}
                    {{--                        </div>--}}
                    {{--                    </th>--}}
                    <th>
                        <div class="text-xs">مبيعات سابقة</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">
                            <div>%</div>
                            <div>نمو مبيعات</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">
                            <div>#</div>
                            <div>بذور فترة</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">
                            <div>$</div>
                            <div>بذور فترة</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">
                            <div>#</div>
                            <div>بذور سابقة</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">
                            <div>$</div>
                            <div>بذور سابقة</div>
                        </div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">
                            <div>%</div>
                            <div>نمو بذور</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">
                            <div>#</div>
                            <div>مبيدات فترة</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">$مبيدات فترة</div>
                    </th>
                    <th>
                        <div class="text-xs">
                            <div>#</div>
                            <div>مبيدات سابقة</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">$مبيدات سابقة</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">
                            <div>%</div>
                            <div>نمو مبيدات</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">#اسمدة فترة</div>
                    </th>
                    <th>
                        <div class="text-xs">$اسمدة فترة</div>
                    </th>
                    <th>
                        <div class="text-xs">#اسمدة سابقة</div>
                    </th>
                    <th>
                        <div class="text-xs">$اسمدة سابقة</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">
                            <div>%</div>
                            <div>نمو اسمدة</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">#اخرى فترة</div>
                    </th>
                    <th>
                        <div class="text-xs">$اخرى فترة</div>
                    </th>
                    <th>
                        <div class="text-xs">#اخرى سابقة</div>
                    </th>
                    <th>
                        <div class="text-xs">$اخرى سابقة</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">
                            <div>%</div>
                            <div>نمو اخرى</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">مميز 1 فترة</div>
                    </th>
                    <th>
                        <div class="text-xs">مميز 1 سابقة</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">
                            <div>%</div>
                            <div>نمو م1</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">مميز 2 فترة</div>
                    </th>
                    <th>
                        <div class="text-xs">مميز 2 سابقة</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">
                            <div>%</div>
                            <div>نمو م2</div>
                        </div>
                    </th>
                    <th>
                        <div class="text-xs">مميز 0 فترة</div>
                    </th>
                    <th>
                        <div class="text-xs">مميز 0 سابقة</div>
                    </th>
                    <th>
                        <div class="text-xs">
                            <div>%</div>
                            <div>نمو م0</div>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @php

                    $total_overThousands = 0;

                    $total_grand_total = 0;
                    $old_total_grand_total = 0;
                    $totalCount_bathoor = 0;
                    $old_totalCount_bathoor = 0;
                    $total_bathoor = 0;
                    $old_total_bathoor = 0;
                    $totalCount_mobedat = 0;
                    $old_totalCount_mobedat = 0;
                    $total_mobedat = 0;
                    $old_total_mobedat = 0;
                    $totalCount_asmedah = 0;
                    $old_totalCount_asmedah = 0;
                    $total_asmedah = 0;
                    $old_total_asmedah = 0;
                    $totalCount_other = 0;
                    $old_totalCount_other = 0;
                    $total_other = 0;
                    $old_total_other = 0;
                    $total_sp0 = 0;
                    $old_total_sp0 = 0;
                    $total_sp1 = 0;
                    $old_total_sp1 = 0;
                    $total_sp2 = 0;
                    $old_total_sp2 = 0;
                @endphp

                @foreach($sap_results as $record)
                    <tr>
                        <td style="background-color: #e8f9e8;" class="whitespace-nowrap">
                            {{ $record["SlpCode"] }}
                        </td>
                        <td style="background-color: #e8f9e8; border-left: 2px solid black;" class="whitespace-nowrap">
                            {{ $record["SlpName"] }}
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round((floatval($record['FullTotalNEW']))/1000)) }}
                            @php $total_grand_total +=  floatval($record['FullTotalNEW'])  @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round((floatval($record['FullTotalOLD']))/1000)) }}
                            @php $old_total_grand_total +=  floatval($record['FullTotalOLD'])  @endphp
                        </td>
                        <td style="border-left: 2px solid black; background-color: #afeeee;">
                            {{ (floatval($record['FullTotalOLD'])) == 0 ? 0 : number_format((((floatval($record['FullTotalNEW']))-(floatval($record['FullTotalOLD'])))/floatval($record['FullTotalOLD']))*100) }}
                        </td>
                        <td>
                            {{ number_format($record['SeedsCount NEW']) }}
                            @php $totalCount_bathoor +=  floatval($record['SeedsCount NEW']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['Seeds NEW']/1000)) }}
                            @php $total_bathoor +=  floatval($record['Seeds NEW']) @endphp
                        </td>
                        <td>
                            {{ number_format($record['SeedsCount OLD']) }}
                            @php $old_totalCount_bathoor +=  floatval($record['SeedsCount OLD']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['Seeds OLD']/1000)) }}
                            @php $old_total_bathoor +=  floatval($record['Seeds OLD']) @endphp
                        </td>
                        <td style="border-left: 2px solid black; background-color: #afeeee;">
                            {{ (floatval($record['Seeds OLD'])) == 0 ? 0 : number_format((((floatval($record['Seeds NEW']))-(floatval($record['Seeds OLD'])))/floatval($record['Seeds OLD']))*100) }}
                        </td>

                        <td>
                            {{ number_format($record['ChemicalsCount NEW']) }}
                            @php $totalCount_mobedat +=  floatval($record['ChemicalsCount NEW']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['Chemicals NEW']/1000)) }}
                            @php $total_mobedat +=  floatval($record['Chemicals NEW']) @endphp
                        </td>
                        <td>
                            {{ number_format($record['ChemicalsCount OLD']) }}
                            @php $old_totalCount_mobedat +=  floatval($record['ChemicalsCount OLD']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['Chemicals OLD']/1000)) }}
                            @php $old_total_mobedat +=  floatval($record['Chemicals OLD']) @endphp
                        </td>
                        <td style="border-left: 2px solid black; background-color: #afeeee;">
                            {{ (floatval($record['Chemicals OLD'])) == 0 ? 0 : number_format((((floatval($record['Chemicals NEW']))-(floatval($record['Chemicals OLD'])))/floatval($record['Chemicals OLD']))*100) }}
                        </td>
                        <td>
                            {{ number_format($record['FertilizersCount NEW']) }}
                            @php $totalCount_asmedah +=  floatval($record['FertilizersCount NEW']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['Fertilizers NEW']/1000)) }}
                            @php $total_asmedah +=  floatval($record['Fertilizers NEW']) @endphp
                        </td>
                        <td>
                            {{ number_format($record['FertilizersCount OLD']) }}
                            @php $old_totalCount_asmedah +=  floatval($record['FertilizersCount OLD']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['Fertilizers OLD']/1000)) }}
                            @php $old_total_asmedah +=  floatval($record['Fertilizers OLD']) @endphp
                        </td>
                        <td style="border-left: 2px solid black; background-color: #afeeee;">
                            {{ (floatval($record['Fertilizers OLD'])) == 0 ? 0 : number_format((((floatval($record['Fertilizers NEW']))-(floatval($record['Fertilizers OLD'])))/floatval($record['Fertilizers OLD']))*100) }}
                        </td>
                        <td>
                            {{ number_format($record['OthersCount NEW']) }}
                            @php $totalCount_other +=  floatval($record['OthersCount NEW']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['Others NEW']/1000)) }}
                            @php $total_other +=  floatval($record['Others NEW']) @endphp
                        </td>
                        <td>
                            {{ number_format($record['OthersCount OLD']) }}
                            @php $old_totalCount_other +=  floatval($record['OthersCount OLD']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['Others OLD']/1000)) }}
                            @php $old_total_other +=  floatval($record['Others OLD']) @endphp
                        </td>
                        <td style="border-left: 2px solid black; background-color: #afeeee;">
                            {{ (floatval($record['Others OLD'])) == 0 ? 0 : number_format((((floatval($record['Others NEW']))-(floatval($record['Others OLD'])))/floatval($record['Others OLD']))*100) }}
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['S1 Sales NEW']/1000))}}
                            @php $total_sp1 +=  floatval($record['S1 Sales NEW']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['S1 Sales OLD']/1000))}}
                            @php $old_total_sp1 +=  floatval($record['S1 Sales OLD']) @endphp
                        </td>
                        <td style="border-left: 2px solid black; background-color: #afeeee;">
                            {{ (floatval($record['S1 Sales OLD'])) == 0 ? 0 : number_format((((floatval($record['S1 Sales NEW']))-(floatval($record['S1 Sales OLD'])))/floatval($record['S1 Sales OLD']))*100) }}
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['S2 Sales NEW']/1000))}}
                            @php $total_sp2 +=  floatval($record['S2 Sales NEW']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['S2 Sales OLD']/1000))}}
                            @php $old_total_sp2 +=  floatval($record['S2 Sales OLD']) @endphp
                        </td>
                        <td style="border-left: 2px solid black; background-color: #afeeee;">
                            {{ (floatval($record['S2 Sales OLD'])) == 0 ? 0 : number_format((((floatval($record['S2 Sales NEW']))-(floatval($record['S2 Sales OLD'])))/floatval($record['S2 Sales OLD']))*100) }}
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['S0 Sales NEW']/1000))}}
                            @php $total_sp0 +=  floatval($record['S0 Sales NEW']) @endphp
                        </td>
                        <td style="background-color: #ffebcd;">
                            {{ number_format(round($record['S0 Sales OLD']/1000))}}
                            @php $old_total_sp0 +=  floatval($record['S0 Sales OLD']) @endphp
                        </td>
                        <td style="border-left: 2px solid black; background-color: #afeeee;">
                            {{ (floatval($record['S0 Sales OLD'])) == 0 ? 0 : number_format((((floatval($record['S0 Sales NEW']))-(floatval($record['S0 Sales OLD'])))/floatval($record['S0 Sales OLD']))*100) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr style="border-top: 2px solid black; font-weight: bold; background-color: #fff8dc;">
                    <td style="border-left: 2px solid black;" colspan="2">آداء الفرع كامل</td>
                    <td>{{ number_format(round($total_grand_total/1000)) }}</td>
                    <td>{{ number_format(round($old_total_grand_total/1000)) }}</td>
                    <td style="border-left: 2px solid black;">
                        {{ (floatval($old_total_grand_total)) == 0 ? 0 : number_format((((floatval($total_grand_total))-(floatval($old_total_grand_total)))/floatval($old_total_grand_total))*100) }}
                    </td>
                    <td>{{ number_format(round($totalCount_bathoor)) }}</td>
                    <td>{{ number_format(round($total_bathoor/1000)) }}</td>
                    <td>{{ number_format(round($old_totalCount_bathoor)) }}</td>
                    <td>{{ number_format(round($old_total_bathoor/1000)) }}</td>
                    <td style="border-left: 2px solid black;">
                        {{ (floatval($old_total_bathoor)) == 0 ? 0 : number_format((((floatval($total_bathoor))-(floatval($old_total_bathoor)))/floatval($old_total_bathoor))*100) }}
                    </td>
                    <td>{{ number_format(round($totalCount_mobedat)) }}</td>
                    <td>{{ number_format(round($total_mobedat/1000)) }}</td>
                    <td>{{ number_format(round($old_totalCount_mobedat)) }}</td>
                    <td>{{ number_format(round($old_total_mobedat/1000)) }}</td>
                    <td style="border-left: 2px solid black;">
                        {{ (floatval($old_total_mobedat)) == 0 ? 0 : number_format((((floatval($total_mobedat))-(floatval($old_total_mobedat)))/floatval($old_total_mobedat))*100) }}
                    </td>
                    <td>{{ number_format(round($totalCount_asmedah)) }}</td>
                    <td>{{ number_format(round($total_asmedah/1000)) }}</td>
                    <td>{{ number_format(round($old_totalCount_asmedah)) }}</td>
                    <td>{{ number_format(round($old_total_asmedah/1000)) }}</td>
                    <td style="border-left: 2px solid black;">
                        {{ (floatval($old_total_asmedah)) == 0 ? 0 : number_format((((floatval($total_asmedah))-(floatval($old_total_asmedah)))/floatval($old_total_asmedah))*100) }}
                    </td>
                    <td>{{ number_format(round($totalCount_other)) }}</td>
                    <td>{{ number_format(round($total_other/1000)) }}</td>
                    <td>{{ number_format(round($old_totalCount_other)) }}</td>
                    <td>{{ number_format(round($old_total_other/1000)) }}</td>
                    <td style="border-left: 2px solid black;">
                        {{ (floatval($old_total_other)) == 0 ? 0 : number_format((((floatval($total_other))-(floatval($old_total_other)))/floatval($old_total_other))*100) }}
                    </td>
                    <td>{{ number_format(round($total_sp1/1000)) }}</td>
                    <td>{{ number_format(round($old_total_sp1/1000)) }}</td>
                    <td style="border-left: 2px solid black;">
                        {{ (floatval($old_total_sp1)) == 0 ? 0 : number_format((((floatval($total_sp1))-(floatval($old_total_sp1)))/floatval($old_total_sp1))*100) }}
                    </td>
                    <td>{{ number_format(round($total_sp2/1000)) }}</td>
                    <td>{{ number_format(round($old_total_sp2/1000)) }}</td>
                    <td style="border-left: 2px solid black;">
                        {{ (floatval($old_total_sp2)) == 0 ? 0 : number_format((((floatval($total_sp2))-(floatval($old_total_sp2)))/floatval($old_total_sp2))*100) }}
                    </td>
                    <td>{{ number_format(round($total_sp0/1000)) }}</td>
                    <td>{{ number_format(round($old_total_sp0/1000)) }}</td>
                    <td style="border-left: 2px solid black;">
                        {{ (floatval($old_total_sp0)) == 0 ? 0 : number_format((((floatval($total_sp0))-(floatval($old_total_sp0)))/floatval($old_total_sp0))*100) }}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <div style="margin-top: 20px; margin-right: 22px;">
            <span style="font-weight: bold; margin-bottom: 20px">ملاحظات:</span>
            <ul style="list-style-type: disc; text-decoration: underline">
                <li style="margin-top: 10px;">جميع أرقام المبيعات الموجودة في الجدول غير شاملة الضريبة.</li>
                <li>جميع الأرقام المالية الموجودة في الجدول هي بالآلاف.</li>
            </ul>
        </div>
    </div>
    <div  wire:loading wire:target="generateReport" class="w-full">
        <div class="w-full" style="border: solid 1px grey;">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgb(255, 255, 255); display: block; shape-rendering: auto;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                <rect x="17.5" y="30" width="15" height="40" fill="#e15b64">
                    <animate attributeName="y" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="18;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.2s"></animate>
                    <animate attributeName="height" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="64;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.2s"></animate>
                </rect>
                <rect x="42.5" y="30" width="15" height="40" fill="#f8b26a">
                    <animate attributeName="y" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.1s"></animate>
                    <animate attributeName="height" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.1s"></animate>
                </rect>
                <rect x="67.5" y="30" width="15" height="40" fill="#abbd81">
                    <animate attributeName="y" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate>
                    <animate attributeName="height" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate>
                </rect>
            </svg>

            <div class="mb-4 bold text-2xl text-center">
                الرجاء الإنتظار
            </div>
        </div>
    </div>
</div>

@section('scripts')

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

    <script>
        Livewire.on('show-container', () => {

            div = document.getElementById("report-btn");
            div_title = document.getElementById("report_title");
            area = document.getElementById("area_id");
            start_date = document.getElementById("start_date");
            end_date = document.getElementById("end_date");

            div.classList.remove("hide");

        });
    </script>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css" />

@stop
