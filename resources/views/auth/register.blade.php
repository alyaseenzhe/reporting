<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mt-4">
                <x-jet-label for="emp_code" value="{{ __('الرقم الوظيفي') }}" />
                <x-jet-input id="emp_code" class="block mt-1 w-full" type="number" name="emp_code" :value="old('emp_code')" required autofocus autocomplete="emp_code" />
            </div>

            <div class="mt-4">
                <x-jet-label for="name" value="{{ __('الاسم الرباعي') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('البريد الإلكتروني') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('كلمة المرور') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('إعادة كلمة المرور') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="area_id" value="{{ __('الفرع') }}" />
                <select id="area_id" name="area_id" class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <option value="-1">الرجاء اختيار الفرع</option>
                        <option value="3">فرع الاحساء</option>
                        <option value="10">فرع جدة</option>
                        <option value="7">فرع الرياض</option>
                        <option value="13">فرع وادي الدواسر</option>
                        <option value="4">فرع الجوف</option>
                        <option value="6">فرع الدمام</option>
                        <option value="5">فرع الخرج</option>
                        <option value="12">فرع نجران</option>
                        <option value="11">فرع حائل</option>
                        <option value="9">فرع تبوك</option>
                        <option value="8">فرع القصيم</option>
                        <option value="505">فرع ساجر</option>
                        <option value="0">اخرى</option>
                </select>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('مُسجل مسبقاً؟') }}
                </a>

                <x-jet-button class="mr-4">
                    {{ __('تسجيل') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
