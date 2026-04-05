<?php

namespace Database\Seeders;

use App\Models\AgriType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgriTypeSeeder extends Seeder
{
    /**
     * Seed the agri types.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'محميات' => true,
            'رشاشات محورية' => false,
            'ري ليات' => false,
            'ري غمر' => false,
            'أشجار مثمرة' => false,
            'حدائق' => false,
        ];

        DB::transaction(function () use ($types) {
            foreach ($types as $name => $hasUnits) {
                AgriType::updateOrCreate(
                    ['name' => $name],
                    ['has_units' => $hasUnits]
                );
            }
        });
    }
}
