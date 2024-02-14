<div>
    <div class="mb-4">
        <a href="{{ route('create.product-target') }}">
            <span style="background-color: #0c5460; color: white; padding: 7px; border-radius: 5px;" class="text-sm bg-blue-950; cursor-pointer">
       المستهدف
        </span>
        </a>
    </div>
    <div id="branch-container" class="mb-6 mt-6">
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
                        <select id="dept_id" name="dept_id[]" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('dept_id') border: solid 1px #fda4af; @enderror">
                            <option value="dept_all" @if(in_array("dept_all", $dept_id)) selected @endif>الكل</option>
                            @if(in_array("3", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="3" @if(in_array("3", $dept_id)) selected @endif>الاحساء</option>
                            @endif
                            @if(in_array("10", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="10" @if(in_array("10", $dept_id)) selected @endif>جدة</option>
                            @endif
                            @if(in_array("7", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="7" @if(in_array("7", $dept_id)) selected @endif>الرياض</option>
                            @endif
                            @if(in_array("13", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="13" @if(in_array("13", $dept_id)) selected @endif>وادي الدواسر</option>
                            @endif
                            @if(in_array("4", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="4" @if(in_array("4", $dept_id)) selected @endif>الجوف</option>
                            @endif
                            @if(in_array("6", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="6" @if(in_array("6", $dept_id)) selected @endif>الدمام</option>
                            @endif
                            @if(in_array("5", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="5" @if(in_array("5", $dept_id)) selected @endif>الخرج</option>
                            @endif
                            @if(in_array("12", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="12" @if(in_array("12", $dept_id)) selected @endif>نجران</option>
                            @endif
                            @if(in_array("11", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="11" @if(in_array("11", $dept_id)) selected @endif>حائل</option>
                            @endif
                            @if(in_array("9", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="9" @if(in_array("9", $dept_id)) selected @endif>تبوك</option>
                            @endif
                            @if(in_array("8", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="8" @if(in_array("8", $dept_id)) selected @endif>القصيم</option>
                            @endif
                            @if(in_array("505", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                                <option value="505" @if(in_array("505", $dept_id)) selected @endif>ساجر</option>
                            @endif
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
                        <option value="cat_all" @if(in_array("cat_all", $cat_type)) selected @endif>الكل</option>
                        <option value="bathoor" @if(in_array("bathoor", $cat_type)) selected @endif>بذور</option>
                        <option value="asmedah" @if(in_array("asmedah", $cat_type)) selected @endif>اسمدة</option>
                        <option value="mobedat" @if(in_array("mobedat", $cat_type)) selected @endif>مبيدات</option>
                        <option value="other" @if(in_array("other", $cat_type)) selected @endif>اخرى</option>
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
                        <option value="sp_all" @if(in_array("sp_all", $sp_type)) selected @endif>الكل</option>
                        <option value="0" @if(in_array("0", $sp_type)) selected @endif>مميز 0</option>
                        <option value="1" @if(in_array("1", $sp_type)) selected @endif>مميز 1</option>
                        <option value="2" @if(in_array("2", $sp_type)) selected @endif>مميز 2</option>
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
                            <option value="vendor_all" @if($vendor_type == "vendor_all") selected @endif>الكل</option>
                            @foreach($vendor_list as $vendor)
                                <option value="{{ $vendor->NodeNo }}" @if($vendor_type == $vendor->NodeNo) selected @endif>{{ $vendor->Arabic_Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('vendor_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
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

    @if($show_msg)
            @php $all_total_emp_sales = []; @endphp
            @php $all_total_emp_tr = []; @endphp
            @php $all_total_dept_sales = []; @endphp
            @php $all_total_dept_tr = []; @endphp
            @php $mod_total_s_value = 0; @endphp
            @php $mode_total_dept_s_value = 0; @endphp

        <div id="report-btn" wire:loading.remove wire:target="generateReport" class="overflow-x-auto w-full">
{{--            @if(count($products_items) > 0)--}}
                @if($results && $dept_id)
                    <div class="mb-5 p-2">
                        <div class="flex flex-col sm:flex-row gap-4 w-full">
                            <div style="background-color: #f5f5f5; padding-right: 20px; padding-top: 20px" class="w-full">
                                <label class="block font-bold mb-5">خيارات</label>

                                <div class="flex flex-row">

                                    @if(count($dept_id) > 1)
                                        <div class="flex items-center mb-4 w-full">
                                            <input id="item_summary" name="item_summary" type="checkbox" value="all_item" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">ملخص الصنف</label>
                                        </div>
                                    @elseif(count($dept_id) == 1)
                                        <div class="flex flex-col items-center mb-4 w-full">
                                            <div>
                                                <input id="item_summary_employee" name="item_summary" type="radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">ملخص الصنف بالموظف</label>
                                            </div>
                                            <div>
                                                <select id="emp_code_selection" name="emp_code_selection"
                                                        class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                        style="@error('dept_id') border: solid 1px #fda4af; @enderror">
                                                    <option value="-1">جميع الموظفين</option>
                                                    @foreach($users as $emp)
                                                        <option value="{{ $emp->emp_code }}">{{ $emp->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="flex items-center mb-4 w-full">
                                            <input id="item_summary" name="item_summary" type="radio" value="all_item" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">ملخص الصنف</label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div style="background-color: #eaf8ff; padding-right: 20px; padding-top: 20px" class="w-full">
                                <label class="block font-bold mb-5">تنويه</label>

                                <div class="flex flex-row">
                                    <div class="flex items-center mb-4 w-full">
                                        <ul class="list-disc mr-4">
                                            <li>رمز
                                                <span class="font-bold">SC</span>
                                                تعني المبيعات الحالية</li>
                                            <li>رمز
                                                <span class="font-bold">T</span>
                                                تعني المستهدف</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($results && $dept_id && count($dept_id) == 1)
                    <table id="tbl2" style="border: 2px solid black;" class="table-container w-full border text-center">
                        <tbody class="text-sm divide-y divide-gray-100">
                            <?php
                                $vendor_id = "*";
                            ?>
                        @foreach($results as $record)
                            <div>
                                @if($record->VendorNo != $vendor_id)
                                        <?php $vendor_id = $record->VendorNo; ?>
                                    <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record->VendorCode }}</td>
                                        <td colspan="29" style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record->VendorName }}</td>
                                    </tr>
                                @endif
                                <?php $vendor_id = $record->VendorNo; ?>
                                <tr>
                                    <th colspan="30" style="border: 2px solid black; background-color: #faebd7" class="col-id-no fixed-header border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div class="w-full text-sm text-center">رقم الصنف</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->ProductCode }}</div>
                                            <div class="w-full text-sm text-center">اسم الصنف</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->ProductName }}</div>
                                            <div class="w-full text-sm text-center">الوحدة</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->BaseUnits }}</div>
                                            <div class="w-full text-sm text-center">التميز</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->SpecialityCode }}</div>
                                            <div class="w-full text-sm text-center">المورد</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->VendorName }}</div>
                                            <div class="w-full text-sm text-center mr-1">
                                                @if($item_price == "WholeSale")
                                                    سعر مؤسسات
                                                @elseif($item_price == "Retail")
                                                    سعر تجزئة
                                                @elseif($item_price == "MaxDiscount")
                                                    أقل سعر
                                                @else
                                                    السعر
                                                @endif
                                            </div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->$item_price }}</div>
                                            <div class="w-full text-sm text-center">مخزون فرع</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ number_format($record->stock) }}</div>
                                            <div class="w-full text-sm text-center">مخزون كلي</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ number_format($record->stock_total) }}</div>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">الشهر</div>
                                    </th>
                                    <?php $loop_counter = 0; ?>
                                    @foreach ($list as $year_key => $year)
                                        @foreach ($year as $month)
                                            <th colspan="2" style="border: 2px solid black; z-index: 10; @if($loop_counter%2 == 0) background-color: #d2dafa; @else background-color: #f8d2fa; @endif" class="border p-2">
                                                <div class="text-sm">{{ $year_key."-".$month }}</div>
                                            </th>
                                            <?php $loop_counter++; ?>
                                        @endforeach
                                    @endforeach
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #d2dafa;" class="border p-2">
                                        <div class="text-sm">مجموع كمية</div>
                                    </th>
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #f8d2fa;" class="border p-2">
                                        <div class="text-sm">مجموع قيمة</div>
                                    </th>
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">الفرق</div>
                                    </th>
                                </tr>
                                <tr>

                                    @foreach ($list as $year_key => $year)
                                        @foreach ($year as $month)
                                            <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                                <div class="text-sm">SC</div>
                                            </th>
                                            <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                                <div class="text-sm">T</div>
                                            </th>
                                        @endforeach
                                    @endforeach
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                        <div class="text-sm">SC</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                        <div class="text-sm">T</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                        <div class="text-sm">SC</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                        <div class="text-sm">T</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">%</div>
                                    </th>
                                </tr>
                                    <?php $new_tr = []; ?>
                                    <?php $new_sales = []; ?>
                                    <?php $total_new_tr = 0; ?>
                                    <?php $total_new_sales = 0; ?>
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
                                    <?php $total_s_qty = 0; $total_f_qty = 0;?>
                                    <?php $total_s_value = 0; $total_f_value = 0;?>

                                @foreach($users as $emp)
                                        <?php $total_new_tr = 0; ?>
                                        <?php $total_new_sales = 0; ?>

                                    <tr class="employee employee-{{$emp->emp_code}}">
                                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                            <div class="text-sm">{{ $emp->name }}</div>
                                        </th>
                                            <?php $month_counter = 1; ?>
                                        @foreach ($list as $year_key => $year)
                                            @foreach ($year as $month)
                                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border">
                                                        <?php
//                                                        $new_result = key_exists('ProductCode', $record) ? $results->where('ProductCode', $record['ProductCode'])->where('EmpCode', $emp->emp_code)->first(): 0;
                                                        $month_num = "month".$month_counter."_".$emp->emp_code;
                                                        $new_result = $record->$month_num;
                                                        ?>
{{--                                                    <div class="text-sm">{{ $new_result ? ($new_result->$month_num == 0 ? "" : number_format($new_result->$month_num)) : ""  }}</div>--}}
                                                    <div class="text-sm">{{ $new_result ? ($new_result == 0 ? "" : number_format($new_result)) : ""  }}</div>
                                                        <?php array_push($new_sales, ($new_result ? $new_result : 0) ) ?>
                                                    @php $total_new_sales += $new_result ? $new_result : 0; @endphp
                                                    @switch($month_counter)
                                                        @case(1)
                                                            @php $total_s1 = $total_s1 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(2)
                                                            @php $total_s2 = $total_s2 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(3)
                                                            @php $total_s3 = $total_s3 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(4)
                                                            @php $total_s4 = $total_s4 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(5)
                                                            @php $total_s5 = $total_s5 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(6)
                                                            @php $total_s6 = $total_s6 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(7)
                                                            @php $total_s7 = $total_s7 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(8)
                                                            @php $total_s8 = $total_s8 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(9)
                                                            @php $total_s9 = $total_s9 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(10)
                                                            @php $total_s10 = $total_s10 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(11)
                                                            @php $total_s11 = $total_s11 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(12)
                                                            @php $total_s12 = $total_s12 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                    @endswitch
                                                </th>
                                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border">
                                                        <?php
                                                        //$new_result = key_exists('ProductCode', $record) ? $new_targets->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->where('branch', $dept_id[0])->where('user_id', $emp->id)->first(): 0;
                                                        $new_result = \Illuminate\Support\Facades\DB::select("select target from `product_targets` where `product_id` = '". $record->ProductCode ."' AND `branch` = '". $dept_id[0] ."' AND `month` = '".$month."' AND `year` = '". intval($year_key) ."' and user_id ='". $emp->id ."'");
                                                        $new_result = count($new_result) > 0 ? $new_result[0] : 0;
                                                        ?>
                                                    <div class="text-sm">{{ $new_result ? ($new_result->target == 0 ? "": $new_result->target) : ""  }}</div>
                                                        <?php array_push($new_tr, ($new_result ? $new_result->target : 0) ) ?>
                                                    @php $total_new_tr += $new_result ? $new_result->target : 0; @endphp
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
                                        <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                            <div class="text-sm">{{ $total_new_sales == 0 ? "" : number_format($total_new_sales) }}</div>
                                        </th>
                                        <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                            <div class="text-sm">{{ $total_new_tr == 0 ? "" : number_format($total_new_tr) }}</div>
                                        </th>
                                        <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                            <?php $sales_total = $total_new_sales*$record->$item_price; ?>
                                            <div class="text-sm">{{ $sales_total == 0 ? "" : number_format($sales_total) }}</div>
                                        </th>
                                        <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                            <?php $target_total = $total_new_tr*$record->$item_price; ?>
                                            <div class="text-sm">{{ $target_total == 0 ? "" : number_format($target_total) }}</div>
                                        </th>
                                        @php $a = $target_total == 0 ? 0 : (($sales_total/$target_total)*100)-100; @endphp
                                        <th style="border: 2px solid black; z-index: 10; @if($a > 0) background-color: #cfffbd; @else background-color: #ffcbcb; @endif" class="border p-2">
                                            <div class="text-sm">{{ $a == 0 ? "" : number_format($a) }}</div>
                                        </th>
                                    </tr>
                                    @php
                                        $total_s_qty = $total_s_qty + $total_new_sales;
                                        $total_f_qty = $total_f_qty + $total_new_tr;

                                        $total_s_value = $total_s_value + $total_new_sales*$record->$item_price;
                                        $total_f_value = $total_f_value + $total_new_tr*$record->$item_price;

                                        if (!array_key_exists($emp->emp_code, $all_total_emp_sales)) {
                                                $all_total_emp_sales[$emp->emp_code] = $total_new_sales*$record->$item_price;
                                            }
                                            else {
                                                $all_total_emp_sales[$emp->emp_code] += $total_new_sales*$record->$item_price;
                                            }

                                            if (!array_key_exists($emp->emp_code, $all_total_emp_tr)) {
                                                $all_total_emp_tr[$emp->emp_code] = $total_new_tr*$record->$item_price;
                                            }
                                            else {
                                                $all_total_emp_tr[$emp->emp_code] += $total_new_tr*$record->$item_price;
                                            }
                                    @endphp
                                @endforeach
                                <tr class="emp-total">
                                    <th style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">المجموع</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s1 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f1 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s2 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f2 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s3 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f3 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s4 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f4 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s5 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f5 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s6 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{$total_f6 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{$total_s7 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{$total_f7 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{$total_s8 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{$total_f8 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{$total_s9 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{$total_f9 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s10 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f10 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s11 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f11 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s12 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f12 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10;background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ number_format($total_s_qty) }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10;background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ number_format($total_f_qty) }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ number_format($total_s_value) }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ number_format($total_f_value) }}</div>
                                    </th>
                                    @php $a = $total_f_value == 0 ? 0 : (($total_s_value/$total_f_value)*100)-100;  @endphp
                                    <th style="border: 2px solid black; z-index: 10; @if($a > 0) background-color: #cfffbd; @else background-color: #ffcbcb; @endif" class="border p-2">
                                        <div class="text-sm">{{ number_format($a) }}</div>
                                    </th>
                                    @php
                                        if ($total_s_value > $total_f_value) {
                                            $mod_total_s_value += $total_f_value;
                                        }
                                        else {
                                            $mod_total_s_value += $total_s_value;
                                        }
                                    @endphp
                                </tr>
                                <tr class="emp-diff">
                                    <th style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">الفرق %</div>
                                    </th>
                                    @php $a = floatval($total_f1) == 0 ? 0 : number_format((($total_s1/$total_f1)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm">{{ floatval($total_f1) == 0 ? 0 : number_format((($total_s1/$total_f1)*100) - 100)}}</div>
                                    </th>
                                    @php $a = floatval($total_f2) == 0 ? 0 : number_format((($total_s2/$total_f2)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm">{{ floatval($total_f2) == 0 ? 0 : number_format((($total_s2/$total_f2)*100) - 100)}}</div>
                                    </th>
                                    @php $a = floatval($total_f3) == 0 ? 0 : number_format((($total_s3/$total_f3)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f3) == 0 ? 0 : number_format((($total_s3/$total_f3)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f4) == 0 ? 0 : number_format((($total_s4/$total_f4)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f4) == 0 ? 0 : number_format((($total_s4/$total_f4)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f5) == 0 ? 0 : number_format((($total_s5/$total_f5)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f5) == 0 ? 0 : number_format((($total_s5/$total_f5)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f6) == 0 ? 0 : number_format((($total_s6/$total_f6)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f6) == 0 ? 0 : number_format((($total_s6/$total_f6)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f7) == 0 ? 0 : number_format((($total_s7/$total_f7)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f7) == 0 ? 0 : number_format((($total_s7/$total_f7)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f8) == 0 ? 0 : number_format((($total_s8/$total_f8)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f8) == 0 ? 0 : number_format((($total_s8/$total_f8)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f9) == 0 ? 0 : number_format((($total_s9/$total_f9)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f9) == 0 ? 0 : number_format((($total_s9/$total_f9)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f10) == 0 ? 0 : number_format((($total_s10/$total_f10)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f10) == 0 ? 0 : number_format((($total_s10/$total_f10)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f11) == 0 ? 0 : number_format((($total_s11/$total_f11)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f11) == 0 ? 0 : number_format((($total_s11/$total_f11)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f12) == 0 ? 0 : number_format((($total_s12/$total_f12)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f12) == 0 ? 0 : number_format((($total_s12/$total_f12)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f_qty) == 0 ? 0 : number_format((($total_s_qty/$total_f_qty)*100) - 100) @endphp
                                    <th colspan="5" style="border: 2px solid black; z-index: 10;@if($a > 0) background-color: #cfffbd; @else background-color: #ffcbcb; @endif" class="border p-2">
                                        <div class="text-sm">{{ floatval($total_f_qty) == 0 ? 0 : number_format((($total_s_qty/$total_f_qty)*100) - 100)}}</div>
                                    </th>
                                </tr>
                            </div>
                        @endforeach
                        </tbody>
                    </table>
                    <div id="summary-container" style="background-color: #f5f5f5" class="overflow-x-auto w-full p-6 mt-4">
                            <div class="text-2xl bold mb-4">مجاميع القيم</div>
                            <table id="tbl3" style="border: 2px solid black;" class="table-fixed table-container w-full border text-center">
                                <tbody class="text-sm divide-y divide-gray-100">
                                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">الموظف</td>
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">SC</td>
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">T</td>
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">الفرق %</td>
                                </tr>
                                    <?php $total_sum_s = 0; ?>
                                    <?php $total_sum_f = 0; ?>
                                @foreach($users as $emp)
                                    <tr class="employee employee-{{$emp->emp_code}}" style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                        <td style="border: 2px solid black;background-color: #FFFFFF" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $emp->name }}</td>
                                            <?php $total_sum_s += $all_total_emp_sales[$emp->emp_code]; ?>
                                        <td id="summary-emp-sales-{{$emp->emp_code}}" style="border: 2px solid black;background-color: #fffacd" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($all_total_emp_sales[$emp->emp_code]) }}</td>
                                            <?php $total_sum_f += $all_total_emp_tr[$emp->emp_code]; ?>
                                        <td id="summary-emp-forcasting-{{$emp->emp_code}}" style="border: 2px solid black; background-color: #e4fdf7;" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($all_total_emp_tr[$emp->emp_code]) }}</td>
                                        <?php $single_emp_diff = $all_total_emp_tr[$emp->emp_code] == 0? 0: (number_format((($all_total_emp_sales[$emp->emp_code]/$all_total_emp_tr[$emp->emp_code])*100)-100));  ?>
                                        <td id="summary-emp-diff-{{$emp->emp_code}}" style="border: 2px solid black;@if($single_emp_diff > 0) background-color: #e8ffdf; @else background-color: #ffeded; @endif" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $single_emp_diff }}</td>

                                    </tr>
                                @endforeach
                                <tr class="emp-summary-total" style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">المجموع</td>
                                    <td id="summary-grand-total-emp-val-{{$emp->emp_code}}" style="border: 2px solid black;background-color: #fff6a1" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format(floatval($total_sum_s)) }}</td>
                                    <td id="summary-grand-total-emp-diff-{{$emp->emp_code}}" style="border: 2px solid black;background-color: #c0fff0;" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format(floatval($total_sum_f)) }}</td>
                                        <?php $total_emp_diff = $total_sum_f == 0? 0 :  number_format((($total_sum_s/$total_sum_f)*100)-100); ?>
                                    <td id="summary-grand-total-emp-diff-{{$emp->emp_code}}" style="border: 2px solid black;@if($total_emp_diff > 0) background-color: #cfffbd; @else background-color: #ffcbcb; @endif" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format(floatval($total_emp_diff)) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <div id="summary-container-emp" style="background-color: #f5f5f5" class="overflow-x-auto w-full p-6 mt-4">
                            <table id="tbl3" style="border: 2px solid black;" class="table-fixed table-container w-full border text-center">
                                <tbody class="text-sm divide-y divide-gray-100">
                                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">مجموع المبيعات المعدل</td>
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">مجموع المستهدف</td>
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">الفرق المعدل %</td>
                                </tr>
                                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                    <td style="border: 2px solid black;background-color: #fffacd" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($mod_total_s_value) }}</td>
                                    <td style="border: 2px solid black; background-color: #e4fdf7;" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($total_sum_f) }}</td>
                                        <?php $single_mod_emp_diff = $total_sum_f == 0? 0: (number_format((($mod_total_s_value/$total_sum_f)*100)-100));  ?>
                                    <td style="border: 2px solid black;@if($single_mod_emp_diff > 0) background-color: #e8ffdf; @else background-color: #ffeded; @endif" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $single_mod_emp_diff }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                @elseif($results && $dept_id && count($dept_id) > 1)
                    <table id="tbl2" style="border: 2px solid black;" class="table-container w-full border text-center">
                        <tbody class="text-sm divide-y divide-gray-100">
                            <?php
                            $vendor_id = "*";
                            ?>
                        @foreach($results as $record)
                            <div>
                                @if($record->VendorNo != $vendor_id)
                                        <?php $vendor_id = $record->VendorNo ?>
                                    <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                        <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record->VendorNo }}</td>
                                        <td colspan="31" style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record->VendorName }}</td>
                                    </tr>
                                @endif
                                    <?php $vendor_id = $record->VendorNo ?>
                                <tr>
                                    <th colspan="32" style="border: 2px solid black; background-color: #faebd7" class="col-id-no fixed-header border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div class="w-full text-sm text-center">رقم الصنف</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->ProductCode }}</div>
                                            <div class="w-full text-sm text-center">اسم الصنف</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->ProductName }}</div>
                                            <div class="w-full text-sm text-center">الوحدة</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->BaseUnits }}</div>
                                            <div class="w-full text-sm text-center">التميز</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->SpecialityCode }}</div>
                                            <div class="w-full text-sm text-center">المورد</div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->VendorName }}</div>
                                            <div class="w-full text-sm text-center mr-1">
                                                @if($item_price == "WholeSale")
                                                    سعر مؤسسات
                                                @elseif($item_price == "Retail")
                                                    سعر تجزئة
                                                @elseif($item_price == "MaxDiscount")
                                                    أقل سعر
                                                @else
                                                    السعر
                                                @endif
                                            </div>
                                            <div style="color: #fd0e0e" class="w-full text-sm text-center">{{ $record->$item_price }}</div>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th rowspan="2" style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">الشهر</div>
                                    </th>
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #f8d2fa;" class="border p-2">
                                        <div class="text-sm">مخزون</div>
                                    </th>
                                        <?php $loop_counter = 0; ?>
                                    @foreach ($list as $year_key => $year)
                                        @foreach ($year as $month)
                                            <th colspan="2" style="border: 2px solid black; z-index: 10; @if($loop_counter%2 == 0) background-color: #d2dafa; @else background-color: #f8d2fa; @endif" class="border p-2">
                                                <div class="text-sm">{{ $year_key."-".$month }}</div>
                                            </th>
                                          <?php $loop_counter++; ?>
                                        @endforeach
                                    @endforeach
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #d2dafa;" class="border p-2">
                                        <div class="text-sm">مجموع كمية</div>
                                    </th>
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #f8d2fa;" class="border p-2">
                                        <div class="text-sm">مجموع قيمة</div>
                                    </th>
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">الفرق</div>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #f8d2fa;" class="border p-2">
                                        <div class="text-sm">فرع</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #f8d2fa;" class="border p-2">
                                        <div class="text-sm">كلي</div>
                                    </th>
                                    @foreach ($list as $year_key => $year)
                                        @foreach ($year as $month)
                                            <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                                <div class="text-sm">SC</div>
                                            </th>
                                            <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                                <div class="text-sm">T</div>
                                            </th>
                                        @endforeach
                                    @endforeach
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                        <div class="text-sm">SC</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                        <div class="text-sm">T</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                        <div class="text-sm">SC</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                        <div class="text-sm">T</div>
                                    </th>
                                        <th style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                            <div class="text-sm">%</div>
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
                                    <?php $depts =  $dept_id; ?>
                                    <?php $stockdept_total =  0; ?>

                                @foreach($depts as $dept_key => $dept)
                                        <?php $dept_sales = 0; $dept_forecast = 0; ?>
                                    <tr class="department">
                                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
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
                                        @php
                                            $stockdept_txt = "stock_".$dept;
                                            $stocktotal_txt = "stocktotal_".$dept;
                                        @endphp
                                        <th style="border: 2px solid black;">
                                            <div class="text-sm">{{ number_format($record->$stockdept_txt) }}</div>
                                            @php $stockdept_total += intval($record->$stockdept_txt); @endphp
                                        </th>
                                        @if ($loop->first)
                                            <th rowspan="{{ count($depts) }}" style="border: 2px solid black;">
                                                <div class="text-sm">{{ $record->$stocktotal_txt }}</div>
                                            </th>
                                        @endif
                                            <?php $month_counter = 1; ?>
                                        @foreach ($list as $year_key => $year)
                                            @foreach ($year as $month)
                                                <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border">
                                                        <?php
//                                                        $new_result = key_exists('ProductCode', $record) ? $results->where('ProductCode', $record['ProductCode'])->where('Department', $dept)->first(): 0;
                                                        $month_num = "month".$month_counter."_".$dept;
                                                        $new_result = $record->$month_num;
                                                        ?>
{{--                                                    <div class="text-sm">{{ $new_result ? ($new_result->$month_num == 0 ? "" : number_format($new_result->$month_num)) : ""  }}</div>--}}
                                                    <div class="text-sm">{{ $new_result ? ($new_result == 0 ? "" : number_format($new_result)) : ""  }}</div>
                                                        <?php //array_push($new_sales, ($new_result ? $new_result->$month_num : 0) ) ?>
                                                        <?php array_push($new_sales, ($new_result ? $new_result : 0) ) ?>
                                                        <?php $dept_sales += ($new_result ? $new_result : 0); ?>
                                                    @php $total_dept_new_sales += $new_result ? $new_result : 0; @endphp
                                                    @switch($month_counter)
                                                        @case(1)
                                                            @php $total_s1 = $total_s1 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(2)
                                                            @php $total_s2 = $total_s2 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(3)
                                                            @php $total_s3 = $total_s3 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(4)
                                                            @php $total_s4 = $total_s4 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(5)
                                                            @php $total_s5 = $total_s5 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(6)
                                                            @php $total_s6 = $total_s6 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(7)
                                                            @php $total_s7 = $total_s7 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(8)
                                                            @php $total_s8 = $total_s8 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(9)
                                                            @php $total_s9 = $total_s9 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(10)
                                                            @php $total_s10 = $total_s10 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(11)
                                                            @php $total_s11 = $total_s11 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                        @case(12)
                                                            @php $total_s12 = $total_s12 + ($new_result ? $new_result : 0); @endphp
                                                            @break
                                                    @endswitch
                                                </th>
                                                <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border">
                                                        <?php
//                                                        $new_result = key_exists('ProductCode', $record) ? $new_targets->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->where('branch', $dept)->first(): 0;

//                                                        $month_num = "month".$month_counter."_".$dept;
//                                                        $month_num = "month".$month_counter;
                                                        $new_result = \Illuminate\Support\Facades\DB::select("select target from `product_target_branch_totals` where `product_id` = '". $record->ProductCode ."' AND `branch` = '". $dept ."' AND `month` = '".$month."' AND `year` = '". intval($year_key) ."'");
                                                        $new_result = count($new_result) > 0 ? $new_result[0] : 0;
                                                        ?>
                                                    <div class="text-sm">{{ $new_result ? ($new_result->target == 0 ? "" : $new_result->target) : ""  }}</div>
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
                                        <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                            <div class="text-sm">{{ $dept_sales == 0 ? "" : number_format($dept_sales) }}</div>
                                        </th>
                                        <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                            <div class="text-sm">{{ $dept_forecast == 0 ? "" : number_format($dept_forecast) }}</div>
                                        </th>
                                        <th style="border: 2px solid black; z-index: 10; background-color: #fafad2;" class="border p-2">
                                            <div class="text-sm">{{ ($dept_sales*$record->$item_price) == 0 ? "" : number_format($dept_sales*$record->$item_price) }}</div>
                                        </th>
                                        <th style="border: 2px solid black; z-index: 10; background-color: #e4fdf7;" class="border p-2">
                                            <div class="text-sm">{{ ($dept_forecast*$record->$item_price) == 0 ? "" : number_format($dept_forecast*$record->$item_price) }}</div>
                                        </th>
                                        @php $a = ($dept_forecast*$record->$item_price) == 0 ? 0 : (((($dept_sales*$record->$item_price)/($dept_forecast*$record->$item_price))*100)-100);  @endphp
                                        <th style="border: 2px solid black; z-index: 10; @if($a > 0) background-color: #cfffbd; @else background-color: #ffcbcb; @endif" class="border p-2">
                                            <div class="text-sm">{{ $a == 0 ? "" : number_format($a) }}</div>
                                        </th>
                                    </tr>
                                    @php
                                        $total_dept_s_qty = $total_dept_s_qty + $dept_sales;
                                        $total_dept_f_qty = $total_dept_f_qty + $dept_forecast;

                                        $total_dept_s_value = $total_dept_s_value + ($dept_sales*$record->$item_price);
                                        $total_dept_f_value = $total_dept_f_value + ($dept_forecast*$record->$item_price);

                                        if (!array_key_exists($dept, $all_total_dept_sales)) {
                                            $all_total_dept_sales[$dept] = $dept_sales*$record->$item_price;
                                        }
                                        else {
                                            $all_total_dept_sales[$dept] += $dept_sales*$record->$item_price;
                                        }

                                        if (!array_key_exists($dept, $all_total_dept_tr)) {
                                            $all_total_dept_tr[$dept] = $dept_forecast*$record->$item_price;
                                        }
                                        else {
                                            $all_total_dept_tr[$dept] += $dept_forecast*$record->$item_price;
                                        }
                                    @endphp
                                @endforeach
                                <tr>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">المجموع</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">{{ $stockdept_total }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">{{ $record->$stocktotal_txt }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s1 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f1 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s2 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f2 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s3 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f3 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s4 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f4 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s5 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f5 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s6 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f6 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s7 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f7 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s8 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f8 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s9 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f9 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s10}}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f10 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s11 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f11 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ $total_s12 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ $total_f12 }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ number_format($total_dept_s_qty) }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ number_format($total_dept_f_qty) }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #fff6a1;" class="border p-2">
                                        <div class="text-sm">{{ number_format($total_dept_s_value) }}</div>
                                    </th>
                                    <th style="border: 2px solid black; z-index: 10; background-color: #c0fff0;" class="border p-2">
                                        <div class="text-sm">{{ number_format($total_dept_f_value) }}</div>
                                    </th>
                                    @php $a = $total_dept_f_value == 0 ? 0 : ((($total_dept_s_value/$total_dept_f_value)*100)-100) @endphp
                                    <th style="border: 2px solid black; z-index: 10; @if($a > 0) background-color: #cfffbd; @else background-color: #ffcbcb; @endif" class="border p-2">
                                        <div class="text-sm">{{ number_format($a) }}</div>
                                    </th>
                                    @php
                                        if($total_dept_s_value > $total_dept_f_value) {
                                            $mode_total_dept_s_value += $total_dept_f_value;
                                        }
                                        else {
                                            $mode_total_dept_s_value += $total_dept_s_value;
                                        }
                                    @endphp
                                </tr>
                                <tr>
                                    <th colspan="3" style="border: 2px solid black; z-index: 10; background-color: #dcdcdc;" class="border p-2">
                                        <div class="text-sm">الفرق %</div>
                                    </th>
                                    @php $a = floatval($total_f1) == 0 ? 0 : number_format((($total_s1/$total_f1)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm">{{ floatval($total_f1) == 0 ? 0 : number_format((($total_s1/$total_f1)*100) - 100)}}</div>
                                    </th>
                                    @php $a = floatval($total_f2) == 0 ? 0 : number_format((($total_s2/$total_f2)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm">{{ floatval($total_f2) == 0 ? 0 : number_format((($total_s2/$total_f2)*100) - 100)}}</div>
                                    </th>
                                    @php $a = floatval($total_f3) == 0 ? 0 : number_format((($total_s3/$total_f3)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f3) == 0 ? 0 : number_format((($total_s3/$total_f3)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f4) == 0 ? 0 : number_format((($total_s4/$total_f4)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f4) == 0 ? 0 : number_format((($total_s4/$total_f4)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f5) == 0 ? 0 : number_format((($total_s5/$total_f5)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f5) == 0 ? 0 : number_format((($total_s5/$total_f5)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f6) == 0 ? 0 : number_format((($total_s6/$total_f6)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f6) == 0 ? 0 : number_format((($total_s6/$total_f6)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f7) == 0 ? 0 : number_format((($total_s7/$total_f7)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f7) == 0 ? 0 : number_format((($total_s7/$total_f7)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f8) == 0 ? 0 : number_format((($total_s8/$total_f8)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f8) == 0 ? 0 : number_format((($total_s8/$total_f8)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f9) == 0 ? 0 : number_format((($total_s9/$total_f9)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f9) == 0 ? 0 : number_format((($total_s9/$total_f9)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f10) == 0 ? 0 : number_format((($total_s10/$total_f10)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f10) == 0 ? 0 : number_format((($total_s10/$total_f10)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f11) == 0 ? 0 : number_format((($total_s11/$total_f11)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f11) == 0 ? 0 : number_format((($total_s11/$total_f11)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_f12) == 0 ? 0 : number_format((($total_s12/$total_f12)*100) - 100) @endphp
                                    <th colspan="2" style="border: 2px solid black; z-index: 10; @if($a <= 0) background-color: #ffebeb; @else background-color: #ebffee; @endif" class="border p-2">
                                        <div class="text-sm"><div class="text-sm">{{ floatval($total_f12) == 0 ? 0 : number_format((($total_s12/$total_f12)*100) - 100)}}</div></div>
                                    </th>
                                    @php $a = floatval($total_dept_f_qty) == 0 ? 0 : number_format((($total_dept_s_qty/$total_dept_f_qty)*100) - 100) @endphp
                                    <th colspan="5" style="border: 2px solid black; z-index: 10; @if($a > 0) background-color: #cfffbd; @else background-color: #ffcbcb; @endif" class="border p-2">
                                        <div class="text-sm">{{ floatval($total_dept_f_qty) == 0 ? 0 : number_format((($total_dept_s_qty/$total_dept_f_qty)*100) - 100)}}</div>
                                    </th>
                                </tr>
                            </div>
                        @endforeach
                        </tbody>
                    </table>
                    <div id="summary-container" style="background-color: #f5f5f5" class="overflow-x-auto w-full p-6 mt-4">
                            <div class="text-2xl bold mb-4">مجاميع القيم</div>
                            <table id="tbl3" style="border: 2px solid black;" class="table-fixed table-container w-full border text-center">
                                <tbody class="text-sm divide-y divide-gray-100">
                                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">الفرع</td>
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">SC</td>
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">T</td>
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">الفرق %</td>
                                </tr>
                                    <?php $total_dept_sum_s = 0; ?>
                                    <?php $total_dept_sum_f = 0; ?>
                                    <?php $summary_grand_total_dept_s = 0; ?>
                                    <?php $summary_grand_total_dept_f = 0; ?>

                                @foreach($depts as $dept)
                                    <tr class="employee" style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                        <td style="border: 2px solid black;background-color: #FFFFFF" class="border p-2 whitespace-nowrap col-id-no" scope="row">
                                            @if($dept == '3')
                                                الاحساء
                                            @elseif($dept == '10')
                                                جدة
                                            @elseif($dept == '7')
                                                الرياض
                                            @elseif($dept == '13')
                                                وادي الدواسر
                                            @elseif($dept == '4')
                                                الجوف
                                            @elseif($dept == '6')
                                                الدمام
                                            @elseif($dept == '5')
                                                الخرج
                                            @elseif($dept == '12')
                                                نجران
                                            @elseif($dept == '11')
                                               حائل
                                            @elseif($dept == '9')
                                                تبوك
                                            @elseif($dept == '8')
                                                القصيم
                                            @elseif($dept == '505')
                                                ساجر
                                            @else
                                                {{ $dept }}
                                            @endif
                                        </td>
                                            <?php $total_dept_sum_s += $all_total_dept_sales[$dept]; ?>
                                        <td id="summary-emp-sales-{{$dept}}" style="border: 2px solid black;background-color: #fffacd" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($all_total_dept_sales[$dept]) }}</td>
                                            <?php $total_dept_sum_f += $all_total_dept_tr[$dept]; ?>
                                        <td id="summary-emp-forcasting-{{$dept}}" style="border: 2px solid black; background-color: #e4fdf7;" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($all_total_dept_tr[$dept]) }}</td>
                                            <?php $single_dept_diff = $all_total_dept_tr[$dept] == 0? 0: (number_format((($all_total_dept_sales[$dept]/$all_total_dept_tr[$dept])*100)-100));  ?>
                                        <td id="summary-emp-diff-{{$dept}}" style="border: 2px solid black;@if($single_dept_diff > 0) background-color: #e8ffdf; @else background-color: #ffeded; @endif" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $single_dept_diff }}</td>
                                        @php $summary_grand_total_dept_s += $all_total_dept_sales[$dept]; @endphp
                                        @php $summary_grand_total_dept_f += $all_total_dept_tr[$dept]; @endphp
                                    </tr>
                                @endforeach
                                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">المجموع</td>
                                    <td id="summary-grand-total-dept-val-{{$dept}}" style="border: 2px solid black;background-color: #fff6a1" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($summary_grand_total_dept_s) }}</td>
                                    <td id="summary-grand-total-dept-diff-{{$dept}}" style="border: 2px solid black;background-color: #c0fff0;" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($summary_grand_total_dept_f) }}</td>
                                        <?php $total_dept_diff = $summary_grand_total_dept_f == 0? 0 :  number_format((($summary_grand_total_dept_s/$summary_grand_total_dept_f)*100)-100); ?>
                                    <td id="summary-grand-total-emp-diff-{{$dept}}" style="border: 2px solid black;@if($total_dept_diff > 0) background-color: #cfffbd; @else background-color: #ffcbcb; @endif" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($total_dept_diff) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <div id="summary-container-dept" style="background-color: #f5f5f5" class="overflow-x-auto w-full p-6 mt-4">
                        <table id="tbl5" style="border: 2px solid black;" class="table-fixed table-container w-full border text-center">
                                <tbody class="text-sm divide-y divide-gray-100">
                                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">مجموع المبيعات المعدل</td>
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">مجموع المستهدف</td>
                                    <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">الفرق المعدل %</td>
                                </tr>
                                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                    <td style="border: 2px solid black;background-color: #fffacd" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($mode_total_dept_s_value) }}</td>
                                    <td style="border: 2px solid black; background-color: #e4fdf7;" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ number_format($summary_grand_total_dept_f) }}</td>
                                        <?php $single_mod_dept_diff = $summary_grand_total_dept_f == 0? 0: (number_format((($mode_total_dept_s_value/$summary_grand_total_dept_f)*100)-100));  ?>
                                    <td style="border: 2px solid black;@if($single_mod_dept_diff > 0) background-color: #e8ffdf; @else background-color: #ffeded; @endif" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $single_mod_dept_diff }}</td>
                                </tr>
                                </tbody>
                            </table>
                    </div>
                @else
                    <div class="w-full p-4 mt-4 text-center bold" style="border: 1px solid; background-color: #ffecec; color: black;">لا يوجد مستهدفات في هذه الشهور ..</div>
                @endif
{{--            @else--}}
{{--                <div class="w-full p-4 mt-4 text-center bold" style="border: 1px solid; background-color: #ffecec; color: black;">لا يوجد مستهدفات في هذه الشهور ..</div>--}}
{{--            @endif--}}
        </div>
    @endif
    @if($loading)
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

@section('fixed-title')
    متابعة المستهدف
@stop
@section('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script>

        Livewire.on('show-container', () => {

            $("#gen-report").html('<b>إنشاء تقرير</b>');

            // $('#item_summary').attr('checked', true);
            $('#item_summary_employee').attr('checked', true);

            $("input[type=checkbox][name=item_summary]").on('change', function () {
                if(this.checked) {
                    $(".employee").addClass("hide");
                    $(".department").addClass("hide");
                }
                else {
                    $(".employee").removeClass("hide");
                    $(".department").removeClass("hide");
                }
            });

            $("input[type=radio][name=item_summary]").on('change', function () {
                if(this.value == 'all_item') {
                    $(".employee").removeClass("hide");
                    $(".department").removeClass("hide");

                    $(".emp-total").removeClass("hide");
                    $(".emp-diff").removeClass("hide");
                    $(".emp-summary-total").removeClass("hide");

                    $(".employee").addClass("hide");
                    $(".department").addClass("hide");
                    $("#emp_code_selection").addClass("hide");
                    $("#summary-container-emp").removeClass("hide");
                }
                else {

                    $("#emp_code_selection").val('-1').change();

                    $(".employee").removeClass("hide");
                    $(".department").removeClass("hide");

                    $(".emp-total").removeClass("hide");
                    $(".emp-diff").removeClass("hide");
                    $(".emp-summary-total").removeClass("hide");

                    $(".employee").removeClass("hide");
                    $(".department").removeClass("hide");
                    $("#emp_code_selection").removeClass("hide");
                }
            });

            $("#emp_code_selection").on('change', function () {
                if($(this).val() == '-1') {
                    $(".employee").removeClass("hide");
                    $(".department").removeClass("hide");

                    $(".emp-total").removeClass("hide");
                    $(".emp-diff").removeClass("hide");
                    $(".emp-summary-total").removeClass("hide");
                    $("#summary-container-emp").removeClass("hide");

                    // $(".employee").addClass("hide");
                    // $(".department").addClass("hide");
                }
                else {
                    emp = $(this).val();
                    $(".employee").removeClass("hide");
                    $(".department").removeClass("hide");

                    $(".emp-total").removeClass("hide");
                    $(".emp-diff").removeClass("hide");
                    $(".emp-summary-total").removeClass("hide");


                    $(`.employee:not(.employee-${emp})`).addClass("hide");
                    $(".department").addClass("hide");

                    $(".emp-total").addClass("hide");
                    $(".emp-diff").addClass("hide");
                    $(".emp-summary-total").addClass("hide");
                    $("#summary-container-emp").addClass("hide");

                    // $(".employee").removeClass("hide");
                    // $(".department").removeClass("hide");
                }
            });

        });

        Livewire.on('finished', () => {
            swal.close();
        });

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

                if(dept_id == null || cat_type == null || sp_type == null || vendor_type == null) {
                    Swal.fire({
                        title: "حدث خطأ",
                        text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                        icon: "error",
                        confirmButtonText: "موافق",
                    });
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

                    Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                }
            });

            $('#reset-btn').on('click', function () {
                $("#dept_id option").prop('selected', false);
                $("#dept_id option[value='dept_all']").prop('selected', true);

                $("#cat_type option").prop('selected', false);
                $("#cat_type option[value='cat_all']").prop('selected', true);

                $("#sp_type option").prop('selected', false);
                $("#sp_type option[value='sp_all']").prop('selected', true);

                $("#vendor_type option").prop('selected', false);
                $("#vendor_type option[value='vendor_all']").prop('selected', true);


                $("#dept_id").change();
                $("#cat_type").change();
                $("#sp_type").change();
                $("#vendor_type").change();
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
