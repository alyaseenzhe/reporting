@section('title')
    تعديل مجموعة
@stop

<div>
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">عنوان المجموعة
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="name" class="form-input w-full @error('name') border-red-300 @enderror">
                @error('name')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>

            {{--            <h1 class="mt-4 bold text-2xl mb-6">نوع التقرير</h1>--}}
        </div>
    </div>
    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="w-full">
                <label class="block font-bold mb-5">نوع التقرير</label>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="commission-report" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">تقرير العمولة</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.aging" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الفواتير المعلقة</label>
                    </div>
                </div>
                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.sales-profit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">مبيعات، هامش/موظف</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.sales-collections" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">التحصيل والمبيعات</label>
                    </div>
                </div>
                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.postponed-by-customers" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">المستحقات بالموظف</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.customer-cash-statement" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">كشف حساب عميل نقدي</label>
                    </div>
                </div>
                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.my-product-target" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">مستهدف الأصناف (الاضافة والمتابعة)</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.my-product-target-only" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">متابعة المستهدف فقط</label>
                    </div>
                </div>
                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.purchase-recommendation" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">توصية الشراء</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.distribution-calc" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">حاسبة التوزيع</label>
                    </div>
                </div>
                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.weekly-report" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">التقرير الإسبوعي</label>
                    </div>

                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.daily-reports" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">التقرير اليومي</label>
                    </div>
                </div>

                <label class="block font-bold mt-6 mb-4">تقارير ساب</label>
                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="report-21" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">كشف حساب عميل</label>
                    </div>

                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="report-11" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">تقرير عمليات الأصناف</label>
                    </div>
                </div>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="report-25" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">تقرير حركة عميل</label>
                    </div>

                    <div class="flex items-center mb-4 w-full">
                        <input name="report_type" wire:model="report_type" type="checkbox" value="report-42" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">تقرير تحليل الفرع</label>
                    </div>
                </div>

                @error('report_type')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>

    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="w-full">
                <label class="block font-bold mb-5">التكلفة</label>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input checked wire:model="cost" type="radio" name="cost" value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">لا</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="cost" type="radio" name="cost" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">نعم</label>
                    </div>
                </div>

                @error('cost')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>
    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="w-full">
                <label class="block font-bold mb-5">صلاحية القراءة</label>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input checked wire:model="read_type" type="radio" name="read_type" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">بيانات المستخدم نفسه فقط</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="read_type" type="radio" name="read_type" value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">بيانات الفرع التابعة للمستخدم</label>
                    </div>
                </div>

                @error('cost')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>
    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="w-full">
                <label class="block font-bold mb-5">صلاحية مستهدف الأصناف</label>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="write_product_target" type="radio" name="write_product_target" value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">يستطيع المستخدم قراءة مستهدف الأصناف للفروع التابعة له</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="write_product_target" type="radio" name="write_product_target" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">يستطيع المستخدم اضافة\تعديل مستهدف الأصناف لنفسه فقط</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="write_product_target" type="radio" name="write_product_target" value="2" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">يستطيع المستخدم اضافة\تعديل مستهدف الأصناف لجميع موظفين الفروع التابع لهم</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="write_product_target" type="radio" name="write_product_target" value="3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">يستطيع المستخدم التعديل فقط لمستهدف الأصناف لجميع موظفين الفروع التابع لهم</label>
                    </div>
                </div>

                @error('write_product_target')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>

    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="w-full">
                <label class="block font-bold mb-5">صلاحية توزيع نسب مستهدف الأصناف</label>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="calculate_all_product_target" type="radio" name="calculate_all_product_target" value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">لا يستطيع المستخدم توزيع نسب مستهدف الأصناف على جميع موظفي فرعه</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="calculate_all_product_target" type="radio" name="calculate_all_product_target" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">يستطيع المستخدم توزيع نسب مستهدف الأصناف على جميع موظفي فرعه</label>
                    </div>
                </div>

                @error('calculate_all_product_target')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>

    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="w-full">
                <label class="block font-bold mb-5">صلاحية تحديد الاصناف الخاصة</label>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="choose_special_product" type="radio" name="choose_special_product" value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">لا يستطيع المستخدم تحديد الصنف الخاص</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="choose_special_product" type="radio" name="choose_special_product" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">يستطيع المستخدم تحديد الصنف الخاص</label>
                    </div>
                </div>

                @error('choose_special_product')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>

    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="w-full">
                <label class="block font-bold mb-5">صلاحية إضافة/تحديث مستهدفات الاصناف الخاصة</label>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="edit_special_product" type="radio" name="edit_special_product" value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">لا يستطيع المستخدم إضافة/تحديث الصنف الخاص</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="edit_special_product" type="radio" name="edit_special_product" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">يستطيع المستخدم إضافة/تحديث الصنف الخاص</label>
                    </div>
                </div>

                @error('edit_special_product')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>

    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="w-full">
                <label class="block font-bold mb-5">صلاحيات الزيارات</label>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input name="visits" wire:model.lazy="visits" type="checkbox" value="enter-visit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الدخول على منصة الزيارات</label>
                    </div>

                    <div class="flex items-center mb-4 w-full">
                        <input name="visits" wire:model.lazy="visits" type="checkbox" value="create-visit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">انشاء الزيارة</label>
                    </div>

                    <div class="flex items-center mb-4 w-full">
                        <input name="visits" wire:model.lazy="visits" type="checkbox" value="view-all-visits" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            الإطلاع على جميع الزيارات</label>
                    </div>
{{--                    <div class="flex items-center mb-4 w-full">--}}
{{--                        <input name="visits" wire:model="visits" type="checkbox" value="edit-visit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">تعديل الزيارة</label>--}}
{{--                    </div>--}}
                </div>
{{--                <div class="flex flex-row">--}}
{{--                    <div class="flex items-center mb-4 w-full">--}}
{{--                        <input name="visits" wire:model="visits" type="checkbox" value="accept-visit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الموافقة على الزيارة</label>--}}
{{--                    </div>--}}
{{--                    <div class="flex items-center mb-4 w-full">--}}
{{--                        <input name="visits" wire:model="visits" type="checkbox" value="reject-visit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">رفض الزيارة</label>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="flex flex-row">--}}
{{--                    <div class="flex items-center mb-4 w-full">--}}
{{--                        <input name="visits" wire:model="visits" type="checkbox" value="close-visit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">إتمام الزيارة</label>--}}
{{--                    </div>--}}
{{--                    <div class="flex items-center mb-4 w-full">--}}
{{--                        <input name="visits" wire:model="visits" type="checkbox" value="cancel-visit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
{{--                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">إلغاء الزيارة</label>--}}
{{--                    </div>--}}
{{--                </div>--}}


                @error('visits')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>

    <div class="mt-8 text-center">
        <button wire:click.prevent="update" wire:loading.attr="disabled" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
            <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                <path
                    d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"></path>
            </svg>
            <span class="mr-2 font-bold" wire:loading.remove wire:target="update">تحديث</span>
            <span class="mr-2 font-bold" wire:loading wire:target="update">الرجاء الانتظار..</span>
        </button>
    </div>
</div>
