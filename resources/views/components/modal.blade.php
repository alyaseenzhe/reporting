<div x-data="{ openModal: false }" class="mx-4">

    <!-- openModal Button -->
    <button
        @click="openModal = true"
        class="h-8 w-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
        <i class="em em-email" aria-role="presentation" aria-label="ENVELOPE"></i>
    </button>

    <!-- Modal Backdrop -->
    <div
        x-show="openModal"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        @click.self="openModal = false"
    >

        <!-- Modal Box -->
        <div
            x-show="openModal"
            x-transition.scale
{{--            class="bg-white w-full max-w-md rounded-lg shadow-lg p-6"--}}
            class="bg-white w-full max-w-4xl   rounded-lg shadow-lg p-6"
        >
            {{$slot}}


        </div>
    </div>
</div>
