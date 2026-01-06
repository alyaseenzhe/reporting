<div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">ملخص تسويق إدارة فنية</div>
    </div>
    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="date" name="start_date" wire:model="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('start_date') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="date" name="end_date" wire:model="end_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('end_date') border: solid 1px #fda4af; @enderror">
                    @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
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
                @if(\Illuminate\Support\Facades\Auth::user()->role == 'a')
                    @if($data)
                        <div class="mt-8 text-center w-full">
                        <button wire:click.prevent="emailReport" wire:loading.attr="disabled"
                                style="background-color: #9b3030;" class="w-full btn hover:bg-indigo-600 text-white">
                            <span class="mr-2 font-bold" wire:loading.remove wire:target="emailReport">
                                <span></span>
                                <span>إرسال التقرير</span>
                            </span>
                            <span class="mr-2 font-bold" wire:loading wire:target="emailReport">
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
    <div id="report-btn" wire:loading.remove wire:target="generateReport" class="hide printable">
        {{-- table 2 (details) --}}
        <div id="tbl2-container" class="overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">رقم الموظف</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">اسم الموظف</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">المنصب</div>
                    </th>
{{--                    <th style="border-left: 2px solid black;" class="border p-2">--}}
{{--                        <div class="text-sm">فروع تمت زيارتها خلال الإسبوع</div>--}}
{{--                    </th>--}}
                    <th style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-sm">عدد زيارات الاسبوع</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-sm">اجمالي مبيعات خلال الزيارة</div>
                    </th>
{{--                    <th style="border-left: 2px solid black;" class="border p-2">--}}
{{--                        <div class="text-sm">--}}
{{--                            <p>فروع زيارتهم شهر سابق</p>--}}
{{--                        <small>( {{ $month_start_date }} - {{$month_end_date}} )</small>--}}
{{--                        </div>--}}
{{--                    </th>--}}
                    <th style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-sm">
                            <p>مبيعات الفروع التي تم زيارتهم شهرين سابقين</p>
                            <small class="whitespace-nowrap">( {{ $month_start_date }} - {{$month_end_date}} )</small>
                        </div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-sm">
                            معدل مبيعات
                            <u>كل الفروع</u>
                            خلال اسبوع
                            </div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">

{{--                {{ $s = $s_total }}--}}
{{--                {{ $m = $m_total }}--}}
{{--                {{ $b = $b_total }}--}}
                @foreach($data as $record)

                    <tr>
                        <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                            {{ $record['emp_id'] }}
                        </td>
                        <td style="border: 2px solid black; background-color: #faebd7" class="border p-2 whitespace-nowrap">
                            {{ $record['emp_name'] }}
                        </td>
                        <td style="border: 2px solid black; background-color: #faebd7" class="border p-2">
                            {{ $record['role_name'] }}
                        </td>
{{--                        <td style="border: 2px solid black; background-color: #f0fff0" class="border p-2">--}}
{{--                            @if(!empty($record['places']))--}}
{{--                                {{ $record['places'] }}--}}
{{--                            @else--}}
{{--                                <span>--</span>--}}
{{--                            @endif--}}
{{--                        </td>--}}
                        <td style="border: 2px solid black; background-color: #f0fff0" class="border p-2">
                            @if(empty($record['visits']))
                                --
                            @else
                                {{ $record['visits'] }}
                            @endif
                        </td>
                        <td style="border: 2px solid black; background-color: #f0fff0" class="border">
                            @if(!empty($record['visits']))
                                    @if($record['role'] == 40)
                                    <div class="w-full h-full">
                                            <?php $_s_total = 0; ?>
                                        <table>
                                            @foreach($wk_s_total as $s)
                                                    <?php $_s_total += floatval($s['total']); ?>
                                                {{--                                                <div class="flex flex-row w-full h-full">--}}
                                                {{--                                                    <div class="w-full h-full" style="border: 2px solid black">{{ $m['place_name'] }}</div>--}}
                                                {{--                                                    <div class="w-full h-full" style="border: 2px solid black">{{ number_format($m['total']) }}</div>--}}
                                                {{--                                                </div>--}}
                                                <tr>

                                                    <td class="w-full h-full" style="border: 1px solid black">
                                                        <p>{{ $s['place_name'] }}</p>
                                                        <p>({{$s['min_date']}})</p>
                                                    </td>
                                                    <td class="w-full h-full" style="border: 1px solid black">{{ number_format($s['total']) }}</td>
                                                </tr>
                                                {{--                                    {{ $m['total'] }}--}}
                                            @endforeach
                                        </table>
                                        <div class="w-full h-full flex-1 bold" style="border: 1px solid black">{{ number_format($_s_total) }}</div>
                                    </div>

{{--                                        @foreach($wk_s_total as $s)--}}
{{--                                            {{ var_dump($s) }}--}}
{{--                                        @endforeach--}}
                                    @elseif($record['role'] == 14)
                                    <div class="w-full h-full">
                                            <?php $_b_total = 0; ?>
                                        <table>

                                            @foreach($wk_b_total as $b)
                                                    <?php $_b_total += floatval($b['total']); ?>
                                                {{--                                                <div class="flex flex-row w-full h-full">--}}
                                                {{--                                                    <div class="w-full h-full" style="border: 2px solid black">{{ $m['place_name'] }}</div>--}}
                                                {{--                                                    <div class="w-full h-full" style="border: 2px solid black">{{ number_format($m['total']) }}</div>--}}
                                                {{--                                                </div>--}}
                                                <tr>

                                                    <td class="w-full h-full" style="border: 1px solid black">
                                                        <p>{{ $b['place_name'] }}</p>
                                                        <p>({{$b['min_date']}})</p>
                                                    </td>
                                                    <td class="w-full h-full" style="border: 1px solid black">{{ number_format($b['total']) }}</td>
                                                </tr>
                                                {{--                                    {{ $m['total'] }}--}}
                                            @endforeach
                                        </table>
                                        <div class="w-full h-full flex-1 bold" style="border: 1px solid black">{{ number_format($_b_total) }}</div>
                                    </div>

{{--                                        @foreach($wk_b_total as $b)--}}
{{--                                            {{ var_dump($b) }}--}}
{{--                                        @endforeach--}}
                                    @elseif($record['role'] == 43)
                                        <div class="w-full h-full">
                                            <?php $_m_total = 0; ?>
                                            <table>

                                            @foreach($wk_m_total as $m)
                                                <?php $_m_total += floatval($m['total']); ?>
{{--                                                <div class="flex flex-row w-full h-full">--}}
{{--                                                    <div class="w-full h-full" style="border: 2px solid black">{{ $m['place_name'] }}</div>--}}
{{--                                                    <div class="w-full h-full" style="border: 2px solid black">{{ number_format($m['total']) }}</div>--}}
{{--                                                </div>--}}
                                                <tr>

                                                    <td class="w-full h-full" style="border: 1px solid black">
                                                        <p>{{ $m['place_name'] }}</p>
                                                        <p>({{$m['min_date']}})</p>
                                                    </td>
                                                    <td class="w-full h-full" style="border: 1px solid black">{{ number_format($m['total']) }}</td>
                                                </tr>
                                                {{--                                    {{ $m['total'] }}--}}
                                            @endforeach
                                            </table>
                                            <div class="w-full h-full flex-1 bold" style="border: 1px solid black">{{ number_format($_m_total) }}</div>
                                        </div>
                                    @endif
                            @else
                                <span>0</span>
                            @endif
{{--                            {{ number_format($record['total']) }}--}}
{{--                            {{ number_format($record['total']) }}--}}
                        </td>
{{--                        <td style="border: 2px solid black; background-color: #f0f8ff" class="border p-2">--}}
{{--                            @if(!empty($record['month_places']))--}}
{{--                                {{ $record['month_places'] }}--}}
{{--                            @else--}}
{{--                                <span>--</span>--}}
{{--                            @endif--}}
{{--                        </td>--}}
                        <td style="border: 2px solid black; background-color: #f0f8ff" class="border p-2">
                            <div class="w-full h-full">
                                    <?php $month_total = 0; ?>
                                    <?php $previous_year_month_total = 0; ?>
                                    <?php $current_avg_sales_total = 0; ?>
                                    <?php $previous_avg_sales_total = 0; ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="border p-2" style="border: 1px solid black">الفرع</th>
                                            <th class="border p-2" style="border: 1px solid black">عدد اسابيع</th>
                                            <th class="border p-2" style="border: 1px solid black; background-color: #ffffe0;">المبيعات حالية</th>
                                            <th class="border p-2" style="border: 1px solid black; background-color: #ffffe0;">معدل اسبوعي</th>
                                            <th class="border p-2" style="border: 1px solid black; background-color: khaki;">مبيعات سابقة</th>
                                            <th class="border p-2" style="border: 1px solid black; background-color: khaki;">معدل سابق اسبوعي</th>
                                            <th class="border p-2" style="border: 1px solid black">نسبة النمو</th>
                                        </tr>
                                    </thead>
                                    @foreach($record['month_total'] as $key => $item)
                                            <?php $num_of_branches = count($record['month_total']);   ?>
                                            <?php $month_total += floatval($item[2]); ?>
                                            <?php $previous_year_month_total += floatval($record['year_total'][$key][2]); ?>
                                            <?php $current_avg_sales = \Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date) == 0 ?  $item[2] : $item[2]/\Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date); ?>
                                            <?php $previous_avg_sales = \Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date) == 0 ?  $record['year_total'][$key][2] : $record['year_total'][$key][2]/\Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date); ?>
                                            <?php $current_avg_sales_total += \Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date) == 0 ?  $item[2] : $item[2]/\Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date); ?>
                                            <?php $previous_avg_sales_total += \Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date) == 0 ?  $record['year_total'][$key][2] : $record['year_total'][$key][2]/\Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date); ?>
                                        <tr>
                                            <td class="w-full h-full" style="border: 1px solid black">
                                                <p>{{ $key }}</p>
                                                <p>({{ $item[1] }})</p>
                                            </td>
                                            <td class="w-full h-full" style="border: 1px solid black">{{ \Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date) }}</td>
                                            <td class="w-full h-full" style="border: 1px solid black; background-color: #ffffe0;">{{ number_format($item[2]) }}</td>
                                            <td class="w-full h-full" style="border: 1px solid black; background-color: #ffffe0;">{{ \Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date) == 0 ?  number_format($item[2]) : number_format($item[2]/\Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date)) }}</td>
                                            <td class="w-full h-full" style="border: 1px solid black; background-color: khaki;">{{ number_format($record['year_total'][$key][2]) }}</td>
                                            <td class="w-full h-full" style="border: 1px solid black; background-color: khaki;">{{ \Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date) == 0 ?  number_format($record['year_total'][$key][2]) : number_format($record['year_total'][$key][2]/\Carbon\Carbon::parse($item[1])->diffInWeeks($month_end_date)) }}</td>
                                            <td class="w-full h-full" style="border: 1px solid black; direction: ltr; font-weight: bold; @if(((($current_avg_sales/$previous_avg_sales)-1)*100) > 0) color: green; @else color: red; @endif">%{{ number_format((($current_avg_sales/$previous_avg_sales)-1)*100) }}</td>
                                        </tr>
                                        {{--                                    {{ $m['total'] }}--}}
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="w-full h-full" style="border: 1px solid black">--</td>
                                        <td class="w-full h-full" style="border: 1px solid black">{{ number_format($month_total) }}</td>
                                        <td class="w-full h-full" style="border: 1px solid black">{{ number_format($current_avg_sales_total/$num_of_branches) }}</td>
                                        <td class="w-full h-full" style="border: 1px solid black">{{ number_format($previous_year_month_total) }}</td>
                                        <td class="w-full h-full" style="border: 1px solid black">{{ number_format($previous_avg_sales_total/$num_of_branches) }}</td>
                                        <td class="w-full h-full" style="border: 1px solid black; font-weight: bold; direction: ltr; @if($previous_year_month_total <= 0 || ((($month_total/$previous_year_month_total)-1)*100) <= 0) color: red; @else color:green; @endif">%{{ $previous_year_month_total == 0 ? '0' : number_format((($month_total/$previous_year_month_total)-1)*100) }}</td>
                                    </tr>
                                </table>
{{--                                <div class="w-full h-full flex-1 bold" style="border: 1px solid black">{{ number_format($month_total) }}</div>--}}
                            </div>
{{--                            {{ number_format($record['month_total']) }}--}}
                        </td>
                        <td style="border: 2px solid black; background-color: #fdf5e6" class="border p-2">
                            {{ number_format($record['week_total']/12) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
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
