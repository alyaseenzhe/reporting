<div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">الفواتير المُستحقة</div>
    </div>
    <div style="border-right: 3px solid #23547c; background-color: #dcefff;" class="p-5 mb-5 leading-8 text-justify">
        <p class="mb-3 font-bold">
            ملاحظة:
        </p>
        <p style="color: #23547c;" class="font-bold">
            الفواتير الموجودة في الجدول هي الفواتير المُستحقة منذ 240 يوم
        </p>
    </div>
    <div wire:loading.remove wire:target="fetch_data" class="overflow-x-auto w-full">
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
                    <div class="text-center text-sm">مبلغ الفاتورة</div>
                </th>
                <th class="border p-2">
                    <div class="text-center text-sm">المبلغ المدفوع</div>
                </th>
                <th class="border p-2">
                    <div class="text-center text-sm">المبلغ المستحق</div>
                </th>
                <th class="border p-2">
                    <div class="text-center text-sm">الايام</div>
                </th>
            </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
            @foreach($records as $record)
                <tr>
                    <td class="border p-2 whitespace-nowrap">
                        {{$record->employee_code}}
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{$record->employee_name}}
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{$record->customer_code}}
                    </td>
                    <td class="border p-2">
                        {{$record->customer_name}}
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{$record->voucherno}}
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($record->voucherdate)->format('Y-m-d') }}
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{number_format($record->Total, 2)}}
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{number_format(abs($record->paid), 2)}}
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{number_format($record->DueAmount, 2)}}
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{$record->days}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
{{--    {{ $records->links() }}--}}

    <div  wire:loading wire:target="fetch_data" class="w-full">
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

@section('css-scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css" />

@stop

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

    <script>
        $(document).ready( function () {

            var collapsedGroups = {};

            $('#voucherTable').DataTable(
                {
                    dom: 'lBfrtip',
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
                        [2, 'asc']
                    ],
                    columnDefs: [ { orderable: false, targets: [0, 1] }],
                    rowGroup: {
                        startRender: null,
                        endRender: function (rows, group) {


                            var customer_name = rows
                                .data()
                                .pluck(3)[0];


                            var voucherTotal = rows
                                .data()
                                .pluck(6)
                                .reduce(function (a, b) {
                                    console.log(b);
                                    return a + parseFloat(b.replace(/\,/g,'')) * 1;
                                }, 0);

                            var paidAmount = rows
                                .data()
                                .pluck(7)
                                .reduce(function (a, b) {
                                    console.log(b);
                                    return a + parseFloat(b.replace(/\,/g,'')) * 1;
                                }, 0);

                            var dueAmount = rows
                                .data()
                                .pluck(8)
                                .reduce(function (a, b) {
                                    console.log(b);
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
                                .append('<td style="border: 1px solid;" colspan="6">المجموع لـ '+ customer_name + "(" + group + ")" + '</td>')
                                .append('<td style="border: 1px solid;" >' + voucherTotal.toLocaleString("en-US") + '</td>')
                                .append('<td style="border: 1px solid;" >' + paidAmount.toLocaleString("en-US") + '</td>')
                                .append('<td style="border: 1px solid;" >' + dueAmount.toLocaleString("en-US") + '</td>')
                                .append('<td style="border: 1px solid;" />');
                        },
                        dataSrc: 2
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
        });
    </script>
@stop
