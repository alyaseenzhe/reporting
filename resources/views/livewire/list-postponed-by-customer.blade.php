<div wire:init="init">
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">المستحقات بالموظف</div>
    </div>
    <div id="report-btn" wire:loading.remove wire:target="load_data">
        <div id="tbl2-container" class="overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
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
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الرصيد</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">المستحق</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">نسبة المستحق</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">المهندس المختص</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @foreach($postponed as $record)
                        @if(strlen($record->Code ) == 7 || strlen($record->Code ) == 9)
                            <tr>
                                <td class="border p-2 whitespace-nowrap">
                                    {{$record->Code}}
                                </td>
                                <td class="border p-2 whitespace-nowrap">
                                    {{$record->Arabic_Name}}
                                </td>
                                <td class="border p-2 whitespace-nowrap">
                                    @if((substr($record->Code, 0, 2) == "01" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-01") && strlen($record->Code ) == 9)
                                        الاحساء
                                    @elseif((substr($record->Code, 0, 2) == "02" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-02") && strlen($record->Code ) == 9)
                                        جدة
                                    @elseif((substr($record->Code, 0, 2) == "03" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-03") && strlen($record->Code ) == 9)
                                        الرياض
                                    @elseif((substr($record->Code, 0, 2) == "04" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-04") && strlen($record->Code ) == 9)
                                        وادي الدواسر
                                    @elseif((substr($record->Code, 0, 2) == "05" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-05") && strlen($record->Code ) == 9)
                                        الجوف
                                    @elseif((substr($record->Code, 0, 2) == "06" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-06") && strlen($record->Code ) == 9)
                                        الدمام
                                    @elseif((substr($record->Code, 0, 2) == "07" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-07") && strlen($record->Code ) == 9)
                                        الخرج
                                    @elseif((substr($record->Code, 0, 2) == "08" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-08") && strlen($record->Code ) == 9)
                                        نجران
                                    @elseif((substr($record->Code, 0, 2) == "09" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-09") && strlen($record->Code ) == 9)
                                        حائل
                                    @elseif((substr($record->Code, 0, 2) == "10" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-10") && strlen($record->Code ) == 9)
                                        تبوك
                                    @elseif((substr($record->Code, 0, 2) == "11" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-11") && strlen($record->Code ) == 9)
                                        القصيم
                                    @elseif((substr($record->Code, 0, 2) == "12" && strlen($record->Code ) == 7) || (substr($record->Code, 0, 4) == "1-12" && strlen($record->Code ) == 9))
                                        ساجر
                                    @endif
                                </td>
                                <td class="border p-2 whitespace-nowrap">
                                    {{number_format($record->due_amount, 2)}}
                                </td>
                                <td class="border p-2 whitespace-nowrap">
                                    <?php
                                        $id = array_search($record->Code, array_column($this->posponed_due_amount, 'Code'));
                                        if ($id) {
                                            $due = $this->posponed_due_amount[$id]['due_amount'];
                                        }
                                        else {
                                            $due = 0;
                                        }
                                    ?>
                                    @if($id)
                                        {{number_format($due, 2)}}
                                    @else
                                        0.00
                                    @endif
                                </td>
                                <td class="border p-2 whitespace-nowrap">
                                    @if($id)
                                        @if($record->due_amount != 0)
                                            %{{number_format(($due/$record->due_amount*100))}}
                                        @else
                                            %0
                                        @endif
                                    @else
                                        %0
                                    @endif
                                </td>
                                <td class="border p-2 whitespace-nowrap">
                                    {{ $record->EmpName }}
                                </td>
                            </tr>
                        @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($load_data_flage)
        <div class="w-full">
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
    @endif
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

            $('#tbl2').DataTable(
                {
                    initComplete: function () {
                        this.api()
                            .columns(2)
                            .every(function () {
                                var column = this;
                                var select = $('<select><option value=""></option></select>')
                                    .appendTo($(column.header()).empty())
                                    .on('change', function () {
                                        var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                                    });

                                column
                                    .data()
                                    .unique()
                                    .sort()
                                    .each(function (d, j) {
                                        select.append('<option value="' + d + '">' + d + '</option>');
                                    });

                                $(column.footer()).empty();

                            });
                    },
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
                    order: [
                        [2, 'asc'], [6, 'asc']
                    ],
                    columnDefs: [ { orderable: false, targets: [0, 1, 2, 3,4, 5, 6] }],
                    rowGroup: {
                        startRender: null,
                        endRender: function (rows, group) {
                            var emp_name = rows
                                .data()
                                .pluck(6)[0];

                            var postponed_total = rows
                                .data()
                                .pluck(3)
                                .reduce(function (a, b) {
                                    // console.log(b);
                                    return a + parseFloat(b.replace(/\,/g,'')) * 1;
                                }, 0);

                            var postponed_dueAmount = rows
                                .data()
                                .pluck(4)
                                .reduce(function (a, b) {
                                    return a + parseFloat(b.replace(/\,/g,'')) * 1;
                                }, 0);

                            due_amount_percent = Math.round((postponed_dueAmount/postponed_total)*100);

                            return $('<tr style="background-color: #f2f0f0; font-weight: bold; color: #233881;" />')
                                .append('<td style="border: 1px solid;" colspan="3">المجموع لـ '+ group + '</td>')
                                .append('<td style="border: 1px solid;" >' + postponed_total.toLocaleString("en-US") + '</td>')
                                .append('<td style="border: 1px solid;" >' + postponed_dueAmount.toLocaleString("en-US") + '</td>')
                                .append('<td style="border: 1px solid;" >%'+ due_amount_percent.toLocaleString("en-US") +'</td>')
                                .append('<td style="border: 1px solid;" ></td>');
                        },
                        dataSrc: [6]
                    }
        })});



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
