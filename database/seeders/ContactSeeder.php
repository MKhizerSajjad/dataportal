<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Factory;

use App\Models\Contacts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
         // Adjust the number (50) as needed
         DB::table('contacts')->insert(factory(Contact::class, 50)->make()->toArray());
    }
}
