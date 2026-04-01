<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branches = [
            '0101' => 'فرع الاحساء',
            '0102' => 'فرع جدة',
            '0103' => 'فرع الرياض',
            '0104' => 'فرع وادي الدواسر',
            '0105' => 'فرع الجوف',
            '0106' => 'فرع الدمام',
            '0107' => 'فرع الخرج',
            '0108' => 'فرع نجران',
            '0109' => 'فرع حائل',
            '0110' => 'فرع تبوك',
            '0111' => 'فرع القصيم',
            '0112' => 'فرع ساجر',
            '0201' => 'مزرعة الدالوة',
            '0202' => 'مزرعة الفضول',
            '0203' => 'مزرعة الدلم',
        ];

        foreach ($branches as $code => $name) {
            Branch::updateOrCreate([
                'code' => $code,
            ], [
                'name' => $name,
            ]);
        }
    }
}
