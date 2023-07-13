<div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">مبيعات، هامش/موظف</div>
    </div>
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
                        <option value="3">فرع الاحساء</option>
                        <option value="10">فرع جدة</option>
                        <option value="7">فرع الرياض</option>
                        <option value="13">فرع وادي الدواسر</option>
                        <option value="4">فرع الجوف</option>
                        <option value="6">فرع الدمام</option>
                        <option value="5">فرع الخرج</option>
                        <option value="12">فرع نجران</option>
                        <option value="11">فرع حائل</option>
                        <option value="9">فرع تبوك</option>
                        <option value="8">فرع القصيم</option>
                        <option value="505">فرع ساجر</option>
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
        {{-- table 2 (details) --}}
        <div id="tbl2-container" class="overflow-x-auto">
            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th style="border-left: 2px solid black;" colspan="2" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">بيانات الموظف</div>
                    </th>
                    <th style="border-left: 2px solid black;" colspan="3" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">اصناف مميز 0</div>
                    </th>
                    <th style="border-left: 2px solid black;" colspan="3" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">اصناف مميز 1</div>
                    </th>
                    <th style="border-left: 2px solid black;" colspan="3" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">اصناف مميز 2</div>
                    </th>
                    <th colspan="3" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">اجماليات</div>
                    </th>
                </tr>
                <tr>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">#</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                        <div class="text-sm">الاسم</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مبيعات م0</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-sm">كلفة م0</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-sm">هامش م0</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مبيعات م1</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-sm">كلفة م1</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-sm">هامش م1</div>
                    </th>
                    <th class="border p-2 whitespace-nowrap">
                        <div class="text-sm">مبيعات م2</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-sm">كلفة م2</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="border p-2">
                        <div class="text-sm">هامش م2</div>
                    </th>

                    <th class="border p-2">
                        <div class="text-sm">اجمالي مبيعات</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-sm">اجمالي كلفة</div>
                    </th>
                    <th class="border p-2">
                        <div class="text-sm">اجمالي الهامش</div>
                    </th>
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">

                    <?php $s0 = 0.00; ?>
                    <?php $c0 = 0.00; ?>
                    <?php $p0 = 0.00; ?>
                    <?php $s1 = 0.00; ?>
                    <?php $c1 = 0.00; ?>
                    <?php $p1 = 0.00; ?>
                    <?php $s2 = 0.00; ?>
                    <?php $c2 = 0.00; ?>
                    <?php $p2 = 0.00; ?>
                    <?php $s_total = 0.00; ?>
                    <?php $c_total = 0.00; ?>
                    <?php $p_total = 0.00; ?>

                @foreach($result_tbl2 as $result2)
                    @if(number_format(floatval($result2['Spl0Value'])) == 0 && number_format(floatval($result2['Spl1Value'])) == 0 && number_format(floatval($result2['Spl2Value'])) == 0 && number_format(floatval($result2['Spl0cost'])) == 0 && number_format(floatval($result2['Spl1Cost'])) == 0 && number_format(floatval($result2['Spl2Cost'])) == 0)
                    @else
                        <tr>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? $result2['Employeecode'] : ""}}</div>
                                </div>
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-right text-gray-800 text-sm">{{$result2 ? $result2['Employeename'] : ""}}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl0Value'])) : ""}}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl0cost'])) : ""}}</div>
                                </div>
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl0Value'] - $result2['Spl0cost'])) : ""}}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl1Value'])) : ""}}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl1Cost'])) : ""}}</div>
                                </div>
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl1Value'] - $result2['Spl1Cost'])) : ""}}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl2Value'])) : ""}}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl2Cost'])) : ""}}</div>
                                </div>
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl2Value'] - $result2['Spl2Cost'])) : ""}}</div>
                                </div>
                            </td>


                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl0Value'] + $result2['Spl1Value'] + $result2['Spl2Value'])) : ""}}</div>
                                </div>
                            </td>
                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl0cost'] + $result2['Spl1Cost'] + $result2['Spl2Cost'])) : ""}}</div>
                                </div>
                            </td>

                            <td class="border p-2 whitespace-nowrap">
                                <div>
                                    <div class="text-center text-gray-800 text-sm">{{$result2 ? number_format(floatval($result2['Spl0Value'] - $result2['Spl0cost']) + floatval($result2['Spl1Value'] - $result2['Spl1Cost']) + floatval($result2['Spl2Value'] - $result2['Spl2Cost'])) : ""}}</div>
                                </div>
                            </td>
                        </tr>
                    @endif
                        <?php $s0 += ($result2 ? floatval($result2['Spl0Value']) : 0) ?>
                        <?php $c0 += ($result2 ? floatval($result2['Spl0cost']) : 0) ?>
                        <?php $p0 += ($result2 ? floatval($result2['Spl0Value'] - $result2['Spl0cost']) : 0) ?>

                        <?php $s1 += ($result2 ? floatval($result2['Spl1Value']) : 0) ?>
                        <?php $c1 += ($result2 ? floatval($result2['Spl1Cost']) : 0) ?>
                        <?php $p1 += ($result2 ? floatval($result2['Spl1Value'] - $result2['Spl1Cost']) : 0) ?>

                        <?php $s2 += ($result2 ? floatval($result2['Spl2Value']) : 0) ?>
                        <?php $c2 += ($result2 ? floatval($result2['Spl2Cost']) : 0) ?>
                        <?php $p2 += ($result2 ? floatval($result2['Spl2Value'] - $result2['Spl2Cost']) : 0) ?>

                        <?php $s_total += ($result2 ? floatval($result2['Spl0Value'] + $result2['Spl1Value'] + $result2['Spl2Value']) : 0) ?>
                        <?php $c_total += ($result2 ? floatval($result2['Spl0cost'] + $result2['Spl1Cost'] + $result2['Spl2Cost']) : 0) ?>
                        <?php $p_total += ($result2 ? (floatval($result2['Spl0Value'] - $result2['Spl0cost']) + floatval($result2['Spl1Value'] - $result2['Spl1Cost']) + floatval($result2['Spl2Value'] - $result2['Spl2Cost'])) : 0) ?>



                @endforeach
                </tbody>
                <tfoot>
                <tr style="background-color: papayawhip; border: 2px solid black; font-weight: bold">
                    <td colspan="2" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">المجموع</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($s0) }}</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($c0) }}</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($p0) }}</td>

                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($s1) }}</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($c1) }}</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($p1) }}</td>

                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($s2) }}</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($c2) }}</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($p2) }}</td>

                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($s_total) }}</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($c_total) }}</td>
                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">{{ number_format($p_total) }}</td>

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
