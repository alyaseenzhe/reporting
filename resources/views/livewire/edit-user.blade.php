@section('title')
    تعديل بيانات مستخدم
@stop

<div>
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">الرقم الوظيفي
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="emp_code" class="form-input w-full @error('emp_code') border-red-300 @enderror">
                @error('emp_code')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
            <div class="w-full">
                <label class="block font-bold mb-2">نوع المستخدم
                    <span class="text-red-500">*</span>
                </label>
                <select name="role" wire:model="role"
                        class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                        style="@error('role') border: solid 1px #fda4af; @enderror">
                    <option value="e">مهندسين فروع</option>
                    <option value="m">مدراء مبيعات</option>
                    <option value="u">الإدارة العليا</option>
                    <option value="a">IT</option>
                </select>
            </div>

        </div>
    </div>
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">الاسم
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="name" class="form-input w-full @error('name') border-red-300 @enderror">
                @error('name')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
            <div class="w-full">
                <label class="block font-bold mb-2">البريد الإلكتروني
                    <span class="text-red-500">*</span>
                </label>
                <input type="email" wire:model="email" class="form-input w-full @error('email') border-red-300 @enderror">
                @error('email')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">كلمة المرور
                    <span class="text-red-500">*</span>
                </label>
                <input type="password" wire:model="password" class="form-input w-full @error('password') border-red-300 @enderror">
                @error('password')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>
    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">الصلاحية</label>
                <select id="group_id" name="group_id" wire:model="group_id"
                        class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                        style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    <option value="-1">الرجاء اختيار الصلاحية</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
                @error('group_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="w-full">
                <label class="block font-bold mb-5">حالة التفعيل</label>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input checked wire:model="is_active" type="radio" name="is_active" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">مفعل</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="is_active" type="radio" name="is_active" value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">غير مفعل</label>
                    </div>
                </div>

                @error('is_active')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>

    <hr style="color: #cbd5e1;border: 2px solid;">

    <h1 class="mt-4 bold text-2xl mb-6">المكان</h1>
    <div class="my-6">
        <select wire:model="sales_dept_code"  class="form-select">
            <option value=''></option>  //alahsaa branch
            <option value='0101'>{{__('0101')}}</option>  //alahsaa branch
            <option value='0102' >{{__('0102')}}</option> // jeddah
            <option value='0103' >{{__('0103')}}</option> //riyadh
            <option value='0104' >{{__('0104')}}</option> // wadi adwasir
            <option value='0105' >{{__('0105')}}</option> //jouf
            <option value='0106' >{{__('0106')}}</option> //dammam
            <option value='0107' >{{__('0107')}}</option>   //kharj
            <option value='0108' >{{__('0108')}}</option> //najran
            <option value='0109' >{{__('0109')}}</option>  //hail
            <option value='0110' >{{__('0110')}}</option> //tabouk
            <option value='0111' >{{__('0111')}}</option> //qaseem
            <option value='0112' >{{__('0112')}}</option> //sajer
        </select>
    </div>
    <hr style="color: #cbd5e1;border: 2px solid;">
    <h1 class="mt-4 bold text-2xl mb-6">الفروع</h1>
    <div>

        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الاحساء</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="10" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">جدة</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="7" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الرياض</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="13" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">وادي الدواسر</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="4" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الجوف</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="6" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الدمام</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="5" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الخرج</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="12" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">نجران</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="11" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">حائل</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="9" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">تبوك</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="8" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">القصيم</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="505" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">ساجر</label>
        </div>

        @error('branches')
        <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
    </div>

    <div class="mt-8 text-center">
        <button wire:click.prevent="update" wire:loading.attr="disabled" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
            <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                <path
                    d="M11.7.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM4.6 14H2v-2.6l6-6L10.6 8l-6 6zM12 6.6L9.4 4 11 2.4 13.6 5 12 6.6z"></path>
            </svg>
            <span class="mr-2 font-bold" wire:loading.remove wire:target="update">تحديث</span>
            <span class="mr-2 font-bold" wire:loading wire:target="update">الرجاء الانتظار..</span>
        </button>
    </div>
</div>
