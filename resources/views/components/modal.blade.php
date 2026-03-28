@props(['modalId' => null, 'openButton' => true])

<div
    x-data="{ openModal: false }"
    class="mx-4"
    @open-modal.window="if ($event.detail?.id == '{{ $modalId }}') openModal = true"
    @keydown.escape.window="openModal = false"
>

    <!-- openModal Button -->
    @if($openButton)
        <button
            @click="openModal = true"
            class="h-8 w-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
            <i class="em em-email" aria-role="presentation" aria-label="ENVELOPE"></i>
        </button>
    @endif

    <!-- Modal Backdrop -->
    <div
        x-show="openModal"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        @click.self.stop="openModal = false"
    >

        <!-- Modal Box -->
        <div
            x-show="openModal"
            x-transition.scale
            class="bg-white w-full max-w-4xl rounded-lg shadow-lg p-6 relative"
            @click.stop
        >
            <button
                type="button"
                @click.stop="openModal = false"
                class="absolute right-3 top-3 rounded-full p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                aria-label="Close modal"
                title="Close"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            {{$slot}}

        </div>
    </div>
</div>
