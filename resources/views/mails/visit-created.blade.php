<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شركة الياسين الزراعية</title>

</head>
<body style="direction: rtl;">
<span class="preheader" style="color: transparent; display: none !important; height: 0; width: 0; opacity: 0; overflow: hidden; visibility: hidden;">

            <span class="grid-label ">👤 الزائر</span>
            @foreach($visit->emps_requester as $req)
                <div class="grid-value  ">{{ $req->user->name }}</div>
            @endforeach

                <span class="grid-label">🗺️ مكان الزيارة</span>
            <div class="grid-value">{{__($visit->branch)}}</div>

</span>
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

    /* Grid Layout */
    .review-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem; /* gap-6 */
    }

    @media (min-width: 768px) {
        .review-grid {
            grid-template-columns: repeat(2, 1fr); /* md:grid-cols-2 */
        }
    }

    /* Column spacing */
    .column-space {
        display: flex;
        flex-direction: column;
        gap: 1rem; /* space-y-4 */
    }

    /* Review Box */
    .review-box {
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem; /* rounded-xl */
        box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
        padding: 1rem; /* p-4 */
    }

    /* Title */
    .review-title {
        font-size: 0.875rem; /* text-sm */
        font-weight: 600; /* font-semibold */
        cursor: pointer;
    }

    /* Flex row for name + status */
    .flex-row {
        display: flex;
        align-items: center;
        gap: 0.5rem; /* gap-2 */
    }

    /* Rating star */
    .star-gold {
        color: #eab308; /* text-yellow-500 */
        margin-left: 0.25rem; /* ml-1 */
    }

    .star-gray {
        color: #d1d5db; /* text-gray-300 */
    }

    /* Review text container */
    .review-text {
        margin-top: 2.25rem; /* mt-9 */
        color: #4b5563; /* text-gray-600 */
    }

    /* Answer block */
    .answer-block {
        margin-bottom: 1rem; /* mb-4 */
    }

    .answer-label {
        font-weight: 600;
    }

    /* Text answer */
    .answer-text {
        margin-top: 0.5rem; /* mt-2 */
        padding: 0.5rem; /* p-2 */
        font-size: 0.875rem; /* text-sm */
        color: #0b0bc6;
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
                تم تعديل الزيارة وتفاصيلها كما يلي 😊
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

    <div class="w-full flex sm:flex-row flex-col gap-4"
         style="@if($visit->status ==0) background-color: #dceeff; /*#fffddc;*/ @elseif($visit->status == 1) background-color: #edffe9; @elseif($visit->status == 2) background-color: #fff0f8; @elseif($visit->status == 4) background-color: #ffd7b5; @elseif($visit->status == 5) background-color: #b9f0ea; @endif border: dashed 1px black; padding: 20px;">
        <div class="w-full">
            <label class="block font-bold mb-6 text-xs">
                حالة الزيارة
            </label>

            <div
                style="@if($visit->status ==0) color: #03045E; /*#7d781a;*/  @elseif($visit->status == 1) color: #418f30; @elseif($visit->status == 2) color: #701345; @elseif($visit->status ==5) color: #022622; @else color: #701345; @endif ">
                @if($visit->status == 0)
                    تحت الموافقة
                @elseif($visit->status == 1)
                    مقبولة<br>
                    تمت الموافقة على طلب الزيارة من قبل {{ App\Models\User::find($visit->approved_by)->name }}
                @elseif($visit->status == 2)
                    مرفوضة
                @elseif($visit->status == 3)
                    التقارير تحت الاجراء

                @elseif($visit->status == 4)
                    ملغية
                @elseif($visit->status == 5)
                    التقارير تامة
                @endif

            </div>
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
    <div style="height:15px;"></div>
    <div class="grid-section">
        <div class="grid-item">
            <span class="grid-label">📍 موضوع الزيارة</span>
            <div class="grid-value">{{ $visit->title }}</div>
        </div>

        <div class="grid-item">
            <span class="grid-label">🗺️ مكان الزيارة</span>
            <div class="grid-value">{{__($visit->branch)}}</div>
        </div>

        <div class="grid-item">
            <span class="grid-label">🎯 سبب الزيارة</span>
            <div class="grid-value">{{ $visit->reason }}</div>
        </div>
    </div>

    <div style="height:15px;"></div>

    <div class="grid-section">
        <div class="grid-item"  >
            <span class="grid-label ">👤 الزائر</span>
            @foreach($visit->emps_requester as $req)
                <div class="grid-value  ">{{ $req->user->name }}</div>
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


    @if($visit->status == 5)

        <div style="height:15px;"></div>

        <div class="grid-section">
{{--            <h1 class="grid-label">  📊 التقييم </h1>--}}
{{--            <div></div>--}}
            <!-- Column 1 -->
            <div class="column-space ">
                @foreach($visit->emps_requester as $req_record)
                    @php
                        $reviews = collect(json_decode($req_record->reviews, true)); // decode to collection
                    @endphp
                    <div class="border rounded-xl shadow p-4">
                        <h2 class="grid-label cursor-pointer collapse-toggle">
                            <div class="flex items-center gap-2">

                                <!-- Name + Status -->
                                {{ $req_record->user->name }}
                                @php
                                    $isReviewWritten = $req_record->reviews && $req_record->reviews != '';
                                    $isrRequesterReview = $visit->requester_reviews && $visit->requester_reviews != '';
                                    $isrRecipientReview = $visit->recipient_reviews && $visit->recipient_reviews != '';
                                    $isMyReview = $req_record->user_id == auth()->id();  // adjust auth if needed

                                @endphp

                                @if(!$isReviewWritten  )

                                    <span style="color: #a40e3b;">(تحت الإجراء)</span>

                                @else
                                    <span style="color: #287c0c;">(تم التقييم)</span>
                                    (
                                    <span class="star-gold ml-1">&#9733;</span>
                                    <span style="color: #a40e3b;">
                                            {{ number_format(floatval($reviews['totalRating']) / (count($reviews['answers']) - 1), 1) }}
                                        </span>
                                    )
                                @endif

                            </div>
                        </h2>

                        @if($isReviewWritten  )


                            <div class="review-text ">
                                @foreach($reviews['answers'] as $answer)
                                    <div class="answer-block">
                                        <label class="answer-label">{{ $answer['text'] }}</label><br>

                                        @if(is_numeric($answer['value']))
                                            <span
                                                class="star-gold inline-block">{!! str_repeat('&#9733;', $answer['value']) !!}</span>
                                            <span
                                                class="star-gray inline-block">{!! str_repeat('&#9733;', 5 - $answer['value']) !!}</span>
                                        @else
                                            <div style="color: #0b0bc6;" class="answer-text">
                                                {{ $answer['value'] }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>

                @endforeach
            </div>

            <!-- Column 2 -->
            <div class="column-space ">
                @foreach($visit->emps_recipients as $rec_record)
                    @php
                        $reviews = collect(json_decode($rec_record->reviews, true)); // decode to collection
                    @endphp
                    @php
                        $isReviewWritten = $rec_record->reviews && $rec_record->reviews != '';
                        $isMyReview = $rec_record->user_id == auth()->id();
//                        $allReviewsDone = $this->reviews_done();
                        $parsedReview = $isReviewWritten ? json_decode($rec_record->reviews, true) : null;
                    @endphp

                    <div class="border rounded-xl shadow p-4">
                        <h2 class="grid-label cursor-pointer collapse-toggle">
                            <div class="flex items-center gap-2">


                                <!-- Name + Status -->
                                {{$rec_record->user?->name}}
                                @if(!$isReviewWritten )
                                    <span style="color: #a40e3b;">(تحت الإجراء)</span>
                                @else
                                    <span style="color: #287c0c;">(تم التقييم)</span>
                                    (
                                    <span class="text-yellow-500 ml-1">&#9733;</span>
                                    <span style="color: #a40e3b;">
                                            {{ number_format(floatval($parsedReview['totalRating']) / (count($parsedReview['answers']) - 1), 1) }}
                                        </span>
                                    )
                                @endif
                            </div>
                        </h2>

                        @if($isReviewWritten)

                            <div class=" review-text ">
                                @foreach($parsedReview['answers'] as $answer)
                                    <div class="answer-block">
                                        <label class="answer-label">{{ $answer['text'] }}</label><br>

                                        @if(is_numeric($answer['value']))
                                            <span
                                                class="star-gold inline-block">{!! str_repeat('&#9733;', $answer['value']) !!}</span>
                                            <span
                                                class="star-gray inline-block">{!! str_repeat('&#9733;', 5 - $answer['value']) !!}</span>
                                        @else
                                            <div style="color: #0b0bc6;" class="answer-text">
                                                {{ $answer['value'] }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                @endforeach
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




</div>
</body>
</html>
