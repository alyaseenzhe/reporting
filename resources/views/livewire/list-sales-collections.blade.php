@section('title')
    8- التحصيل والمبيعات
@stop
<div>
    {{-- Stop trying to control. --}}
{{--    <div--}}
{{--        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">--}}
{{--        <div class="w-full">التحصيل والمبيعات</div>--}}
{{--    </div>--}}
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-row gap-4">
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
            </div>
        </div>
    </div>
    <div id="report-btn" wire:loading.remove wire:target="generateReport" class="hide printable">
{{-- filtering --}}
        <div style="margin-bottom: 30px; padding: 20px; background-color: #f1f1f1;" class="w-full flex flex-row">
            <label class="block font-bold mb-2 w-full">نوع التقرير
                <select id="report_type" name="report_type"
                        class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <option value="show">مختصر</option>
                    <option value="hide">تفصيلي</option>
                </select>
            </label>
        </div>
        {{-- table 2 (details) --}}
        <div id="tbl2-container" class="overflow-x-auto">
            <table wire:key="datatbl-{{ now() }}" id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">رقم العميل</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">اسم العميل</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الفرع</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">اسم المهندس</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مبلغ التحصيل</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">المبيعات النقدية</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">المبيعات الآجلة</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مديونية العميل</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">المستحق</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الحالة</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">

                @if ($this->start_date >= '2011-07-01' && $this->end_date <= '2023-12-31')
                    @foreach($final_results as $record)
                    @if($record['collected'] == 0 && $record['cash'] == 0 && $record['postponed_sales'] == 0 && floatval($record['postponed_amount']) == 0 && floatval($record['postponed_due_amount']) == 0)
                    @else
                        @if(\Illuminate\Support\Facades\Auth::user()->role == 'a' || \Illuminate\Support\Facades\Auth::user()->user_group->read_type == '0')
                            @if(strlen($record['customer_code'] ) == 7 || strlen($record['customer_code'] ) == 9)
                                <tr>
                                    <td class="border p-2 whitespace-nowrap">
                                        {{$record['customer_code']}}
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        {{$record['customer_name']}}
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        @if((substr($record['customer_code'], 0, 2) == "01" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-01") && strlen($record['customer_code'] ) == 9)
                                            الاحساء
                                        @elseif((substr($record['customer_code'], 0, 2) == "02" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-02") && strlen($record['customer_code'] ) == 9)
                                            جدة
                                        @elseif((substr($record['customer_code'], 0, 2) == "03" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-03") && strlen($record['customer_code'] ) == 9)
                                            الرياض
                                        @elseif((substr($record['customer_code'], 0, 2) == "04" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-04") && strlen($record['customer_code'] ) == 9)
                                            وادي الدواسر
                                        @elseif((substr($record['customer_code'], 0, 2) == "05" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-05") && strlen($record['customer_code'] ) == 9)
                                            الجوف
                                        @elseif((substr($record['customer_code'], 0, 2) == "06" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-06") && strlen($record['customer_code'] ) == 9)
                                            الدمام
                                        @elseif((substr($record['customer_code'], 0, 2) == "07" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-07") && strlen($record['customer_code'] ) == 9)
                                            الخرج
                                        @elseif((substr($record['customer_code'], 0, 2) == "08" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-08") && strlen($record['customer_code'] ) == 9)
                                            نجران
                                        @elseif((substr($record['customer_code'], 0, 2) == "09" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-09") && strlen($record['customer_code'] ) == 9)
                                            حائل
                                        @elseif((substr($record['customer_code'], 0, 2) == "10" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-10") && strlen($record['customer_code'] ) == 9)
                                            تبوك
                                        @elseif((substr($record['customer_code'], 0, 2) == "11" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-11") && strlen($record['customer_code'] ) == 9)
                                            القصيم
                                        @elseif((substr($record['customer_code'], 0, 2) == "12" && strlen($record['customer_code'] ) == 7) || (substr($record['customer_code'], 0, 4) == "1-12" && strlen($record['customer_code'] ) == 9))
                                            ساجر
                                        @endif
                                    </td>
                                    <td class="border p-2">
                                        {{$record['emp_name']}}
                                    </td>
                                    <td @if($record['collected'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                        {{number_format($record['collected'], 2)}}
                                    </td>
                                    <td @if($record['cash'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                        {{number_format($record['cash'], 2)}}
                                    </td>
                                    <td @if($record['postponed_sales'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                        {{number_format($record['postponed_sales'], 2)}}
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        {{number_format($record['postponed_amount'], 2)}}
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        {{number_format($record['postponed_due_amount'], 2)}}
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        {{ number_format($record['collected'], 2) == '0.00' && number_format($record['cash'], 2) == '0.00' && number_format($record['postponed_sales'], 2) == '0.00' ? "hide" : "show" }}
                                    </td>
                                </tr>
                            @endif
                        @elseif(($record && /*$record['emp_code'] == \Illuminate\Support\Facades\Auth::user()->emp_code &&*/ \Illuminate\Support\Facades\Auth::user()->user_group->read_type == '1'))
                            @if(strlen($record['customer_code'] ) == 7 || strlen($record['customer_code'] ) == 9)
                                <tr>
                                    <td class="border p-2 whitespace-nowrap">
                                        {{$record['customer_code']}}
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        {{$record['customer_name']}}
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        @if(substr($record['customer_code'], 0, 2) == "01")
                                            الاحساء
                                        @elseif(substr($record['customer_code'], 0, 2) == "02")
                                            جدة
                                        @elseif(substr($record['customer_code'], 0, 2) == "03")
                                            الرياض
                                        @elseif(substr($record['customer_code'], 0, 2) == "04")
                                            وادي الدواسر
                                        @elseif(substr($record['customer_code'], 0, 2) == "05")
                                            الجوف
                                        @elseif(substr($record['customer_code'], 0, 2) == "06")
                                            الدمام
                                        @elseif(substr($record['customer_code'], 0, 2) == "07")
                                            الخرج
                                        @elseif(substr($record['customer_code'], 0, 2) == "08")
                                            نجران
                                        @elseif(substr($record['customer_code'], 0, 2) == "09")
                                            حائل
                                        @elseif(substr($record['customer_code'], 0, 2) == "10")
                                            تبوك
                                        @elseif(substr($record['customer_code'], 0, 2) == "11")
                                            القصيم
                                        @elseif(substr($record['customer_code'], 0, 2) == "12")
                                            ساجر
                                        @endif
                                    </td>
                                    <td class="border p-2">
                                        {{$record['emp_name']}}
                                    </td>
                                    <td @if($record['collected'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                        {{number_format($record['collected'], 2)}}
                                    </td>
                                    <td @if($record['cash'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                        {{number_format($record['cash'], 2)}}
                                    </td>
                                    <td @if($record['postponed_sales'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                        {{number_format($record['postponed_sales'], 2)}}
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        {{number_format($record['postponed_amount'], 2)}}
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        {{number_format($record['postponed_due_amount'], 2)}}
                                    </td>
                                    <td class="border p-2 whitespace-nowrap">
                                        {{ number_format($record['collected'], 2) == '0.00' && number_format($record['cash'], 2) == '0.00' && number_format($record['postponed_sales'], 2) == '0.00' ? "hide" : "show" }}
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @endif
                @endforeach
                @elseif ($this->start_date > '2023-12-31' && $this->end_date > '2023-12-31')
                    @foreach($sap_results as $record)
                        <tr>
                            <td class="border p-2 whitespace-nowrap">
                                {{$record['BusinessPartnerCode2']}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{$record['BusinessPartnerName2']}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{$record['BranchName']}}
                            </td>
                            <td class="border p-2">
                                {{$record['SlpName2']}}
                            </td>
                            <td @if($record['CollectTotalNEW'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                {{number_format($record['CollectTotalNEW'], 2)}}
                            </td>
                            <td @if($record['CashTotalNEW'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                {{number_format($record['CashTotalNEW'], 2)}}
                            </td>
                            <td @if($record['CreditTotalNEW'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                {{number_format($record['CreditTotalNEW'], 2)}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{number_format($record['Balance Due'], 2)}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{number_format($record['121+'], 2)}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{ number_format($record['CollectTotalNEW'], 2) == '0.00' && number_format($record['CashTotalNEW'], 2) == '0.00' && number_format($record['CreditTotalNEW'], 2) == '0.00' ? "hide" : "show" }}
                            </td>
                        </tr>
                    @endforeach
                @elseif ($this->start_date >= '2011-07-01' && $this->end_date > '2023-12-31')
                    @foreach($sap_results as $record)
                        <tr>
                            <td class="border p-2 whitespace-nowrap">
                                {{$record['customer_code']}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{$record['customer_name']}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{$record['BranchName']}}
                            </td>
                            <td class="border p-2">
                                {{$record['emp_name']}}
                            </td>
                            <td @if($record['collected'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                {{number_format($record['collected'], 2)}}
                            </td>
                            <td @if($record['cash'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                {{number_format($record['cash'], 2)}}
                            </td>
                            <td @if($record['postponed_sales'] > 0) style="background-color: #fff8dc;" @endif class="border p-2 whitespace-nowrap">
                                {{number_format($record['postponed_sales'], 2)}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{number_format($record['Balance Due'], 2)}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{number_format($record['121+'], 2)}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{ number_format($record['collected'], 2) == '0.00' && number_format($record['cash'], 2) == '0.00' && number_format($record['postponed_sales'], 2) == '0.00' ? "hide" : "show" }}
                            </td>
                        </tr>
                    @endforeach
                @endif

                </tbody>
            </table>
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

            /*
            if ( $.fn.dataTable.isDataTable('#tbl2') ) {
                $('#tbl2').DataTable().destroy();
                $('#tbl2').empty();
            }*/

            div = document.getElementById("report-btn");
            div_title = document.getElementById("report_title");
            area = document.getElementById("area_id");
            start_date = document.getElementById("start_date");
            end_date = document.getElementById("end_date");

            div.classList.remove("hide");
            // div_title.innerHTML = "تقرير " + area.options[area.selectedIndex].text + "(" + date.value + ")"

            var collapsedGroups = {};

            if ($.fn.DataTable.isDataTable('#tbl2')) {
                return $('#tbl2').DataTable(); // Use the existing instance
            } else {
                return $('#tbl2').DataTable(
                    {
                        dom: 'lBfrtip',
                        "lengthMenu": [ 100, 200, 300, 400 ],
                        "pageLength": 300,
                        "language": {
                            "sEmptyTable": "ليست هناك بيانات متاحة في الجدول",
                            "sLoadingRecords": "جارٍ التحميل...",
                            "sProcessing": "جارٍ التحميل...",
                            "sLengthMenu": "أظهر _MENU_ مدخلات",
                            "sZeroRecords": "لم يعثر على أية سجلات",
                            "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                            "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                            "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                            "sInfoPostFix": "",
                            "sSearch": "ابحث:",
                            "sUrl": "",
                            "oPaginate": {
                                "sFirst": "الأول",
                                "sPrevious": "السابق",
                                "sNext": "التالي",
                                "sLast": "الأخير"
                            },
                            "oAria": {
                                "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
                                "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
                            }
                        },
                        buttons: [
                            {extend: 'copy', text: 'نسخ'},
                            {extend: 'excel', text: 'تصدير إلى اكسل'},
                        ],
                        // start of row group section
                        // paging: false,
                        order: [
                            [3, 'asc']
                        ],
                        columnDefs: [ { orderable: false, targets: [0, 1] } , { target: 9, visible: false }],
                        rowGroup: {
                            startRender: null,
                            endRender: function (rows, group) {


                                var customer_name = rows
                                    .data()
                                    .pluck(3)[0];

                                var collected_amount = rows
                                    .data()
                                    .pluck(4)
                                    .reduce(function (a, b) {
                                        // console.log(b);
                                        return a + parseFloat(b.replace(/\,/g,'')) * 1;
                                    }, 0);

                                var cash_sales = rows
                                    .data()
                                    .pluck(5)
                                    .reduce(function (a, b) {
                                        // console.log(b);
                                        return a + parseFloat(b.replace(/\,/g,'')) * 1;
                                    }, 0);

                                var postponed_sales = rows
                                    .data()
                                    .pluck(6)
                                    .reduce(function (a, b) {
                                        // console.log(b);
                                        return a + parseFloat(b.replace(/\,/g,'')) * 1;
                                    }, 0);


                                var postponed_total = rows
                                    .data()
                                    .pluck(7)
                                    .reduce(function (a, b) {
                                        // console.log(b);
                                        return a + parseFloat(b.replace(/\,/g,'')) * 1;
                                    }, 0);

                                var dueAmount = rows
                                    .data()
                                    .pluck(8)
                                    .reduce(function (a, b) {
                                        // console.log(b);
                                        return a + parseFloat(b.replace(/\,/g,'')) * 1;
                                    }, 0);

                                // dueAmount = $.fn.dataTable.render.number(',', '.', 0, '$').display( dueAmount );

                                // var ageAvg = rows
                                //     .data()
                                //     .pluck(3)
                                //     .reduce( function (a, b) {
                                //         return a + b*1;
                                //     }, 0) / rows.count();

                                return $('<tr style="background-color: #f2f0f0; font-weight: bold; color: #233881;" />')
                                    .append('<td style="border: 1px solid;" colspan="4">المجموع لـ '+ group + '</td>')
                                    .append('<td style="border: 1px solid;" >' + collected_amount.toLocaleString("en-US") + '</td>')
                                    .append('<td style="border: 1px solid;" >' + cash_sales.toLocaleString("en-US") + '</td>')
                                    .append('<td style="border: 1px solid;" >' + postponed_sales.toLocaleString("en-US") + '</td>')
                                    .append('<td style="border: 1px solid;" >' + postponed_total.toLocaleString("en-US") + '</td>')
                                    .append('<td style="border: 1px solid;" >' + dueAmount.toLocaleString("en-US") + '</td>');
                            },
                            dataSrc: [2, 3]
                        }
                        //         rowGroup: {
                        //             // Uses the 'row group' plugin
                        //             dataSrc: 2,
                        //             startRender: function(rows, group) {
                        //                 var collapsed = !!collapsedGroups[group];
                        //
                        //                 rows.nodes().each(function (r) {
                        //                     r.style.display = 'none';
                        //                     if (collapsed) {
                        //                         r.style.display = '';
                        //                     }});
                        //
                        //                 // Add category name to the <tr>. NOTE: Hardcoded colspan
                        //                 return $('<tr/>')
                        //                     .append('<td colspan="8">' + group + ' (' + rows.count() + ')</td>')
                        //                     .attr('data-name', group)
                        //                     .toggleClass('collapsed', collapsed);
                        //             }
                        //         }
                        //     }
                        // );
                        //
                        // // $('#voucherTable tbody').on('click', 'tr.group-start', function() {
                        // //     alert('test');
                        // //     var name = $(this).data('name');
                        // //     collapsedGroups[name] = !collapsedGroups[name];
                        // //     table.draw(false);
                        // // });
                        //
                        // $('#voucherTable tbody').on('click', function() {
                        //     alert('test');
                        //     console.log(collapsedGroups);
                        //     var name = $(this).data('name');
                        //     collapsedGroups[name] = !collapsedGroups[name];
                        //     table.draw(false);
                        // });
                    });
            }






            // filtering
            const report_typeEl = document.querySelector('#report_type');
            report_typeEl.selectedIndex = 0;


            if (report_typeEl.value == "show") {
                // Custom range filtering function
                DataTable.ext.search.push(function (settings, data, dataIndex) {
                    let reportType = data[9]; // use data for the status column
                    // console.log(reportType);
                    // console.log(report_typeEl.value);
                    if (report_typeEl.value == reportType) {
                        return true
                    }

                    return false;
                });

            }
            else {
               DataTable.ext.search.pop();
            }

            const table = new DataTable('#tbl2');
            table.draw();

// Changes to the inputs will trigger a redraw to update the table
            report_typeEl.addEventListener('change', function () {
                if (report_typeEl.value == "show") {
                    // Custom range filtering function
                    DataTable.ext.search.push(function (settings, data, dataIndex) {
                        let reportType = data[9]; // use data for the status column

                        if (report_typeEl.value == reportType) {
                            return true
                        }

                        return false;
                    });
                }
                else {
                    DataTable.ext.search.pop();
                }
                table.draw();
            });
        });



    </script>
@stop
@section('css-scripts')
    <style>
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
