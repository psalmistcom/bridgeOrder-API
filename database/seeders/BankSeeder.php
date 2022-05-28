<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $banks = [
            'Access Bank' => '044',
            'Access Bank (Diamond)' => '063',
            'ALAT by WEMA' => '035A',
            'Carbon' => '565',
            'Ecobank Nigeria' => '050',
            'Fidelity Bank' => '070',
            'First Bank of Nigeria' => '011',
            'First City Monument Bank' => '214',
            'Guaranty Trust Bank' => '058',
            'Heritage Bank' => '030',
            'Keystone Bank' => '082',
            'Kuda Bank' => '50211',
            'Polaris Bank' => '076',
            'Providus Bank' => '101',
            'Stanbic IBTC Bank' => '221',
            'Standard Chartered Bank' => '068',
            'Sterling Bank' => '232',
            'Union Bank of Nigeria' => '032',
            'United Bank For Africa' => '033',
            'Unity Bank' => '215',
            'Wema Bank' => '035',
            'Zenith Bank' => '057',
        ];

        foreach ($banks as $key => $value) {
            DB::table('banks')
                ->updateOrInsert(['bank_name' => $key, 'bank_code' => $value, 'created_at' => now()]);
        }
    }
}
