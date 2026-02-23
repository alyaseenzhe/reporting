<div class="space-y-2">
    @foreach ($record->getMedia('hr-files') as $media)
        <div class="flex justify-between items-center border p-2 rounded">
            <span>{{ $media->file_name }}</span>
            <a href="{{ $media->getUrl() }}" target="_blank"
               class="text-primary-600 underline">
                تحميل
            </a>
        </div>
    @endforeach
</div>
