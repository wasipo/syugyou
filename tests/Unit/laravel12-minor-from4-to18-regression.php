<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Context;

uses(Tests\TestCase::class);

it('can temporarily add context within a scope', function () {
    // Contextにキーを追加している状態
    Context::add('request_id', 'ABC123');

    // 一時的に別のコンテキスト値に差し替えて処理を実行
    Context::scope(function () {
        // スコープ内では新しいイベントIDが設定されている
        Log::info('Processing event', Context::all());
        expect(Context::get('event_id'))->toBe('EVT999');
    }, ['event_id' => 'EVT999']);

    // スコープを抜けると元のコンテキストに復元されている
    expect(Context::get('event_id'))->toBeNull();
    expect(Context::get('request_id'))->toBe('ABC123');
});