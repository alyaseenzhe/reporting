<div>
    <div
        class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <div class="w-full">الفواتير الآجلة</div>
    </div>
    <div wire:loading.remove wire:target="fetch_data" class="overflow-x-auto">
        <table class="table-auto w-full border text-center">
            <thead class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
            <tr>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-lg">رقم الموظف</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-lg">اسم الموظف</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-lg">اسم العميل</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-lg">رقم الفاتورة</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-lg">تاريخ الفاتورة</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-lg">مبلغ الفاتورة</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-lg">المبلغ المدفوع</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-lg">المبلغ المستحق</div>
                </th>
                <th class="border p-2 whitespace-nowrap">
                    <div class="text-lg">الايام</div>
                </th>
            </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
            @foreach($records as $record)
{{--                {{ dd($record) }}--}}
                <tr>
                <td class="border p-2 whitespace-nowrap">
                    <div>
                        <div class="text-center text-gray-800 text-lg">{{$record->EmpCode}}</div>
                    </div>
                </td>
                    <td class="border p-2 whitespace-nowrap">
                        <div>
                            <div class="text-center text-gray-800 text-lg">{{$record->EmpName}}</div>
                        </div>
                    </td>
                <td class="border p-2 whitespace-nowrap">
                    <div>
                        <div class="text-center text-gray-800 text-lg">{{$record->Arabic_Name}}</div>
                    </div>
                </td>
                <td class="border p-2 whitespace-nowrap">
                    <div>
                        <div class="text-center text-gray-800 text-lg">{{$record->voucherno}}</div>
                    </div>
                </td>
                <td class="border p-2 whitespace-nowrap">
                    <div>
                        <div class="text-center text-gray-800 text-lg">{{ \Carbon\Carbon::parse($record->voucherdate)->format('Y-m-d') }}</div>
                    </div>
                </td>
                <td class="border p-2 whitespace-nowrap">
                    <div>
                        <div class="text-center text-gray-800 text-lg">{{number_format($record->Total, 2)}}</div>
                    </div>
                </td>
                <td class="border p-2 whitespace-nowrap">
                    <div>
                        <div class="text-center text-gray-800 text-lg">{{number_format($record->paid, 2)}}</div>
                    </div>
                </td>
                <td class="border p-2 whitespace-nowrap">
                    <div>
                        <div class="text-center text-gray-800 text-lg">{{number_format($record->DueAmount, 2)}}</div>
                    </div>
                </td>
                <td class="border p-2 whitespace-nowrap">
                    <div>
                        <div class="text-center text-gray-800 text-lg">{{$record->days}}</div>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $records->links() }}

    <div  wire:loading wire:target="fetch_data" class="w-full">
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
