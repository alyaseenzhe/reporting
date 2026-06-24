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



    </div>

        </div>
        <div class="w-full flex flex-col sm:flex-row gap-4">

            <div class="w-full">
                <label class="block font-bold mb-2">الموردين</label>
                <x-select_search wire:model="vendor_type">
                    <option all_option="true" value="vendor_all" selected>الكل</option>
                    @foreach($vendor_list as $vendor)
                        <option value="{{ $vendor['VendorCode'] }}">{{ $vendor['VendorName'] }}</option>
                    @endforeach
                </x-select_search>


            </div>


            <div class="w-full">
                <label class="block font-bold mb-2">قيمة المخزون</label>
                <input type="number" wire:model="inventory_value" class="ts-control">
            </div>
            <div class="w-full">
                <label class="block font-bold mb-2">أيام الكفاية</label>
                <input type="number" wire:model="sufficiency_days" class="ts-control">
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
