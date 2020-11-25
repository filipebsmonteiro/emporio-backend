<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeed::class);
        $this->call(BasicSeed::class);
        $this->call(IngProdPrimeiraLojaSeeder::class);
//        $this->call(SegundaLojaSeeder::class);
//        $this->call(IngProdSegundaLojaSeeder::class);
    }
}
