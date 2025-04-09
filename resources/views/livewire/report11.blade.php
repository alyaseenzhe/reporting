@section('title')
    تقرير عمليات الأصناف
@stop
<div>
    <div class="mb-5">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        <span class="mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">الصفحة الرئيسية</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 199.404 199.404"
                             xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                        <a href="{{ route('sap-reports') }}" class="text-gray-700 hover:text-gray-900 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقارير ساب</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 199.404 199.404"
                             xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">تقرير عمليات الأصناف</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <button type="button" class="collapsible active">خيارات البحث</button>

    <div id="branch-container" class="mb-6">
        <div class="flex flex-col gap-4">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div class="w-full">
                    <label class="block font-bold mb-2">تاريخ البداية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="start_date" type="date" name="start_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div wire:ignore class="w-full">
                    <label class="block font-bold mb-2">تاريخ النهاية
                        <span class="text-red-500">*</span>
                    </label>
                    <input id="end_date" type="date" name="end_date"
                           class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                           style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-2">الفرع
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="dept_id" name="dept_id[]" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('dept_id') border: solid 1px #fda4af; @enderror">
                            <option value="dept_all" selected>الكل</option>
                            @if(in_array("3", $branches))
                                <option value="0101" >الاحساء</option>
                            @endif
                            @if(in_array("10", $branches))
                                <option value="0102" >جدة</option>
                            @endif
                            @if(in_array("7", $branches))
                                <option value="0103" >الرياض</option>
                            @endif
                            @if(in_array("13", $branches))
                                <option value="0104" >وادي الدواسر</option>
                            @endif
                            @if(in_array("4", $branches))
                                <option value="0105" >الجوف</option>
                            @endif
                            @if(in_array("6", $branches))
                                <option value="0106" >الدمام</option>
                            @endif
                            @if(in_array("5", $branches))
                                <option value="0107" >الخرج</option>
                            @endif
                            @if(in_array("12", $branches))
                                <option value="0108" >نجران</option>
                            @endif
                            @if(in_array("11", $branches))
                                <option value="0109" >حائل</option>
                            @endif
                            @if(in_array("9", $branches))
                                <option value="0110" >تبوك</option>
                            @endif
                            @if(in_array("8", $branches))
                                <option value="0111" >القصيم</option>
                            @endif
                            @if(in_array("505", $branches))
                                <option value="0112" >ساجر</option>
                            @endif
                            @if(in_array("3", $branches))
                                <option value="0201" >مزرعة الدالوة</option>
                                <option value="0202" >مزرعة الفضول</option>
                                <option value="0203" >مزرعة الدلم</option>
                                <option value="0001" >المركز الرئيسي</option>
                            @endif

                        </select>
                    </div>
                    @error('dept_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="w-full">
                    <label class="block font-bold mb-4">نوع البحث
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-row">
                        <div class="flex items-center w-full">
                            <input type="radio" name="search_type" value="item_code_search"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                برقم الصنف</label>
                        </div>
                        <div class="flex items-center w-full">
                            <input type="radio" name="search_type" value="advanced_search"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">متقدم</label>
                        </div>
                    </div>

                    @error('item_type')
                    <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
                </div>
                {{--                <div class="mt-8 text-center w-full">--}}
                {{--                    <button id="reset-btn" style="background-color: #01290f;" class="w-full btn hover:bg-indigo-600 text-white">--}}
                {{--                        <span class="mr-2 font-bold">--}}
                {{--                            <span></span>--}}
                {{--                            <span>إعادة ضبط</span>--}}
                {{--                        </span>--}}
                {{--                    </button>--}}
                {{--                </div>--}}
            </div>
        </div>
        <div id="filteration-row2" style="padding-left: 20px" class="w-full flex flex-col gap-4 mt-3 hide">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div id="group_container" class="w-full">
                    <label class="block font-bold mb-2">المجموعات
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="group_type" name="group_type"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('cat_type') border: solid 1px #fda4af; @enderror">
                            {{--                            <option value="cat_all" @if(in_array("cat_all", $cat_type)) selected @endif>الكل</option>--}}
                            {{--                            <option value="bathoor" @if(in_array("bathoor", $cat_type)) selected @endif>بذور</option>--}}
                            {{--                            <option value="asmedah" @if(in_array("asmedah", $cat_type)) selected @endif>اسمدة</option>--}}
                            {{--                            <option value="mobedat" @if(in_array("mobedat", $cat_type)) selected @endif>مبيدات</option>--}}
                            {{--                            <option value="other" @if(in_array("other", $cat_type)) selected @endif>اخرى</option>--}}
                            <option value="select_group" selected>اختر مجموعة</option>
                            <option value="groups_all">الكل</option>
                            <option value="commerce">الادارة التجارية</option>
                            <option value="farms">الانتاج الزراعي</option>
                            <option value="sundries">النثريات</option>
                        </select>
                    </div>
                    @error('group_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div id="filteration-row3" style="padding-left: 20px" class="w-full flex flex-col gap-4 mt-3 hide">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div id="cat_container" class="w-full">
                    <label class="block font-bold mb-2">نوع المواد
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="cat_type" name="cat_type" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('cat_type') border: solid 1px #fda4af; @enderror">
                            {{--                            <option value="cat_all" selected>الكل</option>--}}
                            {{--                            @if($group_type == 'groups_all')--}}
                            {{--                                <option value="104">اسمدة أحادية</option>--}}
                            {{--                                <option value="105">اسمدة مركبة ورقية</option>--}}
                            {{--                                <option value="106">اسمدة مركبة ذوابة</option>--}}
                            {{--                                <option value="107">اسمدة مركبة حبيبية</option>--}}
                            {{--                                <option value="108">اسمدة مركبة سائلة ومعلقة</option>--}}
                            {{--                                <option value="109">عناصر نادرة</option>--}}
                            {{--                                <option value="110">احماض دبالية</option>--}}
                            {{--                                <option value="111">احماض امينية</option>--}}
                            {{--                                <option value="112">اعشاب بحرية</option>--}}
                            {{--                                <option value="114">مصحح ملوحة وحموضة</option>--}}
                            {{--                                <option value="115">اسمدة متخصصة</option>--}}
                            {{--                                <option value="116">ترب أساسية</option>--}}
                            {{--                                <option value="117">بوتنج سويل</option>--}}
                            {{--                                <option value="118">عطن</option>--}}
                            {{--                                <option value="120">مبيدات حشرية</option>--}}
                            {{--                                <option value="121">مبيدات فطرية</option>--}}
                            {{--                                <option value="123">مبيدات اعشاب</option>--}}
                            {{--                                <option value="124">حشرات نافعة</option>--}}
                            {{--                                <option value="125">مستخلصات نباتية</option>--}}
                            {{--                                <option value="126">فرمونات وجواذب</option>--}}
                            {{--                                <option value="127">مصائد ولواصق</option>--}}
                            {{--                                <option value="128">مواد لاصقة وناشرة</option>--}}
                            {{--                                <option value="129">مبيدات قوارض</option>--}}
                            {{--                                <option value="130">مبيدات صحة عامة</option>--}}
                            {{--                                <option value="139">مرشات يدوية ملحقاتها</option>--}}
                            {{--                                <option value="140">مقصات ومحشات</option>--}}
                            {{--                                <option value="141">بلاستك تغطية وتعقيم</option>--}}
                            {{--                                <option value="142">صواني ومراكن</option>--}}
                            {{--                                <option value="143">خيوط واسلاك</option>--}}
                            {{--                                <option value="144">شباك وشاش</option>--}}
                            {{--                                <option value="145">معدات قياس</option>--}}
                            {{--                                <option value="147">مواد تعبئة</option>--}}
                            {{--                                <option value="148">الات يدوية</option>--}}
                            {{--                                <option value="150">مرشات الية وملحقاتها</option>--}}
                            {{--                                <option value="151">اليات وملحقاتها</option>--}}
                            {{--                                <option value="152">هوجيندرون</option>--}}
                            {{--                                <option value="154">ميجا جرين للصناعات المتطورة</option>--}}
                            {{--                                <option value="155">ازود</option>--}}
                            {{--                                <option value="156">إدارة مياة أخرى</option>--}}
                            {{--                                <option value="157">داكوم</option>--}}
                            {{--                                <option value="158">كاروسبراي</option>--}}
                            {{--                                <option value="159">اوربيناتي</option>--}}
                            {{--                                <option value="161">اخري (مكائن و قطع غيار)</option>--}}
                            {{--                                <option value="162">نحل وادواته</option>--}}
                            {{--                                <option value="163">صيانة</option>--}}
                            {{--                                <option value="164">مبيعات / مشتريات مباشرة</option>--}}
                            {{--                                <option value="137">بذور نجيل</option>--}}
                            {{--                                <option value="132">بذور محاصيل حقلية</option>--}}
                            {{--                                <option value="131">بذور خضار</option>--}}
                            {{--                                <option value="133">بذور اعلاف</option>--}}
                            {{--                                <option value="134">بذور أشجار مثمرة</option>--}}
                            {{--                                <option value="135">بذور ورقيات</option>--}}
                            {{--                                <option value="165">منتج خضار</option>--}}
                            {{--                                <option value="166">منتج فواكة</option>--}}
                            {{--                                <option value="169">أدوات تعبئة</option>--}}
                            {{--                                <option value="171">أدوات ومواد بيوت محمية</option>--}}
                            {{--                                <option value="172">بذور حبوب</option>--}}
                            {{--                                <option value="173">ريفولس</option>--}}
                            {{--                                <option value="174">جرينوكي</option>--}}
                            {{--                                <option value="175">ركين</option>--}}
                            {{--                                <option value="177">مواد تبخير وتعقيم</option>--}}
                            {{--                                <option value="178">كائنات دقيقة</option>--}}
                            {{--                                <option value="179">ابصال</option>--}}
                            {{--                                <option value="180">الأصول الثابتة</option>--}}
                            {{--                            @elseif($group_type == 'commerce')--}}
                            {{--                                <option value="">اسمدة أحادية</option>--}}
                            {{--                                <option value="">اسمدة مركبة ورقية</option>--}}
                            {{--                                <option value="">اسمدة مركبة ذوابة</option>--}}
                            {{--                                <option value="">اسمدة مركبة حبيبية</option>--}}
                            {{--                                <option value="">اسمدة مركبة سائلة ومعلقة</option>--}}
                            {{--                                <option value="">عناصر نادرة</option>--}}
                            {{--                                <option value="">احماض دبالية</option>--}}
                            {{--                                <option value="">احماض امينية</option>--}}
                            {{--                                <option value="">اعشاب بحرية</option>--}}
                            {{--                                <option value="">مصحح ملوحة وحموضة</option>--}}
                            {{--                                <option value="">اسمدة متخصصة</option>--}}
                            {{--                                <option value="">ترب أساسية</option>--}}
                            {{--                                <option value="">بوتنج سويل</option>--}}
                            {{--                                <option value="">عطن</option>--}}
                            {{--                                <option value="">مبيدات حشرية</option>--}}
                            {{--                                <option value="">مبيدات فطرية</option>--}}
                            {{--                                <option value="">مبيدات اعشاب</option>--}}
                            {{--                                <option value="">حشرات نافعة</option>--}}
                            {{--                                <option value="">مستخلصات نباتية</option>--}}
                            {{--                                <option value="">فرمونات وجواذب</option>--}}
                            {{--                                <option value="">مصائد ولواصق</option>--}}
                            {{--                                <option value="">مواد لاصقة وناشرة</option>--}}
                            {{--                                <option value="">مبيدات قوارض</option>--}}
                            {{--                                <option value="">مبيدات صحة عامة</option>--}}
                            {{--                                <option value="">مرشات يدوية ملحقاتها</option>--}}
                            {{--                                <option value="">مقصات ومحشات</option>--}}
                            {{--                                <option value="">بلاستك تغطية وتعقيم</option>--}}
                            {{--                                <option value="">صواني ومراكن</option>--}}
                            {{--                                <option value="">خيوط واسلاك</option>--}}
                            {{--                                <option value="">شباك وشاش</option>--}}
                            {{--                                <option value="">معدات قياس</option>--}}
                            {{--                                <option value="">مواد تعبئة</option>--}}
                            {{--                                <option value="">الات يدوية</option>--}}
                            {{--                                <option value="">مرشات الية وملحقاتها</option>--}}
                            {{--                                <option value="">اليات وملحقاتها</option>--}}
                            {{--                                <option value="">هوجيندرون</option>--}}
                            {{--                                <option value="">ميجا جرين للصناعات المتطورة</option>--}}
                            {{--                                <option value="">ازود</option>--}}
                            {{--                                <option value="">إدارة مياة أخرى</option>--}}
                            {{--                                <option value="">داكوم</option>--}}
                            {{--                                <option value="">كاروسبراي</option>--}}
                            {{--                                <option value="">اوربيناتي</option>--}}
                            {{--                                <option value="">اخري (مكائن و قطع غيار)</option>--}}
                            {{--                                <option value="">نحل وادواته</option>--}}
                            {{--                                <option value="">صيانة</option>--}}
                            {{--                                <option value="">مبيعات / مشتريات مباشرة</option>--}}
                            {{--                                <option value="">بذور نجيل</option>--}}
                            {{--                                <option value="">بذور محاصيل حقلية</option>--}}
                            {{--                                <option value="">بذور خضار</option>--}}
                            {{--                                <option value="">بذور اعلاف</option>--}}
                            {{--                                <option value="">بذور أشجار مثمرة</option>--}}
                            {{--                                <option value="">بذور ورقيات</option>--}}
                            {{--                                <option value="">أدوات ومواد بيوت محمية</option>--}}
                            {{--                                <option value="">بذور حبوب</option>--}}
                            {{--                                <option value="">ريفولس</option>--}}
                            {{--                                <option value="">جرينوكي</option>--}}
                            {{--                                <option value="">ركين</option>--}}
                            {{--                                <option value="">مواد تبخير وتعقيم</option>--}}
                            {{--                                <option value="">كائنات دقيقة</option>--}}
                            {{--                                <option value="">ابصال</option>--}}
                            {{--                                <option value="">الأصول الثابتة</option>--}}
                            {{--                            @elseif($group_type == 'farms')--}}
                            {{--                                <option value="">منتج خضار</option>--}}
                            {{--                                <option value="">منتج فواكة</option>--}}
                            {{--                            @elseif($group_type == 'sundries')--}}
                            {{--                                <option value="">أدوات تعبئة</option>--}}
                            {{--                            @endif--}}
                            {{--                            --}}{{--                            <option value="cat_all" @if(in_array("cat_all", $cat_type)) selected @endif>الكل</option>--}}
                            {{--                            --}}{{--                            <option value="bathoor" @if(in_array("bathoor", $cat_type)) selected @endif>بذور</option>--}}
                            {{--                            --}}{{--                            <option value="asmedah" @if(in_array("asmedah", $cat_type)) selected @endif>اسمدة</option>--}}
                            {{--                            --}}{{--                            <option value="mobedat" @if(in_array("mobedat", $cat_type)) selected @endif>مبيدات</option>--}}
                            {{--                            --}}{{--                            <option value="other" @if(in_array("other", $cat_type)) selected @endif>اخرى</option>--}}
                            {{--                            --}}{{--                            <option value="cat_all" selected>الكل</option>--}}
                            {{--                            --}}{{--                            @foreach($itemGrp as $item)--}}
                            {{--                            --}}{{--                                <option value="{{ $item['ItemGroupCode'] }}">{{ $item['ItemGroupName'] }}</option>--}}
                            {{--                            --}}{{--                            @endforeach--}}
                        </select>
                    </div>
                    @error('cat_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="sp_container" class="w-full">
                    <label class="block font-bold mb-2">نوع المميز
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="sp_type" name="sp_type" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('sp_type') border: solid 1px #fda4af; @enderror">
                            <option value="sp_all" selected>الكل</option>
                            <option value="0">مميز 0</option>
                            <option value="1">مميز 1</option>
                            <option value="2">مميز 2</option>
                        </select>
                    </div>
                    @error('sp_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="marketing_type_container" class="w-full">
                    <label class="block font-bold mb-2">الإدارات والاقسام
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="marketing_type" name="marketing_type" multiple="multiple"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('sp_type') border: solid 1px #fda4af; @enderror">
                            <option value="marketing_all" selected>الكل</option>
                            <option value="30">ادارة فنية - الاسمدة م1</option>
                            <option value="31">ادارة فنية - المبيدات م1</option>
                            <option value="32">ادارة فنية - البذور م1</option>
                            <option value="40">اقسام تسويقية - الحدائق والصحة العامة</option>
                            <option value="41">اقسام تسويقية - المكافحة المتكاملة</option>
                            <option value="50">الآليات والري - الاليات</option>
                            <option value="51">الآليات والري - الري</option>
                            <option value="52">الآليات والري - الري المطري</option>
                            <option value="53">الآليات والري - الخدمات</option>
                        </select>
                    </div>
                    @error('marketing_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="vendor_container" class="w-full">
                    <label class="block font-bold mb-2">الموردين
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="vendor_type" name="vendor_type"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('vendor_type') border: solid 1px #fda4af; @enderror">
                            <option value="vendor_all" selected>الكل</option>
                            @foreach($vendor_list as $vendor)
                                <option value="{{ $vendor['VendorCode'] }}">{{ $vendor['VendorName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('vendor_type')
                    <span class="error text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div id="customer_container" class="w-full">
                    <label class="block font-bold mb-2">العملاء
                        <span class="text-red-500">*</span>
                    </label>
                    <div wire:ignore>
                        <select id="customer_type" name="customer_type"
                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                                style="@error('customer_type') border: solid 1px #fda4af; @enderror">
                            <option value="customer_all" selected>الكل</option>
                            @foreach($customer_list as $customer)
                                <option value="{{ $customer['CardCode'] }}">{{ $customer['CardName'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('customer_type')
                    <span class="error text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div id="product-code-row" style="padding: 20px" class="w-full flex flex-col gap-4 mt-3 hide">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                <div wire:ignore id="product_code_div" class="w-full">
                    <label class="block font-bold mb-2">رقم الصنف
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="product_code" name="product_code"
                            class="form-input w-full @error('product_code') border-red-300 @enderror"
                            style="@error('products_code') border: solid 1px #fda4af; @enderror">
                        @foreach($products_codes as $item)
                            <option value="{{ $item['ItemCode'] }}">{{ $item['ScribeCode'] . ' | ' . $item['ItemCode'] . ' | ' . $item['ItemName']}}</option>
                        @endforeach
                    </select>
                    {{--                    <input type="text" id="product_code"--}}
                    {{--                           class="form-input w-full @error('product_code') border-red-300 @enderror">--}}
                    @error('product_code')
                    <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
                </div>
            </div>
        </div>
        <div id="submit-row" class="w-full flex flex-col gap-4 mt-3 hide">
            <div class="w-full flex flex-col sm:flex-row gap-4">
                {{--                <div class="w-full">--}}
                {{--                    <label class="block font-bold mb-2">خيارات التجميع--}}
                {{--                        --}}{{--                        <span class="text-red-500">*</span>--}}
                {{--                    </label>--}}
                {{--                    --}}{{--                    <div wire:ignore>--}}
                {{--                    --}}{{--                        <select id="report_type" name="report_type"--}}
                {{--                    --}}{{--                                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
                {{--                    --}}{{--                                style="@error('cat_type') border: solid 1px #fda4af; @enderror">--}}
                {{--                    --}}{{--                            <option value="byItem" selected>11- ملخص عمليات اصناف</option>--}}
                {{--                    --}}{{--                            <option value="byDepartment">12- مبيعات الفروع للصنف</option>--}}
                {{--                    --}}{{--                        </select>--}}
                {{--                    --}}{{--                    </div>--}}
                {{--                    <div wire:ignore class="flex items-center mb-4">--}}
                {{--                        <input id="report_type" name="report_type" type="checkbox" value="byDepartment" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                {{--                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">عمليات الاصناف بالتفصيل للفروع</label>--}}
                {{--                    </div>--}}
                {{--                    @error('report_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror--}}
                {{--                </div>--}}
                <div id="grouping" wire:ignore class="w-full">
                    <label class="block font-bold mb-2">خيارات التجميع (Grouping)
                        {{--                        <span class="text-red-500">*</span>--}}
                    </label>
                    <select id="report_type" name="report_type"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('sp_type') border: solid 1px #fda4af; @enderror">
{{--                        <option value="byItem" selected>بدون تجميع</option>--}}
                        <option value="byDepartment" selected>بالصنف</option>
                        <option value="byItemGroup">بنوع المواد</option>
                        <option value="bySpeciality">بالمميز</option>
                        <option value="byMarketingType">بالاقسام</option>
                        <option value="byVendor">بالمورد</option>
{{--                        <option value="byCustomer">بالعميل</option>--}}
                    </select>
                </div>
                <div class="mt-8 text-center w-full">
                    <button id="gen-report" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
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

    @if($show_msg)
        <div id="tbl2-container" class="tbl-fixed overflow-x-auto mt-9">
            @if(count($group_results) > 0)

                <div class="mb-5 p-2">
                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                        <div class="flex flex-col sm:flex-row gap-4 w-full">
                            <div style="background-color: #f5f5f5; padding-right: 20px; padding-top: 20px" class="w-full">
                                <label class="block font-bold mb-5">خيارات اظهار الأعمدة الخاصة</label>
                                <div class="flex flex-row gap-2.5">
                                    <div class="flex items-center mb-4 ml-8">
                                        <input id="cost" type="checkbox" value="cost" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">اظهار</label>
                                    </div>
                                    {{--                                    <div class="flex items-center mb-4 ml-8">--}}
                                    {{--                                        <input id="cost" type="checkbox" value="cost" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                                    {{--                                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">التكلفة</label>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="flex items-center mb-4 ml-8">--}}
                                    {{--                                        <input id="margin" type="checkbox" value="margin" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                                    {{--                                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الهامش</label>--}}
                                    {{--                                    </div>--}}
                                    {{--                                    <div class="flex items-center mb-4 ml-8">--}}
                                    {{--                                        <input id="margin-percentage" type="checkbox" value="margin-percentage" onchange="hideColumn(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                                    {{--                                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">النسبة</label>--}}
                                    {{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                </div>
            @endif

            <table id="tbl2" style="border: 2px solid black;" class="table-container table-auto w-full border text-center">
                <thead style="border: 2px solid black;" class="text-xs uppercase text-gray-400 bg-gray-50 rounded-sm">
                <tr style="border: 2px solid black;">
                    @if($report_type == 'byItem')
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">كود الصنف</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">الوصف</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">وحدة</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">مميز</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">مورد</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">كمية</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">صافي المبيعات</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="w-full border p-2 whitespace-nowrap">
                            <div class="text-sm">متوسط السعر</div>
                        </th>
                    @else
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">الفرع</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">كمية</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">صافي المبيعات</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            <div class="text-sm">متوسط السعر</div>
                        </th>
                    @endif
                    @if($report_type == 'byDepartment' || $report_type == 'byItemGroup' || $report_type == 'bySpeciality' || $report_type == 'byMarketingType' || $report_type == 'byVendor')
                        {{--                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            <div class="text-sm">قسم</div>--}}
                        {{--                        </th>--}}
                        {{--                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            <div class="text-sm">نوع المواد</div>--}}
                        {{--                        </th>--}}
                        {{--                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            <div class="text-sm">مميز</div>--}}
                        {{--                        </th>--}}
                        {{--                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                        {{--                            <div class="text-sm">كود الصنف</div>--}}
                        {{--                        </th>--}}
                    @endif
                    {{--                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                        <div class="text-sm">اسم الصنف</div>--}}
                    {{--                    </th>--}}
                    {{--                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                        <div class="text-sm">الوحدة</div>--}}
                    {{--                    </th>--}}
                    {{--                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                        @if($report_type == 'byItem')--}}
                    {{--                        <div class="text-sm">الوصف</div>--}}
                    {{--                        @else--}}
                    {{--                            <div class="text-sm">الفرع</div>--}}
                    {{--                        @endif--}}
                    {{--                    </th>--}}
                    {{--                    @if($report_type == 'byItem')--}}
                    {{--                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                            <div class="text-sm">الوحدة</div>--}}
                    {{--                        </th>--}}
                    {{--                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                            <div class="text-sm">مميز</div>--}}
                    {{--                        </th>--}}
                    {{--                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                            <div class="text-sm">المورد</div>--}}
                    {{--                        </th>--}}
                    {{--                    @endif--}}
                    {{--                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                        <div class="text-sm">كمية</div>--}}
                    {{--                    </th>--}}
                    {{--                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                        <div class="text-sm">صافي المبيعات</div>--}}
                    {{--                    </th>--}}
                    {{--                    <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                    {{--                        <div class="text-sm">متوسط السعر</div>--}}
                    {{--                    </th>--}}
                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap cost">
                            <div class="text-sm">التكلفة</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap margin cost">
                            <div class="text-sm">الهامش</div>
                        </th>
                        <th style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap margin-percentage cost">
                            <div class="text-sm">نسبة</div>
                        </th>
                    @endif
                </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                @php
                    $counter = 0;
                    $item_code = "*";
                    $item_group_code = "*";
                    $item_group_itemCode_code = "*";
                    $speciality_code = "*";
                    $marketing_type_code = "*";
                    $vendor_code = "*";
                @endphp
                {{--                @foreach($scribes_results as $record)--}}
                {{--                    <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">--}}
                {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{$record->OldCode}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{$record->ItemName}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" class="border p-2">--}}
                {{--                            {{$record->SalUnitMsr}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{$record->Speciality}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{$record->VendorName}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->TotalQuantitySold)}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->TotalSalesAmount, 2)}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->AverageUnitPrice, 2)}}--}}
                {{--                        </td>--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->Cost, 2)}}--}}
                {{--                        </td>--}}
                {{--                        @php //$margin = floatval($record->Svalue) - floatval($record->SalesTotalCost)  @endphp--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->GrossProfit, 2)}}--}}
                {{--                        </td>--}}
                {{--                        @php //$marginPercentage = floatval($record->SalesTotalCost) != 0 ? (($margin / floatval($record->SalesTotalCost))*100) : '0'; @endphp--}}
                {{--                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                {{--                            {{number_format($record->GrossProfitPer, 2)}}--}}
                {{--                        </td>--}}
                {{--                    </tr>--}}
                {{--                    @php $counter++ @endphp--}}
                {{--                @endforeach--}}
                @if($report_type == 'byItem')
                    @php $item_total = 0; $cost_total = 0; $gross_total = 0; @endphp
                    @foreach($group_results as $record)
                        <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{$record["OldCode"]}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{ $record['ItemName'] }}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2">
                                {{$record["SalUnitMsr"]}}
                            </td>
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                {{$record['Speciality']}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{$record["VendorName"]}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalQuantitySold'])}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['TotalSalesAmount'], 2)}}
                                @php $item_total = $item_total + floatval($record['TotalSalesAmount']); @endphp
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($record['AverageUnitPrice'], 2)}}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                    {{number_format($record["Cost"], 2)}}
                                    @php $cost_total = $cost_total + floatval($record['Cost']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                    {{number_format($record['GrossProfit'], 2)}}
                                    @php $gross_total = $gross_total + floatval($record['GrossProfit']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                    {{--                                    {{number_format($record['GrossProfitPer'], 2)}}--}}
                                    {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                </td>
                            @endif
                        </tr>
                        @php $counter++ @endphp
                    @endforeach

                @elseif($report_type == 'byDepartmentX')
                    @php $currentGroup = null; @endphp
                    @php $dept_item_total = 0; $dept_cost_total = 0; $dept_gross_total = 0; @endphp
                    @php $dept_item_subtotal = 0; $dept_cost_subtotal = 0; $dept_gross_subtotal = 0; @endphp
                    @foreach($group_results as $outer_record)
                        @foreach($outer_record as $record)
                            @if($record["OldCode"] != $item_code)
                                    <?php $item_code = $record["OldCode"]; ?>
                                <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        {{$record["OldCode"]}}
                                    </td>
                                    <td colspan="10" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row justify-between">
                                            <div>الصنف: {{$record["ItemName"]}}</div>
                                            <div>الوحدة: {{$record["SalUnitMsr"]}}</div>
                                            <div>التميز: {{$record['Speciality']}}</div>
                                            <div>المورد: {{$record["VendorName"]}}</div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif">
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                        ادارة فنية - الاسمدة م1
                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                        ادارة فنية - المبيدات م1
                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                        ادارة فنية - البذور م1
                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                        اقسام تسويقية - الحدائق والصحة العامة
                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                        اقسام تسويقية - المكافحة المتكاملة
                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                        الاليات والري - الاليات
                                    @elseif($record["mrkt_type"] == "aleyat - ray")
                                        الاليات والري - الري
                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                        الاليات والري - الري المطري
                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                        الاليات والري - الخدمات
                                    @else
                                        عام
                                    @endif
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record["ItemGroup"]}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record["Speciality"]}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record["ItemName"]}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    {{$record["Department"]}}
                                </td>
                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @if($record["Department"] == "0101")
                                        فرع الاحساء
                                    @elseif($record["Department"] == "0102")
                                        فرع جدة
                                    @elseif($record["Department"] == "0103")
                                        فرع الرياض
                                    @elseif($record["Department"] == "0104")
                                        فرع وادي الدواسر
                                    @elseif($record["Department"] == "0105")
                                        فرع الجوف
                                    @elseif($record["Department"] == "0106")
                                        فرع الدمام
                                    @elseif($record["Department"] == "0107")
                                        فرع الخرج
                                    @elseif($record["Department"] == "0108")
                                        فرع نجران
                                    @elseif($record["Department"] == "0109")
                                        فرع حائل
                                    @elseif($record["Department"] == "0110")
                                        فرع تبوك
                                    @elseif($record["Department"] == "0111")
                                        فرع القصيم
                                    @elseif($record["Department"] == "0112")
                                        فرع ساجر
                                    @elseif($record["Department"] == "0201")
                                        مزرعة الدالوة
                                    @elseif($record["Department"] == "0202")
                                        مزرعة الفضول
                                    @elseif($record["Department"] == "0203")
                                        مزرعة الدلم
                                    @elseif($record["Department"] == "0001")
                                        المركز الرئيسي
                                    @endif
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                    @php $dept_item_total = $dept_item_total + floatval($record['TotalSalesAmount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                        {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                        @php $dept_cost_total = $dept_cost_total + floatval($record['Cost']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                        @php $dept_gross_total = $dept_gross_total + floatval($record['GrossProfit']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
                    @endforeach
                @elseif($report_type == 'byDepartment')

                    @php $currentGroup = null; $currentItemName = null; @endphp
                    @php $itemGroup_item_total = 0; $itemGroup_cost_total = 0; $itemGroup_gross_total = 0; $itemGroup_quantity_total = 0; @endphp
                    @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; @endphp
                    @php $itemGroup_itemName_subtotal = 0; $itemGroup_costName_subtotal = 0; $itemGroup_grossName_subtotal = 0; @endphp

{{--                    @foreach($group_results as $outer_record)--}}
                        @foreach($group_results as $record)
                            @if($currentGroup != $record["OldCode"])

                                {{-- Output subtotals for the previous group --}}
                                @if($currentGroup !== null)
                                    <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                            مجموع جزئي
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{number_format($itemGroup_item_subtotal, 2)}}
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                        </td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                        @endif
                                    </tr>
                                @endif

                                @php $currentGroup = $record["OldCode"]; @endphp
                                @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0;  @endphp
                            @endif

                            @if($record["OldCode"] != $item_group_code)
                                    <?php $item_group_code = $record["OldCode"]; ?>

                                <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                    {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        {{$record["OldCode"]}}--}}
                                    {{--                                    </td>--}}
                                    <td colspan="7" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div>({{ $record["OldCode"] }}) - {{$record["ItemName"]}}</div>
                                            {{--                                            <div>الصنف: {{$record["ItemName"]}}</div>--}}
                                            {{--                                            <div>الوحدة: {{$record["SalUnitMsr"]}}</div>--}}
                                            {{--                                            <div>التميز: {{$record['Speciality']}}</div>--}}
                                            {{--                                            <div>المورد: {{$record["VendorName"]}}</div>--}}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if($record["OldCode"] != $item_group_itemCode_code)
                                    <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                    <td colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row justify-between">
                                            <div>قسم:
                                                <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                                        ادارة فنية - الاسمدة م1
                                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                        ادارة فنية - المبيدات م1
                                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                        ادارة فنية - البذور م1
                                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                        اقسام تسويقية - الحدائق والصحة العامة
                                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                        اقسام تسويقية - المكافحة المتكاملة
                                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                        الاليات والري - الاليات
                                                    @elseif($record["mrkt_type"] == "aleyat - ray")
                                                        الاليات والري - الري
                                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                        الاليات والري - الري المطري
                                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                        الاليات والري - الخدمات
                                                    @else
                                                        عام
                                                    @endif
                                                    </span>
                                            </div>
                                            <div>نوع المادة:
                                                <span style="color: #227dd7">{{$record["ItemGroup"]}}</span>
                                            </div>
                                            <div>الوحدة:
                                                <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                            </div>
                                            <div>التميز:
                                                <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                                            </div>
                                            <div>المورد:
                                                <span style="color: #227dd7">
                                                    {{$record["VendorName"]}}
                                                    </span>
                                            </div>
                                        </div>

                                    {{--                                            مجموع جزئي للصنف--}}
                                    {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        </td>--}}
                                    {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                                    {{--                                        @endif--}}
                                </tr>
                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} hide">

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @if($record["Department"] == "0101")
                                        فرع الاحساء
                                    @elseif($record["Department"] == "0102")
                                        فرع جدة
                                    @elseif($record["Department"] == "0103")
                                        فرع الرياض
                                    @elseif($record["Department"] == "0104")
                                        فرع وادي الدواسر
                                    @elseif($record["Department"] == "0105")
                                        فرع الجوف
                                    @elseif($record["Department"] == "0106")
                                        فرع الدمام
                                    @elseif($record["Department"] == "0107")
                                        فرع الخرج
                                    @elseif($record["Department"] == "0108")
                                        فرع نجران
                                    @elseif($record["Department"] == "0109")
                                        فرع حائل
                                    @elseif($record["Department"] == "0110")
                                        فرع تبوك
                                    @elseif($record["Department"] == "0111")
                                        فرع القصيم
                                    @elseif($record["Department"] == "0112")
                                        فرع ساجر
                                    @elseif($record["Department"] == "0201")
                                        مزرعة الدالوة
                                    @elseif($record["Department"] == "0202")
                                        مزرعة الفضول
                                    @elseif($record["Department"] == "0203")
                                        مزرعة الدلم
                                    @elseif($record["Department"] == "0001")
                                        المركز الرئيسي
                                    @endif
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                    @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                    @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                    @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                        {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                        @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                        @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                        @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                        @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
{{--                    @endforeach--}}
                    @if($currentGroup !== null)
                        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                مجموع جزئي
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($itemGroup_item_subtotal, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                            @endif
                        </tr>
                    @endif

                @elseif($report_type == 'byItemGroup')

                    @php $currentGroup = null; $currentItemName = null; @endphp
                    @php $itemGroup_item_total = 0; $itemGroup_cost_total = 0; $itemGroup_gross_total = 0; $itemGroup_quantity_total = 0; @endphp
                    @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; @endphp
                    @php $itemGroup_itemName_subtotal = 0; $itemGroup_costName_subtotal = 0; $itemGroup_grossName_subtotal = 0; @endphp

{{--                    @foreach($group_results as $outer_record)--}}
                        @foreach($group_results as $record)
                            @if($currentGroup != $record["ItemGroup"])

                                {{-- Output subtotals for the previous group --}}
                                @if($currentGroup !== null)
                                    <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                            مجموع جزئي
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{number_format($itemGroup_item_subtotal, 2)}}
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                        </td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                        @endif
                                    </tr>
                                @endif

                                @php $currentGroup = $record["ItemGroup"]; @endphp
                                @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0;  @endphp
                            @endif

                            @if($record["ItemGroup"] != $item_group_code)
                                    <?php $item_group_code = $record["ItemGroup"]; ?>

                                <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                    {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        {{$record["OldCode"]}}--}}
                                    {{--                                    </td>--}}
                                    <td colspan="7" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div>{{$record["ItemGroup"]}}</div>
                                            {{--                                            <div>الصنف: {{$record["ItemName"]}}</div>--}}
                                            {{--                                            <div>الوحدة: {{$record["SalUnitMsr"]}}</div>--}}
                                            {{--                                            <div>التميز: {{$record['Speciality']}}</div>--}}
                                            {{--                                            <div>المورد: {{$record["VendorName"]}}</div>--}}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if($record["OldCode"] != $item_group_itemCode_code)
                                    <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                    <td colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row justify-between">
                                            <div>قسم:
                                                <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                                        ادارة فنية - الاسمدة م1
                                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                        ادارة فنية - المبيدات م1
                                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                        ادارة فنية - البذور م1
                                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                        اقسام تسويقية - الحدائق والصحة العامة
                                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                        اقسام تسويقية - المكافحة المتكاملة
                                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                        الاليات والري - الاليات
                                                    @elseif($record["mrkt_type"] == "aleyat - ray")
                                                        الاليات والري - الري
                                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                        الاليات والري - الري المطري
                                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                        الاليات والري - الخدمات
                                                    @else
                                                        عام
                                                    @endif
                                                    </span>
                                            </div>
                                            <div>كودالصنف:
                                                <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                                            </div>
                                            <div>الصنف:
                                                <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                                            </div>
                                            <div>الوحدة:
                                                <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                            </div>
                                            <div>التميز:
                                                <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                                            </div>
                                            <div>المورد:
                                                <span style="color: #227dd7">
                                                    {{$record["VendorName"]}}
                                                    </span>
                                            </div>
                                        </div>

                                    {{--                                            مجموع جزئي للصنف--}}
                                    {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        </td>--}}
                                    {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                                    {{--                                        @endif--}}
                                </tr>
                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} hide">
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    @if($record["mrkt_type"] == "fan - asmedah 1")--}}
                                {{--                                        ادارة فنية - الاسمدة م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")--}}
                                {{--                                        ادارة فنية - المبيدات م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")--}}
                                {{--                                        ادارة فنية - البذور م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")--}}
                                {{--                                        اقسام تسويقية - الحدائق والصحة العامة--}}
                                {{--                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")--}}
                                {{--                                        اقسام تسويقية - المكافحة المتكاملة--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")--}}
                                {{--                                        الاليات والري - الاليات--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - ray")--}}
                                {{--                                        الاليات والري - الري--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")--}}
                                {{--                                        الاليات والري - الري المطري--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")--}}
                                {{--                                        الاليات والري - الخدمات--}}
                                {{--                                    @else--}}
                                {{--                                        عام--}}
                                {{--                                    @endif--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["VendorName"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["Speciality"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["OldCode"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["ItemName"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["SalUnitMsr"]}}--}}
                                {{--                                </td>--}}

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @if($record["Department"] == "0101")
                                        فرع الاحساء
                                    @elseif($record["Department"] == "0102")
                                        فرع جدة
                                    @elseif($record["Department"] == "0103")
                                        فرع الرياض
                                    @elseif($record["Department"] == "0104")
                                        فرع وادي الدواسر
                                    @elseif($record["Department"] == "0105")
                                        فرع الجوف
                                    @elseif($record["Department"] == "0106")
                                        فرع الدمام
                                    @elseif($record["Department"] == "0107")
                                        فرع الخرج
                                    @elseif($record["Department"] == "0108")
                                        فرع نجران
                                    @elseif($record["Department"] == "0109")
                                        فرع حائل
                                    @elseif($record["Department"] == "0110")
                                        فرع تبوك
                                    @elseif($record["Department"] == "0111")
                                        فرع القصيم
                                    @elseif($record["Department"] == "0112")
                                        فرع ساجر
                                    @elseif($record["Department"] == "0201")
                                        مزرعة الدالوة
                                    @elseif($record["Department"] == "0202")
                                        مزرعة الفضول
                                    @elseif($record["Department"] == "0203")
                                        مزرعة الدلم
                                    @elseif($record["Department"] == "0001")
                                        المركز الرئيسي
                                    @endif
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                    @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                    @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                    @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                        {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                        @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                        @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                        @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                        @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
{{--                    @endforeach--}}
                    @if($currentGroup !== null)
                        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                مجموع جزئي
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($itemGroup_item_subtotal, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                            @endif
                        </tr>
                    @endif

                @elseif($report_type == 'bySpeciality')

                    @php $currentGroup = null; $currentItemName = null; @endphp
                    @php $itemGroup_item_total = 0; $itemGroup_cost_total = 0; $itemGroup_gross_total = 0; $itemGroup_quantity_total = 0; @endphp
                    @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; @endphp
                    @php $itemGroup_itemName_subtotal = 0; $itemGroup_costName_subtotal = 0; $itemGroup_grossName_subtotal = 0; @endphp

{{--                    @foreach($group_results as $outer_record)--}}
{{--                        @foreach($outer_record as $record)--}}
                        @foreach($group_results as $record)
                            @if($currentGroup != $record["Speciality"])

                                {{-- Output subtotals for the previous group --}}
                                @if($currentGroup !== null)
                                    <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                            مجموع جزئي
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{number_format($itemGroup_item_subtotal, 2)}}
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                        </td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                        @endif
                                    </tr>
                                @endif

                                @php $currentGroup = $record["Speciality"]; @endphp
                                @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0;  @endphp
                            @endif

                            @if($record["Speciality"] != $item_group_code)
                                    <?php $item_group_code = $record["Speciality"]; ?>

                                <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                    {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        {{$record["OldCode"]}}--}}
                                    {{--                                    </td>--}}
                                    <td colspan="7" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div>مميز {{$record["Speciality"]}}</div>
                                            {{--                                            <div>الصنف: {{$record["ItemName"]}}</div>--}}
                                            {{--                                            <div>الوحدة: {{$record["SalUnitMsr"]}}</div>--}}
                                            {{--                                            <div>التميز: {{$record['Speciality']}}</div>--}}
                                            {{--                                            <div>المورد: {{$record["VendorName"]}}</div>--}}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if($record["OldCode"] != $item_group_itemCode_code)
                                    <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                    <td colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row justify-between">
                                            <div>قسم:
                                                <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                                        ادارة فنية - الاسمدة م1
                                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                        ادارة فنية - المبيدات م1
                                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                        ادارة فنية - البذور م1
                                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                        اقسام تسويقية - الحدائق والصحة العامة
                                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                        اقسام تسويقية - المكافحة المتكاملة
                                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                        الاليات والري - الاليات
                                                    @elseif($record["mrkt_type"] == "aleyat - ray")
                                                        الاليات والري - الري
                                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                        الاليات والري - الري المطري
                                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                        الاليات والري - الخدمات
                                                    @else
                                                        عام
                                                    @endif
                                                    </span>
                                            </div>
                                            <div>كودالصنف:
                                                <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                                            </div>
                                            <div>الصنف:
                                                <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                                            </div>
                                            <div>الوحدة:
                                                <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                            </div>
                                            <div>نوع المادة:
                                                <span style="color: #227dd7">
                                                    {{$record['ItemGroup']}}
                                                    </span>
                                            </div>
                                            <div>المورد:
                                                <span style="color: #227dd7">
                                                    {{$record["VendorName"]}}
                                                    </span>
                                            </div>
                                        </div>

                                    {{--                                            مجموع جزئي للصنف--}}
                                    {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        </td>--}}
                                    {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                                    {{--                                        @endif--}}
                                </tr>
                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} hide">
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    @if($record["mrkt_type"] == "fan - asmedah 1")--}}
                                {{--                                        ادارة فنية - الاسمدة م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")--}}
                                {{--                                        ادارة فنية - المبيدات م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")--}}
                                {{--                                        ادارة فنية - البذور م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")--}}
                                {{--                                        اقسام تسويقية - الحدائق والصحة العامة--}}
                                {{--                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")--}}
                                {{--                                        اقسام تسويقية - المكافحة المتكاملة--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")--}}
                                {{--                                        الاليات والري - الاليات--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - ray")--}}
                                {{--                                        الاليات والري - الري--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")--}}
                                {{--                                        الاليات والري - الري المطري--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")--}}
                                {{--                                        الاليات والري - الخدمات--}}
                                {{--                                    @else--}}
                                {{--                                        عام--}}
                                {{--                                    @endif--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["VendorName"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["Speciality"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["OldCode"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["ItemName"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["SalUnitMsr"]}}--}}
                                {{--                                </td>--}}

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @if($record["Department"] == "0101")
                                        فرع الاحساء
                                    @elseif($record["Department"] == "0102")
                                        فرع جدة
                                    @elseif($record["Department"] == "0103")
                                        فرع الرياض
                                    @elseif($record["Department"] == "0104")
                                        فرع وادي الدواسر
                                    @elseif($record["Department"] == "0105")
                                        فرع الجوف
                                    @elseif($record["Department"] == "0106")
                                        فرع الدمام
                                    @elseif($record["Department"] == "0107")
                                        فرع الخرج
                                    @elseif($record["Department"] == "0108")
                                        فرع نجران
                                    @elseif($record["Department"] == "0109")
                                        فرع حائل
                                    @elseif($record["Department"] == "0110")
                                        فرع تبوك
                                    @elseif($record["Department"] == "0111")
                                        فرع القصيم
                                    @elseif($record["Department"] == "0112")
                                        فرع ساجر
                                    @elseif($record["Department"] == "0201")
                                        مزرعة الدالوة
                                    @elseif($record["Department"] == "0202")
                                        مزرعة الفضول
                                    @elseif($record["Department"] == "0203")
                                        مزرعة الدلم
                                    @elseif($record["Department"] == "0001")
                                        المركز الرئيسي
                                    @endif
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                    @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                    @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                    @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                        {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                        @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                        @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                        @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                        @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
{{--                    @endforeach--}}
                    @if($currentGroup !== null)
                        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                مجموع جزئي
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($itemGroup_item_subtotal, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                            @endif
                        </tr>
                    @endif

                @elseif($report_type == 'byMarketingType')

                    @php $currentGroup = null; $currentItemName = null; @endphp
                    @php $itemGroup_item_total = 0; $itemGroup_cost_total = 0; $itemGroup_gross_total = 0; $itemGroup_quantity_total = 0; @endphp
                    @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; @endphp
                    @php $itemGroup_itemName_subtotal = 0; $itemGroup_costName_subtotal = 0; $itemGroup_grossName_subtotal = 0; @endphp

{{--                    @foreach($group_results as $record)--}}
{{--                    @foreach($group_results as $outer_record)--}}

{{--                        @foreach($outer_record as $record)--}}
                        @foreach($group_results as $record)
                            @if($currentGroup != $record["mrkt_type"])

                                {{-- Output subtotals for the previous group --}}
                                @if($currentGroup !== null)
                                    <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                            مجموع جزئي
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{number_format($itemGroup_item_subtotal, 2)}}
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                        </td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                        @endif
                                    </tr>
                                @endif

                                @php $currentGroup = $record["mrkt_type"]; @endphp
                                @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0;  @endphp
                            @endif

                            @if($record["mrkt_type"] != $item_group_code)
                                    <?php $item_group_code = $record["mrkt_type"]; ?>

                                <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                    {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        {{$record["OldCode"]}}--}}
                                    {{--                                    </td>--}}
                                    <td colspan="7" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div>
                                                @if($record["mrkt_type"] == "fan - asmedah 1")
                                                    ادارة فنية - الاسمدة م1
                                                @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                    ادارة فنية - المبيدات م1
                                                @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                    ادارة فنية - البذور م1
                                                @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                    اقسام تسويقية - الحدائق والصحة العامة
                                                @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                    اقسام تسويقية - المكافحة المتكاملة
                                                @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                    الاليات والري - الاليات
                                                @elseif($record["mrkt_type"] == "aleyat - ray")
                                                    الاليات والري - الري
                                                @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                    الاليات والري - الري المطري
                                                @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                    الاليات والري - الخدمات
                                                @else
                                                    عام
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if($record["OldCode"] != $item_group_itemCode_code)
                                    <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                    <td colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row justify-between">
                                            <div>نوع المادة:
                                                <span style="color: #227dd7">{{ $record["ItemGroup"] }}</span>
                                            </div>
                                            <div>كودالصنف:
                                                <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                                            </div>
                                            <div>الصنف:
                                                <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                                            </div>
                                            <div>الوحدة:
                                                <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                            </div>
                                            <div>التميز:
                                                <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                                            </div>
                                            <div>المورد:
                                                <span style="color: #227dd7">
                                                    {{$record["VendorName"]}}
                                                    </span>
                                            </div>
                                        </div>

                                    {{--                                            مجموع جزئي للصنف--}}
                                    {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        </td>--}}
                                    {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                                    {{--                                        @endif--}}
                                </tr>
                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} hide">
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    @if($record["mrkt_type"] == "fan - asmedah 1")--}}
                                {{--                                        ادارة فنية - الاسمدة م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")--}}
                                {{--                                        ادارة فنية - المبيدات م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")--}}
                                {{--                                        ادارة فنية - البذور م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")--}}
                                {{--                                        اقسام تسويقية - الحدائق والصحة العامة--}}
                                {{--                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")--}}
                                {{--                                        اقسام تسويقية - المكافحة المتكاملة--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")--}}
                                {{--                                        الاليات والري - الاليات--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - ray")--}}
                                {{--                                        الاليات والري - الري--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")--}}
                                {{--                                        الاليات والري - الري المطري--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")--}}
                                {{--                                        الاليات والري - الخدمات--}}
                                {{--                                    @else--}}
                                {{--                                        عام--}}
                                {{--                                    @endif--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["VendorName"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["Speciality"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["OldCode"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["ItemName"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["SalUnitMsr"]}}--}}
                                {{--                                </td>--}}

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @if($record["Department"] == "0101")
                                        فرع الاحساء
                                    @elseif($record["Department"] == "0102")
                                        فرع جدة
                                    @elseif($record["Department"] == "0103")
                                        فرع الرياض
                                    @elseif($record["Department"] == "0104")
                                        فرع وادي الدواسر
                                    @elseif($record["Department"] == "0105")
                                        فرع الجوف
                                    @elseif($record["Department"] == "0106")
                                        فرع الدمام
                                    @elseif($record["Department"] == "0107")
                                        فرع الخرج
                                    @elseif($record["Department"] == "0108")
                                        فرع نجران
                                    @elseif($record["Department"] == "0109")
                                        فرع حائل
                                    @elseif($record["Department"] == "0110")
                                        فرع تبوك
                                    @elseif($record["Department"] == "0111")
                                        فرع القصيم
                                    @elseif($record["Department"] == "0112")
                                        فرع ساجر
                                    @elseif($record["Department"] == "0201")
                                        مزرعة الدالوة
                                    @elseif($record["Department"] == "0202")
                                        مزرعة الفضول
                                    @elseif($record["Department"] == "0203")
                                        مزرعة الدلم
                                    @elseif($record["Department"] == "0001")
                                        المركز الرئيسي
                                    @endif
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                    @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                    @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                    @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                        {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                        @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                        @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                        @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                        @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
{{--                    @endforeach--}}
                    @if($currentGroup !== null)
                        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                مجموع جزئي
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($itemGroup_item_subtotal, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                            @endif
                        </tr>
                    @endif

                @elseif($report_type == 'byVendor')

                    @php $currentGroup = null; $currentItemName = null; @endphp
                    @php $itemGroup_item_total = 0; $itemGroup_cost_total = 0; $itemGroup_gross_total = 0; $itemGroup_quantity_total = 0; @endphp
                    @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; @endphp
                    @php $itemGroup_itemName_subtotal = 0; $itemGroup_costName_subtotal = 0; $itemGroup_grossName_subtotal = 0; @endphp

{{--                    @foreach($group_results as $outer_record)--}}
                        @foreach($group_results as $record)
                            @if($currentGroup != $record["VendorName"])

                                {{-- Output subtotals for the previous group --}}
                                @if($currentGroup !== null)
                                    <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                            مجموع جزئي
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{number_format($itemGroup_item_subtotal, 2)}}
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                        </td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                        @endif
                                    </tr>
                                @endif

                                @php $currentGroup = $record["VendorName"]; @endphp
                                @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0;  @endphp
                            @endif

                            @if($record["VendorName"] != $item_group_code)
                                    <?php $item_group_code = $record["VendorName"]; ?>

                                <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                    {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        {{$record["OldCode"]}}--}}
                                    {{--                                    </td>--}}
                                    <td colspan="7" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div>
                                                {{ $record["VendorName"] }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if($record["OldCode"] != $item_group_itemCode_code)
                                    <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                    <td colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row justify-between">
                                            <div>نوع المادة:
                                                <span style="color: #227dd7">{{ $record["ItemGroup"] }}</span>
                                            </div>
                                            <div>كودالصنف:
                                                <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                                            </div>
                                            <div>الصنف:
                                                <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                                            </div>
                                            <div>الوحدة:
                                                <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                            </div>
                                            <div>التميز:
                                                <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                                            </div>
                                            <div>قسم:
                                                <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                                        ادارة فنية - الاسمدة م1
                                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                        ادارة فنية - المبيدات م1
                                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                        ادارة فنية - البذور م1
                                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                        اقسام تسويقية - الحدائق والصحة العامة
                                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                        اقسام تسويقية - المكافحة المتكاملة
                                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                        الاليات والري - الاليات
                                                    @elseif($record["mrkt_type"] == "aleyat - ray")
                                                        الاليات والري - الري
                                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                        الاليات والري - الري المطري
                                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                        الاليات والري - الخدمات
                                                    @else
                                                        عام
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                    {{--                                            مجموع جزئي للصنف--}}
                                    {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        </td>--}}
                                    {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                                    {{--                                        @endif--}}
                                </tr>
                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} hide">
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    @if($record["mrkt_type"] == "fan - asmedah 1")--}}
                                {{--                                        ادارة فنية - الاسمدة م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")--}}
                                {{--                                        ادارة فنية - المبيدات م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")--}}
                                {{--                                        ادارة فنية - البذور م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")--}}
                                {{--                                        اقسام تسويقية - الحدائق والصحة العامة--}}
                                {{--                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")--}}
                                {{--                                        اقسام تسويقية - المكافحة المتكاملة--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")--}}
                                {{--                                        الاليات والري - الاليات--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - ray")--}}
                                {{--                                        الاليات والري - الري--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")--}}
                                {{--                                        الاليات والري - الري المطري--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")--}}
                                {{--                                        الاليات والري - الخدمات--}}
                                {{--                                    @else--}}
                                {{--                                        عام--}}
                                {{--                                    @endif--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["VendorName"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["Speciality"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["OldCode"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["ItemName"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["SalUnitMsr"]}}--}}
                                {{--                                </td>--}}

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @if($record["Department"] == "0101")
                                        فرع الاحساء
                                    @elseif($record["Department"] == "0102")
                                        فرع جدة
                                    @elseif($record["Department"] == "0103")
                                        فرع الرياض
                                    @elseif($record["Department"] == "0104")
                                        فرع وادي الدواسر
                                    @elseif($record["Department"] == "0105")
                                        فرع الجوف
                                    @elseif($record["Department"] == "0106")
                                        فرع الدمام
                                    @elseif($record["Department"] == "0107")
                                        فرع الخرج
                                    @elseif($record["Department"] == "0108")
                                        فرع نجران
                                    @elseif($record["Department"] == "0109")
                                        فرع حائل
                                    @elseif($record["Department"] == "0110")
                                        فرع تبوك
                                    @elseif($record["Department"] == "0111")
                                        فرع القصيم
                                    @elseif($record["Department"] == "0112")
                                        فرع ساجر
                                    @elseif($record["Department"] == "0201")
                                        مزرعة الدالوة
                                    @elseif($record["Department"] == "0202")
                                        مزرعة الفضول
                                    @elseif($record["Department"] == "0203")
                                        مزرعة الدلم
                                    @elseif($record["Department"] == "0001")
                                        المركز الرئيسي
                                    @endif
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                    @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                    @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                    @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                        {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                        @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                        @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                        @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                        @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
{{--                    @endforeach--}}
                    @if($currentGroup !== null)
                        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                مجموع جزئي
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($itemGroup_item_subtotal, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                            @endif
                        </tr>
                    @endif

                @elseif($report_type == 'byCustomer')

                    @php $currentGroup = null; $currentItemName = null; @endphp
                    @php $itemGroup_item_total = 0; $itemGroup_cost_total = 0; $itemGroup_gross_total = 0; $itemGroup_quantity_total = 0; @endphp
                    @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0; @endphp
                    @php $itemGroup_itemName_subtotal = 0; $itemGroup_costName_subtotal = 0; $itemGroup_grossName_subtotal = 0; @endphp

                    @foreach($group_results as $outer_record)
                        @foreach($outer_record as $record)
                            @dd($record)
                            @if($currentGroup != $record["BusinessPartnerCode"])

                                {{-- Output subtotals for the previous group --}}
                                @if($currentGroup !== null)
                                    <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                            مجموع جزئي
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{number_format($itemGroup_item_subtotal, 2)}}
                                        </td>
                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                            {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                                        </td>
                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                                        @endif
                                    </tr>
                                @endif

                                @php $currentGroup = $record["BusinessPartnerCode"]; @endphp
                                @php $itemGroup_item_subtotal = 0; $itemGroup_cost_subtotal = 0; $itemGroup_gross_subtotal = 0; $itemGroup_quantity_subtotal = 0;  @endphp
                            @endif

                            @if($record["BusinessPartnerCode"] != $item_group_code)
                                    <?php $item_group_code = $record["BusinessPartnerCode"]; ?>

                                <tr style="background-color: #faebd7; font-weight: bold; color: red;">
                                    {{--                                    <td style="border: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        {{$record["OldCode"]}}--}}
                                    {{--                                    </td>--}}
                                    <td colspan="7" style="border: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row">
                                            <div>
{{--                                                {{ $record["VendorName"] }}--}}
                                                {{ $record["BusinessPartnerCode"] }} - {{ $record["BusinessPartnerName"] }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @if($record["OldCode"] != $item_group_itemCode_code)
                                    <?php $item_group_itemCode_code = $record["OldCode"]; ?>

                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-top: 2px solid black; border-bottom: 2px dashed #a8a8a8; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    <td rowspan="2" style="border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>
                                    <td colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                        <div class="flex flex-row justify-between">
                                            <div>نوع المادة:
                                                <span style="color: #227dd7">{{ $record["ItemGroup"] }}</span>
                                            </div>
                                            <div>كودالصنف:
                                                <span style="color: #227dd7">{{$record["OldCode"]}}</span>
                                            </div>
                                            <div>الصنف:
                                                <span style="color: #227dd7">
                                                    {{$record["ItemName"]}}
                                                    </span>
                                            </div>
                                            <div>الوحدة:
                                                <span style="color: #227dd7">
                                                    {{$record["SalUnitMsr"]}}
                                                    </span>
                                            </div>
                                            <div>التميز:
                                                <span style="color: #227dd7">
                                                    {{$record['Speciality']}}
                                                    </span>
                                            </div>
                                            <div>قسم:
                                                <span style="color: #227dd7">
                                                    @if($record["mrkt_type"] == "fan - asmedah 1")
                                                        ادارة فنية - الاسمدة م1
                                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")
                                                        ادارة فنية - المبيدات م1
                                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")
                                                        ادارة فنية - البذور م1
                                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")
                                                        اقسام تسويقية - الحدائق والصحة العامة
                                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")
                                                        اقسام تسويقية - المكافحة المتكاملة
                                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")
                                                        الاليات والري - الاليات
                                                    @elseif($record["mrkt_type"] == "aleyat - ray")
                                                        الاليات والري - الري
                                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")
                                                        الاليات والري - الري المطري
                                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")
                                                        الاليات والري - الخدمات
                                                    @else
                                                        عام
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                    {{--                                            مجموع جزئي للصنف--}}
                                    {{--                                            <span style="color: #3f9dad"> {{ $record["ItemName"] }}</span>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                            {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">--}}
                                    {{--                                        </td>--}}
                                    {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>--}}
                                    {{--                                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>--}}
                                    {{--                                        @endif--}}
                                </tr>
                                <tr onclick="show_hide({{$record["OldCode"]}})" style="border-bottom: 2px solid black; background-color: #e4fbff; font-weight: bold; cursor: pointer">
                                    {{--                                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap parent-{{ $record["OldCode"] }}">+</td>--}}
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][3])}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{number_format($totalSalesByItem[$record["OldCode"]][0], 2)}}
                                    </td>
                                    <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                        {{ $totalSalesByItem[$record["OldCode"]][3] != 0? number_format($totalSalesByItem[$record["OldCode"]][0]/$totalSalesByItem[$record["OldCode"]][3], 2) : 0 }}
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][1], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px dashed #a8a8a8;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($totalSalesByItem[$record["OldCode"]][2], 2)}}</td>
                                        <td style="color: #227dd7; border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $totalSalesByItem[$record["OldCode"]][0] == 0 ? 0 : number_format(($totalSalesByItem[$record["OldCode"]][2]/$totalSalesByItem[$record["OldCode"]][0])*100, 2)}}</td>
                                    @endif
                                </tr>
                            @endif
                            <tr class="@if($counter%2==0) bg-white @else bg-gray-200 @endif row-{{$record["OldCode"]}} hide">
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    @if($record["mrkt_type"] == "fan - asmedah 1")--}}
                                {{--                                        ادارة فنية - الاسمدة م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "fan - mobedat 1")--}}
                                {{--                                        ادارة فنية - المبيدات م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "fan - bathoor 1")--}}
                                {{--                                        ادارة فنية - البذور م1--}}
                                {{--                                    @elseif($record["mrkt_type"] == "tasweeg - sehah")--}}
                                {{--                                        اقسام تسويقية - الحدائق والصحة العامة--}}
                                {{--                                    @elseif($record["mrkt_type"] == "tasweeg - mokafahh")--}}
                                {{--                                        اقسام تسويقية - المكافحة المتكاملة--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - aleyat")--}}
                                {{--                                        الاليات والري - الاليات--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - ray")--}}
                                {{--                                        الاليات والري - الري--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - ray matary")--}}
                                {{--                                        الاليات والري - الري المطري--}}
                                {{--                                    @elseif($record["mrkt_type"] == "aleyat - khadamat")--}}
                                {{--                                        الاليات والري - الخدمات--}}
                                {{--                                    @else--}}
                                {{--                                        عام--}}
                                {{--                                    @endif--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["VendorName"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["Speciality"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["OldCode"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["ItemName"]}}--}}
                                {{--                                </td>--}}
                                {{--                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">--}}
                                {{--                                    {{$record["SalUnitMsr"]}}--}}
                                {{--                                </td>--}}

                                <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                    @if($record["Department"] == "0101")
                                        فرع الاحساء
                                    @elseif($record["Department"] == "0102")
                                        فرع جدة
                                    @elseif($record["Department"] == "0103")
                                        فرع الرياض
                                    @elseif($record["Department"] == "0104")
                                        فرع وادي الدواسر
                                    @elseif($record["Department"] == "0105")
                                        فرع الجوف
                                    @elseif($record["Department"] == "0106")
                                        فرع الدمام
                                    @elseif($record["Department"] == "0107")
                                        فرع الخرج
                                    @elseif($record["Department"] == "0108")
                                        فرع نجران
                                    @elseif($record["Department"] == "0109")
                                        فرع حائل
                                    @elseif($record["Department"] == "0110")
                                        فرع تبوك
                                    @elseif($record["Department"] == "0111")
                                        فرع القصيم
                                    @elseif($record["Department"] == "0112")
                                        فرع ساجر
                                    @elseif($record["Department"] == "0201")
                                        مزرعة الدالوة
                                    @elseif($record["Department"] == "0202")
                                        مزرعة الفضول
                                    @elseif($record["Department"] == "0203")
                                        مزرعة الدلم
                                    @elseif($record["Department"] == "0001")
                                        المركز الرئيسي
                                    @endif
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalQuantitySold'])}}
                                    @php $itemGroup_quantity_total = $itemGroup_quantity_total + floatval($record['TotalQuantitySold']); @endphp
                                    @php $itemGroup_quantity_subtotal = $itemGroup_quantity_subtotal + floatval($record['TotalQuantitySold']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                    {{number_format($record['TotalSalesAmount'], 2)}}
                                    @php $itemGroup_item_total = $itemGroup_item_total + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_item_subtotal = $itemGroup_item_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                    @php $itemGroup_itemName_subtotal = $itemGroup_itemName_subtotal + floatval($record['TotalSalesAmount']); @endphp
                                </td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">


                                    @if(in_array($warehouse_id[$record["Department"]], json_decode(Auth::user()->branches)) )
                                        {{number_format($record['AverageUnitPrice'], 2)}}
                                    @endif
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">
                                        {{number_format($record["Cost"], 2)}}
                                        @php $itemGroup_cost_total = $itemGroup_cost_total + floatval($record['Cost']); @endphp
                                        @php $itemGroup_cost_subtotal = $itemGroup_cost_subtotal + floatval($record['Cost']); @endphp
                                        @php $itemGroup_costName_subtotal = $itemGroup_costName_subtotal + floatval($record['Cost']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin cost">
                                        {{number_format($record['GrossProfit'], 2)}}
                                        @php $itemGroup_gross_total = $itemGroup_gross_total + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_gross_subtotal = $itemGroup_gross_subtotal + floatval($record['GrossProfit']); @endphp
                                        @php $itemGroup_grossName_subtotal = $itemGroup_grossName_subtotal + floatval($record['GrossProfit']); @endphp
                                    </td>
                                    <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap margin-percentage cost">
                                        {{ floatval($record['TotalSalesAmount']) == 0 ? 0 : number_format((floatval($record['GrossProfit'])/floatval($record['TotalSalesAmount']))*100, 2)}}
                                    </td>
                                @endif
                            </tr>
                            @php $counter++ @endphp
                        @endforeach
                    @endforeach
                    @if($currentGroup !== null)
                        <tr style="border-top: 2px solid black; background-color: #f1d56f; font-weight: bold">
                            <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                                مجموع جزئي
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">{{number_format($itemGroup_quantity_subtotal)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{number_format($itemGroup_item_subtotal, 2)}}
                            </td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                                {{ $itemGroup_quantity_subtotal != 0 ? number_format($itemGroup_item_subtotal/$itemGroup_quantity_subtotal, 2) : 0 }}
                            </td>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_subtotal, 2)}}</td>
                                <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_subtotal == 0 ? 0 : number_format(($itemGroup_gross_subtotal/$itemGroup_item_subtotal)*100, 2)}}</td>
                            @endif
                        </tr>
                    @endif



                @endif
                </tbody>

                @if($report_type == 'byItem')
                    <tfoot>
                    <tr style="border-top: 2px solid black; background-color: #f8e1ab; font-weight: bold">
                        <td colspan="6" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            الإجمالي
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{number_format($item_total, 2)}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($cost_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($gross_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $item_total == 0 ? 0 : number_format(($gross_total/$item_total)*100, 2)}}</td>
                        @endif
                    </tr>
                    </tfoot>
                @elseif($report_type == 'byDepartmentX')
                    <tfoot>
                    <tr style="border-top: 2px solid black; background-color: #f8e1ab; font-weight: bold">
                        <td colspan="3" style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            الإجمالي
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{number_format($dept_item_total, 2)}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($dept_cost_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($dept_gross_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $dept_item_total == 0 ? 0 : number_format(($dept_gross_total/$dept_item_total)*100, 2)}}</td>
                        @endif
                    </tr>
                    </tfoot>
                @elseif($report_type == 'byDepartment' || $report_type == 'byItemGroup' || $report_type == 'bySpeciality' || $report_type == 'byMarketingType' || $report_type == 'byVendor' || $report_type == 'byCustomer')
                    <tfoot>
                    <tr style="border-top: 2px solid black; background-color: #abdcf8; font-weight: bold">
                        <td style="border-left: 2px solid black;" class="border p-2 whitespace-nowrap">
                            الإجمالي
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{number_format($itemGroup_quantity_total)}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{number_format($itemGroup_item_total, 2)}}
                        </td>
                        <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap">
                            {{ $itemGroup_quantity_total != 0 ? number_format($itemGroup_item_total/$itemGroup_quantity_total, 2) : 0 }}
                        </td>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_cost_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{number_format($itemGroup_gross_total, 2)}}</td>
                            <td style="border-left: 2px solid black;" style="direction: ltr" class="border p-2 whitespace-nowrap cost">{{ $itemGroup_item_total == 0 ? 0 : number_format(($itemGroup_gross_total/$itemGroup_item_total)*100, 2)}}</td>
                        @endif
                    </tr>
                    </tfoot>
                @endif
            </table>
            @else
                <div class="w-full p-6" style="background-color: #fff0f5; border: 1px solid #9f4764; color: #9f4764; text-align: center; font-weight: bold;">
                    <svg class="w-20" style="margin: auto; margin-bottom: 20px" viewBox="0 0 32 32" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:#f9dcc4;}.cls-2{fill:#fff2e9;}.cls-3{fill:#edbe9d;}.cls-4{fill:#577590;}</style></defs><path class="cls-1" d="M23.5,2h-12a.47.47,0,0,0-.35.15l-5,5A.47.47,0,0,0,6,7.5v20A2.5,2.5,0,0,0,8.5,30h15A2.5,2.5,0,0,0,26,27.5V4.5A2.5,2.5,0,0,0,23.5,2Z"/><path class="cls-2" d="M15,2h7a1,1,0,0,1,0,2H15a1,1,0,0,1,0-2Z"/><path class="cls-2" d="M6,13.5v-2a1,1,0,0,1,2,0v2a1,1,0,0,1-2,0Z"/><path class="cls-2" d="M6,24.5v-8a1,1,0,0,1,2,0v8a1,1,0,0,1-2,0Z"/><path class="cls-3" d="M24,20v4a4,4,0,0,1-4,4H11a1,1,0,0,0-1,1h0a1,1,0,0,0,1,1H23.5A2.5,2.5,0,0,0,26,27.5V20a1,1,0,0,0-1-1h0A1,1,0,0,0,24,20Z"/><path class="cls-3" d="M11.69,2a.47.47,0,0,0-.54.11l-5,5A.47.47,0,0,0,6,7.69.5.5,0,0,0,6.5,8h3A2.5,2.5,0,0,0,12,5.5v-3A.5.5,0,0,0,11.69,2Z"/><path class="cls-4" d="M21.5,11.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,16.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/><path class="cls-4" d="M21.5,21.4a1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.23,1.23,0,0,1-1.61,0,2.12,2.12,0,0,0-1.39-.5,2.15,2.15,0,0,0-1.4.5,1.17,1.17,0,0,1-.8.3,1.2,1.2,0,0,1-.81-.3,2.12,2.12,0,0,0-1.39-.5.5.5,0,0,0,0,1,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5,2.07,2.07,0,0,0,1.39-.5,1.23,1.23,0,0,1,1.61,0,2.2,2.2,0,0,0,2.79,0,1.18,1.18,0,0,1,.81-.3,1.15,1.15,0,0,1,.8.3,2.12,2.12,0,0,0,1.4.5.5.5,0,0,0,0-1Z"/></svg>
                    <span class="mt-4">لا يوجد نتائج للعرض</span>
                </div>
            @endif
        </div>
    @endif
</div>

@section('scripts')
    {{--    <script src="{{ asset('js/jquery.min.js') }}"></script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script>

        var groups_all = {
            "104": "اسمدة أحادية",
            "105": "اسمدة مركبة ورقية",
            "106": "اسمدة مركبة ذوابة",
            "107": "اسمدة مركبة حبيبية",
            "108": "اسمدة مركبة سائلة ومعلقة",
            "109": "عناصر نادرة",
            "110": "احماض دبالية",
            "111": "احماض امينية",
            "112": "اعشاب بحرية",
            "114": "مصحح ملوحة وحموضة",
            "115": "اسمدة متخصصة",
            "116": "ترب أساسية",
            "117": "بوتنج سويل",
            "118": "عطن",
            "120": "مبيدات حشرية",
            "121": "مبيدات فطرية",
            "123": "مبيدات اعشاب",
            "124": "حشرات نافعة",
            "125": "مستخلصات نباتية",
            "126": "فرمونات وجواذب",
            "127": "مصائد ولواصق",
            "128": "مواد لاصقة وناشرة",
            "129": "مبيدات قوارض",
            "130": "مبيدات صحة عامة",
            "139": "مرشات يدوية ملحقاتها",
            "140": "مقصات ومحشات",
            "141": "بلاستك تغطية وتعقيم",
            "142": "صواني ومراكن",
            "143": "خيوط واسلاك",
            "144": "شباك وشاش",
            "145": "معدات قياس",
            "147": "مواد تعبئة",
            "148": "الات يدوية",
            "150": "مرشات الية وملحقاتها",
            "151": "اليات وملحقاتها",
            "152": "هوجيندرون",
            "154": "ميجا جرين للصناعات المتطورة",
            "155": "ازود",
            "156": "إدارة مياة أخرى",
            "157": "داكوم",
            "158": "كاروسبراي",
            "159": "اوربيناتي",
            "161": "اخري (مكائن و قطع غيار)",
            "162": "نحل وادواته",
            "163": "صيانة",
            "164": "مبيعات / مشتريات مباشرة",
            "137": "بذور نجيل",
            "132": "بذور محاصيل حقلية",
            "131": "بذور خضار",
            "133": "بذور اعلاف",
            "134": "بذور أشجار مثمرة",
            "135": "بذور ورقيات",
            "165": "منتج خضار",
            "166": "منتج فواكة",
            "169": "أدوات تعبئة",
            "171": "أدوات ومواد بيوت محمية",
            "172": "بذور حبوب",
            "173": "ريفولس",
            "174": "جرينوكي",
            "175": "ركين",
            "177": "مواد تبخير وتعقيم",
            "178": "كائنات دقيقة",
            "179": "ابصال",
            "180": "الأصول الثابتة",
        };
        var commerce = {
            "104": "اسمدة أحادية",
            "105": "اسمدة مركبة ورقية",
            "106": "اسمدة مركبة ذوابة",
            "107": "اسمدة مركبة حبيبية",
            "108": "اسمدة مركبة سائلة ومعلقة",
            "109": "عناصر نادرة",
            "110": "احماض دبالية",
            "111": "احماض امينية",
            "112": "اعشاب بحرية",
            "114": "مصحح ملوحة وحموضة",
            "115": "اسمدة متخصصة",
            "116": "ترب أساسية",
            "117": "بوتنج سويل",
            "118": "عطن",
            "120": "مبيدات حشرية",
            "121": "مبيدات فطرية",
            "123": "مبيدات اعشاب",
            "124": "حشرات نافعة",
            "125": "مستخلصات نباتية",
            "126": "فرمونات وجواذب",
            "127": "مصائد ولواصق",
            "128": "مواد لاصقة وناشرة",
            "129": "مبيدات قوارض",
            "130": "مبيدات صحة عامة",
            "139": "مرشات يدوية ملحقاتها",
            "140": "مقصات ومحشات",
            "141": "بلاستك تغطية وتعقيم",
            "142": "صواني ومراكن",
            "143": "خيوط واسلاك",
            "144": "شباك وشاش",
            "145": "معدات قياس",
            "147": "مواد تعبئة",
            "148": "الات يدوية",
            "150": "مرشات الية وملحقاتها",
            "151": "اليات وملحقاتها",
            "152": "هوجيندرون",
            "154": "ميجا جرين للصناعات المتطورة",
            "155": "ازود",
            "156": "إدارة مياة أخرى",
            "157": "داكوم",
            "158": "كاروسبراي",
            "159": "اوربيناتي",
            "161": "اخري (مكائن و قطع غيار)",
            "162": "نحل وادواته",
            "163": "صيانة",
            "164": "مبيعات / مشتريات مباشرة",
            "137": "بذور نجيل",
            "132": "بذور محاصيل حقلية",
            "131": "بذور خضار",
            "133": "بذور اعلاف",
            "134": "بذور أشجار مثمرة",
            "135": "بذور ورقيات",
            "171": "أدوات ومواد بيوت محمية",
            "172": "بذور حبوب",
            "173": "ريفولس",
            "174": "جرينوكي",
            "175": "ركين",
            "177": "مواد تبخير وتعقيم",
            "178": "كائنات دقيقة",
            "179": "ابصال",
            "180": "الأصول الثابتة",
        };
        var farms = {
            "165": "منتج خضار",
            "166": "منتج فواكه",
        };
        var sundries = {
            "169": "أدوات تعبئة"
        };

        var selected_cat_type = null;
        Livewire.on('show-container', () => {
            $("#gen-report").html('<b>إنشاء تقرير</b>');

        });

        Livewire.on('finished', () => {
            console.log('selected' + selected_cat_type);
            $("#cat_type").select2('val', selected_cat_type);
            old_search_type = $("input[name='search_type']:checked").val();
            var data = $('#group_type').select2("val");
            console.log('old_search_type:'+ old_search_type);

            if(old_search_type == 'item_code_search') {
                // $('#filteration-row2').addClass('hide');
                $('#filteration-row3').addClass('hide');
                $('#product-code-row').removeClass('hide');
                $('#submit-row').removeClass('hide');
            }

            if(old_search_type == 'advanced_search') {
                // $('#filteration-row2').removeClass('hide');
                $('#filteration-row3').removeClass('hide');
                $('#product-code-row').addClass('hide');
                $('#submit-row').removeClass('hide');

                data = 'commerce';

                if(data == 'commerce') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#marketing_type_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#customer_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'farms') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'sundries') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'groups_all') {
                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#customer_container').removeClass('hide');
                    $('#marketing_type_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'select_group') {
                    $('#cat_container').addClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').addClass('hide');
                }
            }




            // $("#cat_type option[value='"+selected_cat_type+"']").prop('selected', true);
            $('.cost').addClass('hide');
            swal.close();


            ///////////////////////////
            var div = document.getElementById("branch-container");
            var btn = document.getElementsByClassName("collapsible");
            div.style.display = "none";
            div.classList.toggle("active");
            $("button.collapsible").removeClass("active");

            // btn.classList.toggle("active");
            var content = div.nextElementSibling;
            if (div.classList.contains("active")) {
                div.style.display = "none"; // Show content if active
            } else {
                div.style.display = "block"; // Hide content if not active
            }

            //////////////////////////////////////////////////
        });

        $(document).ready(function () {

            $('#dept_id').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#group_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#cat_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#sp_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#marketing_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#vendor_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#customer_type').select2({
                dir: "rtl",
                dropdownCssClass: "select-font-size"
            });

            $('#product_code').select2({
                dir: "rtl",
                minimumInputLength: 3,
                dropdownCssClass: "select-font-size"
            });

            // $('#cat_type').val($('#cat_type option:first').val());
            $('#cat_type').append('<option value="cat_all" selected>الكل</option>');
            var prev_depts = $('#dept_id').select2("val");
            var prev_groups = $('#group_type').select2("val");
            var prev_cats = $('#cat_type').select2("val");
            var prev_sps = $('#sp_type').select2("val");
            var prev_marketing = $('#marketing_type').select2("val");
            var prev_vendors = $('#vendor_type').select2("val");
            var prev_customers = $('#customer_type').select2("val");

            $('#dept_id').on('change', function (e) {
                var data = $('#dept_id').select2("val");

                if (prev_depts && prev_depts.includes('dept_all') == false && data.includes('dept_all') == true && prev_depts.length != data.length) {
                    $("#dept_id option").prop('selected', false);
                    $("#dept_id option[value='dept_all']").prop('selected', true);

                    prev_depts = $(this).val();
                    $('#dept_id').change();
                }
                else {

                    if (prev_depts && prev_depts.length != data.length) {
                        $("#dept_id option[value='dept_all']").removeAttr('selected');
                        prev_depts = $(this).val();

                        $("#dept_id").change();
                    }
                }
            });

            $("input[name='search_type']").change(function () {
                search_type = $(this).val();
                $('#product_code').val("");

                if (search_type == "item_code_search") {
                    // $('#filteration-row2').addClass('hide');
                    $('#filteration-row3').addClass('hide');
                    $('#grouping').addClass('hide');
                    $('#product-code-row').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                    $('#product_code').select2({
                        dir: "rtl",
                        minimumInputLength: 3,
                        dropdownCssClass: "select-font-size"
                    });
                }
                else if(search_type == "advanced_search") {
                    // $('#filteration-row2').removeClass('hide');
                    $('#filteration-row3').removeClass('hide');
                    $('#grouping').removeClass('hide');
                    $('#product-code-row').addClass('hide');
                    $('#submit-row').addClass('hide');


                    /* start of hiding and removing filteration 2-3*/
                    $('#cat_type').empty();
                    $('#cat_type').append('<option value="cat_all" selected>الكل</option>');
                    // for (var index = 0; index < categories.length; index++) {
                    //     $('#cat_type').append('<option value="' + categories[index].ItmsGrpCod + '">' + categories[index].ItmsGrpNam + '</option>');
                    // }
                    Object.keys(commerce).forEach(function(key) {
                        // console.log("Key: " + key + ", Value: " + groups_all[key]);
                        $('#cat_type').append('<option value="' + key + '">' + commerce[key] + '</option>');
                    });


                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#marketing_type_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#customer_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                    /* end of hiding and removing filteration 2-3*/

                    re_intialize();
                }

                // re-intialize the select2
                $('#group_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $("#group_type option[value='select_group']").prop('selected', true);
            });

            $('#group_type').on('change', function (e) {
                var data = $('#group_type').select2("val");

                // $('#marketing_type').select2("val");
                $('#marketing_type').val($('#marketing_type option:first').val()).trigger('change');

                if (prev_groups && prev_groups.includes('groups_all') == false && data.includes('groups_all') == true && prev_groups.length != data.length) {
                    $("#group_type option").prop('selected', false);
                    $("#group_type option[value='groups_all']").prop('selected', true);

                    prev_groups = $(this).val();
                    $('#group_type').change();
                }
                else {
                    if (prev_groups && prev_groups.length != data.length) {
                        $("#group_type option[value='groups_all']").removeAttr('selected');
                        prev_groups = $(this).val();
                        $("#group_type").change();
                    }
                }

                // Swal.fire({
                //     title: 'الرجاء الإنتظار',
                //     allowOutsideClick: false,
                //     showCancelButton: false,
                //     showConfirmButton: false,
                //     willOpen: () => {
                //         Swal.showLoading()
                //     },
                // });
                // @this.group_type = data;
                // @this.set('group_type', data);
                // console.log("group_type:" + @this.group_type);
                console.log("group_typexx:" + data);
                // Livewire.emit('item-category', data);
                // Livewire.emit('change-group-type', data);

                // $('#cat_type').empty();

                if(data == 'commerce') {

                    $('#cat_type').empty();
                    $('#cat_type').append('<option value="cat_all" selected>الكل</option>');
                    // for (var index = 0; index < categories.length; index++) {
                    //     $('#cat_type').append('<option value="' + categories[index].ItmsGrpCod + '">' + categories[index].ItmsGrpNam + '</option>');
                    // }
                    Object.keys(commerce).forEach(function(key) {
                        // console.log("Key: " + key + ", Value: " + groups_all[key]);
                        $('#cat_type').append('<option value="' + key + '">' + commerce[key] + '</option>');
                    });


                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#marketing_type_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#customer_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'farms') {

                    $('#cat_type').empty();
                    $('#cat_type').append('<option value="cat_all" selected>الكل</option>');

                    Object.keys(farms).forEach(function(key) {
                        $('#cat_type').append('<option value="' + key + '">' + farms[key] + '</option>');
                    });

                    $('#cat_container').removeClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'sundries') {

                    $('#cat_type').empty();
                    $('#cat_type').append('<option value="cat_all" selected>الكل</option>');

                    Object.keys(sundries).forEach(function(key) {
                        $('#cat_type').append('<option value="' + key + '">' + sundries[key] + '</option>');
                    });


                    $('#cat_container').removeClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'groups_all') {

                    $('#cat_type').empty();
                    $('#cat_type').append('<option value="cat_all" selected>الكل</option>');


                    Object.keys(groups_all).forEach(function(key) {
                        $('#cat_type').append('<option value="' + key + '">' + groups_all[key] + '</option>');
                    });


                    $('#cat_container').removeClass('hide');
                    $('#sp_container').removeClass('hide');
                    $('#vendor_container').removeClass('hide');
                    $('#customer_container').removeClass('hide');
                    $('#marketing_type_container').removeClass('hide');
                    $('#submit-row').removeClass('hide');
                }
                else if(data == 'select_group') {
                    $('#cat_type').empty();
                    $('#cat_container').addClass('hide');
                    $('#sp_container').addClass('hide');
                    $('#marketing_type_container').addClass('hide');
                    $('#vendor_container').addClass('hide');
                    $('#customer_container').addClass('hide');
                    $('#submit-row').addClass('hide');
                }

                // re-intialize the select2
                re_intialize();
                /*
                $('#group_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#cat_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#sp_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#marketing_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });

                $('#vendor_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                */

            });

            // Livewire.on('finished-categories2', () => {
            //     // $('#cat_type').empty();
            //     // $('#cat_type').append('<option value="cat_all" selected>الكل</option>');
            //     // for (var index = 0; index < categories.length; index++) {
            //     //     $('#cat_type').append('<option value="' + categories[index].ItmsGrpCod + '">' + categories[index].ItmsGrpNam + '</option>');
            //     // }
            //     prev_cats = 'cat_all';
            //     $("#filteration-row2").removeClass('hide');
            //
            //     var data = $('#group_type').select2("val");
            //
            //     if(data == 'commerce') {
            //         $('#cat_container').removeClass('hide');
            //         $('#sp_container').removeClass('hide');
            //         $('#marketing_type_container').removeClass('hide');
            //         $('#vendor_container').removeClass('hide');
            //         $('#submit-row').removeClass('hide');
            //     }
            //     else if(data == 'farms') {
            //         $('#cat_container').removeClass('hide');
            //         $('#sp_container').addClass('hide');
            //         $('#marketing_type_container').addClass('hide');
            //         $('#vendor_container').addClass('hide');
            //         $('#submit-row').removeClass('hide');
            //     }
            //     else if(data == 'sundries') {
            //         $('#cat_container').removeClass('hide');
            //         $('#sp_container').addClass('hide');
            //         $('#marketing_type_container').addClass('hide');
            //         $('#vendor_container').addClass('hide');
            //         $('#submit-row').removeClass('hide');
            //     }
            //     else if(data == 'groups_all') {
            //         $('#cat_container').removeClass('hide');
            //         $('#sp_container').removeClass('hide');
            //         $('#vendor_container').removeClass('hide');
            //         $('#marketing_type_container').removeClass('hide');
            //         $('#submit-row').removeClass('hide');
            //     }
            //     else if(data == 'select_group') {
            //         $('#cat_container').addClass('hide');
            //         $('#sp_container').addClass('hide');
            //         $('#marketing_type_container').addClass('hide');
            //         $('#vendor_container').addClass('hide');
            //         $('#submit-row').addClass('hide');
            //     }
            //
            //     // re-intialize the select2
            //     $('#group_type').select2({
            //         dir: "rtl",
            //         dropdownCssClass: "select-font-size"
            //     });
            //     $('#cat_type').select2({
            //         dir: "rtl",
            //         dropdownCssClass: "select-font-size"
            //     });
            //     $('#sp_type').select2({
            //         dir: "rtl",
            //         dropdownCssClass: "select-font-size"
            //     });
            //     $('#marketing_type').select2({
            //         dir: "rtl",
            //         dropdownCssClass: "select-font-size"
            //     });
            //
            //     $('#vendor_type').select2({
            //         dir: "rtl",
            //         dropdownCssClass: "select-font-size"
            //     });
            //
            //     // swal.close();
            // });

            $('#cat_type').on("select2:select select2:unselecting", function (e) {
                var data = $('#cat_type').select2("val");
                // var data = $('#cat_type').select2("val", selected_cat_type);
                console.log('selected: ' + data);
                console.log('prev selected: ' + prev_cats);

                if (prev_cats && prev_cats.includes('cat_all') == false && data.includes('cat_all') == true && prev_cats.length != data.length) {
                    $("#cat_type option").prop('selected', false);
                    $("#cat_type option[value='cat_all']").prop('selected', true);

                    prev_cats = $(this).val();
                    $('#cat_type').change();
                }
                else {
                    if (prev_cats && prev_cats.length != data.length) {
                        $("#cat_type option[value='cat_all']").removeAttr('selected');
                        prev_cats = $(this).val();
                        $("#cat_type").change();
                    }
                }
            });

            $('#sp_type').on("select2:select select2:unselecting", function (e) {
                var data = $('#sp_type').select2("val");

                if (prev_sps && prev_sps.includes('sp_all') == false && data.includes('sp_all') == true && prev_sps.length != data.length) {
                    $("#sp_type option").prop('selected', false);
                    $("#sp_type option[value='sp_all']").prop('selected', true);

                    prev_sps = $(this).val();
                    $('#sp_type').change();
                }
                else {
                    if (prev_sps && prev_sps.length != data.length) {
                        $("#sp_type option[value='sp_all']").removeAttr('selected');
                        prev_sps = $(this).val();
                        $("#sp_type").change();
                    }
                }

            });

            $('#marketing_type').on("select2:select select2:unselecting", function (e) {
                var data = $('#marketing_type').select2("val");

                console.log('selected: ' + data);
                console.log('prev selected: ' + prev_marketing);

                if (prev_marketing && prev_marketing.includes('marketing_all') == false && data.includes('marketing_all') == true && prev_marketing.length != data.length) {
                    $("#marketing_type option").prop('selected', false);
                    $("#marketing_type option[value='marketing_all']").prop('selected', true);

                    prev_marketing = $(this).val();
                    $('#marketing_type').change();
                }
                else {
                    if (prev_marketing && prev_marketing.length != data.length) {
                        $("#marketing_type option[value='marketing_all']").removeAttr('selected');
                        prev_marketing = $(this).val();
                        $("#marketing_type").change();
                    }
                }

            });

            $('#vendor_type').on("select2:select select2:unselecting", function (e) {
                var data = $('#vendor_type').select2("val");

                if (prev_vendors && prev_vendors.includes('vendor_all') == false && data.includes('vendor_all') == true && prev_vendors.length != data.length) {
                    $("#vendor_type option").prop('selected', false);
                    $("#vendor_type option[value='vendor_all']").prop('selected', true);

                    prev_vendors = $(this).val();
                    $('#vendor_type').change();
                }
                else {
                    if (prev_vendors && prev_vendors.length != data.length) {
                        $("#vendor_type option[value='vendor_all']").removeAttr('selected');
                        prev_vendors = $(this).val();
                        $("#vendor_type").change();
                    }
                }

            });

            $('#gen-report').on('click', function () {

                // var report_type = $('#report_type').is(":checked") ? "byDepartment" : "byItem";
                var report_type = $('#report_type').val();
                // alert(report_type);
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var search_type = $("input[name='search_type']:checked").val();
                var product_code = $("#product_code").select2("val");

                var dept_id = $('#dept_id').select2("val");
                var group_type = $('#group_type').select2("val");
                var cat_type = $('#cat_type').select2("val");
                selected_cat_type = $('#cat_type').select2("val");
                var sp_type = group_type == 'groups_all' || group_type == 'commerce' || search_type == 'advanced_search' ? $('#sp_type').select2("val") : null;
                var marketing_type = group_type == 'groups_all' || group_type == 'commerce' || search_type == 'advanced_search' ? $('#marketing_type').select2("val") : null;
                var vendor_type = group_type == 'groups_all' || group_type == 'commerce' || search_type == 'advanced_search' ? $('#vendor_type').select2("val") : null;
                var customer_type = group_type == 'groups_all' || group_type == 'commerce' || search_type == 'advanced_search' ? $('#customer_type').select2("val") : null;

                // clear selections
                $("#cost").prop('checked', false);
                $("#margin").prop('checked', false);
                $("#margin-percentage").prop('checked', false);


                $("#gen-report").html('<b>الرجاء الإنتظار..</b>');

                // Swal.fire({
                //     title: 'الرجاء الإنتظار',
                //     allowOutsideClick: false,
                //     showCancelButton: false,
                //     showConfirmButton: false,
                //     willOpen: () => {
                //         Swal.showLoading()
                //     },
                // });

                // Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code);

                console.log(product_code);
                if (search_type == 'item_code_search') {

                    if(start_date == '' || end_date == '' || dept_id == null || $.trim(product_code) == "") {
                        Swal.fire({
                            title: "حدث خطأ",
                            text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                            icon: "error",
                            confirmButtonText: "موافق",
                        });
                        $("#gen-report").html('<b>إنشاء تقرير</b>');
                    }
                    else if((new Date(start_date).getFullYear()) < 2023  || (new Date(end_date).getFullYear()) < 2023) {
                        Swal.fire({
                            title: "حدث خطأ",
                            text: "الرجاء اختيار تواريخ من 2023 واعلى حتى تتمكن من إنشاء التقرير",
                            icon: "error",
                            confirmButtonText: "موافق",
                        });
                        $("#gen-report").html('<b>إنشاء تقرير</b>');
                    }
                    else {
                        $("#gen-report").html('<b>الرجاء الإنتظار..</b>');

                        Swal.fire({
                            title: 'الرجاء الإنتظار',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading()
                            },
                        });

                        customer_type = 'customer_all';
                        Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code, marketing_type, customer_type);
                        // Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                    }
                }
                else if (search_type == 'advanced_search') {
                    group_type = 'commerce';

                    if (group_type == "commerce" || group_type == "groups_all") {
                        if(start_date == null || start_date == '' || end_date == '' || end_date == null || dept_id == null || cat_type == null || sp_type == null || vendor_type == null) {
                            Swal.fire({
                                title: "حدث خطأ",
                                text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                                icon: "error",
                                confirmButtonText: "موافق",
                            });
                            $("#gen-report").html('<b>إنشاء تقرير</b>');
                        }
                        else if((new Date(start_date).getFullYear()) < 2023  || (new Date(end_date).getFullYear()) < 2023) {
                            Swal.fire({
                                title: "حدث خطأ",
                                text: "الرجاء اختيار تواريخ من 2023 واعلى حتى تتمكن من إنشاء التقرير",
                                icon: "error",
                                confirmButtonText: "موافق",
                            });
                            $("#gen-report").html('<b>إنشاء تقرير</b>');
                        }
                        else {
                            $("#gen-report").html('<b>الرجاء الإنتظار..</b>');

                            Swal.fire({
                                title: 'الرجاء الإنتظار',
                                allowOutsideClick: false,
                                showCancelButton: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                },
                            });

                            Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code, marketing_type, customer_type);
                            // Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                        }
                    }

                    else if (group_type == "farms" || group_type == "sundries") {
                        if(start_date == '' || end_date == '' || dept_id == null || cat_type == null) {
                            Swal.fire({
                                title: "حدث خطأ",
                                text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                                icon: "error",
                                confirmButtonText: "موافق",
                            });
                            $("#gen-report").html('<b>إنشاء تقرير</b>');
                        }
                        else if((new Date(start_date).getFullYear()) < 2023  || (new Date(end_date).getFullYear()) < 2023) {
                            Swal.fire({
                                title: "حدث خطأ",
                                text: "الرجاء اختيار تواريخ من 2023 واعلى حتى تتمكن من إنشاء التقرير",
                                icon: "error",
                                confirmButtonText: "موافق",
                            });
                            $("#gen-report").html('<b>إنشاء تقرير</b>');
                        }
                        else {
                            $("#gen-report").html('<b>الرجاء الإنتظار..</b>');

                            Swal.fire({
                                title: 'الرجاء الإنتظار',
                                allowOutsideClick: false,
                                showCancelButton: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                },
                            });

                            Livewire.emit('create-report', start_date, end_date, dept_id, group_type, cat_type, sp_type, vendor_type, report_type, search_type, product_code, marketing_type, customer_type);
                            // Livewire.emit('create-report', dept_id, cat_type, sp_type, vendor_type);
                        }
                    }
                    else {
                        Swal.fire({
                            title: "حدث خطأ",
                            text: "الرجاء تعبئة جميع الحقول حتى تتمكن من إنشاء التقرير",
                            icon: "error",
                            confirmButtonText: "موافق",
                        });
                        $("#gen-report").html('<b>إنشاء تقرير</b>');
                    }
                }
            });

            // $('#cost input[type="checkbox"]').on('change', function (e) {
            //
            //     alert('dada');
            //     if ($(this).is(':checked')) {
            //         alert('cost ticked');
            //     }
            //     else {
            //         alert('cost not ticked');
            //     }
            // });

        });

        function re_intialize() {

            // re-intialize the select2
            try {
                // $('#group_type').select2({
                //     dir: "rtl",
                //     dropdownCssClass: "select-font-size"
                // });
                $('#cat_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#sp_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#marketing_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
                $('#vendor_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });

                $('#customer_type').select2({
                    dir: "rtl",
                    dropdownCssClass: "select-font-size"
                });
            }
            catch (e) {

            }


        }

        function hideColumn(type) {

            if (type.checked) {
                $('.cost').removeClass('hide');
                // $('.' + type.value).removeClass('hide');
                // console.log(type.val() + ' not ticked');
            }
            else {
                console.log(type.value + ' ticked');
                // $('.' + type.value).addClass('hide');
                $('.cost').addClass('hide');
            }
        }

        function show_hide(acc) {
            if ($('.row-'+acc).hasClass('hide')) {
                $('.row-'+acc).removeClass('hide');
                $('.parent-'+acc).text('-');
            } else {
                $('.row-'+acc).addClass('hide');
                $('.parent-'+acc).text('+');
            }

        }

        /* start of collapsible code*/

        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (this.classList.contains("active")) {
                    content.style.display = "block"; // Show content if active
                } else {
                    content.style.display = "none"; // Hide content if not active
                }

            });
        }

        /* end of collapsible code*/

    </script>
@stop
@section('css-scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }
        .select2-container .select2-selection--single {
            height: 38px !important;
            width: 100%;
            padding-right: 2.5rem;
            padding-top: 0.2rem;
        }
        .select2-selection__arrow {
            height: 34px !important;
        }

        .select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow {
            /* left: 1px; */
            right: 9px;
        }

        .select-font-size {
            font-size: 0.875rem; /* 14px */
            line-height: 1.25rem; /* 20px */
        }

        .hide {
            display: none;
        }

        .record-row { opacity: 1; transform: translateY(0); transition: opacity 0.5s ease, transform 0.5s ease; }
        .record-row.hide-row {
            opacity: 0; transform: translateY(-20px); /* Adjust vertical movement if needed */
        }

        #report-logo {
            display: none;
        }

        /*thead th {*/
        /*    top: 0;*/
        /*    position: sticky;*/
        /*    background-color: #666666;*/
        /*    z-index: 20;*/
        /*}*/
        /*thead th {*/
        /*    position: sticky;*/
        /*    top: 0;*/
        /*    background-color: #f1f1f1;*/
        /*    z-index: 1;*/
        /*}*/

        /*.table-container-x {*/
        /*    max-height: 300px;*/
        /*    overflow-y: auto;*/
        /*    border: 1px solid #ccc;*/
        /*    width: 100%;*/
        /*}*/


        /*#tbl2 thead, tbl2 tfoot, #tbl2 tbody {*/
        /*    display: block;*/
        /*    !*width: 100%;*!*/
        /*}*/
        /*.table-container {*/
        /*    max-height: 400px; !* Adjust the height as needed *!*/
        /*    overflow-y: auto;*/
        /*    border: 1px solid #ccc;*/
        /*}*/

        /*#tbl2 tbody {*/
        /*    max-height: 300px;*/
        /*    overflow-y: auto;*/
        /*    border: 1px solid #ccc;*/
        /*    width: 100%;*/
        /*}*/

        /*#tbl2 thead {*/
        /*    position: sticky;*/
        /*    top: 0;*/
        /*    z-index: 2;*/
        /*}*/

        .tbl-fixed {
            overflow-x: scroll;
            overflow-y: scroll;
            height: fit-content;
            max-height: 70vh;
        }

        table th {
            position: sticky;
            top: 0px;
            background: #f8fafc;
            border: 2px solid black;
        }




        /* Style the button that is used to open and close the collapsible content */
        .collapsible {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            /*text-align: left;*/
            outline: none;
            font-size: 15px;
        }

        /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
        .active, .collapsible:hover {
            background-color: #ccc;
        }

        /* Style the collapsible content. Note: hidden by default */
        #branch-container {
            padding: 18px 18px;
            display: block;
            overflow: hidden;
            background-color: #f1f1f1;
        }

        .collapsible:after {
            content: '\02795'; /* Unicode character for "plus" sign (+) */
            font-size: 13px;
            color: white;
            float: left;
            margin-left: 5px;
        }

        button.active:after {
            content: "\2796"; /* Unicode character for "minus" sign (-) */
    </style>
@stop
