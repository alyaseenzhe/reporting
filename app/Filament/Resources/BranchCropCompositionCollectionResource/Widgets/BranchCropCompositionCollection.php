<?php

namespace App\Filament\Resources\BranchCropCompositionCollectionResource\Widgets;

use Filament\Widgets\PieChartWidget;

class BranchCropCompositionCollection extends PieChartWidget
{
    protected static ?string $heading = 'توزيع المساحة على الفروع';
    protected static ?string $maxHeight = '300px';


    public array $chartData = [];

    protected function getData(): array
    {
        return $this->chartData;
    }
}
