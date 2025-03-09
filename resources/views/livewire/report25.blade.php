@section('title')
    تقرير حركة عميل
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 199.404 199.404"
                             xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                        <a href="{{ route('sap-reports') }}" class="text-gray-700 hover:text-gray-900 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقارير ساب</a>
                    </div>
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
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقرير حركة عميل</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div id="branch-container" class="mb-6 mt-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">العميل
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="customer_name" name="customer_name"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('vendor_type') border: solid 1px #fda4af; @enderror">
                            {{--                            <option value="customer_all">الكل</option>--}}
                            @foreach($customer_list as $customer)
                                <option value="{{ $customer['CardCode'] }}">{{$customer['CardCode']}} : {{ $customer['CardName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('customer_name') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                    </label>
                    <input id="start_date" type="date" name="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div wire:ignore class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                    </label>
                    <input id="end_date" type="date" name="end_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                {{--                <div class="mt-8 text-center w-full">--}}
                {{--                    <button id="reset-btn" style="background-color: #01290f;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                {{--                        <span class="mr-2 font-bold">--}}
                {{--                            <span></span>--}}
                {{--                            <span>إعادة ضبط</span>--}}
                {{--                        </span>--}}
                {{--                    </button>--}}
                {{--                </div>--}}
                <div class="mt-8 text-center w-full">
                    <button id="gen-report" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
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
            </div>
        </div>
    </div>
    @if($show_msg)
        @if($type == 'scribe' || $type == 'both')
            <div id="tbl2-container" class="overflow-x-auto">
                <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                    <tbody style="direction: ltr" class="text-sm divide-y divide-gray-100">
                    <tr style="font-weight: bold; background-color: #dcdcdc;">
                        <td colspan="3" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                            صافي العمليات
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border-left: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            المبيعات
                        </td>
                        <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{$sales_count}}
                            {{--                        {{$scribes_results[0]->sales_count}}--}}
                        </td>
                        <td style="@if(floatval($sales_sum) < 0) color: red; @else color: green; @endif border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{ number_format($sales_sum, 2)}}
                            {{--                        {{ number_format($scribes_results[0]->sales_sum, 2)}}--}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            عكس المبيعات
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{$reverse_count}}
                            {{--                        {{$scribes_results[0]->reverse_count}}--}}
                        </td>
                        <td style="@if(floatval($reverse_sum) < 0) color: red; @else color: green; @endif border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($reverse_sum, 2) }}
                            {{--                        {{number_format($scribes_results[0]->reverse_sum, 2) }}--}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background: rgb(250,235,215); background: linear-gradient(90deg, rgba(250,235,215,1) 75%, rgba(93,125,26,1) 100%);" class="border p-2 whitespace-nowrap">
                            صافي المبيعات
                        </td>

                        <td colspan="2" style="border: 2px solid black; text-align: center" class="border p-2 whitespace-nowrap">
                            {{ number_format(($sales_sum-$reverse_sum), 2) }}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black;background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            سند قبض
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{$receipt_count}}
                            {{--                        {{$scribes_results[0]->receipt_count}}--}}
                        </td>
                        <td style="@if(floatval($receipt_sum) < 0) color: red; @else color: green; @endif border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($receipt_sum, 2) }}
                            {{--                        {{number_format($scribes_results[0]->receipt_sum, 2) }}--}}
                        </td>
                    </tr>

                    {{--                <tr style="font-weight: bold">--}}
                    {{--                    <td style="border: 2px solid black; background: rgb(250,235,215); background: linear-gradient(90deg, rgba(250,235,215,1) 75%, rgba(93,125,26,1) 100%);" class="border p-2 whitespace-nowrap">--}}
                    {{--                        الإجمالي--}}
                    {{--                    </td>--}}
                    {{--                    <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">--}}
                    {{--                        {{$sales_count+ $reverse_count + $receipt_count}}--}}
                    {{--                        {{$scribes_results[0]->sales_count+ $scribes_results[0]->reverse_count + $scribes_results[0]->receipt_count}}--}}
                    {{--                    </td>--}}
                    {{--                    <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">--}}
                    {{--                        {{number_format($sales_sum + $reverse_sum + $receipt_sum, 2) }}--}}
                    {{--                    </td>--}}
                    {{--                </tr>--}}
                    </tbody>
                </table>
                <table id="tbl3" style="border: 2px solid black; margin-top: 20px;" class="table-container table-auto w-full border text-center">
                    <tbody style="direction: ltr" class="text-sm divide-y divide-gray-100">
                    <tr style="font-weight: bold; background-color: #dcdcdc;">
                        <td colspan="3" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                            الرصيد والتعمير
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border-left: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            الحد الإئتماني
                        </td>
                        <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->credit_limit)}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            شهر 1
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->month1, 2)}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            شهر 2
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->month2, 2)}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            شهر 3
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->month3, 2)}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            شهر 4
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->month4, 2)}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            شهر 5
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->month5, 2)}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            اقدم
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->month6, 2)}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background: rgb(250,235,215); background: linear-gradient(90deg, rgba(250,235,215,1) 75%, rgba(93,125,26,1) 100%);" class="border p-2 whitespace-nowrap">
                            الرصيد
                        </td>

                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{ number_format($scribes_results[0]->month1 + $scribes_results[0]->month2 + $scribes_results[0]->month3 + $scribes_results[0]->month4 + $scribes_results[0]->month5 + $scribes_results[0]->month6, 2) }}
                        </td>
                    </tr>
                    </tbody>
                </table>

                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                    <div class="flex flex-col sm:flex-row gap-4 w-full">
                        <div style="background-color: #f5f5f5; padding-right: 20px; padding-top: 20px; margin-top: 15px;" class="w-full">
                            <label class="block font-bold mb-5">خيارات اخفاء الأعمدة الخاصة</label>
                            <div class="flex flex-row gap-2.5">
                                <div class="flex items-center mb-4 ml-8">
                                    <input id="cost" type="checkbox" value="cost" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">اخفاء</label>
                                </div>
                                {{--                                    <div class="flex items-center mb-4 ml-8">--}}
                                {{--                                        <input id="cost" type="checkbox" value="cost" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                                {{--                                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">التكلفة</label>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="flex items-center mb-4 ml-8">--}}
                                {{--                                        <input id="margin" type="checkbox" value="margin" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                                {{--                                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الهامش</label>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="flex items-center mb-4 ml-8">--}}
                                {{--                                        <input id="margin-percentage" type="checkbox" value="margin-percentage" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                                {{--                                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">النسبة</label>--}}
                                {{--                                    </div>--}}
                            </div>
                        </div>
                    </div>
            </div>
        @endif

                <table id="tbl4" style="border: 2px solid black; margin-top: 20px; color: black" class="table-container table-auto w-full border text-center">
                    <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                    <tr style="font-weight: bold; background-color: #dcdcdc; color: #475569;">
                        <td colspan="7" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                            مبيعات وهامش
                        </td>
                    </tr>
                    <tr style="border: 2px solid black; color: black; background-color: antiquewhite;">
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">النوع</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">عدد</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">مبيعات</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">% مبيعات</div>
                        </th>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">
                                <div class="text-sm">تكلفة بضاعة</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">
                                <div class="text-sm">هامش بالريال</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">
                                <div class="text-sm">هامش %</div>
                            </th>
                        @endif
                    </tr>
                    </thead>
                    <tbody style="direction: ltr" class="text-sm divide-y divide-gray-100">
                    <tr style="font-weight: bold">
                        <td style="border-left: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            مميز 0
                        </td>
                        <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->sp0_count)}}
                        </td>
                        <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->sp0_sales, 2)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format(($scribes_results[0]->sp0_sales/($scribes_results[0]->sp0_sales+$scribes_results[0]->sp1_sales+$scribes_results[0]->sp2_sales))*100, 2)}}
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format($scribes_results[0]->sp0_cost, 2)}}
                            </td>
                            <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($scribes_results[0]->sp0_sales) - floatval($scribes_results[0]->sp0_cost), 2)}}
                            </td>
                            <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($scribes_results[0]->sp0_cost) == 0 ? 0 : ((floatval($scribes_results[0]->sp0_sales) - floatval($scribes_results[0]->sp0_cost))/floatval($scribes_results[0]->sp0_cost))*100 , 2)}}
                            </td>
                        @endif
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            مميز 1
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->sp1_count)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->sp1_sales, 2)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format(($scribes_results[0]->sp1_sales/($scribes_results[0]->sp0_sales+$scribes_results[0]->sp1_sales+$scribes_results[0]->sp2_sales))*100, 2)}}
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format($scribes_results[0]->sp1_cost, 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($scribes_results[0]->sp1_sales) - floatval($scribes_results[0]->sp1_cost), 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($scribes_results[0]->sp1_cost) == 0 ? 0 : ((floatval($scribes_results[0]->sp1_sales) - floatval($scribes_results[0]->sp1_cost))/floatval($scribes_results[0]->sp1_cost))*100 , 2)}}
                            </td>
                        @endif
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            مميز 2
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->sp2_count)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->sp2_sales, 2)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format(($scribes_results[0]->sp2_sales/($scribes_results[0]->sp0_sales+$scribes_results[0]->sp1_sales+$scribes_results[0]->sp2_sales))*100, 2)}}
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format($scribes_results[0]->sp2_cost, 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($scribes_results[0]->sp2_sales) - floatval($scribes_results[0]->sp2_cost), 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($scribes_results[0]->sp2_cost) == 0 ? 0 : ((floatval($scribes_results[0]->sp2_sales) - floatval($scribes_results[0]->sp2_cost))/floatval($scribes_results[0]->sp2_cost))*100 , 2)}}
                            </td>
                        @endif
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background: rgb(250,235,215); background: linear-gradient(90deg, rgba(250,235,215,1) 75%, rgba(93,125,26,1) 100%);" class="border p-2 whitespace-nowrap">
                            الإجمالي
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->sp0_count+$scribes_results[0]->sp1_count+$scribes_results[0]->sp2_count)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($scribes_results[0]->sp0_sales+$scribes_results[0]->sp1_sales+$scribes_results[0]->sp2_sales, 2)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format($scribes_results[0]->sp0_cost+$scribes_results[0]->sp1_cost+$scribes_results[0]->sp2_cost, 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($scribes_results[0]->sp0_sales+$scribes_results[0]->sp1_sales+$scribes_results[0]->sp2_sales) - floatval($scribes_results[0]->sp0_cost+$scribes_results[0]->sp1_cost+$scribes_results[0]->sp2_cost), 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($scribes_results[0]->sp0_cost+$scribes_results[0]->sp1_cost+$scribes_results[0]->sp2_cost) == 0 ? 0 : ((floatval($scribes_results[0]->sp0_sales+$scribes_results[0]->sp1_sales+$scribes_results[0]->sp2_sales) - floatval($scribes_results[0]->sp0_cost+$scribes_results[0]->sp1_cost+$scribes_results[0]->sp2_cost))/floatval($scribes_results[0]->sp0_cost+$scribes_results[0]->sp1_cost+$scribes_results[0]->sp2_cost))*100 , 2)}}
                            </td>
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
        @elseif($type == 'sap')
            <div id="tbl2-container" class="overflow-x-auto">
                <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                    <tbody style="direction: ltr" class="text-sm divide-y divide-gray-100">
                    <tr style="font-weight: bold; background-color: #dcdcdc;">
                        <td colspan="3" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                            صافي العمليات
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border-left: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            المبيعات
                        </td>
                        <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{$sales_count}}
                            {{--                        {{$scribes_results[0]->sales_count}}--}}
                        </td>
                        <td style="@if(floatval($sales_sum) < 0) color: red; @else color: green; @endif border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{ number_format($sales_sum, 2)}}
                            {{--                        {{ number_format($scribes_results[0]->sales_sum, 2)}}--}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            عكس المبيعات
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{$reverse_count}}
                            {{--                        {{$scribes_results[0]->reverse_count}}--}}
                        </td>
                        <td style="@if(floatval($reverse_sum) < 0) color: red; @else color: green; @endif border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($reverse_sum, 2) }}
                            {{--                        {{number_format($scribes_results[0]->reverse_sum, 2) }}--}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background: rgb(250,235,215); background: linear-gradient(90deg, rgba(250,235,215,1) 75%, rgba(93,125,26,1) 100%);" class="border p-2 whitespace-nowrap">
                            صافي المبيعات
                        </td>

                        @php $sum = $sales_sum-$reverse_sum; @endphp
                        <td colspan="2" style="border: 2px solid black; text-align: center; @if($sum>0) color: green; @else color: red; @endif" class="border p-2 whitespace-nowrap">
                            {{ number_format(($sum), 2) }}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black;background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            سند قبض
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{$receipt_count}}
                            {{--                        {{$scribes_results[0]->receipt_count}}--}}
                        </td>
                        <td style="@if(floatval($receipt_sum) < 0) color: red; @else color: green; @endif border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($receipt_sum, 2) }}
                            {{--                        {{number_format($scribes_results[0]->receipt_sum, 2) }}--}}
                        </td>
                    </tr>
                    {{--                <tr style="font-weight: bold">--}}
                    {{--                    <td style="border: 2px solid black; background: rgb(250,235,215); background: linear-gradient(90deg, rgba(250,235,215,1) 75%, rgba(93,125,26,1) 100%);" class="border p-2 whitespace-nowrap">--}}
                    {{--                        الإجمالي--}}
                    {{--                    </td>--}}
                    {{--                    <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">--}}
                    {{--                        {{$sales_count+ $reverse_count + $receipt_count}}--}}
                    {{--                        {{$scribes_results[0]->sales_count+ $scribes_results[0]->reverse_count + $scribes_results[0]->receipt_count}}--}}
                    {{--                    </td>--}}
                    {{--                    <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">--}}
                    {{--                        {{number_format($sales_sum + $reverse_sum + $receipt_sum, 2) }}--}}
                    {{--                    </td>--}}
                    {{--                </tr>--}}
                    </tbody>
                </table>
                <table id="tbl3" style="border: 2px solid black; margin-top: 20px;" class="table-container table-auto w-full border text-center">
                    <tbody style="direction: ltr" class="text-sm divide-y divide-gray-100">
                    <tr style="font-weight: bold; background-color: #dcdcdc;">
                        <td colspan="3" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                            الرصيد والتعمير
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border-left: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            الحد الإئتماني
                        </td>
                        <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{ $sap_aging_results->first() ? number_format($sap_aging_results->first()['CreditLine']) : 0}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            شهر 1
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{$sap_aging_results->first() ? number_format($sap_aging_results->first()['0-A1_LC'], 2) : 0}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            شهر 2
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{ $sap_aging_results->first() ? number_format($sap_aging_results->first()['A1-A2_LC'], 2) : 0}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            شهر 3
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{ $sap_aging_results->first() ? number_format($sap_aging_results->first()['A2-A3_LC'], 2) : 0}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            شهر 4
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{ $sap_aging_results->first() ? number_format($sap_aging_results->first()['A3-A4_LC'], 2) : 0}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            شهر 5
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{ $sap_aging_results->first() ? number_format($sap_aging_results->first()['A4-A5_LC'], 2) : 0}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            اقدم
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{ $sap_aging_results->first() ? number_format($sap_aging_results->first()['A5+_LC'], 2) : 0}}
                        </td>
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background: rgb(250,235,215); background: linear-gradient(90deg, rgba(250,235,215,1) 75%, rgba(93,125,26,1) 100%);" class="border p-2 whitespace-nowrap">
                            الرصيد
                        </td>

                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{ $sap_aging_results->first() ? number_format($sap_aging_results->first()['0-A1_LC'] + $sap_aging_results->first()['A1-A2_LC'] + $sap_aging_results->first()['A2-A3_LC'] + $sap_aging_results->first()['A3-A4_LC'] + $sap_aging_results->first()['A4-A5_LC'] + $sap_aging_results->first()['A5+_LC'], 2) : 0 }}
                        </td>
                    </tr>
                    </tbody>
                </table>

                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                    <div class="flex flex-col sm:flex-row gap-4 w-full">
                        <div style="background-color: #f5f5f5; padding-right: 20px; padding-top: 20px; margin-top: 15px;" class="w-full">
                            <label class="block font-bold mb-5">خيارات اخفاء الأعمدة الخاصة</label>
                            <div class="flex flex-row gap-2.5">
                                <div class="flex items-center mb-4 ml-8">
                                    <input id="cost" type="checkbox" value="cost" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">اخفاء</label>
                                </div>
                                {{--                                    <div class="flex items-center mb-4 ml-8">--}}
                                {{--                                        <input id="cost" type="checkbox" value="cost" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                                {{--                                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">التكلفة</label>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="flex items-center mb-4 ml-8">--}}
                                {{--                                        <input id="margin" type="checkbox" value="margin" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                                {{--                                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الهامش</label>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="flex items-center mb-4 ml-8">--}}
                                {{--                                        <input id="margin-percentage" type="checkbox" value="margin-percentage" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                                {{--                                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">النسبة</label>--}}
                                {{--                                    </div>--}}
                            </div>
                        </div>
                    </div>
            </div>
        @endif

                <table id="tbl4" style="border: 2px solid black; margin-top: 20px; color: black" class="table-container table-auto w-full border text-center">
                    <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                    <tr style="font-weight: bold; background-color: #dcdcdc; color: #475569;">
                        <td colspan="7" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                            مبيعات وهامش
                        </td>
                    </tr>
                    <tr style="border: 2px solid black; color: black; background-color: antiquewhite;">
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">النوع</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">عدد</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">مبيعات</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">% مبيعات</div>
                        </th>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">
                                <div class="text-sm">تكلفة بضاعة</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">
                                <div class="text-sm">هامش بالريال</div>
                            </th>
                            <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">
                                <div class="text-sm">هامش %</div>
                            </th>
                        @endif
                    </tr>
                    </thead>
                    <tbody style="direction: ltr" class="text-sm divide-y divide-gray-100">
                    @php $sales0 = $this->sap_sales_results->where('SPL', 'Speciality0')->count() > 0? $this->sap_sales_results->where('SPL', 'Speciality0')->first()['NetSales'] : 0; @endphp
                    @php $sales1 = $this->sap_sales_results->where('SPL', 'Speciality1')->count() > 0? $this->sap_sales_results->where('SPL', 'Speciality1')->first()['NetSales'] : 0; @endphp
                    @php $sales2 = $this->sap_sales_results->where('SPL', 'Speciality2')->count() > 0 ? $this->sap_sales_results->where('SPL', 'Speciality2')->first()['NetSales'] : 0; @endphp
                    @php $sum_sales = floatval($sales0)+floatval($sales1)+floatval($sales2); @endphp
                    <tr style="font-weight: bold">
                        <td style="border-left: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            مميز 0
                        </td>
                        <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($this->sap_sales_results->where('SPL', 'Speciality0')->count() > 0? $this->sap_sales_results->where('SPL', 'Speciality0')->first()['No'] : 0)}}
                        </td>
                        <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($sales0, 2)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($sales_sum != 0 ?($sales0/$sum_sales)*100 : 0, 2)}}
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                @php $cost0 = $this->sap_sales_results->where('SPL', 'Speciality0')->count() > 0? (floatval($this->sap_sales_results->where('SPL', 'Speciality0')->first()['NetSales']) - floatval($this->sap_sales_results->where('SPL', 'Speciality0')->first()['GrssProfit'])) : 0;  @endphp
                                {{number_format($cost0, 2)}}
                            </td>
                            <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format((floatval($sales0) - floatval($cost0)), 2)}}
                            </td>
                            <td style="border-left: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($cost0) == 0 ? 0 : ((floatval($sales0) - floatval($cost0))/floatval($cost0))*100 , 2)}}
                            </td>
                        @endif
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            مميز 1
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($this->sap_sales_results->where('SPL', 'Speciality1')->count() > 0? $this->sap_sales_results->where('SPL', 'Speciality1')->first()['No'] : 0)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($sales1, 2)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($sales_sum != 0 ?($sales1/$sum_sales)*100 : 0, 2)}}
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                @php $cost1 = $this->sap_sales_results->where('SPL', 'Speciality1')->count() > 0? (floatval($this->sap_sales_results->where('SPL', 'Speciality1')->first()['NetSales']) - floatval($this->sap_sales_results->where('SPL', 'Speciality1')->first()['GrssProfit'])) : 0;  @endphp
                                {{number_format($cost1, 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format((floatval($sales1) - floatval($cost1)), 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($cost1) == 0 ? 0 : ((floatval($sales1) - floatval($cost1))/floatval($cost1))*100 , 2)}}
                            </td>
                        @endif
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background-color: #faebd7;" class="border p-2 whitespace-nowrap">
                            مميز 2
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($this->sap_sales_results->where('SPL', 'Speciality2')->count() > 0 ? $this->sap_sales_results->where('SPL', 'Speciality2')->first()['No'] : 0)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($sales2, 2)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format($sales_sum != 0 ?($sales2/$sum_sales)*100 : 0, 2)}}
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                @php $cost2 = $this->sap_sales_results->where('SPL', 'Speciality2')->count() > 0 ? (floatval($this->sap_sales_results->where('SPL', 'Speciality2')->first()['NetSales']) - floatval($this->sap_sales_results->where('SPL', 'Speciality2')->first()['GrssProfit'])) : 0;  @endphp
                                {{number_format($cost2, 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format((floatval($sales2) - floatval($cost2)), 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($cost2) == 0 ? 0 : ((floatval($sales2) - floatval($cost2))/floatval($cost2))*100 , 2)}}
                            </td>
                        @endif
                    </tr>
                    <tr style="font-weight: bold">
                        <td style="border: 2px solid black; background: rgb(250,235,215); background: linear-gradient(90deg, rgba(250,235,215,1) 75%, rgba(93,125,26,1) 100%);" class="border p-2 whitespace-nowrap">
                            الإجمالي
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format(floatval($this->sap_sales_results->where('SPL', 'Speciality0')->count() > 0 ? $this->sap_sales_results->where('SPL', 'Speciality0')->first()['No'] : 0)+floatval($this->sap_sales_results->where('SPL', 'Speciality1')->count() > 0 ? $this->sap_sales_results->where('SPL', 'Speciality1')->first()['No'] : 0)+floatval($this->sap_sales_results->where('SPL', 'Speciality2')->count() > 0 ? $this->sap_sales_results->where('SPL', 'Speciality2')->first()['No'] : 0))}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">
                            {{number_format(floatval($sales0)+floatval($sales1)+floatval($sales2), 2)}}
                        </td>
                        <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap">

                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format(floatval($cost0)+floatval($cost1)+floatval($cost2), 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format((floatval($sales0)+floatval($sales1)+floatval($sales2)) - (floatval($cost0)+floatval($cost1)+floatval($cost2)), 2)}}
                            </td>
                            <td style="border: 2px solid black; text-align: right" class="border p-2 whitespace-nowrap cost">
                                {{number_format((floatval($cost0)+floatval($cost1)+floatval($cost2)) == 0 ? 0 : (((floatval($sales0)+floatval($sales1)+floatval($sales2)) - (floatval($cost0)+floatval($cost1)+floatval($cost2)))/(floatval($cost0)+floatval($cost1)+floatval($cost2)))*100 , 2)}}
                            </td>
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script>

        Livewire.on('show-container', () => {
            $("#gen-report").html('<b>إنشاء تقرير</b>');

        });

        Livewire.on('finished', () => {
            swal.close();
        });

        $(document).ready(function () {

            $('#customer_name').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#gen-report').on('click', function () {


                var customer_id = $('#customer_name').select2("val");
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                $("#cost").prop('checked', false);


                if(start_date == '' || end_date == '' || customer_id == "") {
                    Swal.fire({
                        title: "حدث خطأ",
                        text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                        icon: "error",
                        confirmButtonText: "موافق",
                    });
                    $("#gen-report").html('<b>إنشاء تقرير</b>');
                }
                else {
                    $("#gen-report").html('<b>الرجاء الإنتظار..</b>');

                    Swal.fire({
                        title: 'الرجاء الإنتظار',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading()
                        },
                    });

                    Livewire.emit('create-report', customer_id, start_date, end_date);
                }


                // if(dept_id == null || cat_type == null || sp_type == null || vendor_type == null) {
                //     Swal.fire({
                //         title: "حدث خطأ",
                //         text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                //         icon: "error",
                //         confirmButtonText: "موافق",
                //     });
                // }
                // else {
                //     $("#gen-report").html('<b>الرجاء الإنتظار..</b>');
                //
                //     Swal.fire({
                //         title: 'الرجاء الإنتظار',
                //         allowOutsideClick: false,
                //         showCancelButton: false,
                //         showConfirmButton: false,
                //         willOpen: () => {
                //             Swal.showLoading()
                //         },
                //     });
                //
                //     Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                // }
            });

        });

        function hideColumn(type) {

            if (type.checked) {
                $('.cost').addClass('hide');
                // $('.' + type.value).removeClass('hide');
                // console.log(type.val() + ' not ticked');
            }
            else {
                console.log(type.value + ' ticked');
                // $('.' + type.value).addClass('hide');

                $('.cost').removeClass('hide');
            }
        }

    </script>
@stop
@section('css-scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }
        .select2-container .select2-selection--single {
            height: 38px !important;
            width: 100%;
            padding-right: 2.5rem;
            padding-top: 0.2rem;
        }
        .select2-selection__arrow {
            height: 34px !important;
        }

        .select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow {
            /* left: 1px; */
            right: 9px;
        }

        .select-font-size {
            font-size: 0.875rem; /* 14px */
            line-height: 1.25rem; /* 20px */
        }

        .hide {
            display: none;
        }

        #report-logo {
            display: none;
        }
    </style>
@stop
