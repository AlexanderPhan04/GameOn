<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create fake Vite manifest for testing
        // Laravel Vite looks for manifest in public/build/manifest.json
        $manifestPath = public_path('build/manifest.json');
        $manifestDir = dirname($manifestPath);
        
        if (!File::exists($manifestDir)) {
            File::makeDirectory($manifestDir, 0755, true);
        }
        
        $manifest = [
            'resources/css/app.css' => [
                'file' => 'assets/app.css',
                'src' => 'resources/css/app.css',
                'isEntry' => true,
            ],
            'resources/js/app.js' => [
                'file' => 'assets/app.js',
                'src' => 'resources/js/app.js',
                'isEntry' => true,
            ],
        ];
        
        File::put($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT));
    }
    
    protected function tearDown(): void
    {
        // Clean up fake manifest
        $manifestPath = public_path('build/manifest.json');
        if (File::exists($manifestPath)) {
            File::delete($manifestPath);
        }
        
        parent::tearDown();
    }
}
