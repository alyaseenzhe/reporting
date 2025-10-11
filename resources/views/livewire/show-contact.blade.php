<x-dashboard-layout>
<div>
    <div class="mb-5">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        <span class="mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">الصفحة الرئيسية</span>
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <a href="{{ route('list.contacts') }}" class="text-gray-700 hover:text-gray-900 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        <span class="mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">جهات الاتصال</span>
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400" fill="#94a3b8" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 199.404 199.404"
                             xml:space="preserve">
<g>
    <polygon points="135.412,0 35.709,99.702 135.412,199.404 163.695,171.119 92.277,99.702 163.695,28.285 	"/>
</g>
</svg>
                        <span class="text-gray-400 mr-1 md:mr-2 ml-1 ml:mr-2 text-sm font-medium">عرض جهة اتصال</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div>
        <div class="w-full flex sm:flex-row flex-col gap-4 mb-5" style="background-color: #f5f5f5; padding: 20px;">
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">الاسم</label>
                <div style="color: #5222e1">{{$contact->name}}</div>
            </div>
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">الرقم</label>
                <div style="color: #5222e1">{{$contact->phone }}</div>
            </div>
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">الإيميل</label>
                <div style="color: #5222e1">{{$contact->email}}</div>
            </div>
        </div>
        <div class="w-full flex sm:flex-row flex-col gap-4 mb-5" style="background-color: #f5f5f5; padding: 20px;">
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">اسم الشركة</label>
                <div style="color: #5222e1">{{$contact->company_name}}</div>
            </div>
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">المدينة</label>
                <div style="color: #5222e1">{{$contact->city }}</div>
            </div>
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">العنوان</label>
                <div style="color: #5222e1">{{$contact->address}}</div>
            </div>
        </div>

        <div class="w-full flex sm:flex-row flex-col gap-4 mb-5" style="background-color: #f5f5f5; padding: 20px;">
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">صورة بطاقة العمل من الأمام</label>
                <img src="{{ asset('storage/' .$contact->card_front_pic) }}" width="80" height="50" alt="صورة البطاقة من الخلف" class="w-32 h-32 object-cover rounded">

            </div>
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">المدينة</label>
                <img src="{{ asset('storage/' .$contact->card_back_pic) }}" width="80" height="50" alt="صورة البطاقة من الخلف" class="w-32 h-32 object-cover rounded">

            </div>

        </div>

        <div class="w-full flex sm:flex-row flex-col gap-4 mb-5" style="background-color: #f5f5f5; padding: 20px;">
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">وصف المقابلة والمتطلب </label>
                <div style="color: #5222e1">{{$contact->requests}}</div>

            </div>
            <div class="w-full">
                <label class="block font-bold mb-6 text-xs">ملاحظات موظف الياسين</label>
                <div style="color: #5222e1">{{$contact->note}}</div>

            </div>

        </div>

    </div>
</div>
</x-dashboard-layout>
