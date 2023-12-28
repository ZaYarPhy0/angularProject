<?php

namespace Database\Seeders;

use App\Models\ApplicantResponse;
use App\Models\Brand;
use App\Models\InstallProcess;
use App\Models\Region;
use App\Models\RemarkField;
use App\Models\SaleArea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtherDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = ['MDY 1','MDY 2','G YGN 1','G YGN 2','DT YGN 1','DT YGN 2','TaungGyi','Pathein','Bago','Myeik','MawLaMyaing','Monywa','Meikhtila','Magwe','Sittwe'];

        foreach ($regions as $r) {
            Region::create(['name' => $r]);
        }

        $saleAreas = [
            ['name' => 'Mahar Aung Myay', 'region_id' => '1'],
            ['name' => 'Pyin Oo Lwin', 'region_id' => '1'],
            ['name' => 'Chan Mya Tha Si', 'region_id' => '2'],
            ['name' => 'Kyauk Se', 'region_id' => '2'],
            ['name' => 'Hlaing Thar Yar', 'region_id' => '3'],
            ['name' => 'Hmaw Bi', 'region_id' => '3'],
            ['name' => 'North Okkalapa', 'region_id' => '3'],
            ['name' => 'Thanlyin', 'region_id' => '4'],
            ['name' => 'Thongwa', 'region_id' => '4'],
            ['name' => 'Ahlone', 'region_id' => '5'],
            ['name' => 'Hlaing', 'region_id' => '5'],
            ['name' => 'KHT - 31/10/23', 'region_id' => '5'],
            ['name' => 'Latha', 'region_id' => '5'],
            ['name' => 'Kyauktada', 'region_id' => '6'],
            ['name' => 'Thingangyun', 'region_id' => '6'],
            ['name' => 'AungPan', 'region_id' => '7'],
            ['name' => 'TaungGyi', 'region_id' => '7'],
            ['name' => 'HinThaDa', 'region_id' => '8'],
            ['name' => 'Maubin', 'region_id' => '8'],
            ['name' => 'MyanAung', 'region_id' => '8'],
            ['name' => 'Myaung Mya', 'region_id' => '8'],
            ['name' => 'Pantanaw', 'region_id' => '8'],
            ['name' => 'Pathein', 'region_id' => '8'],
            ['name' => 'Pyapon', 'region_id' => '8'],
            ['name' => 'Bago', 'region_id' => '9'],
            ['name' => 'Nyaung Lay Bin', 'region_id' => '9'],
            ['name' => 'Taungoo', 'region_id' => '9'],
            ['name' => 'Dawei', 'region_id' => '10'],
            ['name' => 'Myeik', 'region_id' => '10'],
            ['name' => 'Maw La Myaing', 'region_id' => '11'],
            ['name' => 'Thaton', 'region_id' => '11'],
            ['name' => 'Monywa', 'region_id' => '12'],
            ['name' => 'Meiktila', 'region_id' => '13'],
            ['name' => 'PyinMaNa', 'region_id' => '13'],
            ['name' => 'Sittwe', 'region_id' => '15'],
        ];

        foreach ($saleAreas as $area) {
            SaleArea::create([
                'name' => $area['name'],
                'region_id' => $area['region_id'],
            ]);
        }

        $brands = ['SAMSUNG', 'APPLE', 'HUAWEI', 'NOKIA','LENOVO', 'XIAOMI', 'REDMI', 'HONOR', 'OPPO', 'REALME', 'ONEPLUS', 'VIVO', 'MEIZU', 'ZTE', 'INFINIX', 'TECNO','KEYPAD','OTHER'];

        foreach ($brands as $b) {
            Brand::create(['name' => $b]);
        }

        $installProcess=[
            'Smooth','Device Issue','App Issue','Client Issue'
        ];
        foreach ($installProcess as $i) {
            InstallProcess::create(['name' => $i]);
        }

        $remarkField=[
            ['name'=>'OK','install_id'=>1],
            ['name'=>'IOS User','install_id'=>2],
            ['name'=>'Keypad User','install_id'=>2],
            ['name'=>'Bad Phone Conditions','install_id'=>2],
            ['name'=>'Access Issues','install_id'=>3],
            ['name'=>'OTP Issues','install_id'=>3],
            ['name'=>'Client Decline','install_id'=>4],
            ['name'=>'Client In Rush','install_id'=>4],
            ['name'=>'Client Has No Phone','install_id'=>4]
        ];

        foreach ($remarkField as $r) {
            RemarkField::create([
                'name' => $r['name'],
                'install_id' => $r['install_id'],
            ]);
        }

        $applicantResponse=[
            ['name'=>'Accepted','install_id'=>1],
            ['name'=>'Not Ok To Install','install_id'=>2],
            ['name'=>'Not Ok To Install','install_id'=>3],
            ['name'=>'Declined','install_id'=>4],
            ['name'=>'Accepted','install_id'=>4],
        ];
        foreach($applicantResponse as $a) {
            ApplicantResponse::create([
                'name' => $a['name'],
                'install_id' => $a['install_id'],
            ]);
        }
    }
}
