<?php

declare(strict_types=1);

namespace Tests\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TestAddLinkHeadersForPreloadedAssets
{
    private static int $limit = 20; // デフォルト制限数

    public static function using(int $limit): void
    {
        self::$limit = $limit;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 仮想的なアセットリストを生成（実際のViteビルドの代わり）
        $assets = [
            '/build/app.js',
            '/build/app.css',
            '/build/chunk1.js',
            '/build/chunk2.js',
            '/build/chunk3.js',
            '/build/chunk4.js',
            '/build/chunk5.js',
            '/build/chunk6.js',
            '/build/chunk7.js',
            '/build/chunk8.js',
            '/build/chunk9.js',
            '/build/chunk10.js',
            '/build/chunk11.js',
            '/build/chunk12.js',
            '/build/chunk13.js',
        ];

        // 制限数まで切り詰め
        $limitedAssets = array_slice($assets, 0, self::$limit);

        // Linkヘッダを生成
        $linkHeaders = [];
        foreach ($limitedAssets as $asset) {
            $extension = pathinfo($asset, PATHINFO_EXTENSION);
            $as = $extension === 'css' ? 'style' : 'script';
            $linkHeaders[] = "<{$asset}>; rel=preload; as={$as}";
        }

        if (!empty($linkHeaders)) {
            $response->headers->set('Link', implode(', ', $linkHeaders));
        }

        return $response;
    }

    public static function getLimit(): int
    {
        return self::$limit;
    }

    public static function reset(): void
    {
        self::$limit = 20;
    }
}