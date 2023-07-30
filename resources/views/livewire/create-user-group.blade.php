@section('title')
    إنشاء مجموعة جديدة
@stop

<div>
{{--    <div class="mb-6">--}}
{{--        <div class="flex flex-col sm:flex-row gap-4">--}}
{{--            <div class="w-full">--}}
{{--                <label class="block font-bold mb-2">نوع المستخدم--}}
{{--                    <span class="text-red-500">*</span>--}}
{{--                </label>--}}
{{--                <select name="user_type" wire:model="role"--}}
{{--                        class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"--}}
{{--                        style="@error('role') border: solid 1px #fda4af; @enderror">--}}
{{--                    <option value="e">مهندسين فروع</option>--}}
{{--                    <option value="m">مدراء مبيعات</option>--}}
{{--                    <option value="u">الإدارة العليا</option>--}}
{{--                    <option value="a">IT</option>--}}
{{--                </select>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}
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
                        <input name="report_type" wire:model="report_type" type="checkbox" value="list.non-paid-vouchers" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الفواتير المستحقة</label>
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

    <div class="mt-8 text-center">
        <button wire:click.prevent="create" wire:loading.attr="disabled" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
            <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                <path
                    d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"></path>
            </svg>
            <span class="mr-2 font-bold" wire:loading.remove wire:target="create">إضافة</span>
            <span class="mr-2 font-bold" wire:loading wire:target="create">الرجاء الانتظار..</span>
        </button>
    </div>
</div>
