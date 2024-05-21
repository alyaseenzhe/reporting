<div>
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
                <div class="mt-8 text-center w-full">
                    <button id="reset-btn" style="background-color: #01290f;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold">
                            <span></span>
                            <span>إعادة ضبط</span>
                        </span>
                    </button>
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

    <div id="tbl2-container" class="overflow-x-auto">
        <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
            <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
            <tr style="border: 2px solid black;">
                <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                    <div class="text-sm">رقم العميل</div>
                </th>
                <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                    <div class="text-sm">رقم العملية</div>
                </th>
                <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                    <div class="text-sm">تاريخ العملية</div>
                </th>
                <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                    <div class="text-sm">الوصف</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-sm">مدين</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-sm">دائن</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-sm">الاجمالي التراكمي</div>
                </th>
            </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">

            @foreach($scribes_results as $record)
                <tr>
                    <td class="border p-2 whitespace-nowrap">
                        {{$record->CardCode}}
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{$record->TransId}}
                    </td>
                    <td class="border p-2">
                        {{ \Carbon\Carbon::parse($record->RefDate)->format('Y-m-d')}}
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        {{$record->LineMemo}}
                    </td>
                    <td style="direction: ltr" class="border p-2 whitespace-nowrap">
                        {{number_format($record->Debit, 2)}}
                    </td>
                    <td style="direction: ltr" class="border p-2 whitespace-nowrap">
                        {{number_format($record->Credit, 2)}}
                    </td>
                    <td style="direction: ltr" class="border p-2 whitespace-nowrap">
                        {{number_format($record->CumulativeBalance, 2)}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
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

                Livewire.emit('create-report', customer_id);


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
