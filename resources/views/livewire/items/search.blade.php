<button style="border: 1px solid #838383;" type="button" class="collapsible active" @click="container = !container"  >خيارات البحث</button>

<div style="border: 1px solid #838383;" id="branch-container" x-show="container" @if($show_msg) x-show="false" @endif class="mb-6">
    <div class="flex flex-col gap-4">
        <div class="w-full flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">تاريخ البداية
                    <span class="text-red-500">*</span>
                </label>
                <input id="start_date" wire:model.live="start_date" type="date" name="start_date"
                       class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                       style="@error('start_date') border: solid 1px #fda4af; @enderror">
                @error('start_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="w-full">
                <label class="block font-bold mb-2">تاريخ النهاية
                    <span class="text-red-500">*</span>
                </label>
                <input id="end_date" wire:model.live="end_date" type="date" name="end_date"
                       class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                       style="@error('end_date') border: solid 1px #fda4af; @enderror">
                @error('end_date') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="w-full">
                <label class="block font-bold mb-2">الفرع
                    <span class="text-red-500">*</span>
                </label>
                <x-multiselect wire:model.live="dept_id" multiple  >
                    <option all_option="true" value="dept_all" selected>الكل</option>

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
{{--                    @foreach ($branchOptions as $branchKey => $options)--}}
{{--                        @if (in_array($branchKey, $branches))--}}
{{--                            @foreach ($options as [$value, $label])--}}
{{--                                <option value="dept_all">{{ __('ط§ظ„ظƒظ„') }}</option>--}}

{{--                            @if($value !== '1010')--}}
{{--                                    <option value="{{ $value }}">{{ $label }}</option>--}}
{{--                                @else--}}
{{--                                <option value="{{ $value }}">{{ $label }}</option>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
                </x-multiselect>
                @error('dept_id')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>

{{--            <div class="w-full">--}}
{{--                <label class="block font-bold mb-2">الفرع--}}
{{--                    <span class="text-red-500">*</span>--}}
{{--                </label>--}}
{{--                <div>--}}
{{--                    <select id="dept_id" name="dept_id[]" wire:model.lazy="dept_id" multiple --}}{{--multiple="multiple"=--}}
{{--                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
{{--                            style="@error('dept_id') border: solid 1px #fda4af; @enderror">--}}
{{--                        <option value="dept_all" selected>الكل</option>--}}
{{--                        @if(in_array("3", $branches))--}}
{{--                            <option value="0101" >الاحساء</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("10", $branches))--}}
{{--                            <option value="0102" >جدة</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("7", $branches))--}}
{{--                            <option value="0103" >الرياض</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("13", $branches))--}}
{{--                            <option value="0104" >وادي الدواسر</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("4", $branches))--}}
{{--                            <option value="0105" >الجوف</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("6", $branches))--}}
{{--                            <option value="0106" >الدمام</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("5", $branches))--}}
{{--                            <option value="0107" >الخرج</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("12", $branches))--}}
{{--                            <option value="0108" >نجران</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("11", $branches))--}}
{{--                            <option value="0109" >حائل</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("9", $branches))--}}
{{--                            <option value="0110" >تبوك</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("8", $branches))--}}
{{--                            <option value="0111" >القصيم</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("505", $branches))--}}
{{--                            <option value="0112" >ساجر</option>--}}
{{--                        @endif--}}
{{--                        @if(in_array("3", $branches))--}}
{{--                            <option value="0201" >مزرعة الدالوة</option>--}}
{{--                            <option value="0202" >مزرعة الفضول</option>--}}
{{--                            <option value="0203" >مزرعة الدلم</option>--}}
{{--                            <option value="0001" >المركز الرئيسي</option>--}}
{{--                        @endif--}}

{{--                    </select>--}}
{{--                </div>--}}
{{--                @error('dept_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror--}}
{{--            </div>--}}
            <div class="w-full">
                <label class="block font-bold mb-4">نوع البحث
                    <span class="text-red-500">*</span>
                </label>
                <div class="flex flex-row">
                    <div class="flex items-center w-full">
                        <input type="radio" name="search_type" wire:model.live="search_type" @click="itemSearch =true, advancedSearch= false" value="item_code_search"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            برقم الصنف</label>
                    </div>
                    <div class="flex items-center w-full">
                        <input type="radio" name="search_type" wire:model.live="search_type" value="advanced_search" @click="advancedSearch = true, itemSearch=false"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">متقدم</label>
                    </div>
                </div>

                @error('item_type')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror

            </div>

        </div>
    </div>
{{--    <div id="filteration-row2" style="padding-left: 20px" class="w-full flex flex-col gap-4 mt-3 hide ">--}}
{{--        <div class="w-full flex flex-col sm:flex-row gap-4">--}}
{{--            <div id="group_container" class="w-full">--}}
{{--                <label class="block font-bold mb-2">المجموعات--}}
{{--                    <span class="text-red-500">*</span>--}}
{{--                </label>--}}
{{--                <div >--}}
{{--                    <select id="group_type" wire:model.lazy="group_type" name="group_type"--}}
{{--                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
{{--                            style="@error('cat_type') border: solid 1px #fda4af; @enderror">--}}

{{--                        <option value="select_group" selected>اختر مجموعة</option>--}}
{{--                        <option value="groups_all">الكل</option>--}}
{{--                        <option value="commerce">الادارة التجارية</option>--}}
{{--                        <option value="farms">الانتاج الزراعي</option>--}}
{{--                        <option value="sundries">النثريات</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--                @error('group_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div id="filteration-row3" style="padding-left: 20px" class="w-full flex flex-col gap-4 mt-3 hide" x-show="advancedSearch">
        <div class="w-full flex flex-col sm:flex-row gap-4">
            <div id="cat_container" class="w-full" >
                <label class="block font-bold mb-2">نوع المواد
                    <span class="text-red-500">*</span>
                </label>
                <div >
                    <x-select_search wire:model="cat_type">
                        <option  value="cat_all">الكل</option>
                        <option value="104">اسمدة أحادية</option>
                        <option value="105">اسمدة مركبة ورقية</option>
                        <option value="106">اسمدة مركبة ذوابة</option>
                        <option value="107">اسمدة مركبة حبيبية</option>
                        <option value="108">اسمدة مركبة سائلة ومعلقة</option>
                        <option value="109">عناصر نادرة</option>
                        <option value="110">احماض دبالية</option>
                        <option value="111">احماض امينية</option>
                        <option value="112">اعشاب بحرية</option>
                        <option value="114">مصحح ملوحة وحموضة</option>
                        <option value="115">اسمدة متخصصة</option>
                        <option value="116">ترب أساسية</option>
                        <option value="117">بوتنج سويل</option>
                        <option value="118">عطن</option>
                        <option value="120">مبيدات حشرية</option>
                        <option value="121">مبيدات فطرية</option>
                        <option value="123">مبيدات اعشاب</option>
                        <option value="124">حشرات نافعة</option>
                        <option value="125">مستخلصات نباتية</option>
                        <option value="126">فرمونات وجواذب</option>
                        <option value="127">مصائد ولواصق</option>
                        <option value="128">مواد لاصقة وناشرة</option>
                        <option value="129">مبيدات قوارض</option>
                        <option value="130">مبيدات صحة عامة</option>
                        <option value="139">مرشات يدوية ملحقاتها</option>
                        <option value="140">مقصات ومحشات</option>
                        <option value="141">بلاستك تغطية وتعقيم</option>
                        <option value="142">صواني ومراكن</option>
                        <option value="143">خيوط واسلاك</option>
                        <option value="144">شباك وشاش</option>
                        <option value="145">معدات قياس</option>
                        <option value="147">مواد تعبئة</option>
                        <option value="148">الات يدوية</option>
                        <option value="150">مرشات الية وملحقاتها</option>
                        <option value="151">اليات وملحقاتها</option>
                        <option value="152">هوجيندرون</option>
                        <option value="154">ميجا جرين للصناعات المتطورة</option>
                        <option value="155">ازود</option>
                        <option value="156">إدارة مياة أخرى</option>
                        <option value="157">داكوم</option>
                        <option value="158">كاروسبراي</option>
                        <option value="159">اوربيناتي</option>
                        <option value="161">اخري (مكائن و قطع غيار)</option>
                        <option value="162">نحل وادواته</option>
                        <option value="163">صيانة</option>
                        <option value="164">مبيعات / مشتريات مباشرة</option>
                        <option value="137">بذور نجيل</option>
                        <option value="132">بذور محاصيل حقلية</option>
                        <option value="131">بذور خضار</option>
                        <option value="133">بذور اعلاف</option>
                        <option value="134">بذور أشجار مثمرة</option>
                        <option value="135">بذور ورقيات</option>
                        <option value="165">منتج خضار</option>
                        <option value="166">منتج فواكة</option>
                        <option value="169">أدوات تعبئة</option>
                        <option value="171">أدوات ومواد بيوت محمية</option>
                        <option value="172">بذور حبوب</option>
                        <option value="173">ريفولس</option>
                        <option value="174">جرينوكي</option>
                        <option value="175">ركين</option>
                        <option value="177">مواد تبخير وتعقيم</option>
                        <option value="178">كائنات دقيقة</option>
                        <option value="179">ابصال</option>
                        <option value="180">الأصول الثابتة</option>
                    </x-select_search>
{{--                    <select id="cat_type" name="cat_type"  multiple --}}{{--multiple="multiple"--}}
{{--                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
{{--                            style="@error('cat_type') border: solid 1px #fda4af; @enderror">--}}

{{--                    </select>--}}
                </div>
                @error('group_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div id="sp_container"  class="w-full" >
                <label class="block font-bold mb-2">نوع المميز
                    <span class="text-red-500">*</span>
                </label>
                <div >
                    <x-multiselect wire:model="sp_type" name="sp_type" multiple>
                        <option all_option="true" value="sp_all" selected>الكل</option>
                        <option value="0">مميز 0</option>
                        <option value="1">مميز 1</option>
                        <option value="2">مميز 2</option>
                    </x-multiselect>
{{--                    <select wire:model="sp_type" id="sp_type"  name="sp_type" multiple --}}{{-- multiple="multiple" --}}
{{--                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
{{--                            style="@error('sp_type') border: solid 1px #fda4af; @enderror">--}}
{{--                        <option value="sp_all" selected>الكل</option>--}}
{{--                        <option value="0">مميز 0</option>--}}
{{--                        <option value="1">مميز 1</option>--}}
{{--                        <option value="2">مميز 2</option>--}}
{{--                    </select>--}}
                </div>
                @error('sp_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div id="marketing_type_container" class="w-full" >
                <label class="block font-bold mb-2">الإدارات والاقسام
                    <span class="text-red-500">*</span>
                </label>
                <div >
                    @php
                        $marketingTypeOptions = method_exists($this, 'marketingTypeOptions')
                            ? $this->marketingTypeOptions()
                            : null;
                    @endphp
                    <x-multiselect wire:model.live="marketing_type" multiple>
                        <option all_option="true" value="marketing_all" selected>الكل</option>
                        @if($marketingTypeOptions !== null)
                            @foreach($marketingTypeOptions as $marketingTypeCode => $marketingTypeLabel)
                                <option value="{{ $marketingTypeCode }}">{{ $marketingTypeLabel }}</option>
                            @endforeach
                        @else
                        <option value="30">ادارة فنية - الاسمدة م1</option>
                        <option value="31">ادارة فنية - المبيدات م1</option>
                        <option value="32">ادارة فنية - البذور م1</option>
                        <option value="40">اقسام تسويقية - الحدائق والصحة العامة</option>
                        <option value="41">اقسام تسويقية - المكافحة المتكاملة</option>
                        <option value="50">الاليات والري - الاليات</option>
                        <option value="51">الاليات والري - الري</option>
                        <option value="52">الاليات والري - الري المطري</option>
                        <option value="53">الاليات والري - الخدمات</option>
                        @endif
                    </x-multiselect>

{{--                    <select id="marketing_type" name="marketing_type" multiple--}}


{{--                            --}}{{--multiple="multiple" --}}{{-- wire:model.lazy="marketing_type"--}}
{{--                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
{{--                            style="@error('sp_type') border: solid 1px #fda4af; @enderror">--}}
{{--                        <option value="marketing_all" selected>الكل</option>--}}
{{--                        <option value="30">ادارة فنية - الاسمدة م1</option>--}}
{{--                        <option value="31">ادارة فنية - المبيدات م1</option>--}}
{{--                        <option value="32">ادارة فنية - البذور م1</option>--}}
{{--                        <option value="40">اقسام تسويقية - الحدائق والصحة العامة</option>--}}
{{--                        <option value="41">اقسام تسويقية - المكافحة المتكاملة</option>--}}
{{--                        <option value="50">الآليات والري - الاليات</option>--}}
{{--                        <option value="51">الآليات والري - الري</option>--}}
{{--                        <option value="52">الآليات والري - الري المطري</option>--}}
{{--                        <option value="53">الآليات والري - الخدمات</option>--}}
{{--                    </select>--}}
                </div>
                @error('marketing_type') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div  class="w-full" >
                <label class="block font-bold mb-2">الموردين
                    <span class="text-red-500">*</span>
                </label>
                <div >
                    <x-select_search wire:model="vendor_type" >
                        <option value="vendor_all" selected>الكل</option>
                        @foreach($vendor_list as $vendor)--}}
                         <option value="{{ $vendor['VendorCode'] }}">{{ $vendor['VendorName'] }}</option>
                        @endforeach
                    </x-select_search>
{{--                    @dd($vendor_list)--}}
{{--                    <select  name="vendor_type" wire:model.lazy="vendor_type"--}}
{{--                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
{{--                            style="@error('vendor_type') border: solid 1px #fda4af; @enderror">--}}
{{--                        <option value="vendor_all" selected>الكل</option>--}}
{{--                        @foreach($vendor_list as $vendor)--}}
{{--                            <option value="{{ $vendor['VendorCode'] }}">{{ $vendor['VendorName'] }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                </div>
                @error('vendor_type')
                <span class="error text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div id="customer_container" class="w-full" >
                <label class="block font-bold mb-2">العملاء
                    <span class="text-red-500">*</span>
                </label>
                <div>
{{--                    <x-select_search wire:model.defer="dept_id" wire:model.lazy="customer_type">--}}
{{--                        <option all_option="true" value="z">الكل</option>--}}
{{--                        <option value="customer_all" selected>الكل</option>--}}
{{--                        @foreach($customer_list as $customer)--}}
{{--                            <option value="{{ $customer['CardCode'] }}">{{ $customer['CardCode'] }} - {{ $customer['CardName'] }}</option>--}}
{{--                        @endforeach--}}
{{--                    </x-select_search>--}}

                    <select name="customer_type" wire:model.live="customer_type"
                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                            style="@error('customer_type') border: solid 1px #fda4af; @enderror">
                        <option value="customer_all" selected>الكل</option>
                        @foreach($customer_list as $customer)
                            <option value="{{ $customer['CardCode'] }}">{{ $customer['CardCode'] }} - {{ $customer['CardName'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('customer_type')
                <span class="error text-red-600 text-sm">{{ $message }}</span>
                @enderror

            </div>

            <div class="mt-8 text-center w-full">
                <button wire:click="generateReport" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
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
{{--            <div id="sales_container" class="w-full">--}}
{{--                <label class="block font-bold mb-2">الموظفين--}}
{{--                    <span class="text-red-500">*</span>--}}
{{--                </label>--}}
{{--                <div>--}}
{{--                    <x-select_search id="emps_type" name="emps_type" wire:model.live="emps_type"--}}
{{--                            class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
{{--                            style="@error('emps_type') border: solid 1px #fda4af; @enderror">--}}
{{--                        <option all_option="true" value="employees_all" selected>الكل</option>--}}
{{--                        @foreach($emps as $employee)--}}
{{--                            <option value="{{ $employee->emp_code }}" data-dept="{{ $employee->sales_dept_code }}">{{ $employee->name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </x-select_search>--}}
{{--                </div>--}}
{{--                @error('emps_type')--}}
{{--                <span class="error text-red-600 text-sm">{{ $message }}</span>--}}
{{--                @enderror--}}
{{--            </div>--}}
        </div>
    </div>
    <div id="product-code-row" x-show="itemSearch" style="padding: 20px" class="w-full flex flex-col gap-4 mt-3 hide" >
        <div class="w-full flex flex-col sm:flex-row gap-4">
            <div id="product_code_div" class="w-full">
                <label class="block font-bold mb-2">رقم الصنف
                    <span class="text-red-500">*</span>
                </label>
                <x-select_search id="product_code" name="product_code" wire:model="product_code"
                        class="form-input w-full @error('product_code') border-red-300 @enderror"
                        style="@error('products_code') border: solid 1px #fda4af; @enderror">
                    @foreach($products_codes as $item)
                        <option></option>
                        <option value="{{ $item['ItemCode'] }}">{{ $item['ScribeCode'] . ' | ' . $item['ItemCode'] . ' | ' . $item['ItemName']}}</option>
                    @endforeach
                </x-select_search>
                {{--                    <input type="text" id="product_code"--}}
                {{--                           class="form-input w-full @error('product_code') border-red-300 @enderror">--}}
                @error('product_code')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
            <div class="mt-8 text-center w-full">
                <button wire:click="generateReport" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
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

{{--    <div id="submit-row" class="w-full flex flex-col gap-4 mt-3 hide" >--}}

{{--            <div class="w-full flex flex-col sm:flex-row gap-4" x-show="advancedSearch">--}}

{{--        <div id="sortBy" class="w-full" >--}}
{{--            <!-- Sales -->--}}
{{--            <label class="block font-bold mb-2">الترتيب بالأعمدة (Sorting)--}}

{{--            </label>--}}
{{--            <div class="flex flex-wrap gap-2">--}}


{{--                <label class="cursor-pointer">--}}
{{--                    <input--}}
{{--                        type="radio"--}}
{{--                        name="sortBy"--}}
{{--                        value="ItemCode"--}}
{{--                        wire:model.defer ="sortBy"--}}
{{--                        wire:change="generateReport"--}}
{{--                        class="sr-only peer"--}}
{{--                    >--}}

{{--                    <span class="block px-3 py-1.5 text-sm rounded border--}}
{{--        peer-checked:bg-gray-600--}}
{{--        peer-checked:text-white peer-checked:border-blue-600--}}
{{--        hover:bg-gray-400 transition">--}}
{{--        بالكود--}}
{{--    </span>--}}
{{--                </label>--}}

{{--                <label class="cursor-pointer">--}}
{{--                    <input--}}
{{--                        type="radio"--}}
{{--                        name="sortBy"--}}
{{--                        value="TotalQuantitySale"--}}
{{--                        wire:model.defer ="sortBy"--}}
{{--                        wire:change="generateReport"--}}
{{--                        class="hidden peer"--}}
{{--                    >--}}

{{--                    <span class="block px-3 py-1.5 text-sm rounded border--}}
{{--        peer-checked:bg-gray-600--}}
{{--        peer-checked:text-white peer-checked:border-blue-600--}}
{{--        hover:bg-gray-400 transition">--}}
{{--                                 اجمالي الكميات--}}
{{--                            </span>--}}
{{--                </label>--}}

{{--                @if(\Illuminate\Support\Facades\Auth::user()->user_group->cost == '1' || \Illuminate\Support\Facades\Auth::user()->role == 'a')--}}
{{--                    <label class="cursor-pointer">--}}
{{--                        <input--}}
{{--                            type="radio"--}}
{{--                            name="sortBy"--}}
{{--                            value="TotalQuantitySale"--}}
{{--                            wire:model.defer="sortBy"--}}
{{--                            class="hidden peer"--}}
{{--                        >--}}

{{--                        <div class="block px-3 py-1.5 text-sm rounded border--}}
{{--        peer-checked:bg-gray-600--}}
{{--        peer-checked:text-white peer-checked:border-blue-600--}}
{{--        hover:bg-gray-400 transition">--}}
{{--                            الهامش--}}
{{--                        </div>--}}
{{--                    </label>--}}

{{--                    <label class="cursor-pointer">--}}
{{--                        <input--}}
{{--                            type="radio"--}}
{{--                            name="sortBy"--}}
{{--                            value="TotalSalesPer"--}}
{{--                            wire:model="sortBy"--}}
{{--                            wire:change="generateReport"--}}
{{--                            class="hidden peer"--}}
{{--                        >--}}

{{--                        <span class="block px-3 py-1.5 text-sm rounded border--}}
{{--        peer-checked:bg-gray-600--}}
{{--        peer-checked:text-white peer-checked:border-blue-600--}}
{{--        hover:bg-gray-400 transition">--}}
{{--                               النسبة--}}
{{--                            </span>--}}
{{--                    </label>--}}

{{--                @endif--}}
{{--            </div>--}}


{{--        </div>--}}



{{--        <div id="sortDir" class="w-full" >--}}
{{--            <!-- Sales -->--}}
{{--            <label class="block font-bold mb-2">نوع الترتيب</label>--}}

{{--            <div class="flex flex-wrap gap-2">--}}
{{--                --}}{{--                        <div class="flex gap-2">--}}
{{--                <!-- ASC -->--}}
{{--                <label class="cursor-pointer">--}}
{{--                    <input--}}
{{--                        type="radio"--}}
{{--                        name="sortDir"--}}
{{--                        value="ASC"--}}
{{--                        wire:model="sortDir"--}}
{{--                        wire:change="generateReport"--}}
{{--                        class="hidden peer"--}}
{{--                    >--}}
{{--                    <span class="px-2 py-1 text-sm--}}
{{--                                rounded border--}}
{{--            peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600--}}
{{--            hover:bg-gray-400 transition flex items-center gap-1">--}}
{{--                                    ASC ▲--}}
{{--                                </span>--}}
{{--                </label>--}}

{{--                <!-- DESC -->--}}


{{--                <label class="cursor-pointer">--}}
{{--                    <input--}}
{{--                        type="radio"--}}
{{--                        name="sortDir"--}}
{{--                        value="DESC"--}}
{{--                        wire:model="sortDir"--}}
{{--                        class="hidden peer"--}}
{{--                        wire:change="generateReport"--}}
{{--                    >--}}
{{--                    <div class="px-2 py-1 text-sm rounded border--}}
{{--            peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600--}}
{{--            hover:bg-gray-400 transition flex items-center gap-1">--}}
{{--                        DESC ▼--}}
{{--                    </div>--}}
{{--                </label>--}}
{{--                --}}{{--                        </div>--}}
{{--            </div>--}}
{{--        </div>--}}


{{--        <div class="mt-8 text-center w-full">--}}
{{--            <button wire:click="generateReport" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">--}}
{{--                    <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">--}}
{{--                        <span></span>--}}
{{--                        <span>إنشاء تقرير</span>--}}
{{--                    </span>--}}
{{--                <span class="mr-2 font-bold" wire:loading wire:target="generateReport">--}}
{{--                    <span></span>--}}
{{--                    <span>الرجاء الانتظار</span>--}}
{{--                    </span>--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

</div>

