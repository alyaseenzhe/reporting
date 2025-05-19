<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شركة الياسين الزراعية</title>
    <style>
        table td {
            border: solid 1px black;
            padding: 0.5rem;
        }

        table th{
            border: solid 1px black;
            padding: 0.5rem;
        }
    </style>
</head>
<body style="direction: rtl;">
<div>
    <div style="text-align: center">
        <img width="123" height="98" style="width:1.2833in;height:1.0166in" src="https://reporting.alyaseenagri.com/images/logo-horizontal.png">
    </div>
    <br>
    <div
        style="text-align: center;" class="flex flex-col sm:flex-row gap-4 border mb-4 justify-center text-center text-2xl p-3 font-bold bg-gray-50">
        <h2>اعمال {{ $employee }}</h2>
    </div>
    <div style="font-weight: bold; text-align: center">
        <span>({{ $start_date }}</span>
        <span>إلى</span>
        <span>{{ $end_date }})</span>
    </div>
    {{-- table 2 (details) --}}
    <div>
        <div class="w-full flex sm:flex-row flex-col gap-4 mb-5" style="background-color: #f5f5f5; padding: 20px;">
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">اسم الموظف</label>
                <div style="color: #5222e1">{{$data[0]->user->name}}</div>
            </div>
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">الفترة</label>
                <div style="color: #5222e1">{{$data[0]->start_of_week}} - {{ $data[0]->end_of_week }}</div>
            </div>
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">عدد المهام المنجزة</label>
                <div style="color: #5222e1">{{count($data)}}</div>
            </div>
        </div>
        {{-- table 2 (details) --}}
        <div id="tbl2-container" style="overflow: auto">
            <table id="tbl2" style="border: 2px solid black; border-collapse: collapse; text-align: center" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    <th class="whitespace-nowrap">
                        <div class="text-xs">اليوم</div>
                    </th>
                    <th style="border-left: 2px solid black;" class="whitespace-nowrap">
                        <div class="text-xs">نوع النشاط</div>
                    </th>
                    <th>
                        <div class="text-xs">الموقع</div>
                    </th>
                    <th>
                        <div class="text-xs">العميل</div>
                    </th>
                    <th style="border-left: 2px solid black;">
                        <div class="text-xs">المرافقون</div>
                    </th>
                    <th>
                        <div class="text-xs">العمل/المنجزات</div>
                    </th>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">

                @foreach($data as $record)
                    <tr>
                        <td style="background-color: #e8f9e8;" class="whitespace-nowrap">
                            @php $trans = \Carbon\Carbon::parse($record->report_date); $trans->locale('ar'); @endphp
                            <div>{{ $trans->translatedFormat('l') }}</div>
                            <div>{{$record->report_date}}</div>
                        </td>
                        <td style="background-color: #e8f9e8; border-left: 2px solid black;" class="whitespace-nowrap">
                            @if($record->report_type == 'work')
                                تقرير عمل
                            @elseif($record->report_type == 'visit')
                                تقرير زيارة
                            @elseif($record->report_type == 'sales')
                                تحصيل/مبيعات
                            @elseif($record->report_type == 'general-rept')
                                تقرير عام عن عميل
                            @elseif($record->report_type == 'meeting')
                                إجتماع
                            @endif
                        </td>
                        <td>
                            {{$record->location2}}
                        </td>
                        <td>
                            {{$record->customer_name}}
                        </td>
                        <td style="border-left: 2px solid black;">
                            {{$record->companion}}
                        </td>
                        <td>
                            {{$record->report_note}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
