<div class="space-y-2">
    @foreach ($record->getMedia() as $media)
        <div class="flex justify-between items-center border p-2 rounded">
            <div>
                <div>{{ $media->file_name }}</div>
                <div class="text-sm text-gray-500">{{ $media->collection_name }}</div>
            </div>
            <a href="{{ $media->getUrl() }}" target="_blank"
               class="text-primary-600 underline">
                تحميل
            </a>
        </div>
    @endforeach
</div>
