<div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">الفواتير المُعلقة</div>
    </div>
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
                    <label class="block font-bold mb-2">الشهر
                        <small>(آخر يوم في الشهر)</small>
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="date" type="month" name="selected_date" wire:model="selected_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('selected_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
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

    <div wire:loading.remove wire:target="generateReport" class="overflow-x-auto w-full">

        @if(count($aging_records) > 0)
            <div class="mb-5 p-2">
                <div class="flex flex-col sm:flex-row gap-4 w-full">
                    <div style="background-color: #f5f5f5; padding-right: 20px; padding-top: 20px" class="w-full">
                        <label class="block font-bold mb-5">خيارات</label>
                        <div class="flex flex-row gap-2.5">
                            <div class="flex items-center mb-4 ml-8">
                                <input id="record" type="checkbox" value="record" onchange="hideRows(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">اخفاء التفاصيل</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table id="voucherTable" class="table-auto w-full border text-center">
                <thead class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr>
                    <th class="border p-2">
                        <div class="text-center text-sm">#</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">اسم الموظف</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">#</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">اسم العميل</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">رقم الفاتورة</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">تاريخ الفاتورة</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">متبقي من الفاتورة</div>
                    </th>
                    {{--                <th class="border p-2">--}}
                    {{--                    <div class="text-center text-sm">المبلغ المدفوع</div>--}}
                    {{--                </th>--}}
                    {{--                <th class="border p-2">--}}
                    {{--                    <div class="text-center text-sm">المبلغ المستحق</div>--}}
                    {{--                </th>--}}

                    <th class="border p-2">
                        <div class="text-center text-sm">الايام</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php
                    $customer_id = "*";
                    $customer_name = "*";
                    $emp_id = "*";
                    $emp_name = "*";
                    $customer_total = 0;
                    $customer_total_120 = 0;
                    ?>
                @foreach($aging_records as $record)
{{--                    @if(\Illuminate\Support\Facades\Auth::user()->role == 'a' || \Illuminate\Support\Facades\Auth::user()->user_group->read_type == '0')--}}
                        {{--                    @if(number_format($record["Debit (LC)"], 2) != '0.00')--}}
                        @if($loop->first)
                                <?php $customer_id = $record["Business Partner Code"]; ?>
                                <?php $customer_name = $record["Business Partner Name"]; ?>
                                <?php $emp_id = $record["Memo"]; ?>
                                <?php $emp_name = $record["SlpName"]; ?>
                        @endif
                        @if($record["Business Partner Code"] != $customer_id)

                            <tr style="background-color: #f2f0f0; font-weight: bold; color: #233881; border: dotted 2px;">
                                <td style="border: 1px dotted;" >{{$emp_id}}</td>
                                <td style="border: 1px dotted;" >{{$emp_name}}</td>
                                {{--            <td style="border: 1px dotted;" colspan="6">المجموع لـ--}}
                                <td style="border: 1px dotted;">{{ $customer_id }}</td>
                                <td style="border: 1px dotted;">{{ $customer_name }}</td>
                                <td style="border: 1px dotted;" colspan="2">
                                    <span>مستحق: </span>
                                    <span style="@if($customer_total_120 > 0) color:red; @else color:green @endif">{{number_format($customer_total_120, 2)}}</span>
                                    <span style="@if($customer_total_120 > 0) color:red; @else color:green @endif">({{number_format((floatval($customer_total_120)/floatval($customer_total))*100, 2)}}%)</span>
                                </td>
                                <td style="border: 1px dotted;" >{{number_format($customer_total, 2)}}</td>
                                <td style="border: 1px dotted;"></td>
                            </tr>
                                <?php $customer_id = $record["Business Partner Code"]; ?>
                                <?php $customer_name = $record["Business Partner Name"]; ?>
                                <?php $emp_id = $record["Memo"]; ?>
                                <?php $emp_name = $record["SlpName"]; ?>

                            @php $customer_total = 0; $customer_total_120 = 0; @endphp
                        @endif
                        {{--                        @if (!$loop->first)--}}
                        {{--                            --}}
                        {{--                            @php $customer_id = $record["Business Partner Code"]; @endphp--}}
                        {{--                        @endif--}}

                        <tr class="customer-record">
                            <td class="border p-2 whitespace-nowrap">
                                {{$record["Memo"]}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{$record["SlpName"]}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{$record["Business Partner Code"]}}
                            </td>
                            <td class="border p-2">
                                {{$record["Business Partner Name"]}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{$record["Document Number"]}}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($record["Posting Date"])->format('Y-m-d') }}
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                {{number_format($record["Debit (LC)"], 2)}}
                                @php $customer_total += $record["Debit (LC)"]; @endphp
                                @if(floatval(\Carbon\Carbon::parse($record["Posting Date"])->diffInDays(\Carbon\Carbon::parse($last_date))) >= 120)
                                    @php $customer_total_120 += $record["Debit (LC)"]; @endphp
                                @endif
                            </td>
                            {{--                        <td class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format(abs($record->paid), 2)}}--}}
                            {{--                        </td>--}}
                            {{--                        <td class="border p-2 whitespace-nowrap">--}}
                            {{--                            {{number_format($record->DueAmount, 2)}}--}}
                            {{--                        </td>--}}
                            <td style="@if(\Carbon\Carbon::parse($record["Posting Date"])->diffInDays(\Carbon\Carbon::parse($last_date)) > 120) color:red; @endif" class="border p-2 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($record["Posting Date"])->diffInDays(\Carbon\Carbon::parse($last_date)) }}
                            </td>
                        </tr>
                        {{--                    @endif--}}
                        @if($loop->last)
                            <tr style="background-color: #f2f0f0; font-weight: bold; color: #233881; border: dotted 2px;">
                                <td style="border: 1px dotted;" >{{$emp_id}}</td>
                                <td style="border: 1px dotted;" >{{$emp_name}}</td>
                                {{--            <td style="border: 1px dotted;" colspan="6">المجموع لـ--}}
                                <td style="border: 1px dotted;">{{ $customer_id }}</td>
                                <td style="border: 1px dotted;">{{ $customer_name }}</td>
                                <td style="border: 1px dotted;" colspan="2">
                                    <span>مستحق: </span>
                                    <span style="@if($customer_total_120 > 0) color:red; @else color:green @endif">{{number_format($customer_total_120, 2)}}</span>
                                    <span style="@if($customer_total_120 > 0) color:red; @else color:green @endif">({{number_format((floatval($customer_total_120)/floatval($customer_total))*100, 2)}}%)</span>
                                </td>
                                <td style="border: 1px dotted;" >{{number_format($customer_total, 2)}}</td>
                                <td style="border: 1px dotted;"></td>
                            </tr>
                                <?php $customer_id = $record["Business Partner Code"]; ?>
                                <?php $customer_name = $record["Business Partner Name"]; ?>
                                <?php $emp_id = $record["Memo"]; ?>
                                <?php $emp_name = $record["SlpName"]; ?>

                            @php $customer_total = 0; $customer_total_120 = 0; @endphp
                        @endif
{{--                    @endif--}}
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>


@section('css-scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css" />

    <style>
        .hide {
            display: none;
        }
    </style>

@stop

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    {{--    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>--}}
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>--}}

    <script>
        $(document).ready( function () {

            flag = true;
//             Livewire.on('show-data', () => {
//                 var collapsedGroups = {};
//
//                 // if (flag == true) {
//                 //     alert('kkkkkXXXX');
//                 //     $('#voucherTable').DataTable(
//                 //         {
//                 //             dom: 'lBfrtip',
//                 //             "language": {
//                 //                 "sEmptyTable": "ليست هناك بيانات متاحة في الجدول",
//                 //                 "sLoadingRecords": "جارٍ التحميل...",
//                 //                 "sProcessing": "جارٍ التحميل...",
//                 //                 "sLengthMenu": "أظهر _MENU_ مدخلات",
//                 //                 "sZeroRecords": "لم يعثر على أية سجلات",
//                 //                 "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
//                 //                 "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
//                 //                 "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
//                 //                 "sInfoPostFix": "",
//                 //                 "sSearch": "ابحث:",
//                 //                 "sUrl": "",
//                 //                 "oPaginate": {
//                 //                     "sFirst": "الأول",
//                 //                     "sPrevious": "السابق",
//                 //                     "sNext": "التالي",
//                 //                     "sLast": "الأخير"
//                 //                 },
//                 //                 "oAria": {
//                 //                     "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
//                 //                     "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
//                 //                 }
//                 //             },
//                 //             buttons: [
//                 //                 {extend: 'copy', text: 'نسخ'},
//                 //                 {extend: 'excel', text: 'تصدير إلى اكسل'},
//                 //             ],
//                 //             // start of row group section
//                 //             // paging: false,
//                 //             order: [
//                 //                 [2, 'asc']
//                 //             ],
//                 //             columnDefs: [ { orderable: false, targets: [0, 1] }],
//                 //             rowGroup: {
//                 //                 startRender: null,
//                 //                 endRender: function (rows, group) {
//                 //
//                 //
//                 //                     var customer_name = rows
//                 //                         .data()
//                 //                         .pluck(3)[0];
//                 //
//                 //
//                 //                     var voucherTotal = rows
//                 //                         .data()
//                 //                         .pluck(6)
//                 //                         .reduce(function (a, b) {
//                 //                             console.log(b);
//                 //                             return a + parseFloat(b.replace(/\,/g,'')) * 1;
//                 //                         }, 0);
//                 //
//                 //                     // var paidAmount = rows
//                 //                     //     .data()
//                 //                     //     .pluck(7)
//                 //                     //     .reduce(function (a, b) {
//                 //                     //         console.log(b);
//                 //                     //         return a + parseFloat(b.replace(/\,/g,'')) * 1;
//                 //                     //     }, 0);
//                 //                     //
//                 //                     // var dueAmount = rows
//                 //                     //     .data()
//                 //                     //     .pluck(8)
//                 //                     //     .reduce(function (a, b) {
//                 //                     //         console.log(b);
//                 //                     //         return a + parseFloat(b.replace(/\,/g,'')) * 1;
//                 //                     //     }, 0);
//                 //
//                 //                     // dueAmount = $.fn.dataTable.render.number(',', '.', 0, '$').display( dueAmount );
//                 //
//                 //                     // var ageAvg = rows
//                 //                     //     .data()
//                 //                     //     .pluck(3)
//                 //                     //     .reduce( function (a, b) {
//                 //                     //         return a + b*1;
//                 //                     //     }, 0) / rows.count();
//                 //
//                 //                     return $('<tr style="background-color: #f2f0f0; font-weight: bold; color: #233881;" />')
//                 //                         .append('<td style="border: 1px solid;" colspan="6">المجموع لـ '+ customer_name + "(" + group + ")" + '</td>')
//                 //                         .append('<td style="border: 1px solid;" >' + voucherTotal.toLocaleString("en-US") + '</td>')
//                 //                         // .append('<td style="border: 1px solid;" >' + paidAmount.toLocaleString("en-US") + '</td>')
//                 //                         // .append('<td style="border: 1px solid;" >' + dueAmount.toLocaleString("en-US") + '</td>')
//                 //                         .append('<td style="border: 1px solid;" />');
//                 //                 },
//                 //                 dataSrc: 2
//                 //             }
//                 //             //         rowGroup: {
//                 //             //             // Uses the 'row group' plugin
//                 //             //             dataSrc: 2,
//                 //             //             startRender: function(rows, group) {
//                 //             //                 var collapsed = !!collapsedGroups[group];
//                 //             //
//                 //             //                 rows.nodes().each(function (r) {
//                 //             //                     r.style.display = 'none';
//                 //             //                     if (collapsed) {
//                 //             //                         r.style.display = '';
//                 //             //                     }});
//                 //             //
//                 //             //                 // Add category name to the <tr>. NOTE: Hardcoded colspan
//                 //             //                 return $('<tr/>')
//                 //             //                     .append('<td colspan="8">' + group + ' (' + rows.count() + ')</td>')
//                 //             //                     .attr('data-name', group)
//                 //             //                     .toggleClass('collapsed', collapsed);
//                 //             //             }
//                 //             //         }
//                 //             //     }
//                 //             // );
//                 //             //
//                 //             // // $('#voucherTable tbody').on('click', 'tr.group-start', function() {
//                 //             // //     alert('test');
//                 //             // //     var name = $(this).data('name');
//                 //             // //     collapsedGroups[name] = !collapsedGroups[name];
//                 //             // //     table.draw(false);
//                 //             // // });
//                 //             //
//                 //             // $('#voucherTable tbody').on('click', function() {
//                 //             //     alert('test');
//                 //             //     console.log(collapsedGroups);
//                 //             //     var name = $(this).data('name');
//                 //             //     collapsedGroups[name] = !collapsedGroups[name];
//                 //             //     table.draw(false);
//                 //             // });
//                 //         });
//                 //
//                 //     flag = false;
//                 // }
//
//
//
// //                 // filtering
// //                 const minEl = document.querySelector('#min');
// //
// // // Custom range filtering function
// //                 DataTable.ext.search.push(function (settings, data, dataIndex) {
// //                     let min = parseInt(minEl.value, 10);
// //                     let days = parseFloat(data[9]) || 0; // use data for the age column
// //                     console.log(days);
// //
// //                     if (isNaN(min) || min <= days) {
// //                         return true;
// //                     }
// //
// //                     return false;
// //                 });
//
//
//                 const table = new DataTable('#voucherTable');
//                 table.draw();
//
// // Changes to the inputs will trigger a redraw to update the table
// //                 minEl.addEventListener('input', function () {
// //                     table.draw();
// //                 });
//
//             })

            // const table = new DataTable('#voucherTable');
            // table.draw();

            Livewire.on('show-data', () => {
                $('#record').prop('checked', false);
            });

        });

        function hideRows(type) {

            if (type.checked) {
                $('.customer-record').addClass('hide');
            }
            else {
                $('.customer-record').removeClass('hide');
            }
        }
    </script>
@stop
