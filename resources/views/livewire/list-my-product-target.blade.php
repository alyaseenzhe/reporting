<div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">متابعة المستهدف</div>
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
                        <option value="all">جميع المخازن</option>
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
                @if($dept_id != -1)
                    <div class="w-full">
                        <label class="block font-bold mb-2">المهندسين
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="user_id" name="user_id" wire:model="user_id"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('item_id') border: solid 1px #fda4af; @enderror">
                            <option value="-1">الرجاء اختيار المهندس</option>
                            <option value="all">جميع المهندسين</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endif
                @if($user_id != -1)
                    <div class="w-full">
                        <label class="block font-bold mb-2">أول شهر
                            <span class="text-red-500">*</span>
                        </label>
                        <input id="date" type="month" name="selected_month" wire:model="selected_month"
                               class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                               style="@error('item_id') border: solid 1px #fda4af; @enderror">
                        @error('selected_month') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endif
                @if($selected_month)
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
                @endif
            </div>
        </div>
    </div>


    @if($show_msg)
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
                            <td style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorNo'] }}</td>
                            <td colspan="15" style="border: 2px solid black;background-color: #dcdcdc" class="border p-2 whitespace-nowrap col-id-no" scope="row">{{ $record['VendorName'] }}</td>
                        </tr>
                    @endif
                        <?php $vendor_id = $record['VendorNo'] ?>
                    <tr>
                        <th colspan="16" style="border: 2px solid black; background-color: #faebd7" class="col-id-no fixed-header border p-2 whitespace-nowrap">
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
                            <?php $new_tr = []; ?>
                            <?php $total_new_tr = 0; ?>
                            <?php $old_tr = []; ?>
                            <?php $total_old_tr = 0; ?>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">الشهر</div>
                        </th>
                        @foreach ($list as $year_key => $year)
                            @foreach ($year as $month)
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    <div class="text-sm">{{ $year_key."-".$month }}</div>
                                </th>
                            @endforeach
                        @endforeach
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">السعر</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">مجموع كمية</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">مجموع قيمة</div>
                        </th>
                    </tr>
                    <tr>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">المستهدف</div>
                        </th>
                        @foreach ($list as $year_key => $year)
                            @foreach ($year as $month)
                                <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                    {{--                                <input id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$loop->iteration}}" type="number" wire:model="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$loop->iteration}}" class="form-input w-full">--}}

                                        <?php
                                        if ($dept_id == "all") {
                                            $new_result = key_exists('ProductCode', $record) ? $new_targets->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first(): 0;
                                        }
                                        else {
                                            $new_result = key_exists('ProductCode', $record) ? $new_targets->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->where('branch', $dept_id)->first(): 0;
                                        }
                                        ?>
                                        <?php //$new_result = key_exists('ProductCode', $record) ? $new_targets->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->where('branch', $dept_id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->first(): 0; ?>
                                    {{--                                <div class="text-sm">{{ dd($record['ProductCode']) }}</div>--}}
                                    <div class="text-sm">{{ $new_result ? $new_result->target : 0  }}</div>
                                        <?php array_push($new_tr, ($new_result ? $new_result->target : 0) ) ?>
                                    @php $total_new_tr += $new_result ? $new_result->target : 0; @endphp

                                    {{--                                <div class="text-sm">{{ $new_targets->where('product_id', '220020')->where('month', $month)->where('year', $year_key)->where('branch', $dept_id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->first() ? $new_targets->where('product_id', '220020')->where('month', $month)->where('year', $year_key)->where('branch', $dept_id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->first()->target : 0 }}</div>--}}
                                    {{--                                <div class="text-sm">{{ $new_targets->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->where('branch', $dept_id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->first() }}</div>--}}
                                </th>
                            @endforeach
                        @endforeach
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $record['MaxDiscount'] }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $total_new_tr }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $total_new_tr*$record['MaxDiscount'] }}</div>
                        </th>
                    </tr>
                    <tr>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">مبيعات</div>
                        </th>
                        {{--                    @php $target_counter = 1; @endphp--}}
                        {{--                    @foreach ($list as $year_key => $year)--}}
                        {{--                        @foreach ($year as $month)--}}
                        {{--                            @php $target_counter++; @endphp--}}
                        {{--                            <th style="border: 2px solid black; z-index: 10" class="border p-2">--}}
                        {{--                                <div class="text-sm">{{ number_format($record['month'.$target_counter]) }}</div>--}}
                        {{--                                <?php array_push($old_tr, (number_format($record['month'.$target_counter])) ) ?>--}}
                        {{--                            </th>--}}
                        {{--                        @endforeach--}}
                        {{--                    @endforeach--}}

                        @for($i = 1; $i <= 12; $i++)
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{ number_format($record['month'.$i]) }}</div>
                                    <?php array_push($old_tr, (number_format($record['month'.$i])) ) ?>
                                @php $total_old_tr += intval(number_format($record['month'.$i])); @endphp

                            </th>
                        @endfor
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $record['MaxDiscount'] }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $total_old_tr }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $total_old_tr*$record['MaxDiscount'] }}</div>
                        </th>

                    </tr>
                    <tr>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">الفرق %</div>
                        </th>
                        {{--                    @foreach ($list as $year_key => $year)--}}
                        {{--                        @foreach ($year as $month)--}}
                        {{--                            <th style="border: 2px solid black; z-index: 10" class="border p-2">--}}
                        {{--                                <div class="text-sm">{{ $old_tr[$loop->iteration-1] == 0? $new_tr[$loop->iteration-1] == 0 ? 0 : "100": number_format(intval($new_tr[$loop->iteration-1])/intval($old_tr[$loop->iteration-1])*100) }}</div>--}}
                        {{--                            </th>--}}
                        {{--                        @endforeach--}}
                        {{--                    @endforeach--}}
                        @for($i = 1; $i <= 12; $i++)
                            @php $data = $old_tr[$i-1] == 0? $new_tr[$i-1] == 0 ? 0 : 100: number_format(intval($new_tr[$i-1])/intval($old_tr[$i-1])*100); @endphp
                            <th style="border: 2px solid black; z-index: 10; @if($data <= 0) background-color: #ffe4e4; @else background-color: #e4ffea; @endif" class="border p-2">
                                <div class="text-sm">{{ $old_tr[$i-1] == 0? $new_tr[$i-1] == 0 ? 0 : "100": number_format(intval($new_tr[$i-1])/intval($old_tr[$i-1])*100) }}</div>
                            </th>
                        @endfor
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $record['MaxDiscount'] }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ $total_new_tr + $total_old_tr }}</div>
                        </th>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">{{ ($total_new_tr + $total_old_tr)*$record['MaxDiscount'] }}</div>
                        </th>
                    </tr>

                @endforeach
            @else
                <div class="w-full p-4 mt-4 text-center bold" style="border: 1px solid; background-color: #ffecec; color: black;">لا يوجد مستهدفات لهذا المستخدم في هذه الشهور ..</div>
            @endif
            </tbody>
        </table>
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
    <script>

        Livewire.on('show-container', () => {
            // div = document.getElementById("report-btn");
            // div.classList.remove("hide");
            // $('th #x').text('AA');
            // alert('aaaa');
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
