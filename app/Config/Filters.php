<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

// session manage namespace 
use App\Filters\AuthFilter;
use App\Filters\ApiAuthFilter;
use App\Filters\SellerApiAuthFilter;

class Filters extends BaseFilters
{
    // List of filter aliases
    public array $aliases = [
        'auth' => AuthFilter::class,
        'API_authentication' => ApiAuthFilter::class,
        'SELLER_API_authentication' => SellerApiAuthFilter::class,
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
    ];

    // List of required filters
    public array $required = [
        'before' => [
            'forcehttps', // Force Global Secure Requests
            'pagecache',  // Web Page Caching
        ],
        'after' => [
            'pagecache',   // Web Page Caching
            'performance', // Performance Metrics
            'toolbar',     // Debug Toolbar
        ],
    ];

    // List of global filters
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    // List of filters for specific HTTP methods
    public array $methods = [];

    // List of filters for particular URI patterns
    public array $filters = [
        'API_authentication' => ['before' => 'Buyer/*'],
        'SELLER_API_authentication' => [
            'before' => 'Seller/*',
            'before' => 'api/*'
        ],
        'auth' => ['before' => ['admin/*',]],  // Apply 'auth' filter to all 'admin' routes 
    ];
}
