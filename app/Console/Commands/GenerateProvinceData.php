<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GHNService;
use Illuminate\Support\Facades\Storage;

class GenerateProvinceData extends Command
{
    protected $signature = 'generate:province-data';
    protected $description = 'Fetch data from GHN API and generate a JSON file for provinces, districts, and wards';
    protected $ghnService;

    public function __construct(GHNService $ghnService)
    {
        parent::__construct();
        $this->ghnService = $ghnService;
    }

    public function handle()
    {
        $this->info('Fetching data from GHN API...');
        $result = [];

        try {
            // Fetch provinces
            $provinces = $this->ghnService->fetchProvinces();

            foreach ($provinces as $province) {
                $provinceId = $province['ProvinceID'];
                $result[$provinceId] = [
                    'ProvinceName' => $province['ProvinceName'],
                    'Districts' => [],
                ];

                // Fetch districts for each province
                $districts = $this->ghnService->fetchDistricts($provinceId);

                foreach ($districts as $district) {
                    $districtId = $district['DistrictID'];
                    $result[$provinceId]['Districts'][$districtId] = [
                        'DistrictName' => $district['DistrictName'],
                        'Wards' => [],
                    ];

                    // Fetch wards for each district
                    $wards = $this->ghnService->fetchWards($districtId);

                    foreach ($wards as $ward) {
                        $wardCode = $ward['WardCode'];
                        $result[$provinceId]['Districts'][$districtId]['Wards'][$wardCode] = [
                            'WardName' => $ward['WardName'],
                        ];
                    }
                }
            }

            // Save result to a JSON file with readable Unicode characters
            Storage::disk('local')->put(
                'provinces.json',
                json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            $this->info('Data successfully written to storage/app/provinces.json');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}