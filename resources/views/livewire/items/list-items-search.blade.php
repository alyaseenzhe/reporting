<button style="border: 1px solid #838383;" type="button" class="collapsible active" @click="container = !container">خيارات البحث</button>

<div style="border: 1px solid #838383;" id="branch-container" x-show="container" @if($show_msg) x-show="false" @endif class="mb-6">
    <div class="w-full flex flex-col gap-4 p-4">
        <div class="w-full flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">رقم الصنف</label>
                <x-select_search id="product_code" name="product_code" wire:model="product_code"
                                 class="form-input w-full @error('product_code') border-red-300 @enderror">
                    <option all_option="true" value="">الكل</option>
                    @foreach($products_codes as $item)
                        <option value="{{ $item['ItemCode'] }}">{{ $item['ItemCode'] . ' - ' . $item['ItemName'] }}</option>
                    @endforeach
                </x-select_search>
                @error('product_code')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full">
                <label class="block font-bold mb-2">رقم صنف المورد</label>
                <x-select_search id="catalog_number" name="catalog_number" wire:model="catalog_number"
                                 class="form-input w-full">
                    <option all_option="true" value="">الكل</option>
                    @foreach($catalog_numbers as $catalogNumber)
                        <option value="{{ $catalogNumber }}">{{ $catalogNumber }}</option>
                    @endforeach
                </x-select_search>
            </div>

            <div class="w-full">
                <label class="block font-bold mb-2">نوع المواد</label>
                <x-select_search wire:model="cat_type">
                    <option all_option="true" value="cat_all">الكل</option>
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
                    <option value="156">إدارة مياه أخرى</option>
                    <option value="157">داكوم</option>
                    <option value="158">كاروسبراي</option>
                    <option value="159">اوربيناتيت</option>
                    <option value="161">اخرى (مكائن و قطع غيار)</option>
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
            </div>
            <div class="w-full">
                <label class="block font-bold mb-2">نوع المميز</label>
                <x-multiselect wire:model="sp_type" name="sp_type" multiple>
                    <option all_option="true" value="sp_all" selected>الكل</option>
                    <option value="0">مميز 0</option>
                    <option value="1">مميز 1</option>
                    <option value="2">مميز 2</option>
                </x-multiselect>
            </div>
            <div class="w-full">
                <label class="block font-bold mb-2">نوع المنتج</label>
                <div class="flex  gap-8 pt-2">
                    <label class="inline-flex items-center gap-2">
                        <input git wire:model="item_validity" type="checkbox" value="valid" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <span>نشط</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input wire:model="item_validity" type="checkbox" value="invalid" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <span>غير نشط</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="w-full flex flex-col sm:flex-row gap-4">


            <div class="w-full">
                <label class="block font-bold mb-2">الإدارات والاقسام</label>
                <x-multiselect wire:model.live="marketing_type" multiple>
                    <option all_option="true" value="marketing_all" selected>الكل</option>
                    <option value="30">ادارة فنية - الاسمدة م1</option>
                    <option value="31">ادارة فنية - المبيدات م1</option>
                    <option value="32">ادارة فنية - البذور م1</option>
                    <option value="40">اقسام تسويقية - الحدائق والصحة العامة</option>
                    <option value="41">اقسام تسويقية - المكافحة المتكاملة</option>
                    <option value="50">الآليات والري - الآليات</option>
                    <option value="51">الآليات والري - الري</option>
                    <option value="52">الآليات والري - الري المطري</option>
                    <option value="53">الآليات والري - الخدمات</option>
                </x-multiselect>
            </div>

            <div class="w-full">
                <label class="block font-bold mb-2">الموردين</label>
                <x-select_search wire:model="vendor_type">
                    <option all_option="true" value="vendor_all" selected>الكل</option>
                    @foreach($vendor_list as $vendor)
                        <option value="{{ $vendor['VendorCode'] }}">{{ $vendor['VendorName'] }}</option>
                    @endforeach
                </x-select_search>
            </div>

            <div class="w-full mt-8">
                <button wire:click="generateReport" style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white ">
                    <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">إنشاء تقرير</span>
                    <span class="mr-2 font-bold" wire:loading wire:target="generateReport">الرجاء الانتظار</span>
                </button>
            </div>
        </div>


    </div>
</div>
