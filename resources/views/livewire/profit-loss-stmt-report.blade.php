@section('title')
    تقرير الارباح والخسائر
@stop
<div>
    <div id="branch-container" class="mb-6 mt-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">الفرع
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="branch_id" name="branch_id"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('vendor_type') border: solid 1px #fda4af; @enderror">
                            <option value="all">الكل</option>
                            <option value="0001">المركز الرئيسي</option>
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
                    @error('branch_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
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
        <div id="tbl2-container" class="overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">رقم الحساب</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">اسم الحساب</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مدين</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">دائن</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الاجمالي</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @php
                    $counter = 0;
                    $full_total = 0;
                    $account_code = "*";
                    $profit = 0;
                @endphp
                @foreach($sap_results as $key2 => $record)
                    @if($record['Account Code2'] != $account_code)
                            <?php $account_code = $record['Account Code2'] ?>
                        <tr onclick="show_hide({{$account_code}})" style="background-color: #fff3d3; border: 2px solid black; font-weight: bold; cursor: pointer">
                            <td style="border: 2px solid black;" class="p-2 whitespace-nowrap col-id-no parent-{{$account_code}}" scope="row">+</td>
                            <td colspan="3" style="border: 2px solid black;" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['Account Code2'] }} - {{ $record['AccName'] }}</td>
                            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($record['Total'], 2) }}</td>
                            @php $full_total = $full_total + floatval($record['Total']); @endphp
                            @php if($account_code == 41 || $account_code == 51) $profit = $profit + floatval($record['Total']); @endphp
                        </tr>
                            @if($account_code == 51)
                            <tr style="background-color: #d9ddde; border: 2px solid black; font-weight: bold;">
                                <td colspan="4" style="border: 2px solid black;" class="border p-2 whitespace-nowrap col-id-no" scope="row">هامش الربح</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($profit, 2) }}</td>
                            </tr>
                            @endif
                    @endif
                        <?php $account_code = $record['Account Code2'] ?>


                    <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{ $record['Account Code2'] }} hide">
                        <td style="border-left: 2px solid black;" class="font-bold border p-2 whitespace-nowrap">
                            {{$record['Account Code']}}
                        </td>
                        <td style="border-left: 2px solid black;" class="font-bold border p-2 whitespace-nowrap">
                            {{$record['Account Name']}}
                        </td>
                        <td style="border-left: 2px solid black;direction: ltr; color: #721c24" class="border p-2 whitespace-nowrap">
                            {{number_format($record['DebitAmount'], 2)}}
                        </td>
                        <td style="border-left: 2px solid black; direction: ltr; color: #1c7430" class="border p-2 whitespace-nowrap">
                            {{ floatval($record['CreditAmount']) != 0 ? number_format($record['CreditAmount'], 2) : ""}}
                        </td>
                        <td style="border-left: 2px solid black; direction: ltr; @if(($record['CreditAmount']-$record['DebitAmount']) > 0) color: #1c7430 @else color: #721c24 @endif" class="border p-2 whitespace-nowrap">
                            {{ number_format(($record['CreditAmount']-$record['DebitAmount']), 2) }}
                        </td>
                    </tr>
                    @php $counter++ @endphp
                @endforeach
                </tbody>
                <tfoot>
                <tr style="background-color: #5b5b5b; color: white; border: 2px solid black; font-weight: bold">
                    <td colspan="4" style="border: 2px solid black;" class="border p-2 whitespace-nowrap col-id-no" scope="row">الإجمالي النهائي</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($full_total, 2) }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
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

            $('#branch_id').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#gen-report').on('click', function () {


                var branch_id = $('#branch_id').select2("val");
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();


                if(start_date == '' || end_date == '' || branch_id == "") {
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

                    Livewire.emit('create-report', branch_id, start_date, end_date);
                }
            });
        });

        function show_hide(acc) {
            if ($('.row-'+acc).hasClass('hide')) {
                $('.row-'+acc).removeClass('hide');
                $('.parent-'+acc).text('-');
            } else {
                $('.row-'+acc).addClass('hide');
                $('.parent-'+acc).text('+');
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
