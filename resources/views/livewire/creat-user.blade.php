@section('title')
    إنشاء مستخدم جديد
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
                <select name="user_type" wire:model="role"
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
    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">كلمة المرور
                    <span class="text-red-500">*</span>
                </label>
                <input type="password" wire:model="password" class="form-input w-full @error('password') border-red-300 @enderror">
                @error('password')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
            <div class="w-full">
                <label class="block font-bold mb-2">إعادة كلمة المرور
                    <span class="text-red-500">*</span>
                </label>
                <input type="password" wire:model="password_confirmation" class="form-input w-full @error('password_confirmation') border-red-300 @enderror">
                @error('password_confirmation')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>

    <div class="mb-10">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">المدير المباشر</label>
                <select id="manager_id" name="manager_id" wire:model="manager_id"
                        class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                        style="@error('item_id') border: solid 1px #fda4af; @enderror">
                    <option value="-1">الرجاء اختيار المدير</option>
                    @foreach($users as $user)
                        <option value="{{ $user->emp_code }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('manager_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
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
    <div class="grid md:grid-cols-2">
    <div>
    <h1 class="mt-4 bold text-2xl mb-6">الفروع</h1>
    <div>
        <div class="mb-6">
            <label class="block font-bold mb-3">تعيين الفروع</label>
            <div class="flex flex-col sm:flex-row gap-4">
                <label class="flex items-center">
                    <input wire:model="branch_mode" type="radio" value="all" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <span class="mr-2 text-sm font-medium text-gray-900">الكل</span>
                </label>
{{--                <label class="flex items-center">--}}
{{--                    <input wire:model="branch_mode" type="radio" value="one" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">--}}
{{--                    <span class="mr-2 text-sm font-medium text-gray-900">فرع واحد</span>--}}
{{--                </label>--}}
                <label class="flex items-center">
                    <input wire:model="branch_mode" type="radio" value="selection" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <span class="mr-2 text-sm font-medium text-gray-900">مجموعة مختارة</span>
                </label>
            </div>
        </div>

{{--        @if($branch_mode === 'one')--}}
{{--            <div class="mb-6">--}}
{{--                <label class="block font-bold mb-2">الفرع</label>--}}
{{--                <select wire:model="one_branch" class="form-select w-full">--}}
{{--                    <option value="">اختر الفرع</option>--}}
{{--                    @foreach($branchOptions as $branchValue => $branchLabel)--}}
{{--                        <option value="{{ $branchValue }}">{{ $branchLabel }}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        @endif--}}

        @if($branch_mode === 'selection')
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="3" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الاحساء</label>
        </div>
        <div class="flex items-center mb-4">
            <input wire:model="branches" type="checkbox" value="10" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="default-checkbox" class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">جدة</label>
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
        @endif

        @error('branches')
        <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
    </div>
    </div>
   <div>
    <h1 class="mt-4 bold text-2xl mb-6">الأقسام التسويقية</h1>
    <div>
        <div class="mb-6">
            <label class="block font-bold mb-3">تعيين الأقسام التسويقية</label>
            <div class="flex flex-col sm:flex-row gap-4">
                <label class="flex items-center">
                    <input wire:model="mrkt_mode" type="radio" value="all" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <span class="mr-2 text-sm font-medium text-gray-900">الكل</span>
                </label>
                {{--                <label class="flex items-center">--}}
                {{--                    <input wire:model="branch_mode" type="radio" value="one" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">--}}
                {{--                    <span class="mr-2 text-sm font-medium text-gray-900">فرع واحد</span>--}}
                {{--                </label>--}}
                <label class="flex items-center">
                    <input wire:model="mrkt_mode" type="radio" value="selection" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <span class="mr-2 text-sm font-medium text-gray-900">مجموعة مختارة</span>
                </label>
            </div>
        </div>
    </div>

    @if($mrkt_mode === 'selection')
        @foreach(self::MRKT_TYPES as $mrkt_type)
            <div class="flex items-center mb-4">
                <input wire:model="mrkt_types" type="checkbox" value="{{$mrkt_type}}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{__($mrkt_type)}}</label>
            </div>
        @endforeach
    @endif

    @error('mrkt_types')
    <div class="text-xs mt-1 text-red-500">{{$message}}</div>
    @enderror
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
