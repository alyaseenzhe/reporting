<div id="content">
    <div class="mb-4">
        <a href="{{ route('list.my-product-target') }}">
            <span style="background-color: #0c5460; color: white; padding: 7px; border-radius: 5px;" class="text-sm bg-blue-950; cursor-pointer">
        متابعة المستهدف
        </span>
        </a>
    </div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">إضافة مستهدف جديد</div>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">المخزن
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="dept_id" name="dept_id" wire:model="dept_id"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('item_id') border: solid 1px #fda4af; @enderror">
                        <option value="-1">الرجاء اختيار المخزن</option>
                        @foreach($branches as $branch)
                            @if($branch == "3")
                                <option value="3">الاحساء</option>
{{--                            @elseif($branch == "509")--}}
                                <option value="509">منطقة القرية العليا</option>
                            @elseif($branch == "10")
                                <option value="10">جدة</option>
{{--                            @elseif($branch == "510")--}}
                                <option value="510">منطقة المدينة المنورة</option>
                            @elseif($branch == "7")
                                <option value="7">الرياض</option>
                            @elseif($branch == "13")
                                <option value="13">وادي الدواسر</option>
                            @elseif($branch == "4")
                                <option value="4">الجوف</option>
                            @elseif($branch == "6")
                                <option value="6">الدمام</option>
                            @elseif($branch == "5")
                                <option value="5">الخرج</option>
                            @elseif($branch == "12")
                                <option value="12">نجران</option>
{{--                            @elseif($branch == "515")--}}
                                <option value="515">منطقة الباحة</option>
                            @elseif($branch == "11")
                                <option value="11">حائل</option>
                            @elseif($branch == "9")
                                <option value="9">تبوك</option>
                            @elseif($branch == "8")
                                <option value="8">القصيم</option>
                            @elseif($branch == "505")
                                <option value="505">ساجر</option>
                            @endif
                        @endforeach


                    </select>
                    @error('dept_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">الشهر
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="date" type="month" name="selected_month" wire:model="selected_month"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('selected_month') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">نوع العرض
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="filter_type" name="filter_type" wire:model="filter_type"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('filter_type') border: solid 1px #fda4af; @enderror">
                        <option value="vendor">عرض الاصناف بالمورد</option>
                        <option value="product">عرض الاصناف برقم الصنف</option>
                    </select>
                    @error('filter_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                @if($filter_type == 'vendor')
                    <div class="w-full">
                        <label class="block font-bold mb-2">الموردين
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="vendor_id" name="vendor_id" wire:model="vendor_id"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('vendor_id') border: solid 1px #fda4af; @enderror">
                            <option value="-1">اختر المورد</option>
                            @foreach($vendor_list as $vendor)
                                <option value="{{ $vendor->NodeNo }}">{{ $vendor->Arabic_Name }}</option>
                            @endforeach
                        </select>
                        @error('vendor_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endif
                @if($filter_type == 'product')
                    <div class="w-full">
                    <label class="block font-bold mb-2">رقم الصنف
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="prod_id" name="prod_id" wire:model="prod_id"
                            class="block text-gray-900 w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm sm:text-sm border-gray-300 rounded"
                            style="@error('prod_id') border: solid 1px #fda4af; @enderror">
                    @error('prod_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                @endif

{{--                @if($btn_generate)--}}
                    <div class="mt-8 text-center w-full">
                        <button wire:click.prevent="generateReport" wire:loading.attr="disabled"
                                style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">
                            <span></span>
                            <span>عرض الأصناف</span>
                        </span>
                            <span class="mr-2 font-bold" wire:loading wire:target="generateReport">
                        <span></span>
                        <span>الرجاء الانتظار</span>
                        </span>
                        </button>
                    </div>
{{--                @endif--}}
                @if($results)
                    @if($btn_save)
{{--                        <div class="mt-8 text-center w-full">--}}
{{--                            <button wire:click.prevent="processData" wire:loading.attr="disabled"--}}
{{--                                    style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">--}}
{{--                        <span class="mr-2 font-bold" wire:loading.remove wire:target="processData">--}}
{{--                            <span></span>--}}
{{--                            <span>حفظ</span>--}}
{{--                        </span>--}}
{{--                                <span class="mr-2 font-bold" wire:loading wire:target="processData">--}}
{{--                        <span></span>--}}
{{--                        <span>الرجاء الانتظار</span>--}}
{{--                        </span>--}}
{{--                            </button>--}}
{{--                        </div>--}}

                        <div class="mt-8 text-center w-full">
                            <button id="clear-btn" wire:click.prevent="clear_btn"
                                    style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold">
                            <span></span>
                            <span>مسح</span>
                        </span>
                                <span class="mr-2 font-bold" wire:loading wire:target="clear_btn">
                        <span></span>
                        <span>الرجاء الانتظار</span>
                        </span>
                            </button>
                        </div>
                        <div class="mt-8 text-center w-full">
                            <button id="test-btn"
                                    style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold">
                            <span></span>
                            <span>حفظ</span>
                        </span>
                                <span class="mr-2 font-bold" wire:loading wire:target="processData">
                        <span></span>
                        <span>الرجاء الانتظار</span>
                        </span>
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <button class="back-to-top" type="button"></button>
    @if($show_msg)
        <div id="percentage-container" class="mb-6">
            <div style="background-color: whitesmoke; padding: 30px" class="overflow-x-auto w-full">
                <table id="tbl2" style="border: 2px solid black;" class="table-container w-full border text-center">
                    <tr>
                        <th style="border: 2px solid black; background-color: #fff8ef" class="col-id-no fixed-header border p-2 whitespace-nowrap">الموظف</th>
                        <th style="border: 2px solid black; background-color: #fff8ef" class="col-id-no fixed-header border p-2 whitespace-nowrap">النسبة</th>
                    </tr>
                    @foreach($emps as $key => $employee)
                        <tr style="background-color: #FFFFFF">
                            <td style="border: 2px solid black; z-index: 10; padding: 10px;" class="border">{{ $employee->name }}</td>
                            <td style="border: 2px solid black; z-index: 10" class="border">
                                <div class="w-full">
                                    @if($key === array_key_last($emps->toArray()))
                                        <div wire:ignore id="emp--{{$employee->emp_code}}--readonly"
                                             class="emps_percentage_readonly block text-gray-900 w-full text-center"
                                             style="padding: 10px; @error('emps_percentage') border: solid 1px #fda4af; @enderror">
                                        </div>
                                    @else
                                        <input id="emp--{{$employee->emp_code}}--active" wire:model.defer="emps_percentage.{{$employee->emp_code}}" type="number" min="0" max="100" step="0.1" oninput="this.value =!!this.value && Math.abs(this.value) >= 0 && Math.abs(this.value) <= 100 ? Math.abs(this.value) : null"
                                               class="emps_percentage block text-gray-900 w-full text-center"
                                               style="@error('emps_percentage') border: solid 1px #fda4af; @enderror">
                                    @endif
                                    @error('emps_percentage') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>

                @if(\Illuminate\Support\Facades\Auth::user()->user_group->calculate_all_product_target == '1')
                    <div class="mt-8 text-center w-full">
                        <button id="recalculate-btn"
                                style="background-color: #026832;" class="btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold">
                            <span></span>
                            <span>توزيع الكل</span>
                        </span>
                        </button>
                    </div>
                @endif

            </div>
        </div>
    @endif

    <div class="notification-box flex flex-col items-center justify-center w-full z-50 mb-3">
        <!-- Notification container -->
    </div>

    @if($show_msg)
        <div id="table-container" class="overflow-x-auto w-full">
            <table id="tbl2" style="border: 2px solid black;" class="table-container w-full border text-center">
            <tbody class="text-sm divide-y divide-gray-100">
            @if($results)
                    <?php
                    $vendor_id = "*";
                    ?>
                @foreach($results[0] as $record)
                    @if($record['VendorNo'] != $vendor_id)
                            <?php $vendor_id = $record['VendorNo'] ?>
                        <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
{{--                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>--}}
                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>
                            <td colspan="15" style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorName'] }}</td>
                        </tr>
                    @endif
                        <?php $vendor_id = $record['VendorNo'] ?>
                    <tr>
                        <th colspan="14" style="border: 2px solid black; background-color: #faebd7" class="col-id-no fixed-header border p-2 whitespace-nowrap">
                            <div class="flex flex-row">
                                <div class="w-full text-sm text-center">رقم الصنف</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record['ProductCode'] }}</div>
                                <div class="w-full text-sm text-center">اسم الصنف</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record['ProductName'] }}</div>
                                <div class="w-full text-sm text-center">الوحدة</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record['BaseUnits'] }}</div>
                                <div class="w-full text-sm text-center">التميز</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record['SpecialityCode'] }}</div>
                                <div class="w-full text-sm text-center">المورد</div>
                                <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record['VendorName'] }}</div>
                                <div class="w-full text-sm text-center">
                                    <button id="btn-target--{{ $record['ProductCode'] }}" class="btn-target" style="color: white; background-color: rosybrown; padding: 5px;">توزيع</button>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
{{--                        @if(Auth::user()->user_group->write_product_target == '2')--}}
{{--                            <th style="border: 2px solid black; z-index: 10" class="border p-2">--}}
{{--                                <div class="text-sm">الموظف</div>--}}
{{--                            </th>--}}
{{--                        @endif--}}
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">الشهر</div>
                        </th>
                        @foreach ($current_year_list as $year_key => $year)
                            @foreach ($year as $month)
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">{{ $year_key."-".$month }}</div>
                                </th>
                            @endforeach
                        @endforeach
                    </tr>
                    @if(Auth::user()->user_group->write_product_target == '2')
                        <tr style="background-color: #e9e9e9">
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">اجمالي المستهدف</div>
                            </th>
                            @php $target_counter =1; $arr_tar = []; @endphp
                            @foreach ($current_year_list as $year_key => $year)
                                @foreach ($year as $month_key => $month)
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        @php    $fromDate = \Carbon\Carbon::now();
                                        $toDate = \Carbon\Carbon::parse($year_key."-". $month ."-01");
                                        $diff = $fromDate->diffInMonths($toDate, false);
                                        $current = $current_target->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first();
                                        @endphp
                                        {{--                                @if($diff < 3)--}}
                                        @if($toDate->lt(\Carbon\Carbon::parse('2023-07-01')))
                                            {{--                                        {{ $current ? $current->target : $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first() }}--}}
                                            {{ $current ? $current->target : "-" }}
                                            @php array_push($arr_tar, ($current ? $current->target : "-")); @endphp
                                            {{--                                    <div id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$loop->iteration}}" class="w-full">{{ $current ? $current->target : "N/A" }}</div>--}}
                                        @else
                                            <input min="0" id="totaltarget--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" type="number" wire:model.defer="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}" class="total-target form-input w-full">
{{--                                            <input min="0" id="totaltarget--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" type="number" wire:model.defer="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}" placeholder="{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="total-target form-input w-full">--}}
                                        @endif
                                        @php $target_counter++; @endphp
                                    </th>
                                @endforeach
                            @endforeach
                        </tr>
                        @foreach($emps as $emp)
                            <tr>
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">{{ $emp->name }}</div>
                                </th>
                                @php $target_counter =1; $arr_tar = []; @endphp
                                @foreach ($current_year_list as $year_key => $year)
                                    @foreach ($year as $month_key => $month)
                                        <th wire:key="key--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" style="border: 2px solid black; z-index: 10" class="border p-2">
                                            @php    $fromDate = \Carbon\Carbon::now();
                                        $toDate = \Carbon\Carbon::parse($year_key."-". $month ."-01");
                                        $diff = $fromDate->diffInMonths($toDate, false);
                                        $current = $current_target->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first();
                                            @endphp
                                            {{--                                @if($diff < 3)--}}
                                            @if($toDate->lt(\Carbon\Carbon::parse('2023-07-01')))
                                                {{--                                        {{ $current ? $current->target : $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first() }}--}}
                                                {{ $current ? $current->target : "-" }}
                                                @php array_push($arr_tar, ($current ? $current->target : "-")); @endphp
                                                {{--                                    <div id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$loop->iteration}}" class="w-full">{{ $current ? $current->target : "N/A" }}</div>--}}
                                            @else
                                                <div>
{{--                                                    <input wire:key="key-{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" value="0" min="0" id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" type="number" wire:model.defer="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}.{{$emp->emp_code}}" placeholder="{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="form-input w-full">--}}
                                                    <input wire:key="key-{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" value="0" min="0" id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" type="number" wire:model.defer="emp_target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$emp->emp_code}}" placeholder="{{ $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="target form-input w-full">
                                                </div>
                                            @endif
                                            @php $target_counter++; @endphp
                                        </th>
                                    @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">المستهدف</div>
                            </th>
                            @php $target_counter =1; $arr_tar = []; @endphp
                            @foreach ($current_year_list as $year_key => $year)
                                @foreach ($year as $month_key => $month)
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        @php    $fromDate = \Carbon\Carbon::now();
                                        $toDate = \Carbon\Carbon::parse($year_key."-". $month ."-01");
                                        $diff = $fromDate->diffInMonths($toDate, false);
                                        $current = $current_target->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first();
                                        @endphp
                                        {{--                                @if($diff < 3)--}}
                                        @if($toDate->lt(\Carbon\Carbon::parse('2023-07-01')))
                                            {{--                                        {{ $current ? $current->target : $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first() }}--}}
                                            {{ $current ? $current->target : "-" }}
                                            @php array_push($arr_tar, ($current ? $current->target : "-")); @endphp
                                            {{--                                    <div id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$loop->iteration}}" class="w-full">{{ $current ? $current->target : "N/A" }}</div>--}}
                                        @else
                                            <div>
                                                <input min="0" id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" type="number" wire:model.defer="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}" placeholder="{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="target form-input w-full">
                                            </div>
                                        @endif
                                        @php $target_counter++; @endphp
                                    </th>
                                @endforeach
                            @endforeach
                        </tr>
                    @endif
                    <tr>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">مبيعات تاريخية</div>
                        </th>
                        {{--                    @foreach ($list as $year_key => $year)--}}
                        {{--                        @foreach ($year as $month)--}}
                        @php $sales = []; @endphp
                        @php $target_counter =1; @endphp
{{--                        @for($i = 1; $i <= 12; $i++)--}}
                        @foreach ($current_year_list as $year_key => $year)
                            @foreach ($year as $month_key => $month)
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div id="sales--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" class="text-sm">{{ number_format($record['month'.$target_counter]) }}</div>
                                    @php array_push($sales, $record['month'.$target_counter]); @endphp
                                </th>
                                @php $target_counter++; @endphp
                            @endforeach
                        @endforeach
{{--                        @endfor--}}
                        {{--{{--                        @endforeach--}}
                        {{--                    @endforeach--}}
                    </tr>
                    <tr>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">الفرق %</div>
                        </th>
                        @php $target_counter2 =1; @endphp
                        @foreach ($current_year_list as $year_key => $year)
                            @foreach ($year as $month)
                                {{--                    @for($i = 1; $i <= 12; $i++)--}}
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    @php
                                        $fromDate = \Carbon\Carbon::now();
                                        $toDate = \Carbon\Carbon::parse($year_key."-". $month ."-01");
                                        $diff = $fromDate->diffInMonths($toDate, false);

                                    @endphp
{{--                                    @if($diff < 3)--}}
                                    @if($toDate->lt(\Carbon\Carbon::parse('2023-07-01')))

                                        @if(array_key_exists($target_counter2, $arr_tar) && is_numeric($arr_tar[$target_counter2-1]))
                                            @php $res = $sales[$target_counter2-1] == 0? 0 :  $arr_tar[$target_counter2-1] / $sales[$target_counter2-1]*100;  @endphp
                                            <div style="@if($res > 0) color: #6ab200 @else color: #fd162c @endif">{{ $res }}</div>
                                        @else
                                            <div style="color: #fd162c">-</div>
                                        @endif
                                    @else
                                        <span wire:ignore id="diff--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter2}}" class="w-full"></span>
                                    @endif
                                    {{--                                <span id="x" class="w-full">--}}

                                    {{--                                <input type="number" wire:model="diff.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$loop->iteration}}" value="{{ $record['month'. $loop->iteration] }}" class="form-input w-full" readonly>--}}
                                </th>
                                {{--                    @endfor--}}
                                @php $target_counter2++; @endphp
                            @endforeach
                        @endforeach
                    </tr>

                @endforeach
            @endif
            </tbody>
        </table>
        </div>
    </div>
    @endif
    <div wire:loading wire:target="generateReport" class="w-full">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

    <script>
        var employee_code = [];
        Livewire.on('show-container', () => {
            $('th span').empty();
            // div = document.getElementById("report-btn");
            // div.classList.remove("hide");
            // $('th #x').text('AA');
            // alert('aaaa');
            var targets = [];

            // console.log($("input[name$='active']"));
            console.log($('.emps_percentage'));

            employee_code = [];
            $("[id^='emp--']").each(function () {
                var txt = $(this).attr('id').split('--');
                employee_code.push(txt[1]);
            });



            $('.emps_percentage').on('keyup', function () {
                console.log('coco');
                var total = 0;
                $(".emps_percentage").each(function () {

                    if ((total + parseFloat($(this).val())) > 100) {
                        console.log('dude');
                        $(this).val(0);
                    }

                    total = total + parseFloat($(this).val() ? $(this).val() : 0);
                });

                $(".emps_percentage_readonly").text(100-total);
            });

            console.log(employee_code);

            $('.total-target').on('keyup', function () {
                console.log('kaka');
                console.log($(this).val());
                console.log($(this).attr('id'));
                console.log(employee_code);

                var txt = $(this).attr('id');
                txt = txt.split("--");
                txt= txt[1]+"--"+txt[2]+"--"+txt[3];
                console.log(txt);
                var target_entered = $(this).val();

                var sales = $("#sales--"+txt).text();
                console.log("#sales--"+txt);
                console.log('sales: ' + sales);
                $("#diff--"+txt).text(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100));

                max_percent = 0;
                max_percent_emp = 0;
                target_total = 0;

                employee_code.forEach(function (element, idx, array) {
                    percent = 0;
                    if (idx === array.length - 1){
                        percent = parseFloat($("#emp--"+element+"--readonly").text());
                        if (percent > max_percent) {
                            max_percent = percent;
                            max_percent_emp = element;
                        }
                    }
                    else {
                        percent = parseFloat($("#emp--"+element+"--active").val());
                        if (percent > max_percent) {
                            max_percent = percent;
                            max_percent_emp = element;
                        }
                    }

                    console.log(percent);
                    console.log("#target--"+txt+"--"+element);
                    // console.log(target_entered);
                    // $("#target--"+txt+"--"+element).val(target_entered);
                    $("#target--"+txt+"--"+element).val(Math.round(parseFloat(target_entered)*(percent/100)));
                    target_total += Math.round(parseFloat(target_entered)*(percent/100));
                    // @this.emp_target = Math.round(parseFloat(target_entered)*(percent/100));
                    // $("#"+value[2]+"--"+element).val(value[3]*(percent/100));
                    // document.getElementById("#"+value[2]+"--"+element).value = value[3]*(percent/100);

                    console.log(element);
                });

                if(target_total > target_entered) {
                    console.log('minus');
                    edited_num = $("#target--"+txt+"--"+max_percent_emp).val();
                    $("#target--"+txt+"--"+max_percent_emp).val(parseInt(edited_num)-1);
                }
                else if (target_total < target_entered) {
                    console.log('plus');
                    edited_num = $("#target--"+txt+"--"+max_percent_emp).val();
                    $("#target--"+txt+"--"+max_percent_emp).val(parseInt(edited_num)+1);
                }


                // var total = 0;
                // $(".emps_percentage").each(function () {
                //
                //     if ((total + parseFloat($(this).val())) > 100) {
                //         console.log('dude');
                //         $(this).val(0);
                //     }
                //
                //     total = total + parseFloat($(this).val() ? $(this).val() : 0);
                // });
                //
                // $(".emps_percentage_readonly").text(100-total);
            });

            $('.target').on('keyup', function () {
                console.log('kaka');
                console.log($(this).val());
                console.log($(this).attr('id'));
                console.log(employee_code);

                var txt = $(this).attr('id');
                txt_original = txt.split("--");
                txt= txt_original[0]+ "--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];
                totaltarget_txt= "#totaltarget--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];
                sales_txt= "#sales--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];
                diff_txt= "#diff--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];

                console.log(txt);
                console.log(totaltarget_txt);
                // var target_entered = $(this).val();

                total = 0;

                employee_code.forEach(function (element, idx, array) {
                    total += $("#"+txt+"--"+element).val() ? parseInt($("#"+txt+"--"+element).val()) : 0;
                    console.log("tar:" + $("#"+txt+"--"+element).val());
                    console.log('total:'+ total);
                });
                $(totaltarget_txt).val(total);

                var sales = $(sales_txt).text();
                console.log(sales_txt);
                console.log('sales: ' + sales);
                $(diff_txt).text(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(total)/parseFloat(sales))*100));

                // var sales = $("#sales--"+txt).text();
                // console.log("#sales--"+txt);
                // console.log('sales: ' + sales);
                // $("#diff--"+txt).text(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100));
                //
                // employee_code.forEach(function (element, idx, array) {
                //     percent = 0;
                //     if (idx === array.length - 1){
                //         percent = parseFloat($("#emp--"+element+"--readonly").text());
                //     }
                //     else {
                //         percent = parseFloat($("#emp--"+element+"--active").val());
                //     }
                //
                //     console.log(percent);
                //     console.log("#target--"+txt+"--"+element);
                //     // console.log(target_entered);
                //     // $("#target--"+txt+"--"+element).val(target_entered);
                //     $("#target--"+txt+"--"+element).val(Math.round(parseFloat(target_entered)*(percent/100)));
                //     // @this.emp_target = Math.round(parseFloat(target_entered)*(percent/100));
                //     // $("#"+value[2]+"--"+element).val(value[3]*(percent/100));
                //     // document.getElementById("#"+value[2]+"--"+element).value = value[3]*(percent/100);
                //
                //     console.log(element);
                // });


                // var total = 0;
                // $(".emps_percentage").each(function () {
                //
                //     if ((total + parseFloat($(this).val())) > 100) {
                //         console.log('dude');
                //         $(this).val(0);
                //     }
                //
                //     total = total + parseFloat($(this).val() ? $(this).val() : 0);
                // });
                //
                // $(".emps_percentage_readonly").text(100-total);
            });

            $('#recalculate-btn').on('click', function () {

                if($('.emps_percentage').val()) {
                    console.log($(this).attr('id'));
                    btn_id = $(this).attr('id');
                    btn_id = btn_id.split('--');
                    selector_txt = 'totaltarget--'+ btn_id[1];
                    console.log(selector_txt);
                    const elements = document.querySelectorAll(".total-target");
                    console.log(elements);

                    elements.forEach(element =>{
                        if (element.value) {

                            // recalculating indiviuals based on the percentage
                            var txt = element.id;
                            txt = txt.split("--");
                            txt= txt[1]+"--"+txt[2]+"--"+txt[3];
                            console.log(txt);
                            var target_entered = element.value;

                            var sales = $("#sales--"+txt).text();
                            console.log("#sales--"+txt);
                            console.log('sales: ' + sales);
                            $("#diff--"+txt).text(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100));

                            max_percent = 0;
                            max_percent_emp = 0;
                            target_total = 0;

                            employee_code.forEach(function (element, idx, array) {
                                percent = 0;
                                if (idx === array.length - 1){
                                    percent = parseFloat($("#emp--"+element+"--readonly").text());
                                    if (percent > max_percent) {
                                        max_percent = percent;
                                        max_percent_emp = element;
                                    }
                                }
                                else {
                                    percent = parseFloat($("#emp--"+element+"--active").val());
                                    if (percent > max_percent) {
                                        max_percent = percent;
                                        max_percent_emp = element;
                                    }
                                }

                                console.log(percent);
                                console.log("#target--"+txt+"--"+element);
                                // console.log(target_entered);
                                // $("#target--"+txt+"--"+element).val(target_entered);
                                $("#target--"+txt+"--"+element).val(Math.round(parseFloat(target_entered)*(percent/100)));
                                // @this.emp_target = Math.round(parseFloat(target_entered)*(percent/100));
                                // $("#"+value[2]+"--"+element).val(value[3]*(percent/100));
                                // document.getElementById("#"+value[2]+"--"+element).value = value[3]*(percent/100);
                                target_total += Math.round(parseFloat(target_entered)*(percent/100));

                                console.log(element);
                            });

                            if(target_total > target_entered) {
                                edited_num = $("#target--"+txt+"--"+max_percent_emp).val();
                                $("#target--"+txt+"--"+max_percent_emp).val(parseInt(edited_num)-1);
                            }
                            else if (target_total < target_entered) {
                                edited_num = $("#target--"+txt+"--"+max_percent_emp).val();
                                $("#target--"+txt+"--"+max_percent_emp).val(parseInt(edited_num)+1);
                            }

                            //     end of recalculating indiviuals
                        }


                    });
                }
            });
            $('.btn-target').on('click', function () {
                console.log($(this).attr('id'));
                btn_id = $(this).attr('id');
                btn_id = btn_id.split('--');
                selector_txt = 'totaltarget--'+ btn_id[1];
                console.log(selector_txt);
                // const elements = document.querySelectorAll(".total-target");
                const elements = document.querySelectorAll("input[id^='"+selector_txt+"']");
                console.log(elements);

                elements.forEach(element =>{
                    if (element.value) {

                        // recalculating indiviuals based on the percentage
                        var txt = element.id;
                        txt = txt.split("--");
                        txt= txt[1]+"--"+txt[2]+"--"+txt[3];
                        console.log(txt);
                        var target_entered = element.value;

                        var sales = $("#sales--"+txt).text();
                        console.log("#sales--"+txt);
                        console.log('sales: ' + sales);
                        $("#diff--"+txt).text(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100));

                        max_percent = 0;
                        max_percent_emp = 0;
                        target_total = 0;

                        employee_code.forEach(function (element, idx, array) {
                            percent = 0;
                            if (idx === array.length - 1){
                                percent = parseFloat($("#emp--"+element+"--readonly").text());
                                if (percent > max_percent) {
                                    max_percent = percent;
                                    max_percent_emp = element;
                                }
                            }
                            else {
                                percent = parseFloat($("#emp--"+element+"--active").val());
                                if (percent > max_percent) {
                                    max_percent = percent;
                                    max_percent_emp = element;
                                }
                            }

                            console.log(percent);
                            console.log("#target--"+txt+"--"+element);
                            // console.log(target_entered);
                            // $("#target--"+txt+"--"+element).val(target_entered);
                            $("#target--"+txt+"--"+element).val(Math.round(parseFloat(target_entered)*(percent/100)));
                            // @this.emp_target = Math.round(parseFloat(target_entered)*(percent/100));
                            // $("#"+value[2]+"--"+element).val(value[3]*(percent/100));
                            // document.getElementById("#"+value[2]+"--"+element).value = value[3]*(percent/100);
                            target_total += Math.round(parseFloat(target_entered)*(percent/100));

                            console.log(element);
                        });


                        if(target_total > target_entered) {
                            edited_num = $("#target--"+txt+"--"+max_percent_emp).val();
                            $("#target--"+txt+"--"+max_percent_emp).val(parseInt(edited_num)-1);
                        }
                        else if (target_total < target_entered) {
                            edited_num = $("#target--"+txt+"--"+max_percent_emp).val();
                            $("#target--"+txt+"--"+max_percent_emp).val(parseInt(edited_num)+1);
                        }

                    //     end of recalculating indiviuals
                    }


                });

            });

            $('#test-btn').on('click', function () {
                // alert('hi');
                $("#test-btn").html('<b>الرجاء الإنتظار..</b>');
                targets = [];
                const elements = document.querySelectorAll("input[id^='target--']");
                console.log(elements);
                // const elements = $("input[id^='target--']");
                elements.forEach(element =>{
                    if (element.value) {
                        $("#test-btn").prop('value', 'الرجاء الإنتظار..');
                        console.log(element.value);
                    //     // targets[element.id] = element.value;
                    //     targets.push(element)
                        targets.push(element.id + "|" +element.value)
                    //     targets.push({element.id: element.value});
                    }
                    // targets.push({element.id: element.value});


                });
                // console.log($("input[id^='target--']"));

                // console.log(targets[0]);
                Livewire.emit('targets-entered', targets);
                // Livewire.emit('targets-entered', targets);
                // console.log(targets);

            });

            // $('#clear-btn').on('click', function () {
            //     $('th span').empty();
            //
            //     $('.total-target').val('');
            //     $('.target').val('');
            //     $('.emps_percentage').val('');
            //     $('.emps_percentage_readonly').text('');
            // });

        })



        // Livewire.on('diff-update', value => {
        //     console.log(value);
        //     $('#'+value[0]).text(value[1]);
        //     employee_code.forEach(function (element, idx, array) {
        //         percent = 0;
        //         if (idx === array.length - 1){
        //             percent = parseFloat($("#emp--"+element+"--readonly").text());
        //         }
        //         else {
        //             percent = parseFloat($("#emp--"+element+"--active").val());
        //         }
        //
        //         $("#"+value[2]+"--"+element).val(value[3]*(percent/100));
        //         // document.getElementById("#"+value[2]+"--"+element).value = value[3]*(percent/100);
        //
        //         console.log(element);
        //     });
        // });


        Livewire.on('msg', value => {
           // sendNotification('success', 'تم حفظ البيانات بنجاح!');
            Swal.fire({
                title: "تم إضافة المستهدف بنجاح",
                icon: "success",
                timer: 2000,
                timerProgressBar: true,
            });

           $('th span').empty();

           $('.total-target').empty();
           $('.target').empty();
           $('.emps_percentage').val('');
           $('.emps_percentage_readonly').text('');
            // $('.notification-box').empty();

        });

        Livewire.on('clear-btn', value => {
            $('th span').empty();
                // $('.total-target').val('');
                // $('.target').val('');
                // $('.emps_percentage').val('');
                $('.emps_percentage_readonly').text('');
        });


        function sendNotification(type, text) {
            let notificationBox = document.querySelector(".notification-box");
            const alerts = {
                info: {
                    icon: `<svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>`,
                    color: "blue-500"
                },
                error: {
                    icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>`,
                    color: "red-500"
                },
                warning: {
                    icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
</svg>`,
                    color: "yellow-500"
                },
                success: {
                    icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>`,
                    color: "green-500"
                }
            };
            let component = document.createElement("div");
            component.className = `flex w-full gap-2 bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 opacity-0 transform transition-all duration-500 mb-1`;
            component.style = `background-color: #e6fffa; color: #22543d; border-top: 4px solid #38b2ac;`;
            component.innerHTML = `${alerts[type].icon}<p>${text}</p>`;
            notificationBox.appendChild(component);
            setTimeout(() => {
                component.classList.remove("opacity-0");
                component.classList.add("opacity-1");
            }, 1); //1ms For fixing opacity on new element
            setTimeout(() => {
                component.classList.remove("opacity-1");
                component.classList.add("opacity-0");
                //component.classList.add("-translate-y-80"); //it's a little bit buggy when send multiple alerts
                component.style.margin = 0;
                component.style.padding = 0;
            }, 5000);
            setTimeout(() => {
                component.style.setProperty("height", "0", "important");
            }, 5100);
            setTimeout(() => {
                // notificationBox.removeChild(component);
                $('.notification-box').empty();
            }, 5700);
            //If you can do something more elegant than timeouts, please do, but i can't
        }



        // Back to top
        var amountScrolled = 200;
        var amountScrolledNav = 25;

        $("div").scroll(function() {
            console.log('hiii');
            // console.log($(window).scrollTop());
            const element = document.getElementById("content");
            console.log($(this).scrollTop());
            if ( $(this).scrollTop() > amountScrolled ) {
                $('button.back-to-top').addClass('show');
                console.log('dododo');
            } else {
                $('button.back-to-top').removeClass('show');
                console.log('xexexexe');
            }
        });

        $('button.back-to-top').click(function() {
            console.log('hello');
            $('div').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

    </script>
@stop
@section('css-scripts')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <style>
        .hide {
            display: none;
        }

        #report-logo {
            display: none;
        }

        button.back-to-top{
            margin: 0 !important;
            padding: 0 !important;
            background: #fff;
            height: 0px;
            width: 0px;
            overflow: hidden;
            border-radius: 50px;
            -webkit-border-radius: 50px;
            -moz-border-radius: 50px;
            color: transparent;
            clear: both;
            visibility: hidden;
            position: fixed;
            cursor: pointer;
            display: block;
            border: none;
            right: 50px;
            bottom: 75px;
            font-size: 0px;
            outline: 0 !important;
            z-index: 99;
            -webkit-transition: all .3s ease-in-out;
            transition: all .3s ease-in-out;
        }
        button.back-to-top:hover,
        button.back-to-top:active,
        button.back-to-top:focus,{
            outline: 0 !important;
        }
        button.back-to-top::before,
        button.back-to-top::after {
            content: "";
            display: block;
            vertical-align: middle;
            border-bottom: solid 10px #EA5D5F;
            border-left: solid 10px transparent;
            line-height: 0;
            border-right: solid 10px transparent;
            height: 0;
            margin: 18px auto 0;
            width: 0;
            border-radius:20px;
            visibility: hidden;
        }
        button.back-to-top.show::after,
        button.back-to-top.show::before{
            visibility: visible;
        }
        button.back-to-top::after {
            border-bottom-color:#fff;
            position: relative;
            top:-24px;
        }
        button.back-to-top.show {
            display: block;
            background: #fff;
            color: #00ab6c;
            font-size: 25px;
            opacity: 0.8;
            /*right: 25px;*/
            bottom: 50px;
            height: 50px;
            width: 50px;
            visibility: visible;
            box-shadow: 0px 2px 4px 1px rgba(0, 0, 0, 0.25);
            -webkit-box-shadow: 0px 2px 4px 1px rgba(0, 0, 0, 0.25);
            -moz-box-shadow: 0px 2px 4px 1px rgba(0, 0, 0, 0.25);
        }
        button.back-to-top.show:active {
            box-shadow: 0px 4px 8px 2px rgba(0, 0, 0, 0.25);
            -webkit-box-shadow: 0px 4px 8px 2px rgba(0, 0, 0, 0.25);
            -moz-box-shadow: 0px 4px 8px 2px rgba(0, 0, 0, 0.25);
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
