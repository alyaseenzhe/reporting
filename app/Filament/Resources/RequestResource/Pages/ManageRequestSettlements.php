<?php

namespace App\Filament\Resources\RequestResource\Pages;

use App\Filament\Resources\RequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\Concerns\HasRecordBreadcrumb;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ManageRequestSettlements extends Page
{
    use HasRecordBreadcrumb;
    use InteractsWithRecord;
    use WithFileUploads;

    protected static string $resource = RequestResource::class;

    protected static string $view = 'filament.resources.request-resource.pages.manage-request-settlements';

    protected static ?string $title = 'Manage Settlements';

    protected ?string $maxContentWidth = '7xl';

    public array $doneStates = [];

    public $attachment;

    protected $rules = [
        'attachment' => 'required|file|max:10240',
    ];

    public function mount($record): void
    {
        static::authorizeResourceAccess();

        $this->record = $this->resolveRecord($record);

        abort_unless(static::getResource()::canEdit($this->getRecord()), 403);

        $this->fillDoneStates();
    }

    public function uploadAttachment(): void
    {
        $department = $this->getAllowedDepartment();

        if (! $department) {
            abort(403);
        }

        $this->validate();

        $collection = sprintf('%s-files', $department);
        $filename = $this->attachment->getClientOriginalName();
        $tempPath = $this->attachment->storeAs('temp-uploads', $filename, 'local');

        $this->getRecord()
            ->addMedia(storage_path('app/' . $tempPath))
            ->usingFileName($filename)
            ->toMediaCollection($collection, 'public');

        Storage::disk('local')->delete($tempPath);
        $this->attachment = null;

        $this->notify('success', 'Attachment uploaded successfully.');
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save Changes')
                ->icon('heroicon-o-save')
                ->color('success')
                ->action('saveDoneStates'),
            Actions\Action::make('edit')
                ->label('Back to Request')
                ->icon('heroicon-o-arrow-left')
                ->url(static::getResource()::getUrl('edit', ['record' => $this->getRecord()])),
        ];
    }

    public function saveDoneStates(): void
    {
        $settlements = $this->getVisibleSettlements();

        foreach ($settlements as $settlement) {
            $state = (bool) ($this->doneStates[$settlement->getKey()] ?? false);

            $this->record->settlements()->updateExistingPivot($settlement->getKey(), [
                'is_done' => $state,
            ]);
        }

        $this->fillDoneStates();

        $this->notify('success', 'Settlement statuses updated successfully.');
    }

    protected function fillDoneStates(): void
    {
        $this->doneStates = $this->getVisibleSettlements()
            ->mapWithKeys(fn ($settlement) => [
                $settlement->getKey() => (bool) ($settlement->pivot?->is_done),
            ])
            ->toArray();
    }

    protected function getViewData(): array
    {
        $settlements = $this->getVisibleSettlements();
        $doneCount = collect($this->doneStates)->filter(fn ($state) => (bool) $state)->count();
        $totalCount = $settlements->count();

        return [
            'requestRecord' => [
                'id' => $this->getRecord()->getKey(),
                'type' => $this->cleanUtf8($this->getRecord()->type) ?: 'Request',
                'user_name' => $this->cleanUtf8(optional($this->getRecord()->user)->name) ?: '-',
            ],
            'settlements' => $settlements->map(function ($settlement): array {
                return [
                    'id' => $settlement->getKey(),
                    'name' => $this->cleanUtf8($settlement->name) ?: 'Settlement',
                    'department' => $this->cleanUtf8($settlement->department) ?: '-',
                    'is_done' => (bool) ($this->doneStates[$settlement->getKey()] ?? false),
                ];
            })->values()->all(),
            'doneCount' => $doneCount,
            'pendingCount' => $totalCount - $doneCount,
            'totalCount' => $totalCount,
            'progress' => $totalCount > 0 ? (int) round(($doneCount / $totalCount) * 100) : 0,
            'attachments' => $this->getAllowedDepartment()
                ? $this->getRecord()
                    ->getMedia(sprintf('%s-files', $this->getAllowedDepartment()))
                    ->map(fn ($media) => [
                        'id' => $media->getKey(),
                        'name' => $media->file_name,
                        'url' => $media->getUrl(),
                    ])
                    ->toArray()
                : [],
            'attachmentCollection' => $this->getAllowedDepartment()
                ? strtoupper($this->getAllowedDepartment()).' Files'
                : 'Attachments',
        ];
    }

    protected function getVisibleSettlements(): Collection
    {
        $department = $this->getAllowedDepartment();

        $query = $this->getRecord()
            ->settlements()
            ->withPivot('is_done')
            ->orderBy('name');

        if (! $department) {
            $query->whereRaw('1 = 0');
        } else {
            $query->where('department', $department);
        }

        return $query->get();
    }

    protected function getAllowedDepartment(): ?string
    {
        return match (auth()->user()?->role) {
            'a', 'it' => 'it',
            'hr' => 'hr',
            default => null,
        };
    }

    protected function cleanUtf8($value): string
    {
        if ($value === null) {
            return '';
        }

        $value = (string) $value;

        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        $cleaned = @iconv('UTF-8', 'UTF-8//IGNORE', $value);

        if ($cleaned !== false && mb_check_encoding($cleaned, 'UTF-8')) {
            return $cleaned;
        }

        $converted = @mb_convert_encoding($value, 'UTF-8', 'Windows-1256');

        if ($converted !== false && mb_check_encoding($converted, 'UTF-8')) {
            return $converted;
        }

        return '';
    }
}
