<?php

namespace Database\Seeders;

use App\Models\CropCatalogCategory;
use App\Models\CropCatalogItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CropCatalogSeeder extends Seeder
{
    /**
     * Seed the crop catalog from the approved Excel source.
     *
     * @return void
     */
    public function run()
    {
        $catalog = [
            'خضار محمية' => [
                'الطماطم',
                'فلفل حلو',
                'فلفل حار',
                'خيار',
                'باذنجان',
                'فاصولياء',
                'قرع أخضر',
                'كوسه',
                'فراولة',
            ],
            'خضار خارجية' => [
                'طماطم',
                'خيار',
                'فلفل حلو',
                'فلفل حار',
                'باذنجان',
                'فاصوليا',
                'كوسة',
                'قرع اخضر',
                'قرع عسلي',
                'حبحب',
                'شمام',
                'ملفوف',
                'زهرة',
                'بروكلي',
                'بطاطس',
                'جزر',
                'بصل',
                'ثوم',
                'شمندر/ بنجر',
                'بازلاء',
                'لوبيا',
                'بامية',
                'فول',
                'لفت / شلغم',
                'كيريلا',
                'بصل اخضر',
            ],
            'ورقيات' => [
                'سبانخ',
                'بقدونس',
                'كزبرة',
                'شبت',
                'نعناع',
                'فجل',
                'خس',
                'جرجير',
                'كرفس',
                'ملوخية',
            ],
            'حبوب' => [
                'قمح',
                'شعير',
                'ذرة',
                'الدخن',
                'ذرة سكرية',
            ],
            'اعلاف' => [
                'برسيم',
                'ذرة العلفية',
                'سورجم علفي',
                'حشيشة السودان',
                'رودس',
                'البلوبانيك',
            ],
            'الاشجار المثمرة' => [
                'برتقال',
                'ليمون',
                'يوسفي',
                'جريب فروت',
                'ترنج',
                'زيتون',
                'تمور',
                'منجا',
                'عنب',
                'رمان',
                'تين',
                'خوخ',
                'مشمش',
                'برقوق',
                'كنار',
                'بابايا',
                'موز',
                'جوافا',
                'قهوة',
                'تفاح',
                'كمثرى',
                'قطشة',
                'نكتارين',
                'كمكوات',
                'الاسكدينا',
                'التوت',
            ],
            'حدائق' => [
                'نجيل',
                'زهور',
                'شجيرات زينة',
                'أشجار زينه',
            ],
        ];

        DB::transaction(function () use ($catalog) {
            $categoryNames = array_keys($catalog);

            foreach ($catalog as $categoryName => $items) {
                $category = CropCatalogCategory::updateOrCreate(
                    ['name' => $categoryName],
                    ['sort_order' => array_search($categoryName, $categoryNames, true) + 1]
                );

                foreach ($items as $itemIndex => $itemName) {
                    CropCatalogItem::updateOrCreate(
                        [
                            'crop_catalog_category_id' => $category->id,
                            'name' => $itemName,
                        ],
                        [
                            'sort_order' => $itemIndex + 1,
                        ]
                    );
                }
            }
        });
    }
}
