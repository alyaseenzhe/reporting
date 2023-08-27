<div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">مستهدف الأصناف</div>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">المخزن
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="dept_id" name="dept_id" wire:model="dept_id"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('item_id') border: solid 1px #fda4af; @enderror">
                        <option value="-1">الرجاء اختيار المخزن</option>
                        <option value="3">الاحساء</option>
                        <option value="509">منطقة القرية العليا</option>
                        <option value="10">جدة</option>
                        <option value="510">منطقة المدينة المنورة</option>
                        <option value="7">الرياض</option>
                        <option value="13">وادي الدواسر</option>
                        <option value="4">الجوف</option>
                        <option value="6">الدمام</option>
                        <option value="5">الخرج</option>
                        <option value="12">نجران</option>
                        <option value="515">منطقة الباحة</option>
                        <option value="11">حائل</option>
                        <option value="9">تبوك</option>
                        <option value="8">القصيم</option>
                        <option value="505">ساجر</option>


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

        {{-- table 2 (details) --}}
        <div id="tbl2-container" class="overflow-x-auto overflow-y-auto" style="height: 700px">
            <table id="tbl2" style="border: 2px solid black;" class="table-container w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr>
                    <th rowspan="2" style="border: 2px solid black; background-color: #666666; z-index: 20" class="col-id-no fixed-header border p-2 whitespace-nowrap">
                        <div class="text-sm">#</div>
                    </th>
                    <th rowspan="2" style="border: 2px solid black; background-color: #666666; z-index: 20" class="col-first-name fixed-header border p-2 whitespace-nowrap">
                        <div class="text-sm">الاسم</div>
                    </th>
                    <th rowspan="2" style="border: 2px solid black; background-color: #666666; z-index: 15;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الوحدة</div>
                    </th>
                    <th rowspan="2" style="border: 2px solid black; background-color: #666666; z-index: 15;" class="border p-2">
                        <div class="text-sm">المستهدف المنفذ</div>
                    </th>
                    <th rowspan="2" style="border: 2px solid black; background-color: #666666; z-index: 15;" class="border p-2">
                        <div class="text-sm">القيمة</div>
                    </th>
                    @foreach ($list as $year_key => $year)
                        @foreach ($year as $month)
                            {{--                            <th class="border p-2">--}}
                            {{--                                <div class="text-sm">{{ $year_key."_".$month."_T" }}</div>--}}
                            {{--                            </th>--}}
                            {{--                            <th class="border p-2">--}}
                            {{--                                <div class="text-sm">{{ $year_key."_".$month."_R" }}</div>--}}
                            {{--                            </th>--}}
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{ $year_key."-".$month }}</div>
                            </th>
                        @endforeach
                    @endforeach
                </tr>
{{--                <tr style="position: sticky;top: 39.5px;">--}}
{{--                    <th class="fixed-header col-id-no" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black; width: 50%">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black; width: 50%">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">T</th>--}}
{{--                    <th class="fixed-header" style="border: 2px solid black;">R</th>--}}
{{--                </tr>--}}
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">

                @if($results)
                        <?php
                        $grand_value_total = 0;
//                        dd($results[0][0]['VendorNo']);
                        $vendor_id = "*";

                        ?>

                    @foreach($results[0] as $record)
                        @if($record['VendorNo'] != $vendor_id)
                                <?php $vendor_id = $record['VendorNo'] ?>
                            <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                                <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['vendor_code'] }}</td>
                                <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-first-name" scope="row">{{ $record['vendor_name'] }}</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                                <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
                                {{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
                            </tr>
                        @endif
                            <?php $vendor_id = $record['VendorNo'] ?>
                        <tr>
                            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap col-id-no" scope="row">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{ $record['Code'] }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap col-first-name" scope="row">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{ $record['Arabic_Name'] }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{ $record['BaseUnits'] }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div>
                                        <?php
                                        $total_target = 0;
                                        $total_target +=$record['rmonth1'] > 0 ? $record['rmonth1'] : $record['tmonth1'] ;
                                        $total_target +=$record['rmonth2'] > 0 ? $record['rmonth2'] : $record['tmonth2'] ;
                                        $total_target +=$record['rmonth3'] > 0 ? $record['rmonth3'] : $record['tmonth3'] ;
                                        $total_target +=$record['rmonth4'] > 0 ? $record['rmonth4'] : $record['tmonth4'] ;
                                        $total_target +=$record['rmonth5'] > 0 ? $record['rmonth5'] : $record['tmonth5'] ;
                                        $total_target +=$record['rmonth6'] > 0 ? $record['rmonth6'] : $record['tmonth6'] ;
                                        $total_target +=$record['rmonth7'] > 0 ? $record['rmonth7'] : $record['tmonth7'] ;
                                        $total_target +=$record['rmonth8'] > 0 ? $record['rmonth8'] : $record['tmonth8'] ;
                                        $total_target +=$record['rmonth9'] > 0 ? $record['rmonth9'] : $record['tmonth9'] ;
                                        $total_target +=$record['rmonth10'] > 0 ? $record['rmonth10'] : $record['tmonth10'] ;
                                        $total_target +=$record['rmonth11'] > 0 ? $record['rmonth11'] : $record['tmonth11'] ;
                                        $total_target +=$record['rmonth12'] > 0 ? $record['rmonth12'] : $record['tmonth12'] ;

                                        ?>
                                    <div class="text-center text-gray-800 text-sm">{{ number_format($total_target) }}</div>
                                    {{--                                    <div class="text-center text-gray-800 text-sm">{{ number_format($record['tmonth1']+$record['tmonth2']+$record['tmonth3']+$record['tmonth4']+$record['tmonth5']+$record['tmonth6']+$record['tmonth7']+$record['tmonth8']+$record['tmonth9']+$record['tmonth10']+$record['tmonth11']+$record['tmonth12']) }}</div>--}}
                                    {{--                                    <div class="text-center text-gray-800 text-sm">{{ number_format($record['total_target']) }}</div>--}}
                                </div>
                            </td>
                            <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">
                                            <?php
                                            $total_value = 0;
                                            $total_value +=$record['rmonth1'] > 0 ? $record['rmonth1']*$record['WholeSale'] : $record['tmonth1']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth2'] > 0 ? $record['rmonth2']*$record['WholeSale'] : $record['tmonth2']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth3'] > 0 ? $record['rmonth3']*$record['WholeSale'] : $record['tmonth3']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth4'] > 0 ? $record['rmonth4']*$record['WholeSale'] : $record['tmonth4']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth5'] > 0 ? $record['rmonth5']*$record['WholeSale'] : $record['tmonth5']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth6'] > 0 ? $record['rmonth6']*$record['WholeSale'] : $record['tmonth6']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth7'] > 0 ? $record['rmonth7']*$record['WholeSale'] : $record['tmonth7']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth8'] > 0 ? $record['rmonth8']*$record['WholeSale'] : $record['tmonth8']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth9'] > 0 ? $record['rmonth9']*$record['WholeSale'] : $record['tmonth9']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth10'] > 0 ? $record['rmonth10']*$record['WholeSale'] : $record['tmonth10']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth11'] > 0 ? $record['rmonth11']*$record['WholeSale'] : $record['tmonth11']*$record['WholeSale'] ;
                                            $total_value +=$record['rmonth12'] > 0 ? $record['rmonth12']*$record['WholeSale'] : $record['tmonth12']*$record['WholeSale'] ;
                                            $grand_value_total += $total_value;
                                            ?>
                                        {{ number_format($total_value, 2) }}
                                        {{--                                        {{ number_format($record['Value'], 2) }}--}}
                                    </div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth1'] > 0 ? number_format($record['rmonth1']) :  number_format($record['tmonth1']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth2'] > 0 ? number_format($record['rmonth2']) :  number_format($record['tmonth2']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth3'] > 0 ? number_format($record['rmonth3']) :  number_format($record['tmonth3']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth4'] > 0 ? number_format($record['rmonth4']) :  number_format($record['tmonth4']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth5'] > 0 ? number_format($record['rmonth5']) :  number_format($record['tmonth5']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth6'] > 0 ? number_format($record['rmonth6']) :  number_format($record['tmonth6']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth7'] > 0 ? number_format($record['rmonth7']) :  number_format($record['tmonth7']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth8'] > 0 ? number_format($record['rmonth8']) :  number_format($record['tmonth8']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth9'] > 0 ? number_format($record['rmonth9']) :  number_format($record['tmonth9']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth10'] > 0 ? number_format($record['rmonth10']) :  number_format($record['tmonth10']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth11'] > 0 ? number_format($record['rmonth11']) :  number_format($record['tmonth11']) }}</div>
                                </div>
                            </td>
                            <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="w-10 text-center text-gray-800 text-sm">{{ $record['rmonth12'] > 0 ? number_format($record['rmonth12']) :  number_format($record['tmonth12']) }}</div>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                @endif
                </tbody>
                <tfoot>
                <tr style="background-color: #dcdcdc; border: 2px solid black; font-weight: bold">
                    {{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
                    {{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
                    {{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
                    <td colspan="4" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">مجموع القيمة</td>
                    <td  style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ isset($grand_value_total)? number_format($grand_value_total) : 0 }}</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
{{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
                    {{--                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">-</td>--}}
                </tr>
                </tfoot>
            </table>
        </div>

    </div>

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
    <script>
        Livewire.on('show-container', () => {

            // if ( $.fn.dataTable.isDataTable('#tbl2') ) {
            //     $('#tbl2').DataTable().destroy();
            //     $('#tbl2').empty();
            // }
            //
            div = document.getElementById("report-btn");
            // div_title = document.getElementById("report_title");
            // area = document.getElementById("area_id");
            // date = document.getElementById("date");
            //
            div.classList.remove("hide");
            // div_title.innerHTML = "تقرير " + area.options[area.selectedIndex].text + "(" + date.value + ")"

            // $('#tbl2').DataTable();

        })

    </script>
@stop
@section('css-scripts')
    <style>

        .freeze-table {
            border-spacing: 0;
            font-family: "Segoe UI", sans-serif, "Helvetica Neue";
            font-size: 14px;
            padding: 0;
            border: 1px solid #ccc;
        }

        thead th {
            top: 0;
            position: sticky;
            background-color: #666;
            color: #fff;
            z-index: 20;
            min-height: 30px;
            height: 30px;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #F2F2F2;
        }

        th, td {
            padding: 0;
            outline: 1px solid #000000;
            border: none;
            /*outline-offset: 1px;*/
            padding-right: 5px;
        }

        tr {
            min-height: 25px;
            height: 25px;
        }
        .col-id-no {
            right: 0;
            position: sticky;
        }
        .col-first-name {
            right: 65px;
            position: sticky;
        }

        .fixed-header {
            z-index: 20;
        }

        tr:nth-child(even) td[scope=row] {
            background-color: #f2f2f2;
        }

        tr:nth-child(odd) td[scope=row] {
            background-color: white;
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
