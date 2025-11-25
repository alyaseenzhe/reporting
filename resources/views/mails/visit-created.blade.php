<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شركة الياسين الزراعية</title>

</head>
<body style="direction: rtl;">
<style>
    .grid-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 10px;
        background-color: #f5f5f5;
        padding: 20px;
        border-radius: 8px;
    }

    .grid-item {
        padding: 10px;
    }

    .grid-label {
        font-weight: bold;
        display: block;
        margin-bottom: 4px;
    }

    .grid-value {
        color: #5222e1;
        white-space: pre-wrap;
    }
</style>

<div style="direction: rtl; font-family: Arial, sans-serif; background-color: #ffffff; color: #333; padding: 20px">
    <div style="text-align: center;">
        <img width="123" height="98" src="https://reporting.alyaseenagri.com/images/logo-horizontal.png" alt="Logo">
    </div>

    @if($type == 'add')

        <h2 style="text-align: center; color: #5222e1; margin-top: 10px;">📩 إشعار طلب زيارة جديد</h2>
        <p style="font-size: 16px; margin: 20px 0; background-color: #f0f0ff; padding: 15px; border-radius: 10px;">
            هلا  {{ $branch_manger_name }}،<br>
            جاك طلب زيارة جديد وتفاصيله كالتالي، بإمكانك عرض التفاصيل كاملة واتخاذ القرار المناسب بالموافقة او الرفض من خلال الزر أدناه 😊
        </p>

    @elseif($type == 'update')

        <h2 style="text-align: center; color: #5222e1; margin-top: 10px;">📩 إشعار بتعديل الزيارة</h2>
            <p style="color: #1c7430; font-size: 16px; margin: 20px 0; background-color: #f0f0ff; padding: 15px; border-radius: 10px;">
                اهلاً،<br>
                تم إلغاء تعديل وتفاصيلها كما يلي 😊
            </p>


    @elseif($type == 'cancel')

        <h2 style="text-align: center; color: #5222e1; margin-top: 10px;">📩 إشعار بإلغاء الزيارة</h2>
            <p style="color: #1c7430; font-size: 16px; margin: 20px 0; background-color: #f0f0ff; padding: 15px; border-radius: 10px;">
                اهلاً،<br>
                تم إلغاء الزيارة وتفاصيلها كما يلي 😊
            </p>

    @elseif($type == 'approve')

        <h2 style="text-align: center; color: #28a745; margin-top: 10px;">✅ تمت الموافقة على الزيارة</h2>
        <p style="color: #1c7430; font-size: 16px; margin: 20px 0; background-color: #f0f0ff; padding: 15px; border-radius: 10px;">
            اهلاً،<br>
            تمت الموافقة على طلب الزيارة من قبل {{ App\Models\User::find($visit->approved_by)->name }} والتفاصيل كما يلي 😊
        </p>

    @elseif($type == 'reject')

        <h2 style="text-align: center; color: #62182e; margin-top: 10px;">❌ تم رفض طلب الزيارة</h2>
        <p style="color: #62182e; font-size: 16px; margin: 20px 0; background-color: #f0f0ff; padding: 15px; border-radius: 10px;">
            اهلاً،<br>
            تم رفض طلب الزيارة والتفاصيل كما يلي 😊
        </p>
    @elseif($type == 'reviews-done')

        <h2 style="text-align: center; color: #007C91; margin-top: 10px;">📋 تم الانتهاء من تقييم الزيارة</h2>
        <p style="color: #007C91; font-size: 16px; margin: 20px 0; background-color: #e6f7fb; padding: 15px; border-radius: 10px;">
            أهلاً،<br>
            تم الانتهاء من تقييم الزيارة، يمكنك الاطلاع عليها من خلال الدخول على رابط الصفحة الموجود بالأسفل ✅
        </p>

    @elseif($type == 'reminder')

        <h2 style="text-align: center; color: #007C91; margin-top: 10px;">🔔 إشعار تذكير </h2>
        <p style="color: #007C91; font-size: 16px; margin: 20px 0; background-color: #e6f7fb; padding: 15px; border-radius: 10px;">
            أهلاً،<br>
            للتذكير موعد الزيارة قرب في {{$visit->banch }} يوم {{$visit->start->translatedFormat('l j F Y')}} 🔔
        </p>


    @elseif($type == 'AcceptReminder')

        <h2 style="text-align: center; color: #007C91; margin-top: 10px;">🔔 إشعار تذكير </h2>
        <p style="color: #007C91; font-size: 16px; margin: 20px 0; background-color: #e6f7fb; padding: 15px; border-radius: 10px;">
            أهلاً،<br>
           اشعار تذكير بقبول أو رفض الزيارة 🔔
        </p>
    @endif


    <div class="grid-section">
        <div class="grid-item">
            <span class="grid-label">📍 موضوع الزيارة</span>
            <div class="grid-value">{{ $visit->title }}</div>
        </div>

        <div class="grid-item">
            <span class="grid-label">🗺️ مكان الزيارة</span>
            <div class="grid-value">
                @switch($visit->branch)
                    @case("0101") فرع الاحساء @break
                    @case("0102") فرع جدة @break
                    @case("0103") فرع الرياض @break
                    @case("0104") فرع وادي الدواسر @break
                    @case("0105") فرع الجوف @break
                    @case("0106") فرع الدمام @break
                    @case("0107") فرع الخرج @break
                    @case("0108") فرع نجران @break
                    @case("0109") فرع حائل @break
                    @case("0110") فرع تبوك @break
                    @case("0111") فرع القصيم @break
                    @case("0112") فرع ساجر @break
                    @case("0201") مزرعة الدالوة @break
                    @case("0202") مزرعة الفضول @break
                    @case("0203") مزرعة الدلم @break
                @endswitch
            </div>
        </div>

        <div class="grid-item">
            <span class="grid-label">🎯 سبب الزيارة</span>
            <div class="grid-value">{{ $visit->reason }}</div>
        </div>
    </div>

    <div style="height:15px;"></div>

    <div class="grid-section">
        <div class="grid-item">
            <span class="grid-label">👤 الزائر</span>
            @foreach($visit->emps_requester as $req)
                <div class="grid-value">{{ $req->user->name }}</div>
            @endforeach
        </div>

        <div class="grid-item">
            <span class="grid-label">🎯 التحضيرات المطلوبه من الفرع</span>
            <div class="grid-value">{{ $visit->goals }}</div>
        </div>
    </div>
    <div style="height:15px;"></div>

    <div class="grid-section">
        <div class="grid-item">
            <span class="grid-label">📅 تاريخ بداية الزيارة</span>
            <div class="grid-value">{{ \Carbon\Carbon::parse($visit->start)->format('Y-m-d') }}</div>
        </div>

        <div class="grid-item">
            <span class="grid-label">📆 تاريخ نهاية الزيارة</span>
            <div class="grid-value">{{ \Carbon\Carbon::parse($visit->end)->format('Y-m-d') }}</div>
        </div>

    </div>
    <div class="grid-section">

        <div class="grid-item">
            <span class="grid-label">⏰ وقت الزيارة</span>
            <div class="grid-value">{{ \Carbon\Carbon::parse($visit->start)->format('h:i A') }}</div>
        </div>
        <div class="grid-item">
            <span class="grid-label">⏰ وقت الانتهاء</span>
            <div class="grid-value">{{ \Carbon\Carbon::parse($visit->end)->format('h:i A') }}</div>
        </div>

    </div>
    @if($type == 'approve')
        <div style="height:15px;"></div>

        <div class="grid-section" style="background-color:#ecffd5;">
            <div class="grid-item">
                <span class="grid-label">ملاحظات إضافية على الموافقة</span>
                <div class="grid-value" style="color:#3b6200;">
                    {{ $visit->status_notice ?: 'لا يوجد' }}
                </div>
            </div>
        </div>
    @endif

    @if($type == 'reject')
        <div style="height:15px;"></div>

        <div class="grid-section" style="background-color:#ffbcd2;">
            <div class="grid-item">
                <span class="grid-label">اسباب الرفض</span>
                <div class="grid-value" style="color:#62182e;">
                    {{ $visit->status_notice ?: 'لا يوجد' }}
                </div>
            </div>
        </div>
    @endif
    <div style="text-align:center; padding:20px;">
        <a href="{{ route('show.visit', ['id' => $visit->id]) }}"
           style="background-color:#5222e1; color:#fff; padding:14px 28px;
              text-decoration:none; border-radius:6px; font-weight:bold;">
            👁️‍🗨️ عرض تفاصيل الزيارة
        </a>
    </div>





    {{--    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse;">--}}

{{--        <!-- Section 1 -->--}}
{{--        <tr style="background-color: #f5f5f5;">--}}
{{--            <td style="padding: 20px;" colspan="3">--}}
{{--                <table width="100%" cellpadding="0" cellspacing="0" role="presentation">--}}
{{--                    <tr>--}}
{{--                        <td width="33%" style="padding: 10px;">--}}
{{--                            <label style="font-weight: bold;">📍 موضوع الزيارة</label>--}}
{{--                            <div style="color: #5222e1;">{{ $visit->title }}</div>--}}
{{--                        </td>--}}
{{--                        <td width="33%" style="padding: 10px;">--}}
{{--                            <label style="font-weight: bold;">🗺️ مكان الزيارة</label>--}}
{{--                            <div style="color: #5222e1;">--}}
{{--                                @switch($visit->branch)--}}
{{--                                    @case("0101") فرع الاحساء @break--}}
{{--                                    @case("0102") فرع جدة @break--}}
{{--                                    @case("0103") فرع الرياض @break--}}
{{--                                    @case("0104") فرع وادي الدواسر @break--}}
{{--                                    @case("0105") فرع الجوف @break--}}
{{--                                    @case("0106") فرع الدمام @break--}}
{{--                                    @case("0107") فرع الخرج @break--}}
{{--                                    @case("0108") فرع نجران @break--}}
{{--                                    @case("0109") فرع حائل @break--}}
{{--                                    @case("0110") فرع تبوك @break--}}
{{--                                    @case("0111") فرع القصيم @break--}}
{{--                                    @case("0112") فرع ساجر @break--}}
{{--                                    @case("0201") مزرعة الدالوة @break--}}
{{--                                    @case("0202") مزرعة الفضول @break--}}
{{--                                    @case("0203") مزرعة الدلم @break--}}
{{--                                @endswitch--}}
{{--                            </div>--}}
{{--                        </td>--}}
{{--                        <td width="33%" style="padding: 10px;">--}}
{{--                            <label style="font-weight: bold;">🎯 سبب الزيارة</label>--}}
{{--                            <div style="color: #5222e1;">{{ $visit->reason }}</div>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                </table>--}}
{{--            </td>--}}
{{--        </tr>--}}

{{--        <!-- Spacer -->--}}
{{--        <tr style="background-color: #ffffff;">--}}
{{--            <td style="height: 15px;"></td>--}}
{{--        </tr>--}}

{{--        <!-- Section 2 -->--}}
{{--        <tr style="background-color: #f5f5f5;">--}}
{{--            <td style="padding: 20px;" colspan="3">--}}
{{--                <table width="100%" cellpadding="0" cellspacing="0" role="presentation">--}}
{{--                    <tr>--}}
{{--                        <td width="50%" style="padding: 10px;">--}}
{{--                            <label style="font-weight: bold;">👤الزائر</label>--}}
{{--                            @foreach($visit->emps_requester as $req)--}}
{{--                                <div style="color: #5222e1;">{{ $req->user->name }}</div>--}}
{{--                            @endforeach--}}
{{--                        </td>--}}
{{--                        <td style="padding: 20px;">--}}
{{--                            <label style="font-weight: bold;">🎯 التحضيرات المطلوبه من الفرع</label>--}}
{{--                            <div style="color: #5222e1; white-space: pre-wrap; margin-top: 10px;">--}}
{{--                                {{ $visit->goals }}--}}
{{--                            </div>--}}
{{--                        </td>--}}
{{--                        <td width="50%" style="padding: 10px;">--}}
{{--                            <label style="font-weight: bold;">📬 ابلاغ الموظفين</label>--}}
{{--                            <div style="color: #5222e1;">--}}
{{--                                @foreach($visit->emps_recipients as $req)--}}
{{--                                    <span>{{ $req->user->name }}@if (!$loop->last), @endif </span>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                </table>--}}
{{--            </td>--}}
{{--        </tr>--}}

{{--        <!-- Spacer -->--}}
{{--        <tr style="background-color: #ffffff;">--}}
{{--            <td style="height: 15px;"></td>--}}
{{--        </tr>--}}

{{--        <!-- Section 3 -->--}}
{{--        <tr style="background-color: #f5f5f5;">--}}
{{--            <td style="padding: 20px;" colspan="3">--}}
{{--                <table width="100%" cellpadding="0" cellspacing="0" role="presentation">--}}
{{--                    <tr>--}}
{{--                        <td width="33%" style="padding: 10px;">--}}
{{--                            <label style="font-weight: bold;">📅 تاريخ بداية الزيارة</label>--}}
{{--                            <div style="color: #5222e1;">{{ \Carbon\Carbon::parse($visit->start)->format('Y-m-d') }}</div>--}}
{{--                        </td>--}}
{{--                        <td width="33%" style="padding: 10px;">--}}
{{--                            <label style="font-weight: bold;">📆 تاريخ نهاية الزيارة</label>--}}
{{--                            <div style="color: #5222e1;">{{ \Carbon\Carbon::parse($visit->end)->subDay()->format('Y-m-d') }}</div>--}}
{{--                            <div style="color: #5222e1;">{{ \Carbon\Carbon::parse($visit->end)->format('Y-m-d') }}</div>--}}
{{--                        </td>--}}
{{--                        <td width="33%" style="padding: 10px;">--}}
{{--                            <label style="font-weight: bold;">⏰ وقت الزيارة</label>--}}
{{--                            <div style="color: #5222e1;">{{ \Carbon\Carbon::parse($visit->start)->format('h:i A') }}</div>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                </table>--}}
{{--            </td>--}}
{{--        </tr>--}}

{{--        <!-- Spacer -->--}}
{{--        <tr style="background-color: #ffffff;">--}}
{{--            <td style="height: 15px;"></td>--}}
{{--        </tr>--}}

{{--        <!-- Section 4 -->--}}
{{--        <tr style="background-color: #f5f5f5;">--}}
{{--            <td style="padding: 20px;" colspan="3">--}}
{{--                <label style="font-weight: bold;">🎯 التحضيرات المطلوبه من الفرع</label>--}}
{{--                <div style="color: #5222e1; white-space: pre-wrap; margin-top: 10px;">--}}
{{--                    {{ $visit->goals }}--}}
{{--                </div>--}}
{{--            </td>--}}
{{--        </tr>--}}

{{--        @if($type == 'approve')--}}
{{--            <!-- Spacer -->--}}
{{--            <tr style="background-color: #ffffff;">--}}
{{--                <td style="height: 15px;"></td>--}}
{{--            </tr>--}}

{{--            <!-- Section 4 -->--}}
{{--            <tr style="background-color: #ecffd5;">--}}
{{--                <td style="padding: 20px;" colspan="3">--}}
{{--                    <label style="font-weight: bold;">ملاحظات إضافية على الموافقة</label>--}}
{{--                    <div style="color: #3b6200; white-space: pre-wrap; margin-top: 10px;">--}}
{{--                        {{ $visit->status_notice ? $visit->status_notice : 'لا يوجد' }}--}}
{{--                    </div>--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @elseif($type == 'reject')--}}
{{--            <!-- Spacer -->--}}
{{--            <tr style="background-color: #ffffff;">--}}
{{--                <td style="height: 15px;"></td>--}}
{{--            </tr>--}}

{{--            <!-- Section 4 -->--}}
{{--            <tr style="background-color: #ffbcd2;">--}}
{{--                <td style="padding: 20px;" colspan="3">--}}
{{--                    <label style="font-weight: bold;">اسباب الرفض</label>--}}
{{--                    <div style="color: #62182e; white-space: pre-wrap; margin-top: 10px;">--}}
{{--                        {{ $visit->status_notice ? $visit->status_notice : 'لا يوجد' }}--}}
{{--                    </div>--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @endif--}}

{{--        <!-- Spacer -->--}}
{{--        <tr style="background-color: #ffffff;">--}}
{{--            <td style="height: 20px;"></td>--}}
{{--        </tr>--}}

{{--        <!-- View Details Button -->--}}
{{--        <tr style="background-color: #ffffff;">--}}
{{--            <td colspan="3" style="text-align: center; padding: 20px;">--}}
{{--                <a href="{{ route('show.visit', ['id' => $visit->id]) }}" style="background-color: #5222e1; color: #fff; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold;">--}}
{{--                <a href="#" style="background-color: #5222e1; color: #fff; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold;">--}}
{{--                    👁️‍🗨️ عرض تفاصيل الزيارة--}}
{{--                </a>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--    </table>--}}

</div>
</body>
</html>
