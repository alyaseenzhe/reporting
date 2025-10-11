


<div>
<div class="w-full">
    <img src="{{asset('images/banner.png')}}" width="100%" />
</div>
{{--@livewireScripts--}}


<div class="grid grid-cols-2 gap-2 bg-white p-5  max-w-9xl mx-auto my-8">

    <div class="mx-20 ">

        <div class="mb-6">
            <div class="flex flex-col sm:flex-row gap-4 my-4">
                <div class="w-full">
                    <label class="block font-bold mb-2 text-green-800">الاسم الاول
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="first_name" class="form-input w-full @error('first_name') border-red-300 @enderror">
                    @error('first_name')
                    <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
                </div>{{--        <div class="flex flex-col sm:flex-row gap-4">--}}
                <div class="w-full">
                    <label class="block font-bold mb-2 text-green-800">الاسم الاخير
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="last_name" class="form-input w-full @error('last_name') border-red-300 @enderror">
                    @error('last_name')
                    <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
                </div>
            </div>
            <div class="w-full my-4">
                <label class="block font-bold mb-2">رقم الجوال
                    <span class="text-red-500">*</span>
                </label>
                <input type="number" wire:model="phone" class="form-input w-full @error('phone') border-red-300 @enderror">
                @error('phone')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
            <div class="w-full my-4" >
                <label class="block font-bold mb-2">البريد الإلكتروني
                </label>
                <input type="email" wire:model="email" class="form-input w-full @error('email') border-red-300 @enderror">
                @error('email')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>

            <div class="w-full my-4" >
                <label class="block font-bold mb-2">اسم الشركة
                </label>
                <input type="email" wire:model="company_name" class="form-input w-full @error('company_name') border-red-300 @enderror">
                @error('company_name')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
            {{--        </div>--}}

            <div class="w-full my-4">

                <label class="block font-bold mb-2">المدينة
                    <span class="text-red-500">*</span>
                </label>

            <select name="city" wire:model="city"
                    class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                    style="@error('city') border: solid 1px #fda4af; @enderror">
                <option value="aldammam">الدمام</option>
                <option value="alahsaa">الأحساء</option>
                <option value="aljawf">الجوف</option>
                <option value="hail">حائل</option>
                <option value="tabuk">تبوك</option>
                <option value="alqassim">القصيم</option>
                <option value="riyadh">الرياض</option>
                <option value="sajer">ساجر</option>
                <option value="alkharj">الخرج</option>
                <option value="dawasir">وادي الدواسر</option>
                <option value="jeddah">جدة</option>
                <option value="najran">نجران</option>
            </select>
            @error('branch')
            <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
        </div>

{{--            <div class="w-full my-4">--}}
{{--                <label class="block font-bold mb-2">المدينة--}}
{{--                    <span class="text-red-500">*</span>--}}
{{--                </label>--}}
{{--                <input type="text" wire:model="city" class="form-input w-full @error('city') border-red-300 @enderror">--}}
{{--                @error('city')--}}
{{--                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror--}}
{{--            </div>--}}
        </div>

        <div class="w-full my-4" >
            <label class="block font-bold mb-2">العنوان
            </label>
            <input type="text" wire:model="address" class="form-input w-full @error('address') border-red-300 @enderror">
            @error('address')
            <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
        </div>


<div class="sm:flex gap-4">
    <div class="my-4">
    <label class="block font-bold mb-2">صورة بطاقة العمل امام
    </label>
        <x-file_upload  model="card_front_pic"/>
    </div>
    <div class="my-4">
        <label class="block font-bold mb-2">صورة بطاقة العمل من الخلف
        </label>
        <x-file_upload  model='card_back_pic'/>
    </div>

</div>

        <div class="w-full my-4">

            <h1 class="block font-bold mb-2">الاهتمامات</h1>


            <div>


                <div class="sm:flex">
                    <div class="flex items-center m-4 mx-2">
                        <input wire:model="interests" type="checkbox" value="seeds" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">البذور</label>
                    </div>

                    <div class="flex items-center m-4 mx-2">
                        <input wire:model="interests" type="checkbox" value="plant_food" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">تغذية النبات</label>
                    </div>

                    <div class="flex items-center m-4 mx-2">
                        <input wire:model="interests" type="checkbox" value="plant_protection" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">وقاية النباتات</label>
                    </div>


                    <div class="flex items-center m-4 mx-2">
                        <input wire:model="interests" type="checkbox" value="planet_tech" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">تقنيات الزراعة</label>
                    </div>

                    <div class="flex items-center m-4 mx-2">
                        <input wire:model="interests" type="checkbox" value="parks" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الحدائق</label>
                    </div>


                    <div class="flex items-center m-4 mx-2">
                        <input wire:model="interests" type="checkbox" value="mechanisms" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الآليات</label>
                    </div>

                    <div class="flex items-center m-4 mx-2">
                        <input wire:model="interests" type="checkbox" value="health" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">الصحة العامة</label>
                    </div>

                    <div class="flex items-center m-4 mx-2">
                        <input wire:model="interests" type="checkbox" value="irrigation" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label   class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">قسم الري</label>
                    </div>
                </div>
                @error('interests')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror
            </div>
        </div>

        <div class="w-full my-4">

            <h1 class="block font-bold mb-2">وصف المقابلة والمتطلب <span class="text-red-500">*</span></h1>


            <div>
            <textarea type="textarea" wire:model="requests" class="form-input w-full @error('requests') border-red-300 @enderror">
            </textarea>
                @error('requests')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror

            </div>
        </div>
        <div class="w-full my-4">

            <h1 class="block font-bold mb-2">ملاحظات موظف الياسين</h1>
            <div>
            <textarea type="textarea" wire:model="note" class="form-input w-full @error('note') border-red-300 @enderror">
            </textarea>
                @error('note')
                <div class="text-xs mt-1 text-red-500">{{$message}}</div> @enderror

            </div>
        </div>

    </div>
    {{--    <div>--}}
    {{--        <video id="camera" autoplay playsinline class="w-full max-w-md"></video>--}}
    {{--        <canvas id="snapshot" class="hidden"></canvas>--}}

    {{--        <button wire:click="capturePhoto" onclick="takePhoto()" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">--}}
    {{--            Capture & Save--}}
    {{--        </button>--}}
    {{--    </div>--}}
    {{--    @dd('storage/photos/' . $file_name)--}}
    {{--    @if (session()->has('message'))--}}
    {{--        <div class="mt-4 text-green-600">{{ session('message') }}</div>--}}

    {{--        <img src="{{ asset('storage/photos/' . $filename) }}" class="mt-2 w-full max-w-md">--}}
    {{--    @endif--}}
    {{--    <img src="{{ asset('storage/photos/' . $filename) }}" alt="Girl in a jacket" class="mt-2 w-full max-w-md">--}}
    {{--    <img src="{{ asset('storage/photos/' .'photo_1759986479.png') }}" alt="Girl in a jacket" class="mt-2 w-full max-w-md">--}}

    {{--    <script>--}}
    {{--        let video = document.getElementById('camera');--}}
    {{--        let canvas = document.getElementById('snapshot');--}}

    {{--        // Start camera--}}
    {{--        navigator.mediaDevices.getUserMedia({ video: true })--}}
    {{--            .then(stream => {--}}
    {{--                video.srcObject = stream;--}}
    {{--            });--}}

    {{--        function takePhoto() {--}}
    {{--            const canvas = document.getElementById('snapshot');--}}
    {{--            const video = document.getElementById('camera');--}}

    {{--            canvas.width = video.videoWidth;--}}
    {{--            canvas.height = video.videoHeight;--}}
    {{--            canvas.getContext('2d').drawImage(video, 0, 0);--}}
    {{--            const imageData = canvas.toDataURL('image/png');--}}

    {{--            if (window.livewire) {--}}
    {{--                window.livewire.emit('savePhoto', imageData);--}}
    {{--            } else {--}}
    {{--                console.error('Livewire is not available yet.');--}}
    {{--            }--}}
    {{--        }--}}

    {{--    </script>--}}
    {{--    <div class="mt-8 text-center">--}}
    {{--        <button wire:click="create" type="submit"  class="btn bg-indigo-500 hover:bg-indigo-600 text-white">--}}
    {{--            <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">--}}
    {{--                <path--}}
    {{--                    d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"></path>--}}
    {{--            </svg>--}}
    {{--            <span class="mr-2 font-bold">إضافة</span>--}}
    {{--            <span class="mr-2 font-bold" wire:loading wire:target="create">الرجاء الانتظار..</span>--}}
    {{--        </button>--}}
    {{--    </div>--}}


{{--    <div class="mt-8 text-center">--}}
{{--        <button wire:click.prevent="create" type="submit" wire:loading.attr="disabled" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">--}}
{{--            <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">--}}
{{--                <path--}}
{{--                    d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"></path>--}}
{{--            </svg>--}}
{{--            <span class="mr-2 font-bold" wire:loading.remove wire:target="create">إضافة</span>--}}
{{--            <span class="mr-2 font-bold" wire:loading wire:target="create">الرجاء الانتظار..</span>--}}
{{--        </button>--}}
{{--    </div>--}}
    <div class="mt-8 text-center">
        <button wire:click.prevent="create" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
            انشاء
        </button>
    </div>
</div>

</div>

{{--@livewireStyles--}}

