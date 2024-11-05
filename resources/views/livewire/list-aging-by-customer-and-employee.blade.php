<div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">التعمير</div>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">الفرع
                        <span class="text-red-500">*</span>
                    </label>
{{--                    <select id="area_id" name="area_id" wire:model="area_id"--}}
                    <select id="area_id" name="area_id"
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
                    <label class="block font-bold mb-2">التاريخ
                        <span class="text-red-500">*</span>
                    </label>
{{--                    <input id="date" type="date" name="selected_date" wire:model="selected_date"--}}
                    <input id="selected_date" type="date" name="selected_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('selected_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mt-8 text-center w-full">
{{--                    <button id="generateReport" wire:click.prevent="generateReport" wire:loading.attr="disabled"--}}
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

    <div wire:loading.remove wire:target="generateReport" class="overflow-x-auto w-full">

        @if(count($aging_records) > 0)
            <div class="mb-5 p-2">
                <div class="flex flex-col sm:flex-row gap-4 w-full">
                    <div style="background-color: #f5f5f5; padding: 20px;" class="w-full">
                        <label class="block font-bold mb-5">خيارات</label>
                        <div class="flex flex-row gap-6" style="align-items: baseline;">
                            <div>
                                <select id="emp_code_selection" name="emp_code_selection"
                                        class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                        style="@error('dept_id') border: solid 1px #fda4af; @enderror">
                                    <option value="-1">جميع الموظفين</option>
                                    @foreach($emps as $emp => $key)
                                        <option value="emp-{{ $key }}">{{ $emp }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <input id="cust_status" name="cust_status" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">العملاء النشيطين فقط</label>
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
                        <div class="text-center text-sm">اسم العميل</div>
                    </th>
{{--                    <th class="border p-2">--}}
{{--                        <div class="text-center text-sm">#</div>--}}
{{--                    </th>--}}
                    <th class="border p-2">
                        <div class="text-center text-sm">اسم الموظف</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">الحد الإئتماني</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">فترة السداد</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">رصيد العميل</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">0-30 يوم</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">31-60 يوم</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">61-90 يوم</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">91-120 يوم</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-center text-sm">< 120 يوم</div>
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
                    $total_30 = 0;
                    $total_60 = 0;
                    $total_90 = 0;
                    $total_120 = 0;
                    $total_above_120 = 0;

                    $emp_customer_total = 0;
                    $emp_total_30 = 0;
                    $emp_total_60 = 0;
                    $emp_total_90 = 0;
                    $emp_total_120 = 0;
                    $emp_total_above_120 = 0;

                    $branch_customer_total = 0;
                    $branch_total_30 = 0;
                    $branch_total_60 = 0;
                    $branch_total_90 = 0;
                    $branch_total_120 = 0;
                    $branch_total_above_120 = 0;

                    $active_emp_customer_total = 0;
                    $active_emp_total_30 = 0;
                    $active_emp_total_60 = 0;
                    $active_emp_total_90 = 0;
                    $active_emp_total_120 = 0;
                    $active_emp_total_above_120 = 0;

                    $active_branch_customer_total = 0;
                    $active_branch_total_30 = 0;
                    $active_branch_total_60 = 0;
                    $active_branch_total_90 = 0;
                    $active_branch_total_120 = 0;
                    $active_branch_total_above_120 = 0;
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
{{--                        total by Business Partner--}}
                        @if($record["Business Partner Code"] != $customer_id)

                            <tr style="background-color: #f2f0f0; color: #233881; border: solid 2px;" class="emp emp-{{$emp_id}} @if($valid_for[$customer_id] == 'N') nonactive-customer @endif">
                                {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
                                <td style="border: 2px solid white;">{{ $customer_id }}</td>
                                <td style="border: 2px solid white;">{{ $customer_name }}</td>
{{--                                <td style="border: 2px solid white;">{{ $valid_for[$customer_id] }}</td>--}}
{{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                                <td style="border: 2px solid white;" >{{$emp_name}}</td>
                                <td style="border: 2px solid white;">{{ number_format($customer_credit_limit[$customer_id]) }}</td>
                                <td style="border: 2px solid white;">{{ $customer_payment_term[$customer_id] }}</td>
                                <td style="border: 2px solid white;">{{ number_format($customer_total, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($total_30, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($total_60, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($total_90, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($total_120, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($total_above_120, 2) }}</td>
                            </tr>

                            {{--                        total by Sales Person--}}
                            @if($record["SlpName"] != $emp_name)
                                <tr style="background-color: #fff5c2; font-weight: bold; color: #721c24; border: solid 2px;" class="non-emp non-emp-{{$emp_id}}">
                                    {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
                                    <td rowspan="2" colspan="5" style="border: 2px solid white;"> المجموع والنسبة لـ {{$emp_name}}</td>
                                    {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                                    <td rowspan="2" style="border: 2px solid white;" >{{number_format($emp_customer_total, 2)}}</td>
                                    <td style="border: 2px solid white;">{{ number_format($emp_total_30, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($emp_total_60, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($emp_total_90, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($emp_total_120, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($emp_total_above_120, 2) }}</td>
                                </tr>
                                <tr style="background-color: #fff5c2; font-weight: bold; color: #721c24; border: solid 2px;" class="non-emp non-emp-{{$emp_id}}">
                                    {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
{{--                                    <td colspan="5" style="border: 2px solid white;"> النسبة لـ {{$emp_name}}</td>--}}
                                    {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                                    <td style="border: 2px solid white;">{{ number_format(($emp_total_30/$emp_customer_total)*100, 2) }}%</td>
                                    <td style="border: 2px solid white;">{{ number_format(($emp_total_60/$emp_customer_total)*100, 2) }}%</td>
                                    <td style="border: 2px solid white;">{{ number_format(($emp_total_90/$emp_customer_total)*100, 2) }}%</td>
                                    <td style="border: 2px solid white;">{{ number_format(($emp_total_120/$emp_customer_total)*100, 2) }}%</td>
                                    <td style="border: 2px solid white;">{{ number_format(($emp_total_above_120/$emp_customer_total)*100, 2) }}%</td>
                                </tr>

                                <tr style="background-color: #00a1d3; font-weight: bold; color: #721c24; border: solid 2px;" class="active-emp active-emp-{{$emp_id}} hide">
                                    {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
                                    <td rowspan="2" colspan="5" style="border: 2px solid white;"> المجموع والنسبة لـ {{$emp_name}}</td>
                                    {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                                    <td rowspan="2" style="border: 2px solid white;" >{{number_format($active_emp_customer_total, 2)}}</td>
                                    <td style="border: 2px solid white;">{{ number_format($active_emp_total_30, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($active_emp_total_60, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($active_emp_total_90, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($active_emp_total_120, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($active_emp_total_above_120, 2) }}</td>
                                </tr>
                                <tr style="background-color: #00a1d3; font-weight: bold; color: #721c24; border: solid 2px;" class="active-emp active-emp-{{$emp_id}} hide">
                                    {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
{{--                                    <td colspan="5" style="border: 2px solid white;"> النسبة لـ {{$emp_name}}</td>--}}
                                    {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                                    <td style="border: 2px solid white;">{{ number_format($active_emp_customer_total > 0 ? (($active_emp_total_30/$active_emp_customer_total)*100) : 0, 2) }}%</td>
                                    <td style="border: 2px solid white;">{{ number_format($active_emp_customer_total > 0 ? (($active_emp_total_60/$active_emp_customer_total)*100) : 0, 2) }}%</td>
                                    <td style="border: 2px solid white;">{{ number_format($active_emp_customer_total > 0 ? (($active_emp_total_90/$active_emp_customer_total)*100) : 0, 2) }}%</td>
                                    <td style="border: 2px solid white;">{{ number_format($active_emp_customer_total > 0 ? (($active_emp_total_120/$active_emp_customer_total)*100) : 0, 2) }}%</td>
                                    <td style="border: 2px solid white;">{{ number_format($active_emp_customer_total > 0 ? (($active_emp_total_above_120/$active_emp_customer_total)*100) : 0, 2) }}%</td>
                                </tr>

                                @php $emp_customer_total = 0; $emp_total_30 = 0; $emp_total_60 = 0; $emp_total_90 = 0; $emp_total_120 = 0; $emp_total_above_120 = 0; @endphp
                                @php $active_emp_customer_total = 0; $active_emp_total_30 = 0; $active_emp_total_60 = 0; $active_emp_total_90 = 0; $active_emp_total_120 = 0; $active_emp_total_above_120 = 0; @endphp
                            @endif
                                <?php $customer_id = $record["Business Partner Code"]; ?>
                                <?php $customer_name = $record["Business Partner Name"]; ?>
                                <?php $emp_id = $record["Memo"]; ?>
                                <?php $emp_name = $record["SlpName"]; ?>

                            @php $customer_total = 0; $total_30 = 0; $total_60 = 0; $total_90 = 0; $total_120 = 0; $total_above_120 = 0; @endphp
                        @endif

                        @php $num_days = \Carbon\Carbon::parse($record["Posting Date"])->diffInDays(\Carbon\Carbon::parse($last_date));  @endphp
                        @php $customer_total += $record["Debit (LC)"]; @endphp
                        @php $emp_customer_total += $record["Debit (LC)"]; @endphp



                        @php $active_emp_customer_total += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp
                        @php $branch_customer_total += $record["Debit (LC)"]; @endphp
                        @php $active_branch_customer_total += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp

                        @if($num_days <= 30)
                            @php $total_30 += $record["Debit (LC)"]; @endphp
                            @php $emp_total_30 += $record["Debit (LC)"]; @endphp
                            @php $branch_total_30 += $record["Debit (LC)"]; @endphp

                            @php $active_emp_total_30 += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp
                            @php $active_branch_total_30 += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp

                        @elseif($num_days > 30 && $num_days <= 60)
                            @php $total_60 += $record["Debit (LC)"]; @endphp
                            @php $emp_total_60 += $record["Debit (LC)"]; @endphp
                            @php $branch_total_60 += $record["Debit (LC)"]; @endphp

                            @php $active_emp_total_60 += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp
                            @php $active_branch_total_60 += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp
                        @elseif($num_days > 60 && $num_days <= 90)
                            @php $total_90 += $record["Debit (LC)"]; @endphp
                            @php $emp_total_90 += $record["Debit (LC)"]; @endphp
                            @php $branch_total_90 += $record["Debit (LC)"]; @endphp

                            @php $active_emp_total_90 += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp
                            @php $active_branch_total_90 += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp
                        @elseif($num_days > 90 && $num_days <= 120)
                            @php $total_120 += $record["Debit (LC)"]; @endphp
                            @php $emp_total_120 += $record["Debit (LC)"]; @endphp
                            @php $branch_total_120 += $record["Debit (LC)"]; @endphp

                            @php $active_emp_total_120 += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp
                            @php $active_branch_total_120 += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp
                        @elseif($num_days > 120)
                            @php $total_above_120 += $record["Debit (LC)"]; @endphp
                            @php $emp_total_above_120 += $record["Debit (LC)"]; @endphp
                            @php $branch_total_above_120 += $record["Debit (LC)"]; @endphp

                            @php $active_emp_total_above_120 += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp
                            @php $active_branch_total_above_120 += $valid_for[$customer_id] == 'Y'? $record["Debit (LC)"] : 0; @endphp
                        @endif



                        @if($loop->last)
                            <tr style="background-color: #f2f0f0; color: #233881; border: solid 2px;" class="emp emp-{{$emp_id}} @if($valid_for[$customer_id] == 'N') nonactive-customer @endif">
                                {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
                                <td style="border: 2px solid white;">{{ $customer_id }}</td>
                                <td style="border: 2px solid white;">{{ $customer_name }}</td>
{{--                                <td style="border: 2px solid white;">{{ $valid_for[$customer_id] }}</td>--}}
{{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                                <td style="border: 2px solid white;" >{{$emp_name}}</td>
                                <td style="border: 2px solid white;">{{ number_format($customer_credit_limit[$customer_id]) }}</td>
                                <td style="border: 2px solid white;">{{ $customer_payment_term[$customer_id] }}</td>
                                <td style="border: 2px solid white;">{{ number_format($customer_total, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($total_30, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($total_60, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($total_90, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($total_120, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($total_above_120, 2) }}</td>
                            </tr>

                            {{--                        total by Sales Person--}}
{{--                            @if($record["SlpName"] != $emp_name)--}}
                                <tr style="background-color: #fff5c2; font-weight: bold; color: #721c24; border: solid 2px;" class="non-emp non-emp-{{$emp_id}}">
                                    {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
                                    <td rowspan="2" colspan="5" style="border: 2px solid white;"> المجموع والنسبة لـ {{$emp_name}}</td>
                                    {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                                    <td rowspan="2" style="border: 2px solid white;" >{{number_format($emp_customer_total, 2)}}</td>
                                    <td style="border: 2px solid white;">{{ number_format($emp_total_30, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($emp_total_60, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($emp_total_90, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($emp_total_120, 2) }}</td>
                                    <td style="border: 2px solid white;">{{ number_format($emp_total_above_120, 2) }}</td>
                                </tr>
                                <tr style="background-color: #fff5c2; font-weight: bold; color: #721c24; border: solid 2px;" class="non-emp non-emp-{{$emp_id}}">
                                {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
{{--                                <td colspan="5" style="border: 2px solid white;"> النسبة لـ {{$emp_name}}</td>--}}
                                {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                                <td style="border: 2px solid white;">{{ number_format(($emp_total_30/$emp_customer_total)*100, 2) }}%</td>
                                <td style="border: 2px solid white;">{{ number_format(($emp_total_60/$emp_customer_total)*100, 2) }}%</td>
                                <td style="border: 2px solid white;">{{ number_format(($emp_total_90/$emp_customer_total)*100, 2) }}%</td>
                                <td style="border: 2px solid white;">{{ number_format(($emp_total_120/$emp_customer_total)*100, 2) }}%</td>
                                <td style="border: 2px solid white;">{{ number_format(($emp_total_above_120/$emp_customer_total)*100, 2) }}%</td>
                            </tr>

                                <tr style="background-color: #00a1d3; font-weight: bold; color: #721c24; border: solid 2px;" class="active-emp active-emp-{{$emp_id}} hide">
                                {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
                                <td rowspan="2" colspan="5" style="border: 2px solid white;"> المجموع والنسبة لـ {{$emp_name}}</td>
                                {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                                <td rowspan="2" style="border: 2px solid white;" >{{number_format($active_emp_customer_total, 2)}}</td>
                                <td style="border: 2px solid white;">{{ number_format($active_emp_total_30, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($active_emp_total_60, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($active_emp_total_90, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($active_emp_total_120, 2) }}</td>
                                <td style="border: 2px solid white;">{{ number_format($active_emp_total_above_120, 2) }}</td>
                            </tr>
                                <tr style="background-color: #00a1d3; font-weight: bold; color: #721c24; border: solid 2px;" class="active-emp active-emp-{{$emp_id}} hide">
                                {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
{{--                                <td colspan="5" style="border: 2px solid white;"> النسبة لـ {{$emp_name}}</td>--}}
                                {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                                <td style="border: 2px solid white;">{{ number_format($active_emp_customer_total > 0 ? (($active_emp_total_30/$active_emp_customer_total)*100) : 0, 2) }}%</td>
                                <td style="border: 2px solid white;">{{ number_format($active_emp_customer_total > 0 ? (($active_emp_total_60/$active_emp_customer_total)*100) : 0, 2) }}%</td>
                                <td style="border: 2px solid white;">{{ number_format($active_emp_customer_total > 0 ? (($active_emp_total_90/$active_emp_customer_total)*100) : 0, 2) }}%</td>
                                <td style="border: 2px solid white;">{{ number_format($active_emp_customer_total > 0 ? (($active_emp_total_120/$active_emp_customer_total)*100) : 0, 2) }}%</td>
                                <td style="border: 2px solid white;">{{ number_format($active_emp_customer_total > 0 ? (($active_emp_total_above_120/$active_emp_customer_total)*100) : 0, 2) }}%</td>
                            </tr>

                                @php $emp_customer_total = 0; $emp_total_30 = 0; $emp_total_60 = 0; $emp_total_90 = 0; $emp_total_120 = 0; $emp_total_above_120 = 0; @endphp
                                @php $active_emp_customer_total = 0; $active_emp_total_30 = 0; $active_emp_total_60 = 0; $active_emp_total_90 = 0; $active_emp_total_120 = 0; $active_emp_total_above_120 = 0; @endphp
{{--                            @endif--}}
                                <?php $customer_id = $record["Business Partner Code"]; ?>
                                <?php $customer_name = $record["Business Partner Name"]; ?>
                                <?php $emp_id = $record["Memo"]; ?>
                                <?php $emp_name = $record["SlpName"]; ?>

                            @php $customer_total = 0; $customer_total_120 = 0; $total_30 = 0; $total_60 = 0; $total_90 = 0; $total_120 = 0; $total_above_120 = 0; @endphp
                        @endif
{{--                    @endif--}}
                @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #e6ffcc; font-weight: bold; color: #2d721c; border: solid 2px;" class="full-emps">
                        {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
                        <td colspan="5" style="border: 2px solid white;">المجموع الكلي</td>
                        {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                        <td rowspan="2" style="border: 2px solid white;" >{{number_format($branch_customer_total, 2)}}</td>
                        <td style="border: 2px solid white;">{{ number_format($branch_total_30, 2) }}</td>
                        <td style="border: 2px solid white;">{{ number_format($branch_total_60, 2) }}</td>
                        <td style="border: 2px solid white;">{{ number_format($branch_total_90, 2) }}</td>
                        <td style="border: 2px solid white;">{{ number_format($branch_total_120, 2) }}</td>
                        <td style="border: 2px solid white;">{{ number_format($branch_total_above_120, 2) }}</td>
                    </tr>
                    <tr style="background-color: #e6ffcc; font-weight: bold; color: #2d721c; border: solid 2px;" class="full-emps">
                        {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
                        <td colspan="5" style="border: 2px solid white;">النسبة الكلية </td>
                        {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                        <td style="border: 2px solid white;">{{ $branch_customer_total > 0? number_format(($branch_total_30/$branch_customer_total)*100, 2) : 0 }}%</td>
                        <td style="border: 2px solid white;">{{ $branch_customer_total > 0? number_format(($branch_total_60/$branch_customer_total)*100, 2) : 0 }}%</td>
                        <td style="border: 2px solid white;">{{ $branch_customer_total > 0? number_format(($branch_total_90/$branch_customer_total)*100, 2) : 0 }}%</td>
                        <td style="border: 2px solid white;">{{ $branch_customer_total > 0? number_format(($branch_total_120/$branch_customer_total)*100, 2) : 0 }}%</td>
                        <td style="border: 2px solid white;">{{ $branch_customer_total > 0? number_format(($branch_total_above_120/$branch_customer_total)*100, 2) : 0 }}%</td>
                    </tr>

                    <tr style="background-color: #ffbc91; font-weight: bold; color: #2d721c; border: solid 2px;" class="active-full-emps hide">
                        {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
                        <td colspan="5" style="border: 2px solid white;">المجموع الكلي</td>
                        {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                        <td rowspan="2" style="border: 2px solid white;" >{{number_format($active_branch_customer_total, 2)}}</td>
                        <td style="border: 2px solid white;">{{ number_format($active_branch_total_30, 2) }}</td>
                        <td style="border: 2px solid white;">{{ number_format($active_branch_total_60, 2) }}</td>
                        <td style="border: 2px solid white;">{{ number_format($active_branch_total_90, 2) }}</td>
                        <td style="border: 2px solid white;">{{ number_format($active_branch_total_120, 2) }}</td>
                        <td style="border: 2px solid white;">{{ number_format($active_branch_total_above_120, 2) }}</td>
                    </tr>
                    <tr style="background-color: #ffbc91; font-weight: bold; color: #2d721c; border: solid 2px;" class="active-full-emps hide">
                        {{--            <td style="border: 2px solid white;" colspan="6">المجموع والنسبة لـ--}}
                        <td colspan="5" style="border: 2px solid white;">النسبة الكلية </td>
                        {{--                                <td style="border: 2px solid white;" >{{$emp_id}}</td>--}}
                        <td style="border: 2px solid white;">{{ $active_branch_customer_total > 0? number_format(($active_branch_total_30/$active_branch_customer_total)*100, 2) : 0 }}%</td>
                        <td style="border: 2px solid white;">{{ $active_branch_customer_total > 0? number_format(($active_branch_total_60/$active_branch_customer_total)*100, 2) : 0 }}%</td>
                        <td style="border: 2px solid white;">{{ $active_branch_customer_total > 0? number_format(($active_branch_total_90/$active_branch_customer_total)*100, 2) : 0 }}%</td>
                        <td style="border: 2px solid white;">{{ $active_branch_customer_total > 0? number_format(($active_branch_total_120/$active_branch_customer_total)*100, 2) : 0 }}%</td>
                        <td style="border: 2px solid white;">{{ $active_branch_customer_total > 0? number_format(($active_branch_total_above_120/$active_branch_customer_total)*100, 2) : 0 }}%</td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>
</div>


@section('css-scripts')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />--}}
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" />--}}
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css" />--}}

    <style>
        .hide {
            display: none;
        }
    </style>

@stop

@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    {{--    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>--}}
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>--}}
    {{--    <script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>--}}

    <script>
        $(document).ready( function () {

            flag = true;

            // Livewire.on('show-data', () => {
            Livewire.on('finished', () => {
                $('#record').prop('checked', false);
                $('#cust_status').prop('checked', false);
                $('#emp_code_selection option:first').prop('selected', true);

                swal.close();


                $("#emp_code_selection").on('change', function () {

                    emp = $(this).val();

                    if(emp == '-1') {
                        $(".emp").removeClass("hide");
                        $(".full-emps").removeClass("hide");


                        if($('#cust_status').is(':checked')) {
                            $(".active-emp").removeClass("hide");
                            // $(`.active-emp:not(.active-${emp})`).addClass("hide");

                            $(".non-emp").addClass("hide");

                            $(".full-emps").addClass("hide");
                            $(".active-full-emps").removeClass("hide");

                            $(`.nonactive-customer`).addClass("hide"); // good

                        } else {
                            $(".active-emp").addClass("hide");
                            $(".non-emp").removeClass("hide");
                            // $(`.non-emp:not(.non-${emp})`).addClass("hide");

                            $(".full-emps").removeClass("hide");
                            $(".active-full-emps").addClass("hide");

                            $(`.nonactive-customer`).removeClass("hide"); // good
                        }

                    }
                    else {

                        $(".emp").removeClass("hide");
                        $(`.emp:not(.${emp})`).addClass("hide");
                        $(".full-emps").addClass("hide");
                        $(".active-full-emps").addClass("hide");

                        if($('#cust_status').is(':checked')) {
                            $(".active-emp").removeClass("hide");
                            $(`.active-emp:not(.active-${emp})`).addClass("hide");

                            $(".non-emp").addClass("hide");

                            $(`.nonactive-customer`).addClass("hide"); // good
                            // $(`.nonactive-customer:not(.${emp})`).addClass("hide");// good

                        } else {
                            $(".active-emp").addClass("hide");

                            $(".non-emp").removeClass("hide");
                            $(`.non-emp:not(.non-${emp})`).addClass("hide");
                        }
                    }

                });


                // for customer status checkbox
                $('#cust_status').change(function(event) {

                    emp = $("#emp_code_selection").val();

                    if($(this).is(':checked')) {

                        if(emp == '-1') {

                            $(".active-emp").removeClass("hide");

                            $(".non-emp").addClass("hide");

                            $(".full-emps").addClass("hide");
                            $(".active-full-emps").removeClass("hide");

                            $(".nonactive-customer").addClass("hide"); // good



                        }
                        else {

                            $(".active-emp").removeClass("hide");
                            $(`.active-emp:not(.active-${emp})`).addClass("hide");

                            $(".non-emp").addClass("hide");

                            $(".full-emps").addClass("hide");
                            $(".active-full-emps").addClass("hide");

                            $(".nonactive-customer").addClass("hide"); // good

                            // $(`.nonactive-customer`).removeClass("hide"); // good
                            // $(`.nonactive-customer:not(.${emp})`).addClass("hide");// good

                        }

                    } else {

                        if(emp == '-1') {

                            $(".active-emp").addClass("hide");

                            $(".non-emp").removeClass("hide");

                            $(".full-emps").removeClass("hide");
                            $(".active-full-emps").addClass("hide");

                            $(".nonactive-customer").removeClass("hide"); // good



                        }
                        else {

                            $(`.nonactive-customer`).removeClass("hide"); // good
                            $(`.nonactive-customer:not(.${emp})`).addClass("hide");// good

                            $(".non-emp").removeClass("hide");
                            $(`.non-emp:not(.non-${emp})`).addClass("hide");

                            $(".active-emp").addClass("hide");

                            $(".full-emps").addClass("hide");
                            $(".active-full-emps").addClass("hide");
                        }
                    }
                });






            });


            $('#gen-report').on('click', function () {

                var area_id = $('#area_id').val();
                var selected_date =  $('#selected_date').val();


                if(area_id == null || area_id == -1 || selected_date == null || selected_date == '') {
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

                    Livewire.emit('create-report', area_id, selected_date);
                }
                // }
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
