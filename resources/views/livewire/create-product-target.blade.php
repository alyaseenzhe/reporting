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
        <div class="w-full">المستهدف</div>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">الشهر الإبتدائي
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="date" type="month" name="selected_month" wire:model="selected_month"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('selected_month') border: solid 1px #fda4af; @enderror">
                    @error('selected_month') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">الفرع
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="dept_id" name="dept_id" multiple="multiple"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('dept_id') border: solid 1px #fda4af; @enderror">
                            <option value="-1" selected>الكل</option>
                        @foreach($branches as $branch)
                            @if($branch == "3")
                                <option value="3">الاحساء</option>
{{--                            @elseif($branch == "509")--}}
{{--                                <option value="509">منطقة القرية العليا</option>--}}
                            @elseif($branch == "10")
                                <option value="10">جدة</option>
{{--                            @elseif($branch == "510")--}}
{{--                                <option value="510">منطقة المدينة المنورة</option>--}}
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
{{--                                <option value="515">منطقة الباحة</option>--}}
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
                    </div>
                    @error('dept_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">نوع المواد
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="cat_type" name="cat_type" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('cat_type') border: solid 1px #fda4af; @enderror">
                            <option value="cat_all" selected>الكل</option>
                            <option value="bathoor">بذور</option>
                            <option value="asmedah">اسمدة</option>
                            <option value="mobedat">مبيدات</option>
                            <option value="other">اخرى</option>
                        </select>
                    </div>
                    @error('cat_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">نوع المميز
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="sp_type" name="sp_type" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('sp_type') border: solid 1px #fda4af; @enderror">
                            <option value="sp_all" selected>الكل</option>
                            <option value="0">مميز 0</option>
                            <option value="1">مميز 1</option>
                            <option value="2">مميز 2</option>
                        </select>
                    </div>
                    @error('sp_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">الموردين
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="vendor_type" name="vendor_type"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('vendor_type') border: solid 1px #fda4af; @enderror">
                            <option value="vendor_all" selected>الكل</option>
                            @foreach($vendor_list as $vendor)
                                <option value="{{ $vendor->NodeNo }}">{{ $vendor->Arabic_Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('vendor_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

{{--                @if($btn_generate)--}}
                <div class="mt-8 text-center w-full">
{{--                    <button wire:click.prevent="generateReport" wire:loading.attr="disabled"--}}
{{--                            style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                    <button id="gen-report" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold">
                        <span></span>
                        <span>عرض الأصناف</span>
                    </span>
                        <span class="mr-2 font-bold">
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

{{--                        <div class="mt-8 text-center w-full">--}}
{{--                            <button id="clear-btn" wire:click.prevent="clear_btn"--}}
{{--                                    style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">--}}
{{--                        <span class="mr-2 font-bold">--}}
{{--                            <span></span>--}}
{{--                            <span>مسح</span>--}}
{{--                        </span>--}}
{{--                                <span class="mr-2 font-bold" wire:loading wire:target="clear_btn">--}}
{{--                        <span></span>--}}
{{--                        <span>الرجاء الانتظار</span>--}}
{{--                        </span>--}}
{{--                            </button>--}}
{{--                        </div>--}}
                        @if($write_product_target == '2')
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
                        @elseif($write_product_target == '1')
                            <div class="mt-8 text-center w-full">
                                <button id="emptest-btn"
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
                @endif
            </div>
        </div>
    </div>
    <button class="back-to-top" type="button"></button>
    @if($show_msg)
            <?php $all_total_emp_sales = []; ?>
            <?php $all_total_emp_tr = []; ?>
            <?php $all_total_dept_sales = []; ?>
            <?php $all_total_dept_tr = []; ?>
            <?php $total_historical = 0; ?>

        @if(\Illuminate\Support\Facades\Auth::user()->user_group->write_product_target == '2' || \Illuminate\Support\Facades\Auth::user()->user_group->write_product_target == '3' || \Illuminate\Support\Facades\Auth::user()->user_group->write_product_target == '0')
            <div id="percentage-container" class="mb-6">
                <div style="background-color: whitesmoke; padding: 30px" class="overflow-x-auto w-full">
                    <table id="tbl1" style="border: 2px solid black;" class="table-container w-full border text-center">
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
                                                {{ $emps_percentage->where('emp_code', $employee->emp_code)->first() ? $emps_percentage->where('emp_code', $employee->emp_code)->first()['emp_percentage'] : null}}
                                            </div>
                                        @else
                                            <input id="emp--{{$employee->emp_code}}--active" type="number" min="0" max="100" step="0.1" oninput="this.value =!!this.value && Math.abs(this.value) >= 0 && Math.abs(this.value) <= 100 ? Math.abs(this.value) : null"
                                                   value="{{ $emps_percentage->where('emp_code', $employee->emp_code)->first() ? $emps_percentage->where('emp_code', $employee->emp_code)->first()['emp_percentage'] : null }}"
                                                   class="emps_percentage block text-gray-900 w-full text-center"
                                                   style="@error('emps_percentage') border: solid 1px #fda4af; @enderror" @if(count($dept_id) > 1 || \Illuminate\Support\Facades\Auth::user()->user_group->write_product_target == '0') disabled @endif>
                                        @endif
                                        @error('emps_percentage') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->calculate_all_product_target == '1')
                        @if($edit_special_product == 1)
                            <div class="mt-8 text-center w-full">
                                <button id="recalculate-btn"
                                        style="background-color: #026832;" class="btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold">
                            <span></span>
                            <span>توزيع الكل</span>
                        </span>
                                </button>
                            </div>
                        @elseif($edit_special_product == 0)
                            <div class="mt-8 text-center w-full">
                                <button id="recalculate-btn-not-special"
                                        style="background-color: #026832;" class="btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold">
                            <span></span>
                            <span>توزيع الكل</span>
                        </span>
                                </button>
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        @endif
    @endif

    <div class="notification-box flex flex-col items-center justify-center w-full z-50 mb-3">
        <!-- Notification container -->
    </div>

    @if($show_msg)
        @if(count($dept_id) == 1)
            <div id="table-container" class="overflow-x-auto w-full">
                <table id="tbl2" style="border: 2px solid black;" class="table-fixed table-container w-full border text-center">
                    <tbody class="text-sm divide-y divide-gray-100">
                        @if($results)
                            <?php
                            $vendor_type = "*";
                            ?>
                        @foreach($results[0] as $record)
                                @php $all_total_emp_tr = []; @endphp
                                @if($record['VendorNo'] != $vendor_type)
                                        <?php $vendor_type = $record['VendorNo'] ?>
                                    <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                        {{--                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>--}}
                                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">
                                            <div class="flex items-center justify-center w-full">
                                                <input id="vendor--{{ $vendor_type }}" name="vendor_id" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" @if($choose_special_product == 0) disabled @endif>
                                            </div>
                                        </td>
                                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>
                                        <td colspan="14" style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorName'] }}</td>
                                    </tr>
                                @endif
                                    <?php $vendor_type = $record['VendorNo'] ?>
                                <tr>
                                    <th colspan="15" style="border: 2px solid black; background-color: #faebd7" class="col-id-no fixed-header border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div style="margin: auto;" class="w-full text-sm text-center">
                                                <div class="flex items-center justify-center w-full">
                                                    <input id="item--{{ $record['ProductCode'] }}--{{ $record['VendorNo'] }}" name="item_id" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" @if($special_product_id->where('product_id', $record['ProductCode'])->first()) checked @endif @if($choose_special_product == 0) disabled @endif>
                                                </div>
                                            </div>
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
                                            <div class="w-full text-sm text-center">السعر</div>
                                            <div id="item-price-{{$record['ProductCode']}}" style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record['MaxDiscount'] }}</div>
                                            <div class="w-full text-sm text-center mr-5">فترة الطلب</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record['LeadTime'] }}</div>
                                            <div class="w-full text-sm text-center mr-5">فترة التوزيع</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $dist_days }}</div>
                                            <div class="w-full text-sm text-center">
                                                @if((($edit_special_product == 1 && $special_product_id->where('product_id', $record['ProductCode'])->count() > 0) || ($special_product_id->where('product_id', $record['ProductCode'])->count() == 0)) && ($write_product_target == '2') )
                                                    <button id="btn-target--{{ $record['ProductCode'] }}" class="btn-target" style="color: white; background-color: rosybrown; padding: 5px;">توزيع</button>
                                                @endif
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
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        <div class="text-sm">مجموع كمية</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        <div class="text-sm">مجموع قيمة</div>
                                    </th>
                                </tr>
                                @if(Auth::user()->user_group->write_product_target == '2' || $write_product_target == '3' || $write_product_target == "0")
                                    <tr style="background-color: #e9e9e9">
                                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                            <div class="text-xs">مستهدف الفرع</div>
                                            <div id="copy--{{ $record['ProductCode'] }}" class="copy-btn text-xs" style="text-align: -webkit-center; cursor: pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                                                </svg>
                                            </div>
                                        </th>
                                        @php
                                            $target_counter =1;
                                            $arr_tar = [];
                                            $now = \Carbon\Carbon::now();
                                            $grand_total_target = 0;
                                            $grand_emp_total_target = 0;
                                        @endphp
                                        @foreach ($current_year_list as $year_key => $year)
                                            @foreach ($year as $month_key => $month)
                                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                                    @php    $fromDate = \Carbon\Carbon::now();
                                        $toDate = \Carbon\Carbon::parse($year_key."-". $month ."-01");
                                        $diff = $fromDate->diffInMonths($toDate, false);
                                        $current = $current_target->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first();
                                        $lead_time = round((intval($record['LeadTime'])+intval($dist_days))/30);
                                        $current_month = $now->year."-".$now->month."-01";
                                        $month_days = \Carbon\Carbon::parse($year_key."-".$month."-01")->daysInMonth;
                                        $target_month = $year_key."-".$month."-".$month_days;
                                        $diff_month = Carbon\Carbon::parse($current_month)->diffInMonths($target_month, false);

                                                    @endphp
                                                    {{--                                @if($diff < 3)--}}
                                                    {{--                                        @if($toDate->lt(\Carbon\Carbon::parse('2023-07-01')))--}}
                                                    {{--                                            --}}{{--                                        {{ $current ? $current->target : $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first() }}--}}
                                                    {{--                                            {{ $current ? $current->target : "-" }}--}}
                                                    {{--                                            @php array_push($arr_tar, ($current ? $current->target : "-")); @endphp--}}
                                                    {{--                                            --}}{{--                                    <div id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$loop->iteration}}" class="w-full">{{ $current ? $current->target : "N/A" }}</div>--}}
                                                    {{--                                        @else--}}
                                                        <?php
//                                                $x = $old_targets->where('Code', '170224')->where('month', 12)->where('Year', 2023)->first()['Revision'];
//                                                dd($x);

                                                        $val = $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  floatval($current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->sum('target')) : floatval($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->count() > 0 ? ($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Revision'] > 0 ? $old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Revision']: $old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Taget']) : null);
                                                        $grand_total_target += $val == null ? 0 : $val;
//                                                $val = $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->sum('target') : ($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->count() > 0 ? number_format($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Taget']) : null);
                                                        ?>
                                                    {{--                                            <div>{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  floatval($current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->sum('target')) : floatval($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->count() > 0 ? ($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Revision'] > 0 ? $old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Revision']: $old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Taget']) : null) }}</div>--}}
                                                    {{--                                            <div>{{$record['ProductCode']}} | {{$year_key."-".$month}} | {{ $val }}</div>--}}
                                                    {{--                                        {{ \Illuminate\Support\Facades\Auth::user()->user_group->write_product_target }}--}}
                                                    <input wire:key="input-totaltarget--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{time()}}" min="0" id="totaltarget--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" type="number" class="@if($special_product_id->where('product_id', $record['ProductCode'])->first())special-item @endif total-target form-input w-full" value="{{ $val }}" @if($diff_month < $lead_time || ($edit_special_product == 0 && $special_product_id->where('product_id', $record['ProductCode'])->count() > 0) || $write_product_target == 0) disabled="disabled" @endif>
                                                    {{--                                            <input min="0" id="totaltarget--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" type="number" wire:model.defer="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}" class="total-target form-input w-full" placeholder="<?php echo $val;  ?>" @if($diff_month < $lead_time) disabled="disabled" @endif>--}}
                                                    {{--                                            <input min="0" id="totaltarget--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" type="number" wire:model.defer="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}" class="total-target form-input w-full" placeholder="{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->sum('target') : ($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->count() > 0 ? number_format($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Taget']) : "null")}}" @if($diff_month < $lead_time) disabled="disabled" @endif>--}}
                                                    {{--                                            <input min="0" id="totaltarget--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" type="number" wire:model.defer="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}" class="total-target form-input w-full" placeholder="{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->sum('target') : ($old_targets->where('ProductNo', $record['ProductNo'])/*->where('month', $month)->where('Year', $year_key)*/->count() > 0 ? number_format($old_targets->where('ProductNo', $record['ProductNo'])->where('month', $month)->where('Year', $year_key)->first()['Taget']) : "null")}}" @if($diff_month < $lead_time) disabled="disabled" @endif>--}}
                                                    {{--                                            <input min="0" id="totaltarget--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" type="number" wire:model.defer="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}" placeholder="{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="total-target form-input w-full">--}}
                                                    {{--                                        @endif--}}
                                                    @php $target_counter++; @endphp
                                                </th>
                                            @endforeach
                                        @endforeach
                                        <th style="border: 2px solid black; background-color: #fffacd;" class="border p-2">
                                            {{--                                <span id="total-col--{{ $record['ProductCode'] }}" class="total-col text-xs" style="text-align: center;">{{ $grand_total_target }}</span>--}}
                                            {{--                                    <input type="number" id="total-col--{{ $record['ProductCode'] }}" class="total-col text-xs w-16" style="text-align: center;">--}}
                                            {{--                                    </span>--}}
                                            {{--                                        <div id="total-col--{{ $record['ProductCode'] }}" class="total-col" style="text-align: center;">{{ $grand_total_target }}</div>--}}
                                            <div id="total-col--{{ $record['ProductCode'] }}" class="total-col" style="text-align: center;">{{ $grand_total_target }}</div>

                                        </th>
                                        <th style="border: 2px solid black; background-color: #fffacd;" class="border p-2">
                                            <div id="total-val-col--{{ $record['ProductCode'] }}" class="total-col" style="text-align: center;">{{ number_format($grand_total_target*$record['MaxDiscount']) }}</div>
                                        </th>
                                    </tr>
                                    @foreach($emps as $emp)

                                        <tr>
                                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                                <div class="text-sm">{{ $emp->name }}</div>
                                            </th>
                                            @php $target_counter =1; $arr_tar = []; $grand_emp_total_target = 0; @endphp
                                            @foreach ($current_year_list as $year_key => $year)
                                                @foreach ($year as $month_key => $month)
                                                    @php    $fromDate = \Carbon\Carbon::now();
                                        $toDate = \Carbon\Carbon::parse($year_key."-". $month ."-01");
                                        $diff = $fromDate->diffInMonths($toDate, false);
                                        $current = $current_target->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first();

                                        $month_days = \Carbon\Carbon::parse($year_key."-".$month."-01")->daysInMonth;
                                        $target_month = $year_key."-".$month."-".$month_days;
                                        $current_month = $now->year."-".$now->month."-01";
                                        $lead_time = round((intval($record['LeadTime'])+intval($dist_days))/30);
                                        $diff_month = Carbon\Carbon::parse($current_month)->diffInMonths($target_month, false);
                                        $grand_emp_total_target += $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : 0;

                                                    @endphp
                                                    <th style="border: 2px solid black; z-index: 10; @if($diff_month < $lead_time) background-color: #e9e9e9; @endif" class="border p-2">
                                                        {{--                                @if($diff < 3)--}}
                                                        {{--                                            @if($toDate->lt(\Carbon\Carbon::parse('2023-07-01')))--}}
                                                        {{--                                                --}}{{--                                        {{ $current ? $current->target : $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first() }}--}}
                                                        {{--                                                {{ $current ? $current->target : "-" }}--}}
                                                        {{--                                                @php array_push($arr_tar, ($current ? $current->target : "-")); @endphp--}}
                                                        {{--                                                --}}{{--                                    <div id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$loop->iteration}}" class="w-full">{{ $current ? $current->target : "N/A" }}</div>--}}
                                                        {{--                                            @else--}}
{{--                                                        <div wire:key="key-{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}--{{ time() }}">--}}
                                                            {{--                                                    <div>{{ $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] }}</div>--}}
                                                            {{--                                                    <input wire:key="key-{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" value="0" min="0" id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" type="number" wire:model.defer="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}.{{$emp->emp_code}}" placeholder="{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="form-input w-full">--}}
                                                            {{--                                                    <input wire:key="key-{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" value="0" min="0" id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" type="number" wire:model.defer="emp_target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$emp->emp_code}}" placeholder="{{ $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="target form-input w-full" @if($diff_month < $lead_time) disabled="disabled" @endif>--}}
                                                            <input wire:key="emp-target-target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}--{{ time() }}" min="0" id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" type="number" value="{{ $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="target form-input w-full" @if($diff_month < $lead_time || ($edit_special_product == 0 && $special_product_id->where('product_id', $record['ProductCode'])->first()) || $write_product_target == "0") disabled="disabled" @endif>
{{--                                                        </div>--}}
                                                        {{--                                            @endif--}}
                                                        @php $target_counter++; @endphp
                                                    </th>
                                                @endforeach
                                            @endforeach
                                            <th style="border: 2px solid black; z-index: 10; background-color: #fffacd;" class="border p-2">
                                                <div wire:key="total-emp--{{$record['ProductCode']}}--{{$emp->emp_code}}" id="total-emp--{{$record['ProductCode']}}--{{$emp->emp_code}}" class="text-sm">{{ $grand_emp_total_target }}</div>
                                                {{--                                            <div wire:key="total-emp--{{time()}}" id="total-emp--{{$record['ProductCode']}}--{{$emp->emp_code}}" class="text-sm">{{ $grand_emp_total_target }}</div>--}}
                                                {{--                                            <span>{{ $grand_emp_total_target }}</span>--}}
                                            </th>
                                            <th style="border: 2px solid black; z-index: 10; background-color: #fffacd;" class="border p-2">
                                                <div wire:key="total-emp-val--{{$record['ProductCode']}}--{{$emp->emp_code}}" id="total-emp-val--{{$record['ProductCode']}}--{{$emp->emp_code}}" class="text-sm">{{ number_format($grand_emp_total_target*$record['MaxDiscount']) }}</div>
                                            </th>
                                        </tr>
                                            <?php
                                            if (!array_key_exists($emp->emp_code, $all_total_emp_sales)) {
                                                $all_total_emp_sales[$emp->emp_code] = $grand_emp_total_target*$record['MaxDiscount'];
                                            }
                                            else {
                                                $all_total_emp_sales[$emp->emp_code] += $grand_emp_total_target*$record['MaxDiscount'];
                                            }

                                            if (!array_key_exists($emp->emp_code, $all_total_emp_tr)) {
                                                $all_total_emp_tr[$emp->emp_code] = $grand_emp_total_target;
                                            }
                                            else {
                                                $all_total_emp_tr[$emp->emp_code] += $grand_emp_total_target;
                                            }
                                            ?>
                                    @endforeach
                                @else
                                    @php
                                        $target_counter =1;
                                        $arr_tar = [];
                                        $now = \Carbon\Carbon::now();
                                        $grand_total_target = 0;
                                        $grand_emp_total_target = 0;
                                        $fromDate = \Carbon\Carbon::now();
                                        $toDate = \Carbon\Carbon::parse($year_key."-". $month ."-01");
                                        $diff = $fromDate->diffInMonths($toDate, false);
                                        $current = $current_target->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first();
                                        $lead_time = round((intval($record['LeadTime'])+intval($dist_days))/30);
                                        $current_month = $now->year."-".$now->month."-01";
                                        $month_days = \Carbon\Carbon::parse($year_key."-".$month."-01")->daysInMonth;
                                        $target_month = $year_key."-".$month."-".$month_days;
                                        $diff_month = Carbon\Carbon::parse($current_month)->diffInMonths($target_month, false);

                                    @endphp
                                    @foreach($emps as $emp)
                                        <tr>
                                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                                <div class="text-sm">{{ $emp->name }}</div>
                                            </th>
                                            @php $target_counter =1; $arr_tar = []; $grand_emp_total_target = 0; @endphp
                                            @foreach ($current_year_list as $year_key => $year)
                                                @foreach ($year as $month_key => $month)
                                                    @php    $fromDate = \Carbon\Carbon::now();
                                        $toDate = \Carbon\Carbon::parse($year_key."-". $month ."-01");
                                        $diff = $fromDate->diffInMonths($toDate, false);
                                        $current = $current_target->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first();

                                        $month_days = \Carbon\Carbon::parse($year_key."-".$month."-01")->daysInMonth;
                                        $target_month = $year_key."-".$month."-".$month_days;
                                        $current_month = $now->year."-".$now->month."-01";
                                        $lead_time = round((intval($record['LeadTime'])+intval($dist_days))/30);
                                        $diff_month = Carbon\Carbon::parse($current_month)->diffInMonths($target_month, false);
                                        $grand_emp_total_target += $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : 0;

                                                    @endphp
                                                    <th style="border: 2px solid black; z-index: 10; @if($diff_month < $lead_time) background-color: #e9e9e9; @endif" class="border p-2">
                                                        {{--                                @if($diff < 3)--}}
                                                        {{--                                            @if($toDate->lt(\Carbon\Carbon::parse('2023-07-01')))--}}
                                                        {{--                                                --}}{{--                                        {{ $current ? $current->target : $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first() }}--}}
                                                        {{--                                                {{ $current ? $current->target : "-" }}--}}
                                                        {{--                                                @php array_push($arr_tar, ($current ? $current->target : "-")); @endphp--}}
                                                        {{--                                                --}}{{--                                    <div id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$loop->iteration}}" class="w-full">{{ $current ? $current->target : "N/A" }}</div>--}}
                                                        {{--                                            @else--}}
{{--                                                        <div>--}}
                                                            {{--                                                    <div>{{ $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] }}</div>--}}
                                                            {{--                                                    <input wire:key="key-{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" value="0" min="0" id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" type="number" wire:model.defer="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}.{{$emp->emp_code}}" placeholder="{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="form-input w-full">--}}
                                                            {{--                                                    <input wire:key="key-{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" value="0" min="0" id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" type="number" wire:model.defer="emp_target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$emp->emp_code}}" placeholder="{{ $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="target form-input w-full" @if($diff_month < $lead_time) disabled="disabled" @endif>--}}
                                                            <input wire:key="emptarget--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" min="0" id="emptarget--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}--{{$emp->emp_code}}" type="number" value="{{ $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('user_id', $user_ids[$emp->emp_code])->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="emptarget form-input w-full" @if($diff_month < $lead_time || ($edit_special_product == 0 && $special_product_id->where('product_id', $record['ProductCode'])->first()) || $write_product_target == '0') disabled="disabled" @endif>
{{--                                                        </div>--}}
                                                        {{--                                            @endif--}}
                                                        @php $target_counter++; @endphp
                                                    </th>
                                                @endforeach
                                            @endforeach
                                            <th style="border: 2px solid black; z-index: 10; background-color: #fffacd;" class="border p-2">
                                                <div wire:key="total-emptarget--{{$record['ProductCode']}}--{{$emp->emp_code}}" id="total-emp--{{$record['ProductCode']}}--{{$emp->emp_code}}" class="text-sm">{{ $grand_emp_total_target }}</div>
                                            </th>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        <div class="text-xs">مبيعات تاريخية</div>
                                    </th>
                                    {{--                    @foreach ($list as $year_key => $year)--}}
                                    {{--                        @foreach ($year as $month)--}}
                                    @php $sales = []; @endphp
                                    @php $target_counter =1; @endphp
                                    {{--                        @for($i = 1; $i <= 12; $i++)--}}
                                    @foreach ($current_year_list as $year_key => $year)
                                        @foreach ($year as $month_key => $month)
                                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                                <div id="sales--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" class="text-sm">{{ number_format($record['month'.$target_counter], 0, '', '') }}</div>
                                                @php array_push($sales, $record['month'.$target_counter]); @endphp
                                            </th>
                                            @php $target_counter++; @endphp
                                        @endforeach
                                    @endforeach
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fffacd;" class="border p-2">
                                        {{--                                    <div id="total-sales-emp--{{$record['ProductCode']}}" class="text-sm">{{ number_format(array_sum($sales)) }}</div>--}}
                                        {{ number_format(array_sum($sales)) }}
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fffacd;" class="border p-2">
                                        <div id="total-sales-emp--{{$record['ProductCode']}}" class="text-sm">{{ number_format(array_sum($sales)*$record['MaxDiscount']) }}</div>
                                    </th>
                                    <?php $total_historical += array_sum($sales)*$record['MaxDiscount']; ?>
                                    {{--                        @endfor--}}
                                    {{--{{--                        @endforeach--}}
                                    {{--                    @endforeach--}}
                                </tr>
                                <tr>
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        <div class="text-sm">الفرق %</div>
                                        <div id="historicalsales--{{ $record['ProductCode'] }}" class="historicalsales-btn text-xs" style="text-align: -webkit-center; cursor: pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m0-3l-3-3m0 0l-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75" />
                                            </svg>
                                        </div>
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
            //                                        $diff_value = round((floatval($current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->sum('target') : 0) / floatval($record['month'.$target_counter2]))*100);
                                                    $old_diff_value = floatval($record['month'.$target_counter2]) == 0 ? 0 : round((floatval($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->count() > 0 ? ($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Taget'] != 0 ? number_format($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Taget'], 0, '', ''): number_format($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Revision'], 0, '', '')) : 0 )/ floatval($record['month'.$target_counter2]))*100)-100;
                                                    $diff_value = round((floatval($record['month'.$target_counter2]) == 0 ? 0 :floatval($current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ? $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->sum('target') : 0) / floatval($record['month'.$target_counter2]))*100)-100;
            //                                        echo $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0? $diff_value: null;

                                                @endphp
                                                {{--                                    @if($diff < 3)--}}
                                                {{--                                    @if($toDate->lt(\Carbon\Carbon::parse('2023-07-01')))--}}

                                                {{--                                        @if(array_key_exists($target_counter2, $arr_tar) && is_numeric($arr_tar[$target_counter2-1]))--}}
                                                {{--                                            @php $res = $sales[$target_counter2-1] == 0? 0 :  number_format(floatval($arr_tar[$target_counter2-1]) / floatval($sales[$target_counter2-1])*100, '0', '', '');  @endphp--}}
                                                {{--                                            <div style="@if($res > 0) color: #6ab200 @else color: #fd162c @endif">{{ $res }}</div>--}}
                                                {{--                                        @else--}}
                                                {{--                                            <div style="color: #fd162c">-</div>--}}
                                                {{--                                        @endif--}}
                                                {{--                                    @else--}}
                                                {{--                                        <span>{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->sum('target') }}</span>--}}
                                                {{--                                        <span wire:ignore id="diff--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter2}}" class="w-full"></span>--}}
                                                {{--                                        ($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->count() > 0 ? ($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Taget'] != 0 ? number_format($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Taget']): number_format($old_targets->where('Code', $record['ProductCode'])->where('month', $month)->where('Year', $year_key)->first()['Revision'])) : null);--}}
                                                <input wire:key="diff--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter2}}--{{time()}}" style="text-align: center; font-weight: bold;" type="text" wire:ignore id="diff--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter2}}" placeholder="{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0? $diff_value: $old_diff_value}}" class="w-full form-input" disabled="disabled">
                                                {{--                                    @endif--}}
                                                {{--                                <span id="x" class="w-full">--}}

                                                {{--                                <input type="number" wire:model="diff.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$loop->iteration}}" value="{{ $record['month'. $loop->iteration] }}" class="form-input w-full" readonly>--}}
                                            </th>
                                            {{--                    @endfor--}}
                                            @php $target_counter2++; @endphp
                                        @endforeach
                                    @endforeach
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #fffacd;" class="border p-2">
                                        {{--                                                                <div>good: {{ array_sum($sales) }} - total: {{ array_sum($all_total_emp_tr) }}</div>--}}
                                        <div wire:key="total-diff-emp--{{$record['ProductCode']}}--{{time()}}" id="total-diff-emp--{{$record['ProductCode']}}" class="text-sm">{{ array_sum($sales) == 0 ? "*" : number_format(((array_sum($all_total_emp_tr)/array_sum($sales))*100)-100, 0, '', '') }}</div>
                                    </th>
                                </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div id="summary-container" style="background-color: #f5f5f5" class="overflow-x-auto w-full p-6 mt-4">
                <div class="text-2xl bold mb-4">المجاميع</div>
                <table id="tbl3" style="border: 2px solid black;" class="table-fixed table-container w-full border text-center">
                    <tbody class="text-sm divide-y divide-gray-100">
                        <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                            {{--                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>--}}
                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">الموظف</td>
                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">مجموع القيم</td>
                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">الفرق</td>
                        </tr>
                        <?php $total_sum_val = 0; ?>
                    @foreach($emps as $emp)
                        <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                            {{--                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>--}}
                            <td style="border: 2px solid black;background-color: #FFFFFF" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $emp->name }}</td>
                            <td style="border: 2px solid black;background-color: #fffacd" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($all_total_emp_sales[$emp->emp_code]) }}</td>
                                <?php $single_emp_diff = $total_historical == 0? 0 :  number_format((($all_total_emp_sales[$emp->emp_code]/$total_historical)*100)-100); ?>
                            <td style="border: 2px solid black;@if($single_emp_diff > 0) background-color: #e8ffdf @else background-color: #ffeded @endif" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $single_emp_diff }}</td>
                            <?php $total_sum_val += $all_total_emp_sales[$emp->emp_code]; ?>
                        </tr>
                    @endforeach
                    <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                        {{--                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>--}}
                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">المجموع</td>
                        <td style="border: 2px solid black;background-color: #fffacd" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($total_sum_val) }}</td>
                        <?php $emp_diff = $total_historical == 0? 0 :  number_format((($total_sum_val/$total_historical)*100)-100); ?>
                        <td style="border: 2px solid black; @if($emp_diff > 0) background-color: #e8ffdf @else background-color: #ffeded @endif" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $emp_diff }}</td>
                    </tr>
                    <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                        {{--                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>--}}
                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">مجموع المبيعات التاريخية</td>
                        <td colspan="2" style="border: 2px solid black; background-color: #FFFFFF;" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($total_historical) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @elseif(count($dept_id) > 1)
            <div id="table-container2" class="overflow-x-auto w-full">
                <table id="tbl3" style="border: 2px solid black;" class="table-container w-full border text-center">
                    <tbody class="text-sm divide-y divide-gray-100">
                    {{--                    @if($results)--}}
                        <?php
                        $vendor_id = "*";
                        ?>

                    @foreach($items as $record)

                        <div>
                            @if($record['VendorNo'] != $vendor_id)
                                    <?php $vendor_id = $record['VendorNo'] ?>
                                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>
                                    <td colspan="29" style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorName'] }}</td>
                                </tr>
                            @endif
                                <?php $vendor_id = $record['VendorNo'] ?>
                            <tr>
                                <th colspan="30" style="border: 2px solid black; background-color: #faebd7" class="col-id-no fixed-header border p-2 whitespace-nowrap">
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
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th rowspan="2" style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">الشهر</div>
                                </th>
                                @foreach ($current_year_list as $year_key => $year)
                                    @foreach ($year as $month)
                                        <th colspan="2" style="border: 2px solid black; z-index: 10; @if($loop->iteration%2 == 0) background-color: #e4fdf7; @else background-color: #fafad2; @endif" class="border p-2">
                                            <div class="text-sm">{{ $year_key."-".$month }}</div>
                                        </th>
                                    @endforeach
                                @endforeach
                                <th rowspan="2" style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">السعر</div>
                                </th>
                                <th colspan="2" style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">مجموع كمية</div>
                                </th>
                                <th colspan="2" style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">مجموع قيمة</div>
                                </th>
                            </tr>

                            <tr>

                                @foreach ($current_year_list as $year_key => $year)
                                    @foreach ($year as $month)
                                        <th style="border: 2px solid black; z-index: 10; @if($loop->iteration%2 == 0) background-color: #e4fdf7; @else background-color: #fafad2; @endif" class="border p-2">
                                            <div class="text-sm">S</div>
                                        </th>
                                        <th style="border: 2px solid black; z-index: 10; @if($loop->iteration%2 == 0) background-color: #e4fdf7; @else background-color: #fafad2; @endif" class="border p-2">
                                            <div class="text-sm">F</div>
                                        </th>
                                    @endforeach
                                @endforeach
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">S</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">F</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">S</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">F</div>
                                </th>
                            </tr>
                                <?php $new_tr = []; ?>
                                <?php $new_sales = []; ?>
                                <?php $total_dept_new_tr = 0; ?>
                                <?php $total_dept_new_sales = 0; ?>
                                <?php $old_tr = []; ?>
                                <?php $total_old_tr = 0; ?>
                                <?php $total_s1 = 0; $total_f1 = 0; ?>
                                <?php $total_s2 = 0; $total_f2 = 0;?>
                                <?php $total_s3 = 0; $total_f3 = 0;?>
                                <?php $total_s4 = 0; $total_f4 = 0;?>
                                <?php $total_s5 = 0; $total_f5 = 0;?>
                                <?php $total_s6 = 0; $total_f6 = 0;?>
                                <?php $total_s7 = 0; $total_f7 = 0;?>
                                <?php $total_s8 = 0; $total_f8 = 0;?>
                                <?php $total_s9 = 0; $total_f9 = 0;?>
                                <?php $total_s10 = 0; $total_f10 = 0;?>
                                <?php $total_s11 = 0; $total_f11 = 0;?>
                                <?php $total_s12 = 0; $total_f12 = 0;?>
                                <?php $total_dept_s_qty = 0; $total_dept_f_qty = 0;?>
                                <?php $total_dept_s_value = 0; $total_dept_f_value = 0;?>
                                <?php //$depts =  $results->where('ProductCode', $record['ProductCode']); ?>
                                <?php $depts =  $dept_id ?>

                            @foreach($depts as $dept)
                                    <?php $dept_sales = 0; $dept_forecast = 0; ?>
                                <tr class="department">
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        {{--                                            <div class="text-sm">{{ $dept->Department }}</div>--}}
                                        @if($dept == '3')
                                            <div class="text-sm">الاحساء</div>
                                        @elseif($dept == '10')
                                            <div class="text-sm">جدة</div>
                                        @elseif($dept == '7')
                                            <div class="text-sm">الرياض</div>
                                        @elseif($dept == '13')
                                            <div class="text-sm">وادي الدواسر</div>
                                        @elseif($dept == '4')
                                            <div class="text-sm">الجوف</div>
                                        @elseif($dept == '6')
                                            <div class="text-sm">الدمام</div>
                                        @elseif($dept == '5')
                                            <div class="text-sm">الخرج</div>
                                        @elseif($dept == '12')
                                            <div class="text-sm">نجران</div>
                                        @elseif($dept == '11')
                                            <div class="text-sm">حائل</div>
                                        @elseif($dept == '9')
                                            <div class="text-sm">تبوك</div>
                                        @elseif($dept == '8')
                                            <div class="text-sm">القصيم</div>
                                        @elseif($dept == '505')
                                            <div class="text-sm">ساجر</div>
                                        @else
                                            <div class="text-sm">{{ $dept }}</div>
                                        @endif
                                    </th>
                                        <?php $month_counter = 1; ?>
                                    @foreach ($current_year_list as $year_key => $year)
                                        @foreach ($year as $month)
                                            <th style="border: 2px solid black; z-index: 10; @if($loop->iteration%2 == 0) background-color: #e4fdf7; @else background-color: #fafad2; @endif" class="border">
                                                {{--                                <input id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$loop->iteration}}" type="number" wire:model="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$loop->iteration}}" class="form-input w-full">--}}

                                                    <?php
//                                                        $new_result = key_exists('ProductCode', $record) ? $results->where('ProductCode', $record['ProductCode'])->where('Department', $dept->Department)->first(): 0;
                                                    $new_result = key_exists('ProductCode', $record) ? $results->where('ProductCode', $record['ProductCode'])->where('Department', $dept)->first(): 0;
                                                    $month_num = "month".$month_counter;
                                                    ?>
                                                    <?php //$new_result = key_exists('ProductCode', $record) ? $new_targets->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->where('branch', $dept_id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->first(): 0; ?>
                                                {{--                                <div class="text-sm">{{ dd($record['ProductCode']) }}</div>--}}
                                                <div class="text-sm">{{ $new_result ? number_format($new_result->$month_num) : 0  }}</div>
                                                    <?php array_push($new_sales, ($new_result ? $new_result->$month_num : 0) ) ?>
                                                    <?php $dept_sales += ($new_result ? $new_result->$month_num : 0); ?>
                                                @php $total_dept_new_sales += $new_result ? $new_result->$month_num : 0; @endphp
                                                @switch($month_counter)
                                                    @case(1)
                                                        @php $total_s1 = $total_s1 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(2)
                                                        @php $total_s2 = $total_s2 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(3)
                                                        @php $total_s3 = $total_s3 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(4)
                                                        @php $total_s4 = $total_s4 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(5)
                                                        @php $total_s5 = $total_s5 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(6)
                                                        @php $total_s6 = $total_s6 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(7)
                                                        @php $total_s7 = $total_s7 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(8)
                                                        @php $total_s8 = $total_s8 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(9)
                                                        @php $total_s9 = $total_s9 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(10)
                                                        @php $total_s10 = $total_s10 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(11)
                                                        @php $total_s11 = $total_s11 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                    @case(12)
                                                        @php $total_s12 = $total_s12 + ($new_result ? $new_result->$month_num : 0); @endphp
                                                        @break
                                                @endswitch

                                                {{--                                <div class="text-sm">{{ $new_targets->where('product_id', '220020')->where('month', $month)->where('year', $year_key)->where('branch', $dept_id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->first() ? $new_targets->where('product_id', '220020')->where('month', $month)->where('year', $year_key)->where('branch', $dept_id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->first()->target : 0 }}</div>--}}
                                                {{--                                <div class="text-sm">{{ $new_targets->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->where('branch', $dept_id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->first() }}</div>--}}
                                            </th>
                                            <th style="border: 2px solid black; z-index: 10; @if($loop->iteration%2 == 0) background-color: #e4fdf7; @else background-color: #fafad2; @endif" class="border">
                                                {{--                                <input id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$loop->iteration}}" type="number" wire:model="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$loop->iteration}}" class="form-input w-full">--}}

                                                    <?php
//                                                        $new_result = key_exists('ProductCode', $record) ? $new_targets->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->where('branch', $dept->Department)->first(): 0;
                                                    $new_result = key_exists('ProductCode', $record) ? $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->where('branch', $dept)->first(): 0;
                                                    ?>
                                                    <?php //$new_result = key_exists('ProductCode', $record) ? $new_targets->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->where('branch', $dept_id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->first(): 0; ?>
                                                {{--                                <div class="text-sm">{{ dd($record['ProductCode']) }}</div>--}}
                                                <div class="text-sm">{{ $new_result ? $new_result->target : 0  }}</div>
                                                    <?php array_push($new_tr, ($new_result ? $new_result->target : 0) ) ?>
                                                    <?php $dept_forecast += ($new_result ? $new_result->target : 0); ?>

                                                @php $total_dept_new_tr += ($new_result ? $new_result->target : 0); @endphp
                                                @switch($month_counter)
                                                    @case(1)
                                                        @php $total_f1 = $total_f1 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(2)
                                                        @php $total_f2 = $total_f2 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(3)
                                                        @php $total_f3 = $total_f3 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(4)
                                                        @php $total_f4 = $total_f4 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(5)
                                                        @php $total_f5 = $total_f5 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(6)
                                                        @php $total_f6 = $total_f6 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(7)
                                                        @php $total_f7 = $total_f7 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(8)
                                                        @php $total_f8 = $total_f8 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(9)
                                                        @php $total_f9 = $total_f9 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(10)
                                                        @php $total_f10 = $total_f10 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(11)
                                                        @php $total_f11 = $total_f11 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                    @case(12)
                                                        @php $total_f12 = $total_f12 + ($new_result ? $new_result->target : 0); @endphp
                                                        @break
                                                @endswitch
                                            </th>
                                            @php $month_counter++; @endphp
                                        @endforeach
                                    @endforeach
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        <div class="text-sm">{{ $record['MaxDiscount'] }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        <div class="text-sm">{{ number_format($dept_sales) }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        <div class="text-sm">{{ number_format($dept_forecast) }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        <div class="text-sm">{{ number_format($dept_sales*$record['MaxDiscount']) }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                        <div class="text-sm">{{ number_format($dept_forecast*$record['MaxDiscount']) }}</div>
                                    </th>
                                </tr>
                                @php
                                    $total_dept_s_qty = $total_dept_s_qty + $dept_sales;
                                    $total_dept_f_qty = $total_dept_f_qty + $dept_forecast;

                                    $total_dept_s_value = $total_dept_s_value + ($dept_sales*$record['MaxDiscount']);
                                    $total_dept_f_value = $total_dept_f_value + ($dept_forecast*$record['MaxDiscount']);

                                    if (!array_key_exists($dept, $all_total_dept_sales)) {
                                        $all_total_dept_sales[$dept] = $total_dept_s_value;
                                    }
                                    else {
                                        $all_total_dept_sales[$dept] += $total_dept_s_value;
                                    }

                                    if (!array_key_exists($dept, $all_total_dept_tr)) {
                                        $all_total_dept_tr[$dept] = $total_dept_f_value;
                                    }
                                    else {
                                        $all_total_dept_tr[$dept] += $total_dept_f_value;
                                    }

                                @endphp
                            @endforeach
                            <tr>
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">مجموع الفرع</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_s1 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_f1 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_s2 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_f2 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_s3 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_f3 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_s4 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_f4 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_s5 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_f5 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_s6 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_f6 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_s7 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_f7 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_s8 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_f8 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_s9 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_f9 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_s10 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_f10 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_s11 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                    <div class="text-sm">{{ $total_f11 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_s12 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                    <div class="text-sm">{{ $total_f12 }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10;" class="border p-2">
                                    <div class="text-sm">{{ $record['MaxDiscount'] }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10;" class="border p-2">
                                    <div class="text-sm">{{ number_format($total_dept_s_qty) }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10;" class="border p-2">
                                    <div class="text-sm">{{ number_format($total_dept_f_qty) }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">{{ number_format($total_dept_s_value) }}</div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10;" class="border p-2">
                                    <div class="text-sm">{{ number_format($total_dept_f_value) }}</div>
                                </th>
                            </tr>


                            <tr>
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">الفرق %</div>
                                </th>
                                @php $a = floatval($total_f1) == 0 ? 0 : ceil((($total_s1/$total_f1)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm">{{ floatval($total_f1) == 0 ? 0 : ceil((($total_s1/$total_f1)*100) - 100)}}</div>
                                </th>
                                @php $a = floatval($total_f2) == 0 ? 0 : ceil((($total_s2/$total_f2)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm">{{ floatval($total_f2) == 0 ? 0 : ceil((($total_s2/$total_f2)*100) - 100)}}</div>
                                </th>
                                @php $a = floatval($total_f3) == 0 ? 0 : ceil((($total_s3/$total_f3)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm"><div class="text-sm">{{ floatval($total_f3) == 0 ? 0 : ceil((($total_s3/$total_f3)*100) - 100)}}</div></div>
                                </th>
                                @php $a = floatval($total_f4) == 0 ? 0 : ceil((($total_s4/$total_f4)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm"><div class="text-sm">{{ floatval($total_f4) == 0 ? 0 : ceil((($total_s4/$total_f4)*100) - 100)}}</div></div>
                                </th>
                                @php $a = floatval($total_f5) == 0 ? 0 : ceil((($total_s5/$total_f5)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm"><div class="text-sm">{{ floatval($total_f5) == 0 ? 0 : ceil((($total_s5/$total_f5)*100) - 100)}}</div></div>
                                </th>
                                @php $a = floatval($total_f6) == 0 ? 0 : ceil((($total_s6/$total_f6)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm"><div class="text-sm">{{ floatval($total_f6) == 0 ? 0 : ceil((($total_s6/$total_f6)*100) - 100)}}</div></div>
                                </th>
                                @php $a = floatval($total_f7) == 0 ? 0 : ceil((($total_s7/$total_f7)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm"><div class="text-sm">{{ floatval($total_f7) == 0 ? 0 : ceil((($total_s7/$total_f7)*100) - 100)}}</div></div>
                                </th>
                                @php $a = floatval($total_f8) == 0 ? 0 : ceil((($total_s8/$total_f8)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm"><div class="text-sm">{{ floatval($total_f8) == 0 ? 0 : ceil((($total_s8/$total_f8)*100) - 100)}}</div></div>
                                </th>
                                @php $a = floatval($total_f9) == 0 ? 0 : ceil((($total_s9/$total_f9)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm"><div class="text-sm">{{ floatval($total_f9) == 0 ? 0 : ceil((($total_s9/$total_f9)*100) - 100)}}</div></div>
                                </th>
                                @php $a = floatval($total_f10) == 0 ? 0 : ceil((($total_s10/$total_f10)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm"><div class="text-sm">{{ floatval($total_f10) == 0 ? 0 : ceil((($total_s10/$total_f10)*100) - 100)}}</div></div>
                                </th>
                                @php $a = floatval($total_f11) == 0 ? 0 : ceil((($total_s11/$total_f11)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm"><div class="text-sm">{{ floatval($total_f11) == 0 ? 0 : ceil((($total_s11/$total_f11)*100) - 100)}}</div></div>
                                </th>
                                @php $a = floatval($total_f12) == 0 ? 0 : ceil((($total_s12/$total_f12)*100) - 100) @endphp
                                <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                    <div class="text-sm"><div class="text-sm">{{ floatval($total_f12) == 0 ? 0 : ceil((($total_s12/$total_f12)*100) - 100)}}</div></div>
                                </th>
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">{{ $record['MaxDiscount'] }}</div>
                                </th>
                                @php $a = floatval($total_dept_f_qty) == 0 ? 0 : ceil((($total_dept_s_qty/$total_dept_f_qty)*100) - 100) @endphp
                                <th colspan="4" style="border: 2px solid black; z-index: 10;" class="border p-2">
                                    <div class="text-sm">{{ floatval($total_dept_f_qty) == 0 ? 0 : ceil((($total_dept_s_qty/$total_dept_f_qty)*100) - 100)}}</div>
                                </th>
                                @php //$a = floatval($total_f_value) == 0 ? 0 : ceil((($total_s_value/$total_f_value)*100) - 100) @endphp
                                {{--                                <th colspan="2" style="border: 2px solid black; z-index: 10;" class="border p-2">--}}
                                {{--                                    <div class="text-sm">{{ floatval($total_f_value) == 0 ? 0 : ceil((($total_s_value/$total_f_value)*100) - 100)}}</div>--}}
                                {{--                                </th>--}}
                            </tr>
                        </div>
                    @endforeach
                    {{--                    @else--}}
                    {{--                        <div class="w-full p-4 mt-4 text-center bold" style="border: 1px solid; background-color: #ffecec; color: black;">لا يوجد مستهدفات لهذا المستخدم في هذه الشهور ..</div>--}}
                    {{--                    @endif--}}
                    </tbody>
                </table>
            </div>
            <div id="summary-container2" style="background-color: #f5f5f5" class="overflow-x-auto w-full p-6 mt-4">
                <div class="text-2xl bold mb-4">مجاميع القيم</div>
                <table id="tbl3" style="border: 2px solid black;" class="table-fixed table-container w-full border text-center">
                    <tbody class="text-sm divide-y divide-gray-100">
                    <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                        {{--                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>--}}
                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">الموظف</td>
                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">S</td>
                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">F</td>
                    </tr>
                        <?php $total_sum_val = 0; ?>
                    @foreach($depts as $dept)
                        <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                            {{--                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>--}}
                            <td style="border: 2px solid black;background-color: #FFFFFF" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $dept }}</td>
                            <td style="border: 2px solid black;background-color: #fffacd" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $all_total_dept_sales[$dept] }}</td>
                            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $all_total_dept_tr[$dept] }}</td>
                        </tr>
                    @endforeach
{{--                    <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">--}}
{{--                        --}}{{--                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>--}}
{{--                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">المجموع</td>--}}
{{--                        <td style="border: 2px solid black;background-color: #fffacd" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($total_sum_val) }}</td>--}}
{{--                            <?php $emp_diff = $total_historical == 0? 0 :  number_format((($total_sum_val/$total_historical)*100)-100); ?>--}}
{{--                        <td style="border: 2px solid black; @if($emp_diff > 0) background-color: #e8ffdf @else background-color: #ffeded @endif" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $emp_diff }}</td>--}}
{{--                    </tr>--}}
{{--                    <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">--}}
{{--                        --}}{{--                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>--}}
{{--                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">مجموع المبيعات التاريخية</td>--}}
{{--                        <td colspan="2" style="border: 2px solid black; background-color: #FFFFFF;" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($total_historical) }}</td>--}}
{{--                    </tr>--}}
                    </tbody>
                </table>
            </div>
        @endif
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var employee_code = [];
        Livewire.on('show-container', () => {
            // $('th span').empty();
            // location.reload();
            $('th span').empty();

            $('.total-target').empty();
            $('.target').empty();
            // $('input').val('');
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

            $("[id^='vendor--']").on('change', function () {

                console.log($(this).attr('id'));
                txt = $(this).attr('id');
                txt = txt.split("--");
                vendor_id = txt[1];

                if (this.checked) {
                    $(`[id ^='item--'][id $='--${vendor_id}']`).prop('checked', true);
                }
                else {
                    $(`[id ^='item--'][id $='--${vendor_id}']`).prop('checked', false);
                }
                console.log(this.checked);
                // var txt = $(this).attr('id').split('--');
                // employee_code.push(txt[1]);
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

            $('.total-target').on('focusout change', function () {
                // console.log('kaka');
                // console.log($(this).val());
                // console.log($(this).attr('id'));
                // console.log(employee_code);
                //
                var txt = $(this).attr('id');
                txt = txt.split("--");
                product_id = txt[1];
                month = txt[2];
                txt= txt[1]+"--"+txt[2]+"--"+txt[3];
                // console.log(txt);
                var target_entered = $(this).val();
                //
                var sales = $("#sales--"+txt).text();
                item_price_txt = "#item-price-"+product_id;
                // console.log("#sales--"+txt);
                // console.log('sales: ' + sales);
                // $("#diff--"+txt).text(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100));
                // $("#diff--"+txt).val(parseFloat(sales) == 0 ? "*" : target_entered ? Math.round((parseFloat(target_entered)/parseFloat(sales))*100)-100 : null);
                //
                total_count = 0;
                $(`[id ^='totaltarget--${product_id}']`).each(function () {
                    total_count = total_count + parseFloat($(this).val() ? $(this).val() : 0);
                    $("#total-col--"+product_id).text(total_count).change();
                });

                total_val = parseFloat($("#total-col--"+product_id).text().replace(/,/g, ""))*parseFloat($(item_price_txt).text());
                $("#total-val-col--"+product_id).text(total_val.toLocaleString());
                //
                //
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
                    console.log("teeest#target--"+txt+"--"+element);
                    // console.log(target_entered);
                    // $("#target--"+txt+"--"+element).val(target_entered);
                    // $("#target--"+txt+"--"+element).val(Math.round(parseFloat(target_entered)*(percent/100))).change();
                    $("#target--"+txt+"--"+element).val(Math.round(parseFloat(target_entered)*(percent/100)));
                    // target_total += Math.round(parseFloat(target_entered)*(percent/100));
                    target_total += Math.round(parseFloat(target_entered)*(percent/100));

                    total_count = 0;
                    $(`[id ^='target--${product_id}--'][id $='--${element}']`).each(function () {
                        total_count = total_count + parseFloat($(this).val() ? $(this).val() : 0);
                        console.log('total-emp:'+ "#total-emp--"+product_id+"--"+element);
                        $("#total-emp--"+product_id+"--"+element).text(total_count);
                    });


                    // @this.emp_target = Math.round(parseFloat(target_entered)*(percent/100));
                    // $("#"+value[2]+"--"+element).val(value[3]*(percent/100));
                    // document.getElementById("#"+value[2]+"--"+element).value = value[3]*(percent/100);

                    console.log(element);
                });

                // recalculate total target again
                total_target = 0;
                $(`[id ^='target--${product_id}--${month}--']`).each(function () {
                    total_target = total_target + parseFloat($(this).val() ? $(this).val() : 0);
                    // console.log('total-emp:'+ "#total-emp--"+product_id+"--"+element);
                    // $("#total-emp--"+product_id+"--"+element).text(total_count);
                });

                $(`[id ^='totaltarget--${product_id}--${month}--']`).val(total_target);
                $("#diff--"+txt).val(parseFloat(sales) == 0 ? "*" : target_entered ? Math.round((parseFloat(total_target)/parseFloat(sales))*100-100) : null);


                //
                // if(target_total > target_entered) {
                //     console.log('minus');
                //     edited_num = $("#target--"+txt+"--"+max_percent_emp).val();
                //     $("#target--"+txt+"--"+max_percent_emp).val(parseInt(edited_num)-1).change();
                // }
                // else if (target_total < target_entered) {
                //     console.log('plus');
                //     edited_num = $("#target--"+txt+"--"+max_percent_emp).val();
                //     $("#target--"+txt+"--"+max_percent_emp).val(parseInt(edited_num)+1).change();
                // }





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


                $("#total-diff-emp--"+product_id).text(parseFloat($('#total-sales-emp--'+product_id).text()) == 0 ? "*" : Math.round((parseFloat($('#total-col--'+product_id).text())/parseFloat($('#total-sales-emp--'+product_id).text()))*100)-100);
            });

            $('.target').on('focusout change', function () {
                console.log('kaka');
                console.log($(this).val());
                console.log($(this).attr('id'));
                console.log(employee_code);

                var txt = $(this).attr('id');
                txt_original = txt.split("--");
                product_id = txt_original[1];
                txt= txt_original[0]+ "--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];
                totaltarget_txt= "#totaltarget--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];
                sales_txt= "#sales--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];
                diff_txt= "#diff--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];
                item_price_txt = "#item-price-"+product_id;

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
                $(diff_txt).val(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(total)/parseFloat(sales))*100)).change();

                total_count = 0;
                console.log('holllllllla');
                $(`[id ^='target--${product_id}--'][id $='--${txt_original[4]}']`).each(function () {
                    total_count = total_count + parseFloat($(this).val() ? $(this).val() : 0);
                    console.log('total-emp:'+ "#total-emp--"+product_id+"--"+txt_original[4]);
                    $("#total-emp--"+product_id+"--"+txt_original[4]).text(total_count);
                });


                total_count = 0;
                $(`[id ^='totaltarget--${product_id}--']`).each(function () {
                    total_count = total_count + parseFloat($(this).val() ? $(this).val() : 0);
                    console.log('total-col:'+ "#total-col--"+product_id);
                    $("#total-col--"+product_id).text(total_count);
                });

                total_val = parseFloat($("#total-col--"+product_id).text().replace(/,/g, ""))*parseFloat($(item_price_txt).text());
                $("#total-val-col--"+product_id).text(total_val.toLocaleString());


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

                $("#total-diff-emp--"+product_id).text(parseFloat($('#total-sales-emp--'+product_id).text()) == 0 ? "*" : Math.round((parseFloat($('#total-col--'+product_id).text())/parseFloat($('#total-sales-emp--'+product_id).text()))*100)-100);
            });

            $('.emptarget').on('keyup change', function () {
                // console.log('kaka');
                // console.log($(this).val());
                // console.log($(this).attr('id'));
                // console.log(employee_code);

                var txt = $(this).attr('id');
                txt_original = txt.split("--");
                product_id = txt_original[1];
                txt= txt_original[0]+ "--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];
                // totaltarget_txt= "#totaltarget--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];
                sales_txt= "#sales--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];
                diff_txt= "#diff--" + txt_original[1]+"--"+txt_original[2]+"--"+txt_original[3];

                $(diff_txt).val(parseFloat($(this).val())? Math.round(((parseFloat($(this).val())/parseFloat($(sales_txt).text()))*100)-100) : 0);

                // console.log(txt);
                // console.log(totaltarget_txt);
                // // var target_entered = $(this).val();
                //
                // total = 0;
                //
                // employee_code.forEach(function (element, idx, array) {
                //     total += $("#"+txt+"--"+element).val() ? parseInt($("#"+txt+"--"+element).val()) : 0;
                //     console.log("tar:" + $("#"+txt+"--"+element).val());
                //     console.log('total:'+ total);
                //
                // });
                // $(totaltarget_txt).val(total);
                //
                // var sales = $(sales_txt).text();
                // console.log(sales_txt);
                // console.log('sales: ' + sales);
                // $(diff_txt).val(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(total)/parseFloat(sales))*100)).change();
                //
                total_count = 0;
                console.log('holllllllla');
                $(`[id ^='emptarget--${product_id}--']`).each(function () {
                    console.log('cool');
                    total_count = total_count + parseFloat($(this).val() ? $(this).val() : 0);
                    console.log('total-emp:'+ "#total-emp--"+product_id+"--");
                    console.log(total_count);
                    $("#total-emp--"+product_id+"--"+txt_original[4]).text(total_count);
                });


                // total_count = 0;
                // $(`[id ^='totaltarget--${product_id}--']`).each(function () {
                //     total_count = total_count + parseFloat($(this).val() ? $(this).val() : 0);
                //     console.log('total-col:'+ "#total-col--"+product_id);
                //     $("#total-col--"+product_id).text(total_count);
                // });

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

                console.log("KK#total-diff-emp--"+product_id);
                $("#total-diff-emp--"+product_id).text(parseFloat($('#total-sales-emp--'+product_id).text()) == 0 ? "*" : Math.round((parseFloat($('#total-emp--'+product_id+'--'+txt_original[4]).text())/parseFloat($('#total-sales-emp--'+product_id).text()))*100)-100);
            });

            $('#recalculate-btn').on('click', function () {
                Swal.fire({
                    title: "هل انت متأكد بأنك تريد توزيع مستهدف الفرع؟",
                    text: "سوف يتم توزيع مستهدف الفرع في الفترة لجميع الأصناف على جميع موظفين الفرع بناء على النسبة المدخلة في أعلى الصفحة",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#d33",
                    confirmButtonText: "موافق",
                    cancelButtonText: "إلغاء",
                }).then((result) => {
                    if (result.isConfirmed) {
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
                                    product_id = txt[1];
                                    txt= txt[1]+"--"+txt[2]+"--"+txt[3];
                                    console.log(txt);
                                    var target_entered = element.value;

                                    var sales = $("#sales--"+txt).text();
                                    console.log("#sales--"+txt);
                                    console.log('sales: ' + sales);
                                    // $("#diff--"+txt).text(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100));
                                    $("#diff--"+txt).val(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100)-100);

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

                                        total_count = 0;
                                        $(`[id ^='target--${product_id}--'][id $='--${element}']`).each(function () {
                                            total_count = total_count + parseFloat($(this).val() ? $(this).val() : 0);
                                            console.log('total-emp:'+ "#total-emp--"+product_id+"--"+element);
                                            $("#total-emp--"+product_id+"--"+element).text(total_count);
                                        });

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

                            $("#total-diff-emp--"+product_id).text(parseFloat($('#total-sales-emp--'+product_id).text()) == 0 ? "*" : Math.round((parseFloat($('#total-col--'+product_id).text())/parseFloat($('#total-sales-emp--'+product_id).text()))*100)-100);
                        }
                    }
                });
            });

            $('#recalculate-btn-not-special').on('click', function () {

                Swal.fire({
                    title: "هل انت متأكد بأنك تريد توزيع مستهدف الفرع؟",
                    text: "سوف يتم توزيع مستهدف الفرع في الفترة لجميع الأصناف على جميع موظفين الفرع بناء على النسبة المدخلة في أعلى الصفحة",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#d33",
                    confirmButtonText: "موافق",
                    cancelButtonText: "إلغاء",
                }).then((result) => {
                    if (result.isConfirmed) {
                        if($('.emps_percentage').val()) {
                            console.log($(this).attr('id'));
                            btn_id = $(this).attr('id');
                            btn_id = btn_id.split('--');
                            selector_txt = 'totaltarget--'+ btn_id[1];

                            console.log(selector_txt);
                            const elements = document.querySelectorAll(".total-target:not(.special-item)");
                            console.log(elements);

                            elements.forEach(element =>{
                                if (element.value) {

                                    // recalculating indiviuals based on the percentage
                                    var txt = element.id;
                                    txt = txt.split("--");
                                    product_id = txt[1];
                                    txt= txt[1]+"--"+txt[2]+"--"+txt[3];
                                    console.log(txt);
                                    var target_entered = element.value;

                                    var sales = $("#sales--"+txt).text();
                                    console.log("#sales--"+txt);
                                    console.log('sales: ' + sales);
                                    // $("#diff--"+txt).text(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100));
                                    $("#diff--"+txt).val(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100)-100);

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

                                        total_count = 0;
                                        $(`[id ^='target--${product_id}--'][id $='--${element}']`).each(function () {
                                            total_count = total_count + parseFloat($(this).val() ? $(this).val() : 0);
                                            console.log('total-emp:'+ "#total-emp--"+product_id+"--"+element);
                                            $("#total-emp--"+product_id+"--"+element).text(total_count);
                                        });

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

                            $("#total-diff-emp--"+product_id).text(parseFloat($('#total-sales-emp--'+product_id).text()) == 0 ? "*" : Math.round((parseFloat($('#total-col--'+product_id).text())/parseFloat($('#total-sales-emp--'+product_id).text()))*100)-100);
                        }
                    }
                });
            });

            $('.btn-target').on('click', function () {
                Swal.fire({
                    title: "هل انت متأكد بأنك تريد توزيع مستهدف الفرع؟",
                    text: "سوف يتم توزيع مستهدف الفرع في الفترة لهذا الصنف على جميع موظفين الفرع بناء على النسبة المدخلة في أعلى الصفحة",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#d33",
                    confirmButtonText: "موافق",
                    cancelButtonText: "إلغاء",
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log($(this).attr('id'));
                        btn_id = $(this).attr('id');
                        btn_id = btn_id.split('--');
                        selector_txt = 'totaltarget--'+ btn_id[1];
                        product_id = btn_id[1];
                        console.log(selector_txt);
                        // const elements = document.querySelectorAll(".total-target");
                        const elements = document.querySelectorAll("input[id^='"+selector_txt+"']");
                        console.log(elements);

                        elements.forEach(element =>{
                            if (element.value) {

                                // recalculating indiviuals based on the percentage
                                var txt = element.id;
                                txt = txt.split("--");
                                var month_txt = txt[2];
                                txt= txt[1]+"--"+txt[2]+"--"+txt[3];
                                console.log(txt);
                                var target_entered = element.value;

                                var sales = $("#sales--"+txt).text();
                                console.log("#sales--"+txt);
                                console.log('sales: ' + sales);
                                // $("#diff--"+txt).text(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100));
                                $("#diff--"+txt).val(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100)-100);

                                console.log('');

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

                                    // good
                                    $("#target--"+txt+"--"+element).val(Math.round(parseFloat(target_entered)*(percent/100)));

                                    // $(`input:not([disabled])[id ^='target--${txt}--${element}']`).val(Math.round(parseFloat(target_entered)*(percent/100)));
                                    //
                                    //
                                    // console.log('length');
                                    // console.log(month_txt);
                                    // console.log(`target--${product_id}--${month_txt}`);
                                    // // console.log($(`input:empty[id ^='target--${txt}--']`));
                                    // // console.log($(`input:disabled:empty[id ^='target--${product_id}--']`).length);
                                    // console.log($(`input:disabled:empty[id ^='target--${product_id}--${month_txt}']`).length > 0);
                                    // console.log($(`input:disabled:empty[id ^='totaltarget--${product_id}--${month_txt}']`).length > 0);
                                    // // console.log($(`input:disabled:not(input:empty)[id ^='totaltarget--${product_id}--${month_txt}']`).length > 0);
                                    // console.log($(`input:disabled:empty[id ^='target--${product_id}--${month_txt}']`));
                                    // console.log($(`input:disabled:empty[id ^='totaltarget--${product_id}--${month_txt}']`));
                                    // //console.log($(`input:disabled:empty[id ^='totaltarget--${product_id}--${month_txt}']`));
                                    //
                                    // if ($(`input:disabled:not(input:empty)[id ^='totaltarget--${product_id}--${month_txt}']`).length == 0 && $(`input:disabled:not(input:empty)[id ^='target--${product_id}--${month_txt}']`).length > 0) {
                                    //     // $(`input:disabled:empty[id ^='target--${product_id}--${month_txt}']`)
                                    //     console.log('===== cocka ======');
                                    //     console.log(`target--${txt}--${element}`);
                                    //     $(`input:disabled:empty[id ^='target--${txt}--${element}']`).val(Math.round(parseFloat(target_entered)*(percent/100)));
                                    // }

                                    // @this.emp_target = Math.round(parseFloat(target_entered)*(percent/100));
                                    // $("#"+value[2]+"--"+element).val(value[3]*(percent/100));
                                    // document.getElementById("#"+value[2]+"--"+element).value = value[3]*(percent/100);
                                    target_total += Math.round(parseFloat(target_entered)*(percent/100));

                                    total_count = 0;
                                    $(`[id ^='target--${product_id}--'][id $='--${element}']`).each(function () {
                                        total_count = total_count + parseFloat($(this).val() ? $(this).val() : 0);
                                        console.log('total-emp:'+ "#total-emp--"+product_id+"--"+element);
                                        $("#total-emp--"+product_id+"--"+element).text(total_count);
                                    });

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

                        $("#total-diff-emp--"+product_id).text(parseFloat($('#total-sales-emp--'+product_id).text()) == 0 ? "*" : Math.round((parseFloat($('#total-col--'+product_id).text())/parseFloat($('#total-sales-emp--'+product_id).text()))*100)-100);
                    }
                });
            });

            $('#test-btn').on('click', function () {
                // alert('hi');
                $("#test-btn").html('<b>الرجاء الإنتظار..</b>');

                targets = [];
                emps_percents = [];
                products_codes = [];

                const elements = document.querySelectorAll("input[id^='target--']");
                const percent_elements = document.querySelectorAll("*[class^='emps_percentage']");
                const item_elements = document.querySelectorAll("input[id^='item--']");
                console.log("============================");
                console.log(item_elements);
                // const elements = $("input[id^='target--']");
                elements.forEach(element =>{
                    if (element.value) {
                        $("#test-btn").prop('value', 'الرجاء الإنتظار..');
                        console.log(element.value);
                    //     // targets[element.id] = element.value;
                    //     targets.push(element)
                        targets.push(element.id + "|" +element.value);
                    //     targets.push({element.id: element.value});
                    }
                    // targets.push({element.id: element.value});


                });

                percent_elements.forEach(emp_element => {
                    if (emp_element.tagName.toLowerCase() == 'input') {
                        if (emp_element.value) {
                            emps_percents.push(emp_element.id + "|" + emp_element.value);
                        }
                    }
                    else {
                        if (!isNaN(parseInt($("#"+emp_element.id).text()))) {
                            emps_percents.push(emp_element.id + "|" + $("#"+emp_element.id).text());
                        }
                    }
                });

                item_elements.forEach(code => {
                    products_codes.push(code.id + "|" + code.checked);
                });
                // console.log($("input[id^='target--']"));

                // console.log(targets[0]);
                Livewire.emit('targets-entered', targets, emps_percents, products_codes);
                // Livewire.emit('targets-entered', targets);
                // console.log(targets);

            });

            $('#emptest-btn').on('click', function () {
                // alert('hi');
                $("#emptest-btn").html('<b>الرجاء الإنتظار..</b>');

                targets = [];
                emps_percents = [];
                products_codes = [];

                const elements = document.querySelectorAll("input[id^='emptarget--']");
                const percent_elements = document.querySelectorAll("*[class^='emps_percentage']");
                const item_elements = document.querySelectorAll("input[id^='item--']");
                console.log("============================");
                console.log(item_elements);
                // const elements = $("input[id^='target--']");
                elements.forEach(element =>{
                    if (element.value) {
                        $("#emptest-btn").prop('value', 'الرجاء الإنتظار..');
                        console.log(element.value);
                        //     // targets[element.id] = element.value;
                        //     targets.push(element)
                        targets.push(element.id + "|" +element.value);
                        //     targets.push({element.id: element.value});
                    }
                    // targets.push({element.id: element.value});


                });

                percent_elements.forEach(emp_element => {
                    if (emp_element.tagName.toLowerCase() == 'input') {
                        if (emp_element.value) {
                            emps_percents.push(emp_element.id + "|" + emp_element.value);
                        }
                    }
                    else {
                        if (!isNaN(parseInt($("#"+emp_element.id).text()))) {
                            emps_percents.push(emp_element.id + "|" + $("#"+emp_element.id).text());
                        }
                    }
                });

                item_elements.forEach(code => {
                    products_codes.push(code.id + "|" + code.checked);
                });
                // console.log($("input[id^='target--']"));

                // console.log(targets[0]);
                Livewire.emit('targets-entered', targets, emps_percents, products_codes);
                // Livewire.emit('targets-entered', targets);
                // console.log(targets);

            });

            $('.copy-btn').on('click', function () {

                Swal.fire({
                    title: "هل انت متأكد بنسخ مستهدف الفرع؟",
                    text: "سيقوم بنسخ القيمة الموجودة في اول شهر مفتوح، ووضعها في بقية الأشهر المفتوحة. لذلك سوف تتغير بيانات المستهدف للصنف بناءً على القيمة المنسوخة.",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#d33",
                    confirmButtonText: "موافق",
                    cancelButtonText: "إلغاء",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // start here
                        txt = $(this).attr('id').split('--');
                        product_code = txt[1];

                        //var a = $(`input:not([disabled]):first[id ^='totaltarget--${product_code}']`);
                        // var a = $(`input:not([disabled]):first[id ^='totaltarget--${product_code}']`);
                        // var x = document.querySelectorAll(`[id ^='totaltarget--${product_code}']`)
                        var value_entered = document.querySelector(`input:not([disabled])[id ^='totaltarget--${product_code}']`)
                        console.log("first:");
                        console.log(value_entered.value);

                        // var total_value = $(`[id ^='totaltarget--${product_code}'][id $='--1']`);
                        // $(`[id ^='totaltarget--${product_code}']`).val(total_value.val()).change();
                        // $(`[id ^='totaltarget--${product_code}']`).val(value_entered).change();
                        // $(`[id ^='totaltarget--${product_code}--5']`).val(value_entered).change();
                        $(`input:not([disabled])[id ^='totaltarget--${product_code}']`).val(document.querySelector(`input:not([disabled])[id ^='totaltarget--${product_code}']`).value).change();
                        // end here
                    }
                });
            });

            $('.historicalsales-btn').on('click', function () {
                txt = $(this).attr('id').split('--');
                product_code = txt[1];

                Swal.fire({
                    title: "ادخل الفرق % المطلوب",
                    text: "القيمة المدخلة هي النسبة من قيمة المبيعات التاريخية وسيتم وضعها في حقل مستهدف الفرع. (توضيح: اذا كانت النسبة مثلاً 100%، ستكون القيمة في حقل مستهدف الفرع ،الضعف، واذا كانت القيمة المدخلة هي 0%، ستكون القيمة في حقل مستهدف الفرع هي نفس قيمة المبيعات التاريخية)",
                    icon: "info",
                    input: "number",
                    inputAttributes: {
                        min: '-100',
                    },
                    showCancelButton: true,
                    cancelButtonColor: "#d33",
                    confirmButtonText: "موافق",
                    cancelButtonText: "إلغاء",
                    inputValidator: (value) => {
                        if (value < -100) {
                            return 'اقل رقم يمكنك إدخاله هو -100'
                        }
                    },
                    preConfirm: (value) => {
                        if (!value) {
                            Swal.showValidationMessage('<i class="fa fa-info-circle"></i>يجب إدخال رقم ')
                        }
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(result.value);

                        const elements = document.querySelectorAll(`input:not([disabled])[id^='totaltarget--${product_code}']`);

                        console.log(elements);

                        elements.forEach(element =>{
                            // if (element.value) {

                                // recalculating indiviuals based on the percentage
                                var txt = element.id;
                                txt = txt.split("--");
                                txt= txt[1]+"--"+txt[2]+"--"+txt[3];
                                // console.log(txt);
                                // var target_entered = element.value;

                                var sales = $("#sales--"+txt).text();
                                console.log("#sales--"+txt);
                                console.log('sales: ' + sales);
                                console.log('element: ' + element.id);
                                $("#"+element.id).val(Math.round(parseFloat(sales)*(1+(parseFloat(result.value)/100)))).change();
                                // $("#"+element.id).val(Math.round(parseFloat(sales)*(parseFloat(result.value)/100))).change();
                                // $("#diff--"+txt).text(parseFloat(sales) == 0 ? "*" : Math.round((parseFloat(target_entered)/parseFloat(sales))*100));

                                // max_percent = 0;
                                // max_percent_emp = 0;
                                // target_total = 0;
                                //
                                // employee_code.forEach(function (element, idx, array) {
                                //     percent = 0;
                                //     if (idx === array.length - 1){
                                //         percent = parseFloat($("#emp--"+element+"--readonly").text());
                                //         if (percent > max_percent) {
                                //             max_percent = percent;
                                //             max_percent_emp = element;
                                //         }
                                //     }
                                //     else {
                                //         percent = parseFloat($("#emp--"+element+"--active").val());
                                //         if (percent > max_percent) {
                                //             max_percent = percent;
                                //             max_percent_emp = element;
                                //         }
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
                                //     target_total += Math.round(parseFloat(target_entered)*(percent/100));
                                //
                                //     console.log(element);
                                // });
                                //
                                // if(target_total > target_entered) {
                                //     edited_num = $("#target--"+txt+"--"+max_percent_emp).val();
                                //     $("#target--"+txt+"--"+max_percent_emp).val(parseInt(edited_num)-1);
                                // }
                                // else if (target_total < target_entered) {
                                //     edited_num = $("#target--"+txt+"--"+max_percent_emp).val();
                                //     $("#target--"+txt+"--"+max_percent_emp).val(parseInt(edited_num)+1);
                                // }

                                //     end of recalculating indiviuals
                            // }


                        });

                    }
                });

                // var total_value = $(`[id ^='totaltarget--${product_code}'][id $='--1']`);
                // $(`[id ^='totaltarget--${product_code}']`).val(total_value.val());
            });

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
           // $('.emps_percentage').val('');
           // $('.emps_percentage_readonly').text('');
           emps_percents = [];
            // $('.notification-box').empty();

        });

        Livewire.on('clear-btn', value => {
            $('th span').empty();
                // $('.total-target').val('');
                // $('.target').val('');
                // $('.emps_percentage').val('');
                $('.emps_percentage_readonly').text('');
        });

        // $('#vendor_id').select2({
        //     dir: "rtl",
        //     dropdownCssClass: "select-font-size"
        // });
        // $('#vendor_id').on('change', function (e) {
        //     var data = $('#vendor_id').select2("val");
        //     @this.set('vendor_id', data);
        // });


        $(document).ready(function () {

            $('#dept_id').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });
            $('#cat_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });
            $('#sp_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });
            $('#vendor_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            var prev_depts = $('#dept_id').select2("val");
            var prev_cats = $('#cat_type').select2("val");
            var prev_sps = $('#sp_type').select2("val");
            var prev_vendors = $('#vendor_type').select2("val");

            $('#dept_id').on('change', function (e) {
                var data = $('#dept_id').select2("val");

                if (prev_depts && prev_depts.includes('dept_all') == false && data.includes('dept_all') == true && prev_depts.length != data.length) {
                    $("#dept_id option").prop('selected', false);
                    $("#dept_id option[value='dept_all']").prop('selected', true);

                    prev_depts = $(this).val();
                    $('#dept_id').change();
                }
                else {

                    if (prev_depts && prev_depts.length != data.length) {
                        $("#dept_id option[value='dept_all']").removeAttr('selected');
                        prev_depts = $(this).val();

                        $("#dept_id").change();
                    }
                }
            });

            $('#cat_type').on('change', function (e) {
                var data = $('#cat_type').select2("val");

                if (prev_cats && prev_cats.includes('cat_all') == false && data.includes('cat_all') == true && prev_cats.length != data.length) {
                    $("#cat_type option").prop('selected', false);
                    $("#cat_type option[value='cat_all']").prop('selected', true);

                    prev_cats = $(this).val();
                    $('#cat_type').change();
                }
                else {
                    if (prev_cats && prev_cats.length != data.length) {
                        $("#cat_type option[value='cat_all']").removeAttr('selected');
                        prev_cats = $(this).val();
                        $("#cat_type").change();
                    }
                }
            });

            $('#sp_type').on('change', function (e) {
                var data = $('#sp_type').select2("val");

                if (prev_sps && prev_sps.includes('sp_all') == false && data.includes('sp_all') == true && prev_sps.length != data.length) {
                    $("#sp_type option").prop('selected', false);
                    $("#sp_type option[value='sp_all']").prop('selected', true);

                    prev_sps = $(this).val();
                    $('#sp_type').change();
                }
                else {
                    if (prev_sps && prev_sps.length != data.length) {
                        $("#sp_type option[value='sp_all']").removeAttr('selected');
                        prev_sps = $(this).val();
                        $("#sp_type").change();
                    }
                }

            });

            $('#vendor_type').on('change', function (e) {
                var data = $('#vendor_type').select2("val");

                if (prev_vendors && prev_vendors.includes('vendor_all') == false && data.includes('vendor_all') == true && prev_vendors.length != data.length) {
                    $("#vendor_type option").prop('selected', false);
                    $("#vendor_type option[value='vendor_all']").prop('selected', true);

                    prev_vendors = $(this).val();
                    $('#vendor_type').change();
                }
                else {
                    if (prev_vendors && prev_vendors.length != data.length) {
                        $("#vendor_type option[value='vendor_all']").removeAttr('selected');
                        prev_vendors = $(this).val();
                        $("#vendor_type").change();
                    }
                }

            });

            $('#gen-report').on('click', function () {

                var dept_id = $('#dept_id').select2("val");
                var cat_type = $('#cat_type').select2("val");
                var sp_type = $('#sp_type').select2("val");
                var vendor_type = $('#vendor_type').select2("val");

                $("#gen-report").html('<b>الرجاء الإنتظار..</b>');

                Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
            });
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
            // console.log('hiii');
            // console.log($(window).scrollTop());
            const element = document.getElementById("content");
            // console.log($(this).scrollTop());
            if ( $(this).scrollTop() > amountScrolled ) {
                $('button.back-to-top').addClass('show');
                // console.log('dododo');
            } else {
                $('button.back-to-top').removeClass('show');
                // console.log('xexexexe');
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        input[type=text] {
            color: #0000ff;
        }
        input[type=number] {
            color: #0000ff;
        }

        input[type=number]:disabled {
            background-color: #e9e9e9;
            border: 0;
            color: #000000;
            text-align: center;
        }

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
