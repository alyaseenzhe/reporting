<x-filament::page
    :class="
        \Illuminate\Support\Arr::toCssClasses([
            'filament-resources-list-records-page',
            'filament-resources-' . str_replace('/', '-', $this->getResource()::getSlug()),
        ])
    "
>
    {{ \Filament\Facades\Filament::renderHook('resource.pages.list-records.table.start') }}

    {{ $this->table }}

    {{ \Filament\Facades\Filament::renderHook('resource.pages.list-records.table.end') }}

    @if ($this->getResource() === \App\Filament\Resources\BranchCropCompositionCollectionResource::class)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const page = document.querySelector('.filament-resources-branch-crop-composition-collections');

                if (! page || page.dataset.branchCropDoubleClickBound === 'true') {
                    return;
                }

                page.dataset.branchCropDoubleClickBound = 'true';

                page.addEventListener('click', function (event) {
                    const link = event.target.closest('.filament-tables-column-wrapper > a[href]');

                    if (! link || ! page.contains(link)) {
                        return;
                    }

                    event.preventDefault();
                });

                page.addEventListener('click', function (event) {
                    const row = event.target.closest('.filament-tables-row');

                    if (! row || ! page.contains(row)) {
                        return;
                    }

                    const link = row.querySelector('.filament-tables-column-wrapper > a[href]');

                    if (! link) {
                        return;
                    }

                    window.location.href = link.href;
                });
            });
        </script>
    @endif
</x-filament::page>
