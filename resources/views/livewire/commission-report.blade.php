<div>
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
                            <option value="3">فرع الاحساء</option>
                        @endif
                        @if(in_array("10", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="10">فرع جدة</option>
                        @endif
                        @if(in_array("7", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="7">فرع الرياض</option>
                        @endif
                        @if(in_array("13", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="13">فرع وادي الدواسر</option>
                        @endif
                        @if(in_array("4", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="4">فرع الجوف</option>
                        @endif
                        @if(in_array("6", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="6">فرع الدمام</option>
                        @endif
                        @if(in_array("5", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="5">فرع الخرج</option>
                        @endif
                        @if(in_array("12", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="12">فرع نجران</option>
                        @endif
                        @if(in_array("11", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="11">فرع حائل</option>
                        @endif
                        @if(in_array("9", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="9">فرع تبوك</option>
                        @endif
                        @if(in_array("8", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="8">فرع القصيم</option>
                        @endif
                        @if(in_array("505", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                            <option value="505">فرع ساجر</option>
                        @endif
                    </select>
                    @error('area_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">الشهر
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

        <div id="report-btn" wire:loading.remove wire:target="generateReport" class="hide printable">
    {{--    table 1 --}}
        <div class="overflow-x-auto">
            <div id="report-logo">
                <img src="{{ asset('images/logo-horizontal.png') }}" width="20%" style="margin: auto; margin-bottom: 20px;">
            </div>
            <div
                class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
                <div id="report_title" class="w-full">التقرير</div>
            </div>
            <table style="border: 2px solid black;" class="table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الربح</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مصاريف المخزون (1%)</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مصاريف آجل (1%)</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الصافي</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">نسبة العمولة</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">عمولة الفرع</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                <tr>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result ? number_format($result['net_profit'], 2) : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result ? number_format($result['inventory_total'], 2) : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result ? number_format($result['total_postponed'], 2) : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result ? number_format($result['total'], 2) : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result ? $result['area_commission'] : ""}}</div>
                        </div>
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-sm">{{$result ? number_format($result['calculated_commission'], 2) : ""}}</div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
            <br>
    {{-- table 2 (details) --}}
        <div id="tbl2-container" class="overflow-x-auto">
        <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
            <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
            <tr>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-sm">#</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-sm">الاسم</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-sm">الوظيفة</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">مساهمة الهامش</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">اجمالي المبيعات</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">% استحقاق الحافز</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">مساهمة الربحية %</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">آجل</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">مستحق %</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">اقدم فاتورة</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">يصرف؟</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">نسبة العمولة</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">مدير مبيعات</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">مدير منطقة</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">مدير معرض</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">تطوير مواد 1</div>
                </th>
                <th class="border p-2">
                    <div class="text-sm">تطوير مواد 2</div>
                </th>
            </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
            <?php $employee_profit = 0.00; ?>
            <?php $employee_sales = 0.00; ?>
            <?php $percentage_area_employee = 0.00; ?>
            <?php $employee_postponed = 0.00; ?>
            <?php $employee_postponed_due = 0.00; ?>
            <?php $employee_postponed_due_percentage = 0.00; ?>
            <?php $employee_commission = 0.00; ?>
            <?php $calc_sales_manager = 0.00; ?>
            <?php $calc_area_manager = 0.00; ?>
            <?php $calc_store_manager = 0.00; ?>
            <?php $calc_mat_dev1 = 0.00; ?>
            <?php $calc_mat_dev2 = 0.00; ?>
            <?php $commission_percentage = []; ?>



            @foreach($result_tbl2 as $result2)
                @if(\Illuminate\Support\Facades\Auth::user()->role == 'a' || \Illuminate\Support\Facades\Auth::user()->user_group->read_type == '0')
                    <tr>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? $result2['Employeecode'] : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? $result2['EmployeeName'] : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    {{--                            {{$result2 ? $result2->role : ""}}--}}

                                    @if($result2['role'] == "area_manager")
                                        <span>مدير منطقة</span>
                                    @elseif($result2['role'] == "store_manager")
                                        <span>مدير معرض</span>
                                    @elseif($result2['role'] == "sales_manager")
                                        <span>مدير مبيعات</span>
                                    @elseif($result2['role'] == "mat_dev_manager1")
                                        <span>تطوير مواد 1</span>
                                    @elseif($result2['role'] == "mat_dev_manager2")
                                        <span>تطوير مواد 2</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['employee_profit'])) : ""}}</div>
                            </div>
                        </td>
                            <?php $tot =  $result2 ? floatval($result2['tot']) : 0; ?>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['tot'])) : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if($tot >= 0 && $tot <= 120000)
                                        30
                                        <?php $commission_percentage[$result2['role']] = 30; ?>
                                    @elseif($tot > 120000 && $tot <= 240000)
                                        60
                                        <?php $commission_percentage[$result2['role']] = 60; ?>
                                    @elseif($tot > 240000 && $tot < 400000)
                                        80
                                        <?php $commission_percentage[$result2['role']] = 80; ?>
                                    @elseif($tot >= 400000)
                                        100
                                        <?php $commission_percentage[$result2['role']] = 100; ?>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['percentage_area_employee'])) : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['employee_postponed'])) : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{ $result2['employee_postponed_due'] &&  $result2['employee_postponed']  && floatval(number_format($result2['employee_postponed'])) != 0? number_format(floatval($result2['employee_postponed_due'])) . " (%".number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100).")" : 0}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ?  ($result2['employee_postponed_due'] &&  $result2['employee_postponed']  && floatval(number_format($result2['employee_postponed'])) != 0? \Carbon\Carbon::parse($result2['oldest_voucher'])->format('Y-m-d')." (". \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) ." يوم)" : "-") : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                @if(floatval(number_format($result2['employee_postponed'])) == 0)
                                    @if( \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        <span style="font-weight: bold; color: green">نعم</span>
                                            <?php $pay = true; ?>
                                    @else
                                        <span style="font-weight: bold; color: red">لا</span>
                                            <?php $pay = false; ?>
                                    @endif
                                @else
                                    @if(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        <span style="font-weight: bold; color: green">نعم</span>
                                            <?php $pay = true; ?>
                                    @else
                                        <span style="font-weight: bold; color: red">لا</span>
                                            <?php $pay = false; ?>
                                    @endif
                                @endif

                                {{--                            @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                                {{--                                <span style="font-weight: bold; color: green">نعم</span>--}}
                                {{--                            @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                                {{--                                <span style="font-weight: bold; color: green">نعم</span>--}}
                                {{--                            @else--}}
                                {{--                                <span style="font-weight: bold; color: red">لا</span>--}}
                                {{--                            @endif--}}



                                {{--                        <div class="text-center text-gray-800 text-lg">{{$result2 ?--}}
                                {{--                             (($result2['days'] <= 240 && ((!is_null(number_format(floatval($result2['postponed_partial']))) && number_format(floatval($result2['postponed_partial'])) != 0 && !is_null(number_format(floatval($result2['postponed_total']))) && number_format(floatval($result2['postponed_total'])) != 0))? (number_format(floatval($result2['postponed_partial']))/number_format(floatval($result2['postponed_total']))) : 0 <= 20)? "نعم" : "لا" : ""}}</div>--}}
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['employee_commission'])) : ""}}
                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['employee_commission'])) : ""}}
                                    @else
                                        <span>0</span>
                                    @endif

                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_sales_manager'])) : ""}}
                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_sales_manager'])) : ""}}
                                    @else
                                        <span>0</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_area_manager'])) : ""}}
                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_area_manager'])) : ""}}
                                    @else
                                        <span>0</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_store_manager'])) : ""}}
                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_store_manager'])) : ""}}
                                    @else
                                        <span>0</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_mat_dev1'])) : ""}}
                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_mat_dev1'])) : ""}}
                                    @else
                                        <span>0</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    <div class="text-center text-gray-800 text-sm">
                                        @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                            {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}
                                        @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                            {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}
                                        @else
                                            <span>0</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                        <?php $employee_profit += ($result2 ? floatval($result2['employee_profit']) : 0) ?>
                        <?php $employee_sales += ($result2 ? floatval($result2['tot']) : 0) ?>
                        <?php $percentage_area_employee += ($result2 ? floatval($result2['percentage_area_employee']) : 0) ?>
                        <?php $employee_postponed += ($result2 ? floatval($result2['employee_postponed']) : 0) ?>
                        <?php $employee_postponed_due += ($result2 ? floatval($result2['employee_postponed_due']) : 0) ?>
                        <?php $employee_postponed_due_percentage =  $employee_postponed == 0? 0 : ($employee_postponed_due/$employee_postponed)*100 ?>
                        <?php //$employee_commission += ($result2 ? (((floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240) || (number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)) ? floatval($result2['employee_commission']) : 0) : 0) ?>
                        <?php $employee_commission += ($result2 && $pay? floatval($result2['employee_commission']) : 0) ?>
                        <?php $calc_sales_manager += ($result2 && $pay? floatval($result2['calc_sales_manager']) : 0) ?>
                        <?php $calc_area_manager += ($result2 && $pay? floatval($result2['calc_area_manager']) : 0) ?>
                        <?php $calc_store_manager += ($result2 && $pay? floatval($result2['calc_store_manager']) : 0) ?>
                        <?php $calc_mat_dev1 += ($result2 && $pay? floatval($result2['calc_mat_dev1']) : 0) ?>
                        <?php $calc_mat_dev2 += ($result2 && $pay? floatval($result2['calc_mat_dev2']) : 0) ?>
                @elseif(($result2 && $result2['Employeecode'] == \Illuminate\Support\Facades\Auth::user()->emp_code && \Illuminate\Support\Facades\Auth::user()->user_group->read_type == '1') || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                    <tr>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? $result2['Employeecode'] : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? $result2['EmployeeName'] : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    {{--                            {{$result2 ? $result2->role : ""}}--}}

                                    @if($result2['role'] == "area_manager")
                                        <span>مدير منطقة</span>
                                    @elseif($result2['role'] == "store_manager")
                                        <span>مدير معرض</span>
                                    @elseif($result2['role'] == "sales_manager")
                                        <span>مدير مبيعات</span>
                                    @elseif($result2['role'] == "mat_dev_manager1")
                                        <span>تطوير مواد 1</span>
                                    @elseif($result2['role'] == "mat_dev_manager2")
                                        <span>تطوير مواد 2</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['employee_profit'])) : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['tot'])) : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                                <?php $tot =  $result2 ? floatval($result2['tot']) : 0; ?>
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if($tot >= 0 && $tot <= 120000)
                                        30
                                        <?php $commission_percentage[$result2['role']] = 30; ?>
                                    @elseif($tot > 120000 && $tot <= 240000)
                                        60
                                        <?php $commission_percentage[$result2['role']] = 60; ?>
                                    @elseif($tot > 240000 && $tot < 400000)
                                        80
                                        <?php $commission_percentage[$result2['role']] = 80; ?>
                                    @elseif($tot >= 400000)
                                        100
                                        <?php $commission_percentage[$result2['role']] = 100; ?>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['percentage_area_employee'])) : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['employee_postponed'])) : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{ $result2['employee_postponed_due'] &&  $result2['employee_postponed']  && floatval(number_format($result2['employee_postponed'])) != 0? number_format(floatval($result2['employee_postponed_due'])) . " (%".number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100).")" : 0}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">{{$result2 ?  ($result2['employee_postponed_due'] &&  $result2['employee_postponed']  && floatval(number_format($result2['employee_postponed'])) != 0? \Carbon\Carbon::parse($result2['oldest_voucher'])->format('Y-m-d')." (". \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) ." يوم)" : "-") : ""}}</div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                @if(floatval(number_format($result2['employee_postponed'])) == 0)
                                    @if( \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        <span style="font-weight: bold; color: green">نعم</span>
                                            <?php $pay = true; ?>
                                    @else
                                        <span style="font-weight: bold; color: red">لا</span>
                                            <?php $pay = false; ?>
                                    @endif
                                @else
                                    @if(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        <span style="font-weight: bold; color: green">نعم</span>
                                            <?php $pay = true; ?>
                                    @else
                                        <span style="font-weight: bold; color: red">لا</span>
                                            <?php $pay = false; ?>
                                    @endif
                                @endif

                                {{--                            @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                                {{--                                <span style="font-weight: bold; color: green">نعم</span>--}}
                                {{--                            @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)--}}
                                {{--                                <span style="font-weight: bold; color: green">نعم</span>--}}
                                {{--                            @else--}}
                                {{--                                <span style="font-weight: bold; color: red">لا</span>--}}
                                {{--                            @endif--}}



                                {{--                        <div class="text-center text-gray-800 text-lg">{{$result2 ?--}}
                                {{--                             (($result2['days'] <= 240 && ((!is_null(number_format(floatval($result2['postponed_partial']))) && number_format(floatval($result2['postponed_partial'])) != 0 && !is_null(number_format(floatval($result2['postponed_total']))) && number_format(floatval($result2['postponed_total'])) != 0))? (number_format(floatval($result2['postponed_partial']))/number_format(floatval($result2['postponed_total']))) : 0 <= 20)? "نعم" : "لا" : ""}}</div>--}}
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['employee_commission'])) : ""}}
                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['employee_commission'])) : ""}}
                                    @else
                                        <span>0</span>
                                    @endif

                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_sales_manager'])) : ""}}
                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_sales_manager'])) : ""}}
                                    @else
                                        <span>0</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_area_manager'])) : ""}}
                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_area_manager'])) : ""}}
                                    @else
                                        <span>0</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_store_manager'])) : ""}}
                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_store_manager'])) : ""}}
                                    @else
                                        <span>0</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_mat_dev1'])) : ""}}
                                    @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                        {{$result2 ? number_format(floatval($result2['calc_mat_dev1'])) : ""}}
                                    @else
                                        <span>0</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border p-2 whitespace-nowrap">
                            <div>
                                <div class="text-center text-gray-800 text-sm">
                                    <div class="text-center text-gray-800 text-sm">
                                        @if(floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                            {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}
                                        @elseif(number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)
                                            {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}
                                        @else
                                            <span>0</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                        <?php $employee_profit += ($result2 ? floatval($result2['employee_profit']) : 0) ?>
                        <?php $employee_sales += ($result2 ? floatval($result2['tot']) : 0) ?>
                        <?php $percentage_area_employee += ($result2 ? floatval($result2['percentage_area_employee']) : 0) ?>
                        <?php $employee_postponed += ($result2 ? floatval($result2['employee_postponed']) : 0) ?>
                        <?php $employee_postponed_due += ($result2 ? floatval($result2['employee_postponed_due']) : 0) ?>
                        <?php $employee_postponed_due_percentage =  $employee_postponed == 0? 0 : ($employee_postponed_due/$employee_postponed)*100 ?>
                        <?php //$employee_commission += ($result2 ? (((floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240) || (number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240)) ? floatval($result2['employee_commission']) : 0) : 0) ?>
                        <?php $employee_commission += ($result2 && $pay? floatval($result2['employee_commission']) : 0) ?>
                        <?php $calc_sales_manager += ($result2 && $pay? floatval($result2['calc_sales_manager']) : 0) ?>
                        <?php $calc_area_manager += ($result2 && $pay? floatval($result2['calc_area_manager']) : 0) ?>
                        <?php $calc_store_manager += ($result2 && $pay? floatval($result2['calc_store_manager']) : 0) ?>
                        <?php $calc_mat_dev1 += ($result2 && $pay? floatval($result2['calc_mat_dev1']) : 0) ?>
                        <?php $calc_mat_dev2 += ($result2 && $pay? floatval($result2['calc_mat_dev2']) : 0) ?>
                @endif


{{--                @if((floatval(number_format($result2['employee_postponed'])) == 0 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240) || (number_format(floatval($result2['employee_postponed_due'])/floatval($result2['employee_postponed'])*100) <= 20 && \Carbon\Carbon::parse($result2['oldest_voucher'])->diffInDays($last_date) <= 240))--}}
{{--                    {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}--}}
{{--                @elseif()--}}
{{--                    {{$result2 ? number_format(floatval($result2['calc_mat_dev2'])) : ""}}--}}
{{--                @else--}}
{{--                    <span>0</span>--}}
{{--                @endif--}}
            @endforeach
            </tbody>
            <tfoot>
            <tr style="background-color: papayawhip; border: 2px solid black; font-weight: bold">
                <td colspan="3" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">المجموع</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($employee_profit) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($employee_sales) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ "%" . number_format($percentage_area_employee) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($employee_postponed) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($employee_postponed_due) . " (%". number_format($employee_postponed_due_percentage) . ")" }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($employee_commission) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_sales_manager) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_area_manager) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_store_manager) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_mat_dev1) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_mat_dev2) }}</td>
            </tr>
            <tr style="background-color: papayawhip; border: 2px solid black; font-weight: bold">
                <td colspan="13" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">مبلغ استحقاق الحافز الشهري</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_area_manager*($commission_percentage && array_key_exists('area_manager', $commission_percentage)? ($commission_percentage['area_manager']/100) : 0)) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_store_manager*($commission_percentage && array_key_exists('store_manager', $commission_percentage)? ($commission_percentage['store_manager']/100): 0)) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_mat_dev1*($commission_percentage && array_key_exists('mat_dev_manager1', $commission_percentage)? ($commission_percentage['mat_dev_manager1']/100): 0)) }}</td>
                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($calc_mat_dev2*($commission_percentage && array_key_exists('mat_dev_manager2', $commission_percentage)? ($commission_percentage['mat_dev_manager2']/100): 0)) }}</td>
            </tr>
            </tfoot>
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
    <script>
        Livewire.on('show-container', () => {
            div = document.getElementById("report-btn");
            div_title = document.getElementById("report_title");
            area = document.getElementById("area_id");
            date = document.getElementById("date");

            div.classList.remove("hide");
            div_title.innerHTML = "تقرير " + area.options[area.selectedIndex].text + "(" + date.value + ")"

        })

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
@stop
