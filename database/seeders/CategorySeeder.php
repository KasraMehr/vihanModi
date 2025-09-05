<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'علوم و فناوری', 'slug' => 'science-technology', 'description' => 'کلمات علمی و تکنولوژی'],
            ['name' => 'کامپیوتر و IT', 'slug' => 'computer-it', 'description' => 'واژگان کامپیوتری'],
            ['name' => 'پزشکی', 'slug' => 'medical', 'description' => 'اصطلاحات پزشکی'],
            ['name' => 'اقتصاد و کسب‌وکار', 'slug' => 'business-economics', 'description' => 'واژگان اقتصادی'],
            ['name' => 'هنر و فرهنگ', 'slug' => 'art-culture', 'description' => 'کلمات مرتبط با هنر'],
            ['name' => 'ورزش‌ها', 'slug' => 'sports', 'description' => 'اصطلاحات ورزشی'],
            ['name' => 'طبیعت و محیط زیست', 'slug' => 'nature-environment', 'description' => 'کلمات مرتبط با طبیعت'],
            ['name' => 'سیاست و حقوق', 'slug' => 'politics-law', 'description' => 'واژگان سیاسی'],
            ['name' => 'تاریخ', 'slug' => 'history', 'description' => 'اصطلاحات تاریخی'],
            ['name' => 'مذهب و فلسفه', 'slug' => 'religion-philosophy', 'description' => 'کلمات مذهبی و فلسفی'],
            ['name' => 'روانشناسی', 'slug' => 'psychology', 'description' => 'واژگان روانشناسی'],
            ['name' => 'غذا و آشپزی', 'slug' => 'food-cooking', 'description' => 'اصطلاحات آشپزی'],
            ['name' => 'سفر و گردشگری', 'slug' => 'travel-tourism', 'description' => 'کلمات مرتبط با سفر'],
            ['name' => 'مد و پوشاک', 'slug' => 'fashion', 'description' => 'واژگان مد'],
            ['name' => 'خودرو و حمل‌ونقل', 'slug' => 'automotive-transport', 'description' => 'کلمات خودرویی'],
            ['name' => 'نظامی و امنیتی', 'slug' => 'military-security', 'description' => 'اصطلاحات نظامی'],
            ['name' => 'کودک و نوجوان', 'slug' => 'children', 'description' => 'واژگان ساده برای کودکان'],
            ['name' => 'عامیانه و اصطلاحات', 'slug' => 'slang', 'description' => 'کلمات غیررسمی و عامیانه'],
            ['name' => 'هم‌معنی و متضاد', 'slug' => 'synonyms-antonyms', 'description' => 'کلمات مترادف و متضاد'],
            ['name' => 'کلمات هم‌آوا', 'slug' => 'homophones', 'description' => 'کلمات با تلفظ مشابه'],
            ['name' => 'اختصارات', 'slug' => 'abbreviations', 'description' => 'کلمات کوتاه‌شده'],
            ['name' => 'ریشه‌شناسی', 'slug' => 'etymology', 'description' => 'تاریخچه کلمات'],
            ['name' => 'تلفظ', 'slug' => 'pronunciation', 'description' => 'آواها و تلفظ‌ها'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
