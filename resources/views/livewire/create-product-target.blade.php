<div>
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
                @if($btn_generate)
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
                @if($results)
                    @if($btn_save)
                        <div class="mt-8 text-center w-full">
                            <button wire:click.prevent="processData" wire:loading.attr="disabled"
                                    style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold" wire:loading.remove wire:target="processData">
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

    <div class="notification-box flex flex-col items-center justify-center w-full z-50 mb-3">
        <!-- Notification container -->
    </div>

    <div class="overflow-x-auto w-full">
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
                        <th colspan="13" style="border: 2px solid black; background-color: #faebd7" class="col-id-no fixed-header border p-2 whitespace-nowrap">
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
//                                        dd($current_target_to_edit->where('product_id', "220004")->where('month', "2")->where('year', "2024")->count());
//                                        dd($current_target_to_edit->where('product_id', "220004")->where('month', "2")->where('year', "20245")->first()['target']);
                                    @endphp
                                    {{--                                @if($diff < 3)--}}
                                    @if($toDate->lt(\Carbon\Carbon::parse('2023-07-01')))
{{--                                        {{ $current ? $current->target : $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first() }}--}}
                                        {{ $current ? $current->target : "-" }}
                                        @php array_push($arr_tar, ($current ? $current->target : "-")); @endphp
                                        {{--                                    <div id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$loop->iteration}}" class="w-full">{{ $current ? $current->target : "N/A" }}</div>--}}
                                    @else
                                        <input min="0" id="target--{{$record['ProductCode']}}--{{$year_key."-".$month}}--{{$target_counter}}" type="number" wire:model="target.{{$record['ProductCode']}}.{{$year_key."-".$month}}.{{$target_counter}}" placeholder="{{ $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->count() > 0 ?  $current_target_to_edit->where('product_id', $record['ProductCode'])->where('month', $month)->where('year', $year_key)->first()['target'] : null}}" class="form-input w-full">
                                    @endif
                                    @php $target_counter++; @endphp
                                </th>
                            @endforeach
                        @endforeach
                    </tr>
                    <tr>
                        <th style="border: 2px solid black; z-index: 10" class="border p-2">
                            <div class="text-sm">مبيعات تاريخية</div>
                        </th>
                        {{--                    @foreach ($list as $year_key => $year)--}}
                        {{--                        @foreach ($year as $month)--}}
                        @php $sales = []; @endphp
                        @for($i = 1; $i <= 12; $i++)
                            <th style="border: 2px solid black; z-index: 10" class="border p-2">
                                <div class="text-sm">{{ number_format($record['month'.$i]) }}</div>
                                @php array_push($sales, $record['month'.$i]); @endphp
                            </th>
                        @endfor
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
            $('th span').empty();
            // div = document.getElementById("report-btn");
            // div.classList.remove("hide");
            // $('th #x').text('AA');
            // alert('aaaa');
        })

        Livewire.on('diff-update', value => {
            console.log(value);
            $('#'+value[0]).text(value[1]);
        });


        Livewire.on('msg', value => {
           sendNotification('success', 'تم حفظ البيانات بنجاح!');
           $('th span').empty();
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
                notificationBox.removeChild(component);
            }, 5700);
            //If you can do something more elegant than timeouts, please do, but i can't
        }

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
