@section('title')
    عرض الزيارة
@stop
<input type="hidden" name="visit_id" value="{{$record->id}}">
<div class="mb-5">
    <nav class="sm:flex justify-between" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}"
                   class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    <span class="mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">الصفحة الرئيسية</span>
                </a>
            </li>
            <li class="inline-flex items-center">
                <a href="{{ route('visit-calendar') }}"
                   class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                    <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1"
                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         viewBox="0 0 199.404 199.404"
                         xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                    <span class="mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقويم الزيارات</span>
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1"
                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         viewBox="0 0 199.404 199.404"
                         xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                    <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">عرض الزيارة</span>
                </div>
            </li>
        </ol>




        <div class="flex gap-4 my-4">
            @if($can_close_visit)
                <div wire:ignore class=" text-center  mx-4 flex sm:flex-row flex-col gap-4 justify-end">
                    <div>
                        <button id="close-btn"
                                style="background-color: #484f4a;" class="btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold">
                            <span>إنجاز الزيارة</span>
                        </span>
                        </button>
                    </div>
                </div>
            @endif


            @if(($record->status == 0 || $record->status == 1 || $record->status == 2) && $record->is_requester() && $record->is_deleted == 0)
                <div class="flex flex-row gap-4 justify-center">
                    <div class="flex flex-row gap-4 justify-center">
                        <div>
                            <button id="edit-btn"
                                    style="background-color: #5b53b5;" class="btn hover:bg-indigo-600 text-white">
                    <span class="mr-2 font-bold">
                        <span>تعديل</span>
                    </span>
                            </button>
                        </div>
                        <div>
                            <button id="delete-btn"
                                    style="background-color: #dc3741;" class="btn hover:bg-indigo-600 text-white">
                    <span class="mr-2 font-bold">
                        <span>الغاء الزيارة</span>
                    </span>
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
                @if($can_recipient_approve)

                    <div wire:ignore class="mt-8 text-center flex sm:flex-row flex-col gap-4 justify-end">
                        <p style="color: #72001a;" class="text-sm">أولوية القبول والرفض هي لمشرف المنطقة</p>
                        <div>
                            <button id="approve-btn"
                                    style="background-color: #026832;" class="btn hover:bg-indigo-600 text-white">
                    <span class="mr-2 font-bold">
                        <span>قبول</span>
                    </span>
                            </button>
                        </div>

                        <div>
                            <button id="reject-btn"
                                    style="background-color: #72001a;" class="btn hover:bg-indigo-600 text-white">
                    <span class="mr-2 font-bold">
                        <span>رفض</span>
                    </span>
                            </button>
                        </div>
                    </div>
                @endif


                @if($can_rate)
                    @if($record->is_requester())
                        <div wire:ignore class="mt-8 text-center  flex sm:flex-row flex-col gap-4 justify-end">
                            <div>
                                <button id="req-rate-btn"
                                        style="background-color: #026832;" class="btn hover:bg-indigo-600 text-white">
                    <span class="mr-2 font-bold">
                        <span>تقييم</span>
                    </span>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($record->is_recipient())
                        <div wire:ignore class="mt-8 text-center  flex sm:flex-row flex-col gap-4 justify-end">
                            <div>
                                <button id="rec-rate-btn"
                                        style="background-color: #026832;" class="btn hover:bg-indigo-600 text-white">
                <span class="mr-2 font-bold">
                    <span>تقييم</span>
                </span>
                                </button>
                            </div>
                        </div>
        @endif
        @endif
    </nav>
</div>

<div class="mb-5">
    @if(session()->has('message'))
        <div
            style="background-color: #9ad2dd2b;border: 2px solid #5c9fac;text-align: center;color: #5c9fac;margin-bottom: 20px;"
            class="p-3">
            {{ session('message') }}
        </div>
    @endif
    @if(session()->has('error-message'))
        <div
            style="background-color: #9ad2dd2b;border: 2px solid #5c9fac;text-align: center;color: #5c9fac;margin-bottom: 20px;"
            class="p-3">
            {{ session('error-message') }}
        </div>
    @endif
    @if(session()->has('success'))
        <div x-show="open" x-data="{ open: true }" class="mb-8">
            <div class="px-4 py-2 rounded-sm text-sm bg-green-100 border border-green-200 text-green-600">
                <div class="flex w-full justify-between items-start">
                    <div class="flex">
                        <svg class="w-4 h-4 shrink-0 fill-current opacity-80 mt-[3px] mr-3" viewBox="0 0 16 16">
                            <path
                                d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zM7 11.4L3.6 8 5 6.6l2 2 4-4L12.4 6 7 11.4z"></path>
                        </svg>
                        <div class="px-3">{{ session('success') }}</div>
                    </div>
                    <button class="opacity-70 hover:opacity-80 ml-3 mt-[3px]" @click="open = false">
                        <div class="sr-only">اغلاق</div>
                        <svg class="w-4 h-4 fill-current">
                            <path
                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="w-full flex sm:flex-row flex-col gap-4"
     style="@if($record->status ==0) background-color: #dceeff; /*#fffddc;*/ @elseif($record->status == 1) background-color: #edffe9; @elseif($record->status == 2) background-color: #fff0f8; @elseif($record->status == 4) background-color: #ffd7b5; @elseif($record->status == 5) background-color: #b9f0ea; @endif border: dashed 1px black; padding: 20px;">
    <div class="w-full">
        <label class="block font-bold mb-6 text-xs">
            حالة الزيارة
        </label>

        <div
            style="@if($record->status ==0) color: #03045E; /*#7d781a;*/  @elseif($record->status == 1) color: #418f30; @elseif($record->status == 2) color: #701345; @elseif($record->status ==5) color: #022622; @else color: #701345; @endif ">
            @if($record->status == 0)
                تحت الموافقة
            @elseif($record->status == 1)
                مقبولة<br>
                تمت الموافقة على طلب الزيارة من قبل {{ App\Models\User::find($record->approved_by)->name }}
            @elseif($record->status == 2)
                مرفوضة
                تم الرفض على طلب الزيارة من قبل {{ App\Models\User::find($record->approved_by)->name }}
            @elseif($record->status == 3)
                التقارير تحت الاجراء

            @elseif($record->status == 4)
                ملغية
            @elseif($record->status == 5)
                التقارير تامة
            @endif

        </div>
    </div>
    <div class="w-full">
        <label class="block font-bold mb-6 text-xs">
            @if($record->status == 2)
                اسباب الرفض
            @elseif($record->status == 1 || $record->status == 4)
                ملاحظات
            @endif
        </label>
        <div
            style="@if($record->status ==0) color: #03045E; /*#7d781a;*/  @elseif($record->status == 1) color: #418f30; @else color: #701345; @endif">
            @if($record->status == 1 || $record->status == 2)
                {{ $record->status_notice }}
            @elseif($record->status == 4)
                <p> {{$record->delete_reason}}</p>
            @endif


        </div>
    </div>

</div>
<div id="branch-container" class="my-6">


    <div class="sticky top-16 z-40  p-4  flex items-center justify-between mb-6
     py-2 px-6 text-sm text-white" style=" background-color: #009245; padding: 20px;">
        <div id="visit-info-bar" class="w-full flex sm:flex-row flex-col gap-4">
            <div class="w-full">
                <h2 class="font-bold text-md">موضوع الزيارة: {{ $record->title }}</h2>
            </div>
            <div class="w-full">
                <p class="text-sm text-gray-200">الزائر: {{ $record->requester->name }}</p>
            </div>
            <div class="w-full">
                الفرع: {{ $branches[$record->branch] }}
            </div>
        </div>

    </div>



    <div class="w-full flex sm:flex-row flex-col gap-4" style="background-color: #f5f5f5; padding: 20px;">
        <div class="w-full">
            <label class="block font-bold mb-6 text-xs">سبب الزيارة</label>
            <div style="color: #5222e1">{{$record->reason}}</div>
        </div>
        <div class="w-full">
            <label class="block font-bold mb-6 text-xs">التحضيرات المطلوبه من الفرع</label>
            <div style="color: #5222e1; white-space: pre-wrap;">{{$record->goals}}</div>
        </div>
        <div class="w-full">
            <label class="block font-bold mb-6 text-xs">المرافقون</label>
            <div style="color: #5222e1; white-space: pre-wrap;">{{$record->attendants}}</div>
        </div>
        <div class="w-full">
            <label class="block font-bold mb-6 text-xs">ابلاغ الموظفين</label>
            @foreach($record->emps_recipients as $req)
                <span style="color: #5222e1">{{ $req->user?->name }}@if (!$loop->last), @endif</span>
            @endforeach
        </div>
    </div>

    <div class="w-full flex sm:flex-row flex-col gap-4 mb-6" style="background-color: #f5f5f5; padding: 20px;">
        <div class="w-full">
            <label class="block font-bold mb-6 text-xs">تاريخ بداية الزيارة</label>
            <div style="color: #5222e1">{{ \Carbon\Carbon::parse($record->start)->format('Y-m-d')}}</div>
        </div>

        <div class="w-full">
            <label class="block font-bold mb-6 text-xs">تاريخ نهاية الزيارة</label>
            <div
                {{--                        style="color: #5222e1">{{ \Carbon\Carbon::parse($record->end)->addDays(-1)->format('Y-m-d') }}</div>--}}
                style="color: #5222e1">{{ \Carbon\Carbon::parse($record->end)->format('Y-m-d') }}</div>
        </div>
        <div class="w-full">
            <label class="block font-bold mb-6 text-xs">وقت الزيارة</label>
            <div style="color: #5222e1">{{ \Carbon\Carbon::parse($record->start)->format('h:i A') }}</div>
        </div>

        <div class="w-full">
            <label class="block font-bold mb-6 text-xs">وقت الانتهاء</label>
            <div style="color: #5222e1">{{ \Carbon\Carbon::parse($record->end)->format('h:i A') }}</div>
        </div>
    </div>

</div>




{{--            @php--}}
{{--                $extraServicesMap = [--}}
{{--                    'hotel' => ['label' => 'حجز فندق', 'icon' => '🏨'],--}}
{{--                    'flight' => ['label' => 'حجز طيران', 'icon' => '✈️'],--}}
{{--                    'train' => ['label' => 'حجز قطار', 'icon' => '🚆'],--}}
{{--                ];--}}

{{--                $selectedServices = collect(json_decode($record->extra_services, true));--}}
{{--            @endphp--}}

{{--            <div class="w-full flex sm:flex-row flex-col gap-4" style="background-color: #f5f5f5; padding: 20px;">--}}
{{--                <div class="w-full">--}}
{{--                    <label class="block font-bold mb-6 text-xs">خدمات إضافية</label>--}}

{{--                    @if($selectedServices->count())--}}
{{--                        <div class="flex flex-wrap gap-2">--}}
{{--                            @foreach($extraServicesMap as $key => $item)--}}
{{--                                @if($selectedServices->contains($key))--}}
{{--                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 border border-gray-300">--}}
{{--                            {{ $item['icon'] }} {{ $item['label'] }}--}}
{{--                        </span>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    @else--}}
{{--                        <span class="text-sm text-gray-500">لا توجد خدمات إضافية</span>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}



{{--             collapsable--}}
@if($record->status == 3 || $record->status == 5)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Column 1 -->
        <div class="space-y-4">
            @foreach($record->emps_requester as $req_record)
                @php
                    $reviews = collect(json_decode($req_record->reviews, true)); // decode to collection
                @endphp
                <div class="border rounded-xl shadow p-4">
                    <h2 class="text-sm font-semibold cursor-pointer collapse-toggle">
                        <div class="flex items-center gap-2">
                            <!-- Arrow Icon -->
                            <svg class="w-4 h-4 text-gray-600 transition-transform transform collapse-arrow"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>

                            <!-- Name + Status -->
                            {{ $req_record->user->name }}
                            @php
                                $isReviewWritten = $req_record->reviews && $req_record->reviews != '';
                                $isrRequesterReview = $record->requester_reviews && $record->requester_reviews != '';
                                $isrRecipientReview = $record->recipient_reviews && $record->recipient_reviews != '';
                                $isMyReview = $req_record->user_id == auth()->id();  // adjust auth if needed

//                                        $allReviewsDone = $this->reviews_done();
                            @endphp

                            @if(!$isReviewWritten  )
                                {{--                                    @if(!$isrRequesterReview   )--}}
                                <span style="color: #a40e3b;">(تحت الإجراء)</span>
                                {{--                                    @elseif(!$isMyReview && !$allReviewsDone)--}}
                                {{--                                    @elseif(!$allReviewsDone)--}}
                                {{--                                    @elseif(!($isrRecipientReview && $isrRequesterReview))--}}
                                {{--                                        <span style="color: #287c0c;">(تم التقييم)</span>--}}
                            @else
                                <span style="color: #287c0c;">(تم التقييم)</span>
                                (
                                <span class="text-yellow-500 ml-1">&#9733;</span>
                                <span style="color: #a40e3b;">
                                            {{ number_format(floatval($reviews['totalRating']) / (count($reviews['answers']) - 1), 1) }}
                                        </span>
                                )
                            @endif

                        </div>
                    </h2>

                    {{--                            @if($isReviewWritten && ($isMyReview || $allReviewsDone))--}}
                    @if($isReviewWritten  )
                        {{--                            @if($isrRequesterReview  )--}}

                        <div class="collapse-content mt-9 text-gray-600 hidden">
                            @foreach($reviews['answers'] as $answer)
                                <div class="mb-4">
                                    <label class="font-semibold">{{ $answer['text'] }}</label><br>

                                    @if(is_numeric($answer['value']))
                                        <span
                                            class="text-yellow-500 inline-block">{!! str_repeat('&#9733;', $answer['value']) !!}</span>
                                        <span
                                            class="text-gray-300 inline-block">{!! str_repeat('&#9733;', 5 - $answer['value']) !!}</span>
                                    @else
                                        <div style="color: #0b0bc6;" class="mt-2 p-2 text-sm">
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
        <div class="space-y-4">
            @foreach($record->emps_recipients as $rec_record)
                @php
                    $reviews = collect(json_decode($rec_record->reviews, true)); // decode to collection
                @endphp
                @php
                    $isReviewWritten = $rec_record->reviews && $rec_record->reviews != '';
                    $isMyReview = $rec_record->user_id == auth()->id();
                    $allReviewsDone = $this->reviews_done();
                    $parsedReview = $isReviewWritten ? json_decode($rec_record->reviews, true) : null;
                @endphp

                <div class="border rounded-xl shadow p-4">
                    <h2 class="text-sm font-semibold cursor-pointer collapse-toggle">
                        <div class="flex items-center gap-2">
                            <!-- Arrow Icon -->
                            <svg class="w-4 h-4 text-gray-600 transition-transform transform collapse-arrow"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>

                            <!-- Name + Status -->
                            {{--                                    {{ $branches[$record->branch]}}--}}
                            {{$rec_record->user->name}}
                            @if(!$isReviewWritten )
                                {{--                                    @if(!$isrRecipientReview )--}}
                                <span style="color: #a40e3b;">(تحت الإجراء)</span>
                                {{--                                    @elseif(!$isMyReview && !$allReviewsDone)--}}
                                {{--                                    @elseif(!$allReviewsDone)--}}
                                {{--                                        <span style="color: #287c0c;">(تم التقييم)</span>--}}
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

                    {{--                            @if($isReviewWritten && ($isMyReview || $allReviewsDone))--}}
                    @if($isReviewWritten)
                        {{--                            @dd($parsedReviewRecipient)--}}
                        {{--                            @dd($rec_record)--}}
                        {{--                            @if($isrRecipientReview)--}}
                        <div class="collapse-content mt-9 text-gray-600 hidden">
                            {{--                                    @foreach($parsedReviewRecipient['answers'] as $answer)--}}
                            @foreach($parsedReview['answers'] as $answer)
                                <div class="mb-4">
                                    <label class="font-semibold">{{ $answer['text'] }}</label><br>

                                    @if(is_numeric($answer['value']))
                                        <span
                                            class="text-yellow-500 inline-block">{!! str_repeat('&#9733;', $answer['value']) !!}</span>
                                        <span
                                            class="text-gray-300 inline-block">{!! str_repeat('&#9733;', 5 - $answer['value']) !!}</span>
                                    @else
                                        <div style="color: #0b0bc6;" class="mt-2 p-2 text-sm">
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
    {{-- end of collapsaple--}}
    </div>
    </div>




    </div>

    @section('css-scripts')
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.21.2/dist/sweetalert2.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /*.swal2-confirm,*/
            /*.swal2-cancel {*/
            /*    opacity: 1 !important;*/
            /*    visibility: visible !important;*/
            /*    color: inherit !important;*/
            /*    background: inherit !important;*/
            /*}*/

            .swal2-confirm {
                background-color: #5b53b5 !important; /* or any color you like */
                color: white !important;
                border: none !important;
                opacity: 1 !important;
                visibility: visible !important;
            }

            .swal2-deny {
                background-color: #dc3741 !important; /* or any color you like */
                color: white !important;
                border: none !important;
                opacity: 1 !important;
                visibility: visible !important;
            }

            .swal2-cancel {
                background-color: #6e7881 !important; /* or any color you like */
                color: white !important;
                border: none !important;
                opacity: 1 !important;
                visibility: visible !important;
            }

            /* Responsive SweetAlert2 modal */
            .responsive-modal {
                width: 90vw !important;
                max-width: 600px !important;
                box-sizing: border-box;
                padding: 1rem;
            }

            /* Ensure input stretches correctly */
            .swal2-input,
                /*.swal2-textarea {*/
                /*    width: 100% !important;*/
                /*    box-sizing: border-box;*/
                /*}*/

            .star {
                font-size: 2rem;
                color: #ccc;
                cursor: pointer;
            }

            .star.selected {
                color: #fbbf24; /* Tailwind amber-400 */
            }

        </style>
    @stop
    @section('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('reviewComponent', () => ({
                    questions: [
                        {text: 'جودة التحضير للزيارة', id: 'preparation-quality', type: 'rating'},
                        {text: 'القيمة التسويقية للزيارة', id: 'marketing_value', type: 'rating'},
                        {text: 'القيمة الفنية للزيارة', id: 'technical_value', type: 'rating'},
                        {text: 'تحقيق الزيارة لأهدافها', id: 'visit_goals', type: 'rating'},
                        {text: 'ملاحظات', id: 'notes', type: 'textarea'}
                    ],

                    showRecipientReviewWithNotes() {
                        Swal.fire({
                            title: 'تقييم الزيارة',
                            html: this.questions.map(q => {
                                if (q.type === 'rating') {
                                    return `
                                <div class="rating-block" id="block-${q.id}">
                                    <label>${q.text}</label><br/>
                                    ${[1, 2, 3, 4, 5].map(i =>
                                        `<i class="star" data-question="${q.id}" data-value="${i}">&#9733;</i>`
                                    ).join('')}
                                </div>`;
                                } else if (q.type === 'textarea') {
                                    return `
                                <div class="textarea-block" id="block-${q.id}">
                                    <label>${q.text}</label><br/>
                                    <textarea id="textarea-${q.id}" rows="3"
                                        style="width: 100%; padding: 4px; text-align: right; direction: rtl"></textarea>
                                </div>`;
                                }
                            }).join(''),
                            confirmButtonText: 'إرسال',

                            didOpen: () => {
                                const ratings = {};
                                const stars = Swal.getPopup().querySelectorAll('.star');

                                stars.forEach(star => {
                                    star.addEventListener('click', () => {
                                        const qid = star.dataset.question;
                                        const value = parseInt(star.dataset.value);
                                        ratings[qid] = value;
                                        updateStarStyles(qid, value);
                                    });
                                });

                                function updateStarStyles(questionId, rating) {
                                    const groupStars = Swal.getPopup().querySelectorAll(`.star[data-question="${questionId}"]`);
                                    groupStars.forEach(s => {
                                        s.classList.toggle('selected', parseInt(s.dataset.value) <= rating);
                                    });
                                }

                                Swal._formResults = ratings;
                            },

                            preConfirm: () => {
                                const ratings = Swal._formResults || {};
                                const finalResults = [];
                                let ratingSum = 0;

                                this.questions.forEach(q => {
                                    let value;
                                    if (q.type === 'rating') {
                                        value = ratings[q.id] || null;
                                        if (value !== null) ratingSum += value;
                                    } else if (q.type === 'textarea') {
                                        const val = document.getElementById(`textarea-${q.id}`).value.trim();
                                        value = val;
                                    }

                                    finalResults.push({
                                        text: q.text,
                                        value: value
                                    });
                                });

                                const missing = finalResults.find(r => {
                                    const question = this.questions.find(q => q.text === r.text);
                                    return question.type === 'rating' && (r.value === null || r.value === undefined);
                                });

                                if (missing) {
                                    Swal.showValidationMessage(`الرجاء تقييم: "${missing.text}"`);
                                    return false;
                                }

                                return {
                                    answers: finalResults,
                                    totalRating: ratingSum
                                };
                            }
                        }).then(result => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'الرجاء الإنتظار',
                                    allowOutsideClick: false,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    willOpen: () => Swal.showLoading(),
                                });

                                Livewire.emit('review', result.value);
                            }
                        });
                    }
                }));
            });
        </script>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>

            const branchMap = {
                "0101": "فرع الاحساء",
                "0102": "فرع جدة",
                "0103": "فرع الرياض",
                "0104": "فرع وادي الدواسر",
                "0105": "فرع الجوف",
                "0106": "فرع الدمام",
                "0107": "فرع الخرج",
                "0108": "فرع نجران",
                "0109": "فرع حائل",
                "0110": "فرع تبوك",
                "0111": "فرع القصيم",
                "0112": "فرع ساجر",
                // "0201": "مزرعة الدالوة",
                // "0202": "مزرعة الفضول",
                // "0203": "مزرعة الدلم"
            };

            const employeesByBranch = @json($emps);

            const timeOptions = [
                "08:00 AM", "08:30 AM", "09:00 AM", "09:30 AM", "10:00 AM", "10:30 AM", "11:00 AM", "11:30 AM",
                "12:00 PM", "12:30 PM", "01:00 PM", "01:30 PM", "02:00 PM", "02:30 PM", "03:00 PM", "03:30 PM",
                "04:00 PM", "04:30 PM", "05:00 PM", "05:30 PM", "06:00 PM", "06:30 PM", "07:00 PM", "07:30 PM",
                "08:00 PM", "08:30 PM", "09:00 PM", "09:30 PM", "10:00 PM"
            ];

            {{--        ratings --}}
            function showArabicReviewWithNotes() {
                const questions = [


                    {text: 'جودة التحضير للزيارة', id: 'preparation-quality', type: 'rating'},
                    {text: 'القيمة التسويقية للزيارة', id: 'marketing_value', type: 'rating'},
                    {text: 'القيمة الفنية للزيارة', id: 'technical_value', type: 'rating'},
                    {text: 'تحقيق الزيارة لأهدافها', id: 'visit_goals', type: 'rating'},
                    {text: 'ملاحظات', id: 'notes', type: 'textarea'}
                ];

                Swal.fire({
                    title: 'تقييم الزيارة',
                    html: questions.map(q => {
                        if (q.type === 'rating') {
                            return `
          <div class="rating-block" id="block-${q.id}">
            <label>${q.text}</label><br/>
            ${[1, 2, 3, 4, 5].map(i =>
                                `<i class="star" data-question="${q.id}" data-value="${i}">&#9733;</i>`
                            ).join('')}
          </div>`;
                        } else if (q.type === 'textarea') {
                            return `
          <div class="textarea-block" id="block-${q.id}">
            <label>${q.text}</label><br/>
            <textarea id="textarea-${q.id}" rows="3" style="width: 100%; padding: 4px;"></textarea>
          </div>`;
                        }
                    }).join(''),
                    confirmButtonText: 'إرسال',
                    didOpen: () => {
                        const ratings = {};

                        const stars = Swal.getPopup().querySelectorAll('.star');
                        stars.forEach(star => {
                            star.addEventListener('click', () => {
                                const qid = star.dataset.question;
                                const value = parseInt(star.dataset.value);
                                ratings[qid] = value;
                                updateStarStyles(qid, value);
                            });
                        });

                        function updateStarStyles(questionId, rating) {
                            const groupStars = Swal.getPopup().querySelectorAll(`.star[data-question="${questionId}"]`);
                            groupStars.forEach(s => {
                                s.classList.toggle('selected', parseInt(s.dataset.value) <= rating);
                            });
                        }

                        Swal._formResults = ratings;
                    },
                    preConfirm: () => {
                        const ratings = Swal._formResults || {};
                        const finalResults = [];
                        let ratingSum = 0;

                        questions.forEach(q => {
                            let value;
                            if (q.type === 'rating') {
                                value = ratings[q.id] || null;
                                if (value !== null) {
                                    ratingSum += value;
                                }
                            } else if (q.type === 'textarea') {
                                const val = document.getElementById(`textarea-${q.id}`).value.trim();
                                value = val;
                            }

                            finalResults.push({
                                text: q.text,
                                value: value
                            });
                        });

                        const missing = finalResults.find(r => {
                            const question = questions.find(q => q.text === r.text);
                            return question.type === 'rating' && (r.value === null || r.value === undefined);
                        });

                        if (missing) {
                            Swal.showValidationMessage(`الرجاء تقييم: "${missing.text}"`);
                            return false;
                        }

                        return {
                            answers: finalResults,
                            totalRating: ratingSum
                        };
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        console.log('Arabic Feedback With Sum:', result.value);
                        // Submit to server here

                        Swal.fire({
                            title: 'الرجاء الإنتظار',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading()
                            },
                        });

                        Livewire.emit('review', result.value);
                    }
                });
            }

            function showRecipientReviewWithNotes() {
                const questions = [
                    {text: 'جودة التحضير  للزيارة', id: 'preparation-quality', type: 'rating'},
                    {text: 'القيمة التسويقية للزيارة', id: 'marketing_value', type: 'rating'},
                    {text: 'القيمة الفنية للزيارة', id: 'technical_value', type: 'rating'},
                    // {text: 'مستوى الدعم الفني المقدم', id: 'technical_support', type: 'rating'},
                    // {text: 'مستوى الرضا عن الزيارة', id: 'customer_satisfaction', type: 'rating'},
                    {text: 'تحقيق الزيارة لأهدافها', id: 'visit_goals', type: 'rating'},
                    {text: 'ملاحظات', id: 'notes', type: 'textarea'}
                ];

                Swal.fire({
                    title: 'تقييم الزيارة',
                    html: questions.map(q => {
                        if (q.type === 'rating') {
                            return `
          <div class="rating-block" id="block-${q.id}">
            <label>${q.text}</label><br/>
            ${[1, 2, 3, 4, 5].map(i =>
                                `<i class="star" data-question="${q.id}" data-value="${i}">&#9733;</i>`
                            ).join('')}
          </div>`;
                        } else if (q.type === 'textarea') {
                            return `
          <div class="textarea-block" id="block-${q.id}">
            <label>${q.text}</label><br/>
            <textarea id="textarea-${q.id}" rows="3" style="width: 100%; padding: 4px;text-align: right; direction: rtl"></textarea>
          </div>`;
                        }
                    }).join(''),
                    confirmButtonText: 'إرسال',
                    didOpen: () => {
                        const ratings = {};

                        const stars = Swal.getPopup().querySelectorAll('.star');
                        stars.forEach(star => {
                            star.addEventListener('click', () => {
                                const qid = star.dataset.question;
                                const value = parseInt(star.dataset.value);
                                ratings[qid] = value;
                                updateStarStyles(qid, value);
                            });
                        });

                        function updateStarStyles(questionId, rating) {
                            const groupStars = Swal.getPopup().querySelectorAll(`.star[data-question="${questionId}"]`);
                            groupStars.forEach(s => {
                                s.classList.toggle('selected', parseInt(s.dataset.value) <= rating);
                            });
                        }

                        Swal._formResults = ratings;
                    },
                    preConfirm: () => {
                        const ratings = Swal._formResults || {};
                        const finalResults = [];
                        let ratingSum = 0;

                        questions.forEach(q => {
                            let value;
                            if (q.type === 'rating') {
                                value = ratings[q.id] || null;
                                if (value !== null) {
                                    ratingSum += value;
                                }
                            } else if (q.type === 'textarea') {
                                const val = document.getElementById(`textarea-${q.id}`).value.trim();
                                value = val;
                            }

                            finalResults.push({
                                text: q.text,
                                value: value
                            });
                        });

                        const missing = finalResults.find(r => {
                            const question = questions.find(q => q.text === r.text);
                            return question.type === 'rating' && (r.value === null || r.value === undefined);
                        });

                        if (missing) {
                            Swal.showValidationMessage(`الرجاء تقييم: "${missing.text}"`);
                            return false;
                        }

                        return {
                            answers: finalResults,
                            totalRating: ratingSum
                        };
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        // Submit to server here

                        Swal.fire({
                            title: 'الرجاء الإنتظار',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading()
                            },
                        });

                        Livewire.emit('review', result.value);
                    }
                });
            }

            // Run it


            {{--    end of ratings --}}

            document.addEventListener('DOMContentLoaded', () => {

                const x_requester = {!! $record->emps_requester !!};
                const x_recipients = {!! $record->emps_recipients !!};

                const currentUserId = {{ \Illuminate\Support\Facades\Auth::id()  }};
                {{--const isRecipient = {!! $record->recipient_id !!} === currentUserId;--}}
                {{--const isOwner = {!! $record->requester_id !!} === currentUserId;--}}

                const isRecipient = x_recipients.some(obj => obj.user_id === currentUserId);
                const isOwner = x_requester.some(obj => obj.user_id === currentUserId);
                const isDeleted = {!! $record->is_deleted !!};
                const status = {!! $record->status !!};

                const visit = {!! json_encode($record) !!};
                console.log(formatTime(visit.start));

                console.log('rec: ' + isRecipient);
                console.log('owner:' + isOwner);

                const approveBtn = document.getElementById('approve-btn');
                const rejectBtn = document.getElementById('reject-btn');
                const reqRateBtn = document.getElementById('req-rate-btn');
                const recRateBtn = document.getElementById('rec-rate-btn');
                const closeBtn = document.getElementById('close-btn');
                const editBtn = document.getElementById('edit-btn');
                const deleteBtn = document.getElementById('delete-btn');


                if (approveBtn || rejectBtn) {
                    document.getElementById('approve-btn').addEventListener('click', () => {

                        Swal.fire({
                            title: 'الموافقة',
                            html: `
<label for="approve-reason" style="min-width: 120px;">يرجى كتابة الملاحظات إن وجد</label>
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <textarea id="approve-reason" class="swal2-textarea" style="flex: 1; height: 150px; resize: none; direction: rtl;
                 border: 1px solid #64748b;
                 padding: 0.625em;
                 border-radius: 0em;
                 font-family: inherit;
                 font-size: 10pt;"></textarea>
    </div>`,
                            preConfirm: () => {
                                const reason = document.getElementById('approve-reason').value.trim();
                                return reason;
                            },
                            showCancelButton: true,
                            confirmButtonText: 'تأكيد الموافقة',
                            cancelButtonText: 'عودة',
                        }).then((res) => {
                            if (res.isConfirmed) {

                                Swal.fire({
                                    title: 'الرجاء الإنتظار',
                                    allowOutsideClick: false,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    willOpen: () => {
                                        Swal.showLoading()
                                    },
                                });

                                Livewire.emit('approveVisit', {
                                    status_notice: res.value
                                });

                                // Swal.fire({
                                //     title: 'تمت الموافقة!',
                                //     icon: 'success',
                                //     timer: 2000,
                                //     showConfirmButton: false,
                                //     timerProgressBar: true
                                // });

                            }
                        });


                    });

                    document.getElementById('reject-btn').addEventListener('click', () => {
                        Swal.fire({
                            title: 'سبب الرفض',
                            html: `
<label for="reject-reason" style="min-width: 120px;">يرجى إدخال سبب رفض الزيارة</label>
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <textarea id="reject-reason" class="swal2-textarea" style="flex: 1; height: 150px; resize: none; direction: rtl;
                 border: 1px solid #64748b;
                 padding: 0.625em;
                 border-radius: 0em;
                 font-family: inherit;
                 font-size: 10pt;"></textarea>
    </div>`,
                            preConfirm: () => {
                                const reason = document.getElementById('reject-reason').value.trim();
                                if (!reason) {
                                    Swal.showValidationMessage('يرجى كتابة سبب الرفض');
                                    return false;
                                }
                                return reason;
                            },
                            showCancelButton: true,
                            confirmButtonText: 'تأكيد الرفض',
                            cancelButtonText: 'عودة',
                        }).then((res) => {
                            if (res.isConfirmed) {

                                Swal.fire({
                                    title: 'الرجاء الإنتظار',
                                    allowOutsideClick: false,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    willOpen: () => {
                                        Swal.showLoading()
                                    },
                                });

                                Livewire.emit('rejectVisit', {
                                    status_notice: res.value
                                });

                                // Swal.fire({
                                //     title: 'تم الرفض!',
                                //     icon: 'info',
                                //     timer: 2000,
                                //     showConfirmButton: false,
                                //     timerProgressBar: true
                                // });
                            }
                        });
                    });
                }

                if (reqRateBtn) {
                    document.getElementById('req-rate-btn').addEventListener('click', () => {
                        showArabicReviewWithNotes();
                    });
                }

                if (recRateBtn) {
                    document.getElementById('rec-rate-btn').addEventListener('click', () => {
                        showRecipientReviewWithNotes();
                    });
                }

                if (closeBtn) {
                    document.getElementById('close-btn').addEventListener('click', () => {

                        Swal.fire({
                            title: 'إنجاز الزيارة',
                            text: 'هل تم الانتهاء من عمل الزيارة؟',
                            showCancelButton: true,
                            confirmButtonText: 'نعم',
                            cancelButtonText: 'لا',
                        }).then((res) => {
                            if (res.isConfirmed) {

                                Swal.fire({
                                    title: 'الرجاء الإنتظار',
                                    allowOutsideClick: false,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    willOpen: () => {
                                        Swal.showLoading()
                                    },
                                });

                                Livewire.emit('closeVisit');

                                // Swal.fire({
                                //     title: 'تمت الموافقة!',
                                //     icon: 'success',
                                //     timer: 2000,
                                //     showConfirmButton: false,
                                //     timerProgressBar: true
                                // });

                            }
                        });


                    });
                }

                if (editBtn) {
                    editBtn.addEventListener('click', () => {
                        // Paste your entire Swal.fire({...}) code here
                        // (the one you shared with styling, form, preConfirm, etc.)
                        Swal.fire({
                            title: 'تعديل الزيارة',
                            html:`<style>
  .edit-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    direction: rtl;
    width: 100%;
    box-sizing: border-box;
  }

  .edit-form-group {
    display: flex;
    flex-direction: column;
  }

  .edit-form-group label {
    font-weight: bold;
    margin-bottom: 6px;
    font-size: 14px;
  }

  .edit-form-group input,
  .edit-form-group select,
  .edit-form-group textarea {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-family: inherit;
    box-sizing: border-box;
    width: 100%;
  }

  .edit-form-group textarea {
    resize: none;
    height: 120px;
  }

  /* Mobile: force single column */
  @media (max-width: 600px) {
    .edit-form {
      grid-template-columns: 1fr !important;
    }

    .edit-form-group[style*="grid-column"] {
      grid-column: span 1 !important;
    }
  }
</style>

<div dir="rtl" class="text-center">
<!--  <div class="edit-form-group" style="grid-column: span 2;">-->
  <div class="text-center">
    <label for="edit-title" style="min-width: 120px;text-align:right;" class="text-sm font-bold" >عنوان الزيارة</label>
    <input type="text" id="edit-title" class="form-input w-full" value="${visit.title || ''}">
  </div>

  <div class="text-center">
    <label for="edit-reason"  class="text-sm font-bold">سبب الزيارة</label>
    <input class="form-input w-full" type="text" id="edit-reason" value="${visit.reason || ''}">
  </div>

  <div  class="text-center" >
    <label for="edit-goals" class="text-sm font-bold">التحضيرات المطلوبه من الفرع</label>
    <input class="form-input w-full" id="edit-goals" value="${visit.goals || ''}">
  </div>

  <div class="text-center">
    <label for="edit-branch" class="text-sm font-bold">مكان الزيارة</label>
    <select class="form-select w-full" id="edit-branch" disabled >
      <option value="" disabled>اختر المكان</option>
      ${Object.entries(branchMap).map(([key, name]) =>
                                `<option value="${key}" ${visit.branch === key ? 'selected' : ''}>${name}</option>`
                            ).join('')}
    </select>
  </div>

  <div class="text-center my-2">
    <label for="edit-employees" class="text-sm font-bold">الموظفين</label>
    <select class="form-select w-full" id="edit-employees" multiple></select>
  </div>


<div class="flex w-full gap-2">
   <div class="text-center" >
     <label class="text-sm font-bold" style="">تاريخ البداية</label>
     <input class="form-input w-full" type="date" id="start" value="${visit.start ? new Date(visit.start).toISOString().split('T')[0] : ''}"  >
   </div>
     <div class="text-center">
    <label class="text-sm font-bold" for="edit-visit-time w-full">وقت الزيارة</label>
    <select class="form-select w-full" id="edit-visit-time">
      <option value="" disabled>اختر الوقت</option>
      ${timeOptions.map(time =>
                                `<option value="${time}" ${formatTime(visit.start) == time ? 'selected' : ''}>${time}</option>`
                            ).join('')}
    </select>

</div>
  </div>
<div class="flex w-full gap-2">
   <div class="text-center" >
     <label class="text-sm font-bold" style="min-width: 120px;">تاريخ النهاية</label>
     <input class="form-input w-full" type="date" id="end" value="${visit.end ? new Date(visit.end).toISOString().split('T')[0] : ''}"  >
   </div>

     <div class="text-center">
    <label class="text-sm font-bold" for="edit-ene-time w-full">وقت الانتهاء</label>
    <select class="form-select w-full" id="edit-end-time">
      <option value="" disabled>اختر الوقت</option>

      ${timeOptions.map(time =>
                                `<option value="${time}" ${formatTime(visit.end) === time ? 'selected' : ''}>${time}</option>`
                            ).join('')}
    </select>

</div>
</div>
   <div class="text-center w-full" style="grid-column: span 2;">
     <label class="text-sm font-bold" style="min-width: 120px;">المرافقون</label>
     <input class="form-input w-full" type="text" id="attendants" value="${visit.attendants || ''}" >
   </div>

</div>
`,
                            focusConfirm: false,
                            showCancelButton: true,
                            confirmButtonText: 'تحديث',
                            cancelButtonText: 'عودة',
                            reverseButtons: true,
                            didOpen: () => {
                                const branchSelect = document.getElementById('edit-branch');
                                const employeeSelect = document.getElementById('edit-employees');

                                $(employeeSelect).select2({
                                    dir: "rtl",
                                    dropdownCssClass: "select-font-size",
                                    dropdownParent: document.querySelector('.swal2-popup'),
                                    placeholder: "اختر الموظفين"
                                });

                                const populateEmployees = (branchId, selected = []) => {
                                    const employees = employeesByBranch[branchId] || [];
                                    $(employeeSelect).empty();
                                    disabledEmployees = []; // Reset

                                    employees.forEach(emp => {
                                        const isGroup8 = emp.group == 8;
                                        const shouldBeSelected = selected.includes(emp.id.toString()) ; // SELECT if previously selected OR group 8
                                        // const shouldBeSelected = selected.includes(emp.id.toString()) || isGroup8; // SELECT if previously selected OR group 8

                                        const option = new Option(emp.name, emp.id, shouldBeSelected, shouldBeSelected);
                                        // const option = new Option(emp.name, emp.id);

                                        // if (isGroup8) {
                                        //     option.disabled = true;
                                        //     disabledEmployees.push(emp.id.toString());
                                        // }

                                        $(employeeSelect).append(option);
                                    });

                                    $(employeeSelect).trigger('change');
                                };


                                const initialBranchId = branchSelect.value;
                                // const selectedEmpIds = (info.event.extendedProps.emps_recipients || []).map(emp => emp.user_id.toString());
                                const selectedEmpIds = (x_recipients || []).map(emp => emp.user_id.toString());

                                console.log('======= employees =======')
                                // console.log(info.event.extendedProps.employees);
                                // console.log(info.event);
                                console.log(x_recipients);

                                populateEmployees(initialBranchId, selectedEmpIds);

                                branchSelect.addEventListener('change', () => {
                                    const newBranchId = branchSelect.value;
                                    populateEmployees(newBranchId);
                                });

                                $(employeeSelect).on('select2:unselecting', function (e) {
                                    const id = e.params.args.data.id;
                                    const option = $(this).find(`option[value="${id}"]`);
                                    if (option.prop('disabled')) {
                                        e.preventDefault();
                                    }
                                });
                            },
                            preConfirm: () => {
                                const title = document.getElementById('edit-title').value;
                                const reason = document.getElementById('edit-reason').value;
                                const goals = document.getElementById('edit-goals').value;
                                const branch = document.getElementById('edit-branch').value;
                                const visitTime = document.getElementById('edit-visit-time').value;
                                const endTime = document.getElementById('edit-end-time').value;
                                const attendants = document.getElementById('attendants').value;
                                const start = document.getElementById('start').value;
                                const end = document.getElementById('end').value;
                                const selectedEmployees = $('#edit-employees').val();
                                // let selectedEmployees = $('#edit-employees').val() || [];
                                // disabledEmployees.forEach(id => {
                                //     if (!selectedEmployees.includes(id)) {
                                //         selectedEmployees.push(id);
                                //     }
                                // });

                                if (
                                    !title.trim() ||
                                    !reason.trim() ||
                                    // !goals.trim() ||
                                    !branch ||
                                    !visitTime ||
                                    !selectedEmployees ||
                                    selectedEmployees.length === 0
                                ) {
                                    Swal.showValidationMessage('الرجاء تعبئة جميع الحقول');
                                    return false;
                                }

                                // const startDateTime = combineDateAndTime(info.event.startStr, visitTime);
                                //  beginningDate = visit.start;
                                // endingDate =visit.end;
                                beginningDate = start;
                                endingDate =end;
                                // const formatted = beginningDate.toLocaleDateString('en-GB');

                                const startDateTime = combineDateAndTime( beginningDate.split(' ')[0], visitTime);
                                const endDateTime = combineDateAndTime( endingDate.split(' ')[0], endTime);

                                return {
                                    title,
                                    reason,
                                    goals,
                                    branch,
                                    employees: selectedEmployees,
                                    // start,
                                    start: startDateTime,
                                    // end: visit.end,
                                    end: endDateTime,
                                    attendants: attendants
                                    // end: info.event.endStr
                                };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {

                                Swal.fire({
                                    title: 'الرجاء الإنتظار',
                                    allowOutsideClick: false,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    willOpen: () => {
                                        Swal.showLoading()
                                    },
                                });

                                Livewire.emit('updateVisit', {
                                    // id: info.event.id,
                                    id: visit.id,
                                    title: result.value.title,
                                    reason: result.value.reason,
                                    goals: result.value.goals,
                                    branch: result.value.branch,
                                    employees: result.value.employees,
                                    start: result.value.start,
                                    end: result.value.end,
                                    attendants: result.value.attendants
                                });
                            }
                        });
                    });
                }

                if(deleteBtn) {
                    deleteBtn.addEventListener('click', () => {

                        Swal.fire({
                            title: 'هل متأكد من ذلك؟',
                            text: "سوف يتم إلغاء هذه الزيارة وإبلاغ الشخص المسؤول بمكان الزيارة",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'نعم، الغي الزيارة',
                            cancelButtonText: 'إلغاء الامر'
                        }).then((delResult) => {
                            if (delResult.isConfirmed) {
                                // Livewire.emit('deleteVisit', info.event.id);
                                // Swal.fire({
                                //     title: 'تم الحذف!',
                                //     text: 'هذه الزيارة تم حذفها',
                                //     icon: 'success',
                                //     timer: 2000,
                                //     showConfirmButton: false,
                                //     timerProgressBar: true
                                // });

                                Swal.fire({
                                    title: 'سبب الإلغاء',
                                    html: `
<label for="delete-reason" style="min-width: 120px;">يرجى إدخال سبب إلغاء الزيارة</label>
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <textarea id="delete-reason" class="swal2-textarea" style="flex: 1; height: 150px; resize: none; direction: rtl;
                 border: 1px solid #64748b;
                 padding: 0.625em;
                 border-radius: 0em;
                 font-family: inherit;
                 font-size: 10pt;"></textarea>
    </div>
  `,
                                    focusConfirm: false,
                                    showCancelButton: true,
                                    confirmButtonText: 'إلغاء الزيارة',
                                    cancelButtonText: 'عودة',
                                    customClass: {
                                        popup: 'responsive-modal'
                                    },
                                    preConfirm: () => {
                                        const reason = document.getElementById('delete-reason').value.trim();
                                        if (!reason) {
                                            Swal.showValidationMessage('يجب إدخال السبب قبل الإلغاء');
                                            return false;
                                        }
                                        return reason;
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        const reason = result.value;

                                        Livewire.emit('deleteVisit', {
                                            id: visit.id,
                                            delete_reason: reason
                                        });

                                        // Swal.fire({
                                        //     title: 'تم الحذف!',
                                        //     text: 'هذه الزيارة تم حذفها',
                                        //     icon: 'success',
                                        //     timer: 2000,
                                        //     showConfirmButton: false,
                                        //     timerProgressBar: true
                                        // });
                                    }
                                });


                            }
                        });

                    });
                }


                document.querySelectorAll('.collapse-toggle').forEach(toggle => {
                    toggle.addEventListener('click', function () {
                        const content = this.nextElementSibling;
                        const arrow = this.querySelector('.collapse-arrow');

                        if (content) {
                            content.classList.toggle('hidden');
                        }

                        if (arrow) {
                            arrow.classList.toggle('rotate-180');
                        }
                    });
                });

            });

            function combineDateAndTime(dateStr, timeStr) {
                const [year, month, day] = dateStr.split("T")[0].split("-").map(Number);
                const [time, modifier] = timeStr.split(" ");
                let [hours, minutes] = time.split(":").map(Number);

                if (modifier === "PM" && hours !== 12) hours += 12;
                if (modifier === "AM" && hours === 12) hours = 0;

                const localDate = new Date(year, month - 1, day, hours, minutes);

                // Instead of .toISOString(), format it manually to keep local time
                const pad = n => String(n).padStart(2, '0');
                const localDateTimeString = `${year}-${pad(month)}-${pad(day)}T${pad(hours)}:${pad(minutes)}:00`;

                return localDateTimeString;
            }

            function formatTime(dateObj) {
                if (!dateObj) return '';
                return new Date(dateObj).toLocaleTimeString('en-US', {
                    timeZone: 'Asia/Riyadh',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }

        </script>
    @stop
