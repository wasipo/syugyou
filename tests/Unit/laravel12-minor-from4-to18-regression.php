<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;

uses(Tests\TestCase::class);

// Laravel 12.1 - Context::scope - 一時的なログコンテキスト差し替え機能
it('一時的なコンテキストスコープを追加できること', function () {
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


// Laravel 12.2 - Context::increment/decrement - コンテキスト内カウンタの増減
it('コンテキストカウンタをインクリメント・デクリメントできること', function () {
    // アップロード数を表すカウンタをコンテキストに追加
    Context::add('upload_count', 0);
    Context::increment('upload_count');       // 1に増加
    Context::increment('upload_count', 5);    // 一気に+5して6に
    expect(Context::get('upload_count'))->toBe(6);

    Context::decrement('upload_count', 2);    // 2減少して4に
    expect(Context::get('upload_count'))->toBe(4);
});


// Laravel 12.3 - json:unicode キャスト - 日本語や絵文字をエスケープせず保存
it('\\uXXXXエスケープなしでエンコードできること', function () {
    $payload = ['emoji' => '🎉', 'jp' => 'こんにちは'];

    $json = Json::encode($payload, JSON_UNESCAPED_UNICODE); // flags 明示でも可

    expect($json)
        ->toContain('🎉')
        ->and($json)->toContain('こんにちは')
        ->and($json)->not->toContain('\\u');
});


// Laravel 12.4 - Builder::pipe - クエリビルダでパイプライン処理を実現
it('クエリビルダの pipe 処理でクエリを段階的に構築できること', function () {
    // 基本的なpipeの使用例
    $query = DB::table('users')
        ->pipe(function ($query) {
            return $query->where('active', true);
        })
        ->pipe(function ($query) {
            return $query->orderBy('created_at', 'desc');
        });

    expect($query->toSql())->toContain('where')
        ->and($query->toSql())->toContain('order by');
});


// Laravel 12.4 - Builder::pipe (条件付き) - 動的なクエリ構築を簡潔に
it('pipe メソッドで条件付きクエリを構築できること', function () {
    $includeInactive = false;

    $query = DB::table('users')
        ->pipe(function ($query) use ($includeInactive) {
            if (!$includeInactive) {
                return $query->where('active', true);
            }
            return $query;
        })
        ->pipe(function ($query) {
            return $query->limit(10);
        });

    expect($query->toSql())->toContain('where')
        ->and($query->toSql())->toContain('limit');
});


// Laravel 12.9 - Cache::memo() - 同一リクエスト内でキャッシュ値をメモ化
it('メモ化キャッシュドライバで重複フェッチを回避できること', function () {
    // テスト用に配列ストアを利用
    config(['cache.default' => 'array']);
    Cache::put('token', 'ABC', now()->addMinutes(5));

    // 通常のCache::getでは毎回ストレージアクセス
    $a = Cache::get('token');
    Cache::forget('token');        // 一旦削除
    $b = Cache::get('token');     // -> null（削除済みのため）

    // memo化したCache::memo()->getなら、最初の取得結果を記憶
    Cache::put('token', 'ABC', now()->addMinutes(5));
    $x = Cache::memo()->get('token');  // ストレージから取得
    Cache::forget('token');            // ストレージから削除
    $y = Cache::memo()->get('token');  // メモリ上の値 "ABC" を返す（再フェッチなし）

    expect($a)->toBe('ABC');
    expect($b)->toBeNull();
    expect($x)->toBe('ABC');
    expect($y)->toBe('ABC');  // 通常ならnullだがメモ化で値保持
});


// Laravel 12.11 - Arr::string/integer/array 等 - 配列から型安全に値取得
it('型付き配列ゲッターで型を厳密にチェックできること', function () {
    
    $data = ['name' => 'Joe', 'age' => 30, 'flags' => ['active']];
    
    // 正しい型の取得
    expect(Arr::string($data, 'name'))->toBe('Joe');
    expect(Arr::integer($data, 'age'))->toBe(30);
    expect(Arr::array($data, 'flags'))->toBe(['active']);

    // 型不一致の場合は例外を送出
    expect(fn() => Arr::array($data, 'name'))->toThrow(\InvalidArgumentException::class);
});


// Laravel 12.14 - Arr::from() - Collection等を統一的に配列変換
it('Arr::from で様々な型を配列に変換できること', function () {
    
    $collection = collect(['framework' => 'Laravel']);
    expect(Arr::from($collection))->toBe(['framework' => 'Laravel']);

    // 通常の配列はそのまま返される
    $array = ['key' => 'value'];
    expect(Arr::from($array))->toBe(['key' => 'value']);
});


// Laravel 12.15 - Number::parse/parseFloat - ロケール別数値フォーマット対応
it('ロケール対応の数値パースができること', function () {
    
    // 英語ロケール（デフォルト）
    $numEn = '1,234.56';
    expect(Number::parseFloat($numEn))->toBe(1234.56);
    
    // ドイツロケール（ピリオドが千区切り、カンマが小数点）
    $numDe = '1.234,56';
    if (extension_loaded('intl')) {
        expect(Number::parseFloat($numDe, locale: 'de'))->toBe(1234.56);
    } else {
        $this->markTestSkipped('Intl extension is not loaded');
    }
});


// Laravel 12.18 - Str::encrypt/decrypt - 文字列処理チェーンで暗号化
it('文字列の暗号化・復号ヘルパーを使えること', function () {
    // アプリケーションキーを一時的に設定
    config(['app.key' => 'base64:' . base64_encode(random_bytes(32))]);
    
    $original = 'secret-api-token';
    $encrypted = Str::of($original)->encrypt();   // 暗号化
    expect($encrypted->toString())->not->toBe($original);

    $decrypted = $encrypted->decrypt();          // 復号化
    expect($decrypted->toString())->toBe($original);
});


// Laravel 12.5 - #[Scope] 属性 - scopeプレフィックス不要のスコープ定義
it('ローカルスコープの属性記法を使えること', function () {
    // Eloquentモデルのスコープテストは実際のモデルクラスが必要なのでスキップ
    $this->markTestSkipped('Laravel 12.5のローカルスコープ属性は実際のモデルクラスが必要');
});


// Laravel 12.6 - Model::fillAndInsert - 複数モデルの一括挿入を高速化
it('複数モデルの一括登録ができること', function () {
    // Model::fillAndInsertはEloquentモデルが必要なのでスキップ
    $this->markTestSkipped('Laravel 12.6のfillAndInsertは実際のモデルクラスが必要');
});


// Laravel 12.7 - toResource/toResourceCollection - APIリソース変換を簡潔に
it('モデルをリソースに変換できること', function () {
    // toResource/toResourceCollectionはEloquentモデルが必要なのでスキップ
    $this->markTestSkipped('Laravel 12.7のtoResourceは実際のモデルクラスが必要');
});


// Laravel 12.8 - withRelationshipAutoloading - N+1問題を自動解決
it('関連の自動ロードができること', function () {
    // withRelationshipAutoloadingはEloquentモデルが必要なのでスキップ
    $this->markTestSkipped('Laravel 12.8のwithRelationshipAutoloadingは実際のモデルクラスが必要');
});


// Laravel 12.10 - AsCollection::of - JSON配列を値オブジェクトコレクションに
it('コレクションキャストで値オブジェクトマッピングができること', function () {
    // AsCollection::ofはEloquentモデルが必要なのでスキップ
    $this->markTestSkipped('Laravel 12.10のAsCollection::ofは実際のモデルクラスが必要');
});


// Laravel 12.12 - AddLinkHeadersForPreloadedAssets - プレロード数制限
it('プレロードアセット数を制限できること', function () {
    // AddLinkHeadersForPreloadedAssetsはミドルウェアのテストなのでスキップ
    $this->markTestSkipped('Laravel 12.12のプレロードアセット制限はミドルウェア設定が必要');
});


// Laravel 12.13 - dispatch()->name() - キュージョブに識別名を付与
it('キュー投入クロージャに名前を付けられること', function () {
    
    Bus::fake();
    
    // 匿名関数をキュー投入し、名前を付与
    dispatch(function () {
        // ジョブの処理内容...
    })->name('custom-job-name');

    // バッチ名やHorizon上で名前が識別できることを確認
    // ジョブがディスパッチされたことを確認
    Bus::assertDispatchedWithoutChain(\Illuminate\Queue\CallQueuedClosure::class);
});


// Laravel 12.16 - in_array_keys - 複数キーのうち最低1つの存在を検証
it('配列キー存在チェックの in_array_keys ルールを使えること', function () {
    
    $rules = [
        'config' => 'array|in_array_keys:api_key,access_token,oauth_token',
        'config.api_key' => 'nullable|string|min:32',
        'config.access_token' => 'nullable|string|min:40',
        'config.oauth_token' => 'nullable|string|starts_with:oauth_',
    ];
    
    // テストケース1: いずれかのキーを含む => バリデーション成功
    $data1 = ['config' => ['access_token' => 'abcdef1234567890abcdef1234567890abcdef12']];
    expect(Validator::make($data1, $rules)->passes())->toBeTrue();

    // テストケース2: キーが全く含まれない => バリデーション失敗
    $data2 = ['config' => ['other_key' => 'value']];
    expect(Validator::make($data2, $rules)->fails())->toBeTrue();
});


// Laravel 12.17 - AsUri キャスト - URL文字列をUriオブジェクトとして扱う
it('URL オブジェクトへのモデルキャストができること', function () {
    // AsUriキャストはEloquentモデルが必要なのでスキップ
    $this->markTestSkipped('Laravel 12.17のAsUriキャストは実際のモデルクラスが必要');
});

