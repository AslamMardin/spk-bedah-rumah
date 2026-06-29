<?php

namespace Tests\Unit;

use App\Services\SawService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SawServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_detail_normalisasi_mengembalikan_data_normalisasi_dan_keputusan(): void
    {
        $service = new SawService();

        $result = $service->getDetailNormalisasi();

        $this->assertArrayHasKey('normalisasi', $result);
        $this->assertArrayHasKey('keputusan', $result);
        $this->assertIsArray($result['normalisasi']);
        $this->assertIsArray($result['keputusan']);
    }
}
