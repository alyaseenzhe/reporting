<!DOCTYPE html>
<html lang="ar">
<meta charset="utf-8">
<head>
    <title>Exported Records</title>
    <style>
        @font-face {
            font-family: 'Kufi';
            src: url('{{ public_path('fonts/NotoKufiArabic.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            text-align: right;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<h2>مطالبة مالية</h2>
<table>
    <thead>
    <tr>
        <th>التاريخ</th>
        <th>اسم الموظف</th>
        <th>المجموع</th>
    </tr>
    <tr>
        <td>{{$record->created_at->format('Y-m-d')}}</td>
    </tr>
    </thead>
    <tbody>
{{--    @foreach($records as $record)--}}
{{--        <tr>--}}
{{--            <td>{{ $record->id }}</td>--}}
{{--            <td>{{ $record->name }}</td>--}}
{{--            <td>{{ $record->created_at->format('Y-m-d') }}</td>--}}
{{--        </tr>--}}
{{--    @endforeach--}}
    </tbody>
</table>
</body>
</html>
