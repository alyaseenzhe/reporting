@section('title')
    إنشاء مستخدم جديد
@stop

<div>
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">نوع المستخدم
                    <span class="text-red-500">*</span>
                </label>
                <select name="user_type" wire:model="role"
                        class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                        style="@error('role') border: solid 1px #fda4af; @enderror">
                    <option value="u">إداري</option>
                    <option value="a">مدير</option>
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
