@section('title')
    إعدادات الموقع
@stop

<div>
    <div class="mb-4">
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

    <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">فترة التوزيع للمواد (بالأيام)
                    <span class="text-red-500">*</span>
                </label>
                <input type="number" min="0" wire:model="dist_days" class="form-input w-full @error('dist_days') border-red-300 @enderror">
                @error('dist_days')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <div class="w-full">
                <label class="block font-bold mb-5">اسعار الاصناف</label>

                <div class="flex flex-row">
                    <div class="flex items-center mb-4 w-full">
                        <input checked wire:model="item_price" type="radio" name="item_price" value="MaxDiscount" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">أقل سعر</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="item_price" type="radio" name="item_price" value="WholeSale" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">سعر المؤسسات</label>
                    </div>
                    <div class="flex items-center mb-4 w-full">
                        <input wire:model="item_price" type="radio" name="item_price" value="Retail" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">سعر التجزئة</label>
                    </div>
                </div>

                @error('item_price')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>

    @if(auth()->user()->role == 'a')

    <div class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="w-full">
                <label class="block font-bold mb-2">ارسال رسالة التذكير قبل موعد الزيارة بالأيام
                    <span class="text-red-500">*</span>
                </label>
                <input type="number" min="0" wire:model="reminder_delay_days" class="form-input w-full @error('reminder_delay_days') border-red-300 @enderror">
                  @error('reminder_delay_days')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>
    </div>
    @endif

    <div class="mt-8 text-center">
        <button wire:click.prevent="save" wire:loading.attr="disabled" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
            <span class="mr-2 font-bold" wire:loading.remove wire:target="save">حفظ</span>
            <span class="mr-2 font-bold" wire:loading wire:target="save">الرجاء الانتظار..</span>
        </button>
    </div>
</div>
