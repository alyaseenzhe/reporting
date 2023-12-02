<div>
    <div class="w-full">
        <label class="block font-bold mb-2">الفرع
            <span class="text-red-500">*</span>
        </label>
        <select id="dept_id" name="dept_id" wire:model="dept_id"
                class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                style="@error('item_id') border: solid 1px #fda4af; @enderror">
            <option value="-1">الرجاء اختيار الفرع</option>
            @if(count(json_decode(Auth::user()->branches)) > 1)
                <option value="all">جميع الفروع</option>
            @endif
            @if(in_array("3", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="3">الاحساء</option>
                <option value="509">منطقة القرية العليا</option>
            @endif
            @if(in_array("10", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="10">جدة</option>
                <option value="510">منطقة المدينة المنورة</option>
            @endif
            @if(in_array("7", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="7">الرياض</option>
            @endif
            @if(in_array("13", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="13">وادي الدواسر</option>
            @endif
            @if(in_array("4", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="4">الجوف</option>
            @endif
            @if(in_array("6", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="6">الدمام</option>
            @endif
            @if(in_array("5", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="5">الخرج</option>
            @endif
            @if(in_array("12", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="12">نجران</option>
                <option value="515">منطقة الباحة</option>
            @endif
            @if(in_array("11", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="11">حائل</option>
            @endif
            @if(in_array("9", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="9">تبوك</option>
            @endif
            @if(in_array("8", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="8">القصيم</option>
            @endif
            @if(in_array("505", json_decode(\Illuminate\Support\Facades\Auth::user()->branches)))
                <option value="505">ساجر</option>
            @endif


        </select>
        @error('dept_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>
    @if($dept_id != -1)
        <div class="w-full">
            <label class="block font-bold mb-2">المهندسين
                <span class="text-red-500">*</span>
            </label>
            <select id="user_id" name="user_id" wire:model="user_id"
                    class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                    style="@error('item_id') border: solid 1px #fda4af; @enderror">
                <option value="-1">الرجاء اختيار المهندس</option>
                @if(count($users) > 1)
                    <option value="all">جميع المهندسين</option>
                @endif
                @foreach($users as $user)
                    {{--                                @if(\Illuminate\Support\Facades\Auth::user()->user_group->read_type == "1" && \Illuminate\Support\Facades\Auth::id() == $user->id)--}}
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    {{--                                @elseif(\Illuminate\Support\Facades\Auth::user()->user_group->read_type == "0")--}}
                    {{--                                    <option value="{{ $user->id }}">{{ $user->name }}</option>--}}
                    {{--                                @endif--}}
                @endforeach
            </select>
            @error('user_id') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    @endif
    @if($user_id != -1)
        <div class="w-full">
            <label class="block font-bold mb-2">أول شهر
                <span class="text-red-500">*</span>
            </label>
            <input id="date" type="month" name="selected_month" wire:model="selected_month"
                   class="text-gray-900 form-select block w-full mt-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm border-gray-300 rounded-md"
                   style="@error('item_id') border: solid 1px #fda4af; @enderror">
            @error('selected_month') <span class="error text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    @endif
    @if($selected_month && $user_id != '-1' && $dept_id != '-1')
        <div class="mt-8 text-center w-full">
            <button wire:click.prevent="generateReport" wire:loading.attr="disabled"
                    style="background-color: #026832;" class="w-full btn hover:bg-indigo-600 text-white">
                        <span class="mr-2 font-bold" wire:loading.remove wire:target="generateReport">
                            <span></span>
                            <span>إنشاء تقرير</span>
                        </span>
                <span class="mr-2 font-bold" wire:loading wire:target="generateReport">
                        <span></span>
                        <span>الرجاء الانتظار</span>
                        </span>
            </button>
        </div>
    @endif
    <div>
        @if($results)
            {{ var_dump($results) }}
        @endif
    </div>
</div>
