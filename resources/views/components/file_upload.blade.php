{{--@props($label = 'label')--}}

@props(['label','model' ])

<div x-data="fileUpload()" class="max-w-md  mt-8 mx-4">
    <!-- File Input -->
    <label class="block cursor-pointer bg-white border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500">
        <input type="file" class="hidden" @change="handleFile($event)"  wire:model="{{$model}}" x-ref="fileInput" accept="image/png, image/jpeg">
        <span class="text-gray-600">📎 ادراج الصورة</span>
    </label>

    <!-- Preview -->
    <template x-if="file">
        <div class="mt-4 bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center gap-4">
            <template x-if="isImage">
                <img :src="previewUrl" class="w-20 h-20 object-cover rounded-lg border" alt="Preview">
            </template>

            <template x-if="!isImage">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800" x-text="file.name"></p>
                    <p class="text-xs text-gray-500" x-text="formatSize(file.size)"></p>
                </div>
            </template>

            <button @click="removeFile" class="text-red-500 hover:text-red-700 text-sm">حذف</button>
        </div>
    </template>
</div>

<script>
    function fileUpload() {
        return {
            file: null,
            previewUrl: '',
            isImage: false,

            handleFile(event) {
                const selected = event.target.files[0];
                if (!selected) return;

                this.file = selected;
                this.isImage = selected.type.startsWith('image/');
                if (this.isImage) {
                    this.previewUrl = URL.createObjectURL(selected);
                }
            },

            removeFile() {
                this.file = null;
                this.previewUrl = '';
                this.$refs.fileInput.value = '';
            },

            formatSize(size) {
                const kb = size / 1024;
                return kb < 1024 ? `${kb.toFixed(1)} KB` : `${(kb / 1024).toFixed(1)} MB`;
            }
        };
    }
</script>
