<?php

namespace App\Services;

use Config\Services;

class RajaOngkirService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = Services::curlrequest([
            'timeout' => 10,
            'http_errors' => false,
        ]);

        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = env('RAJAONGKIR_BASE_URL');
    }

    /**
     * Get destinations matching keyword. Fallbacks to mock data if key is 'xxx' or call fails.
     */
    public function getDestination(string $keyword): array
    {
        if (empty($this->apiKey) || $this->apiKey === 'xxx') {
            return $this->getMockDestinations($keyword);
        }

        try {
            $response = $this->client->get(
                $this->baseUrl . 'destination/domestic-destination',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'key'    => $this->apiKey,
                    ],
                    'query' => [
                        'search' => $keyword,
                        'limit'  => 50,
                    ]
                ]
            );

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
        } catch (\Exception $e) {
            // Log error or ignore to trigger fallback
        }

        return $this->getMockDestinations($keyword);
    }

    /**
     * Get cost options between origin and destination. Fallbacks to mock data if key is 'xxx' or call fails.
     */
    public function getCost(string $origin, string $destination, int $weight, string $courier): array
    {
        if (empty($this->apiKey) || $this->apiKey === 'xxx') {
            return $this->getMockCosts($destination);
        }

        try {
            $response = $this->client->post(
                $this->baseUrl . 'calculate/domestic-cost',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'key'    => $this->apiKey,
                    ],
                    'form_params' => [
                        'origin'      => $origin,
                        'destination' => $destination,
                        'weight'      => $weight,
                        'courier'     => $courier,
                    ]
                ]
            );

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
        } catch (\Exception $e) {
            // Log error or ignore to trigger fallback
        }

        return $this->getMockCosts($destination);
    }

    /**
     * Provide mock destinations matching search query.
     */
    private function getMockDestinations(string $keyword): array
    {
        $mockData = [
            [
                'id' => '64999',
                'label' => 'Pedurungan Tengah, Pedurungan, Semarang Kota, Jawa Tengah'
            ],
            [
                'id' => '64942',
                'label' => 'Banjardowo, Genuk, Semarang Kota, Jawa Tengah'
            ],
            [
                'id' => '65042',
                'label' => 'Pendrikan Kidul, Semarang Tengah, Semarang Kota, Jawa Tengah'
            ],
            [
                'id' => '501',
                'label' => 'Danurejan, Yogyakarta Kota, DI Yogyakarta'
            ]
        ];

        $filtered = [];
        foreach ($mockData as $item) {
            if (stripos($item['label'], $keyword) !== false) {
                $filtered[] = $item;
            }
        }

        if (empty($filtered)) {
            $filtered[] = [
                'id' => '99999',
                'label' => ucwords($keyword) . ' (Mock Location)'
            ];
        }

        return ['data' => $filtered];
    }

    /**
     * Provide mock costs based on destination ID.
     */
    private function getMockCosts(string $destination): array
    {
        // Simple variance of shipping costs based on destination ID to feel interactive
        $base = (int)$destination % 3;
        $costs = [
            [
                'service' => 'OKE',
                'description' => 'Layanan Ongkos Kirim Ekonomis',
                'cost' => 8000 + ($base * 1500),
                'etd' => '4-5 Hari'
            ],
            [
                'service' => 'REG',
                'description' => 'Layanan Reguler JNE',
                'cost' => 11000 + ($base * 2000),
                'etd' => '2-3 Hari'
            ],
            [
                'service' => 'YES',
                'description' => 'Yakin Esok Sampai JNE',
                'cost' => 19000 + ($base * 3000),
                'etd' => '1 Hari'
            ]
        ];

        return ['data' => $costs];
    }
}
