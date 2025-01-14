<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProposalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('proposal_types')->insert([
            ['type_ar' => "إيواء للأسر النازحة (خيام، حمامات، مرافق)", 'type_en' => "Shelter for Displaced Families (Tents, Bathrooms, Facilities)"],
            ['type_ar' => "إطعام الطعام (تكيات)", 'type_en' => "Food Distribution (Soup Kitchens)"],
            ['type_ar' => "سقيا الماء", 'type_en' => "Water Supply"],
            ['type_ar' => "توزيع الطرود الغذائية", 'type_en' => "Food Parcel Distribution"],
            ['type_ar' => "توزيع الطرود الصحية", 'type_en' => "Health Package Distribution"],
            ['type_ar' => "توزيع سلال الخضار", 'type_en' => "Vegetable Basket Distribution"],
            ['type_ar' => "توزيع (لحوم، دجاج، بيض)", 'type_en' => "Meat, Chicken, and Egg Distribution"],
            ['type_ar' => "توزيعات نقدية", 'type_en' => "Cash Assistance"],
            ['type_ar' => "صناعة الخبز وتوزيعه للنازحين", 'type_en' => "Bread Production and Distribution for Displaced People"],
            ['type_ar' => "كفالة الأيتام", 'type_en' => "Orphan Sponsorship"],
            ['type_ar' => "كفالة حلقات التحفيظ", 'type_en' => "Memorization Circles Sponsorship"],
            ['type_ar' => "كسوة وملابس", 'type_en' => "Clothing and Apparel Distribution"],
            ['type_ar' => "إطعام الطعام (وجبات)", 'type_en' => "Meal Distribution"],
            ['type_ar' => "توزيع حفاضات أطفال", 'type_en' => "Baby Diaper Distribution"],
            ['type_ar' => "قسائم شرائية", 'type_en' => "Shopping Vouchers"],
            ['type_ar' => "مشروع الأغطية الشتوية - بطانيات-", 'type_en' => "Winter Blanket Project"],
            ['type_ar' => "مشروع التمور", 'type_en' => "Date Distribution Project"],
            ['type_ar' => "مشروع الشوادر", 'type_en' => "Tarp Project"],
            ['type_ar' => "مشروع تبانات اطفال + حليب", 'type_en' => "Baby Formula and Milk Project"],
            ['type_ar' => "أخرى", 'type_en' => "Other"],
        ]);
        
    }
}
