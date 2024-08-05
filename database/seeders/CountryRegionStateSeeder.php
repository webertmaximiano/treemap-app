<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\Store;

use App\Models\RegionCountry;


class CountryRegionStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // criar um pais Brasil
        $country = Country::create([
            'name' => 'Brasil'
        ]);
        
        // criar as regioes do brazil Norte, Nordeste
        $norte = RegionCountry::create([
            'name' => 'Norte',
            'country_id' => $country->id,
        ]);

        $nordeste = RegionCountry::create([
            'name' => 'Nordeste',
            'country_id' => $country->id,
        ]);
        $centroOeste = RegionCountry::create([
            'name' => 'Centro-Oeste',
            'country_id' => $country->id,
        ]);
        $sudeste = RegionCountry::create([
            'name' => 'Sudeste',
            'country_id' => $country->id,
        ]);
        $sul = RegionCountry::create([
            'name' => 'Sul',
            'country_id' => $country->id,
        ]);

        // criar os stados por regiao
        $states = [
            [
                'region_country_id' => $norte->id, // Norte
                'name' => 'Acre',
                'uf' => 'AC',
                'info' => json_encode([
                    'cepStart' => '69900000',
                    'cepEnd' => '69999999',
               ]),
            ],
            [
                'region_country_id' => $nordeste->id, // Nordeste
                'name' => 'Alagoas',
                'uf' => 'AL',
                'info' => json_encode([
                    'cepStart' => '57000000',
                    'cepEnd' => '57999999',
               ]),
            ],
            [
                'region_country_id' => $norte->id, // Norte
                'name' => 'Amapá',
                'uf' => 'AP',
                'info' => json_encode([
                    'cepStart' => '68900000',
                    'cepEnd' => '68999999',
               ]),
            ],
            [

                'region_country_id' => $norte->id, // Norte
                'name' => 'Amazonas',
                'uf' => 'AM',
                'info' => json_encode([
                    'cepStart' => '69000000',
                    'cepEnd' => '69899999',
               ]),
            ],
            [
                'region_country_id' => $nordeste->id, // Nordeste
                'name' => 'Bahia',
                'uf' => 'BA',
                'info' => json_encode([
                    'cepStart' => '40000000',
                    'cepEnd' => '49999999',
               ]),
            ],
            [
                'region_country_id' => $nordeste->id, // Nordeste
                'name' => 'Ceará',
                'uf' => 'CE',
                'info' => json_encode([
                    'cepStart' => '60000000',
                    'cepEnd' => '63999999',
               ]),
            ],
            [
                'region_country_id' => $centroOeste->id, // Centro-Oeste
                'name' => 'Distrito Federal',
                'uf' => 'DF',
                'info' => json_encode([
                    'cepStart' => '70000000',
                    'cepEnd' => '73699999',
               ]),
            ],
            [
                'region_country_id' => $sudeste->id, // Sudeste
                'name' => 'Espírito Santo',
                'uf' => 'ES',
                'info' => json_encode([
                    'cepStart' => '29000000',
                    'cepEnd' => '29999999',
               ]),
            ],
            [
                'region_country_id' => $centroOeste->id, // Centro-Oeste
                'name' => 'Goiás',
                'uf' => 'GO',
                'info' => json_encode([
                    'cepStart' => '72800000',
                    'cepEnd' => '76799999',
               ]),
            ],
            [
                'region_country_id' => $nordeste->id, // Nordeste
                'name' => 'Maranhão',
                'uf' => 'MA',
                'info' => json_encode([
                    'cepStart' => '65000000',
                    'cepEnd' => '65999999', 
               ]),
            ],
            [
                'region_country_id' => $centroOeste->id, // Centro Oeste
                'name' => 'Mato Grosso',
                'uf' => 'MT',
                'info' => json_encode([
                    'cepStart' => '78000000',
                    'cepEnd' => '78899999',
               ]),
            ],
            [
                'region_country_id' => $centroOeste->id, //Centro Oeste
                'name' => 'Mato Grosso do Sul',
                'uf' => 'MS',
                'info' => json_encode([
                    'cepStart' => '79000000',
                    'cepEnd' => '79999999',
               ]),
            ],
            [
                'region_country_id' => $sudeste->id, // Sudeste
                'name' => 'Minas Gerais',
                'uf' => 'MG',
                'info' => json_encode([
                    'cepStart' => '30000000',
                    'cepEnd' => '39999999',
               ]),
            ],
            [
                'region_country_id' => $norte->id, // Norte
                'name' => 'Pará',
                'uf' => 'PA',
                'info' => json_encode([
                    'cepStart' => '66000000',
                    'cepEnd' => '68899999',
               ]),
            ],
            [
                'region_country_id' => $nordeste->id, // Nordeste
                'name' => 'Paraíba',
                'uf' => 'PB',
                'info' => json_encode([
                    'cepStart' => '58000000',
                    'cepEnd' => '58999999',
               ]),
            ],
            [
                'region_country_id' => $sul->id, // Sul
                'name' => 'Paraná',
                'uf' => 'PR',
                'info' => json_encode([
                    'cepStart' => '80000000',
                    'cepEnd' => '87999999',
               ]),
            ],
            [
                'region_country_id' => $nordeste->id, // Nordeste
                'name' => 'Pernambuco',
                'uf' => 'PE',
                'info' => json_encode([
                    'cepStart' => '50000000',
                    'cepEnd' => '56999999',
               ]),
            ],
            [
                'region_country_id' => $nordeste->id, // Nordeste
                'name' => 'Piauí',
                'uf' => 'PI',
                'info' => json_encode([
                    'cepStart' => '64000000',
                    'cepEnd' => '64999999',
               ]),
            ],
            [
                'region_country_id' => $sudeste->id, // Sudeste
                'name' => 'Rio de Janeiro',
                'uf' => 'RJ',
                'info' => json_encode([
                    'cepStart' => '20000000',
                    'cepEnd' => '28999999',
               ]),
            ],
            [
                'region_country_id' => $nordeste->id, // Nordeste
                'name' => 'Rio Grande do Norte',
                'uf' => 'RN',
                'info' => json_encode([
                    'cepStart' => '59000000',
                    'cepEnd' => '59999999',
               ]),
            ],
            [
                'region_country_id' => $norte->id, // Norte
                'name' => 'Rondônia',
                'uf' => 'RO',
                'info' => json_encode([
                    'cepStart' => '76800000',
                    'cepEnd' => '76999999',
               ]),
            ],
            [
                'region_country_id' => $norte->id, // Norte
                'name' => 'Roraima',
                'uf' => 'RR',
                'info' => json_encode([
                    'cepStart' => '69300000',
                    'cepEnd' => '69399999',
               ]),
            ],
            [
                'region_country_id' => $sul->id, // Sul
                'name' => 'Santa Catarina',
                'uf' => 'SC',
                'info' => json_encode([
                    'cepStart' => '88000000',
                    'cepEnd' => '89999999',
               ]),
            ],
            [
                'region_country_id' => $sudeste->id, // Sudeste
                'name' => 'São Paulo',
                'uf' => 'SP',
                'info' => json_encode([
                    'cepStart' => '01000000',
                    'cepEnd' => '19999999',
               ]),
            ],
            [
                'region_country_id' => $nordeste->id, // Nordeste
                'name' => 'Sergipe',
                'uf' => 'SE',
                'info' => json_encode([
                    'cepStart' => '49000000',
                    'cepEnd' => '49999999',
               ]),
            ],
            [
                'region_country_id' => $norte->id, // Norte
                'name' => 'Tocantins',
                'uf' => 'TO',
                'info' => json_encode([
                    'cepStart' => '77000000',
                    'cepEnd' => '77999999',
               ]),
            ],
           
        ];

        foreach ($states as $stateData) {
            State::create($stateData);
        }

    }
}
