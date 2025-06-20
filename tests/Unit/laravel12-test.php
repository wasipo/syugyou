<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Tests\Models\TestPost;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Testing\TestResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\StrayRequestException;
use Illuminate\Events\CallQueuedListener;

uses(Tests\TestCase::class);

beforeEach(function () {
    Schema::create('test_users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamps();
    });
    
    Schema::create('test_posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->foreignId('test_user_id')->constrained('test_users');
        $table->timestamps();
    });
    
    Cache::flush();
    Context::flush();
});

afterEach(function () {
    Schema::dropIfExists('test_posts');
    Schema::dropIfExists('test_users');
    Cache::flush();
    Context::flush();
});

// v12.1.0 - Arr::partition
test('Arr::partition が偶数と奇数に分割できる', function () {
    [$even, $odd] = Arr::partition([1, 2, 3, 4], fn ($v) => $v % 2 === 0);
    expect($even)->toBe([1 => 2, 3 => 4])->and($odd)->toBe([0 => 1, 2 => 3]);
});

// v12.1.0 - Context::scope
test('Context::scope で一時的なスコープが機能する', function () {
    Context::add('global', 'value');
    $result = Context::scope(function () {
        Context::add('scoped', 'temp');
        expect(Context::get('global'))->toBe('value');
        expect(Context::get('scoped'))->toBe('temp');
        return 'done';
    });
    expect($result)->toBe('done');
    expect(Context::get('scoped'))->toBeNull();
});

// v12.2.0 - Context::increment
test('Context::increment で数値をインクリメントできる', function () {
    Context::add('counter', 5);
    Context::increment('counter');
    expect(Context::get('counter'))->toBe(6);
    Context::increment('counter', 3);
    expect(Context::get('counter'))->toBe(9);
    Context::increment('new_counter');
    expect(Context::get('new_counter'))->toBe(1);
});

// v12.2.0 - Context::decrement
test('Context::decrement で数値をデクリメントできる', function () {
    Context::add('counter', 10);
    Context::decrement('counter');
    expect(Context::get('counter'))->toBe(9);
    Context::decrement('counter', 4);
    expect(Context::get('counter'))->toBe(5);
    Context::decrement('new_counter');
    expect(Context::get('new_counter'))->toBe(-1);
});

// v12.4.0 - Arr::sole
test('Arr::sole で単一のマッチ要素を取得できる', function () {
    $array = [
        ['name' => 'John', 'age' => 30],
        ['name' => 'Jane', 'age' => 25],
    ];
    $result = Arr::sole($array, fn ($v) => $v['name'] === 'Jane');
    expect($result)->toBe(['name' => 'Jane', 'age' => 25]);
});

// v12.4.0 - QueueFake::listenersPushed
test('QueueFake でリスナー回数を確認できる', function () {
    Queue::fake();
    Queue::push(new CallQueuedListener('TestListener', 'handle', []));
    expect(Queue::listenersPushed('TestListener'))->toHaveCount(1);
});

// v12.4.0 - Http::pool
test('Http::pool で複数のHTTPリクエストを並列実行できる', function () {
    Http::fake([
        'example.com/1' => Http::response(['id' => 1]),
        'example.com/2' => Http::response(['id' => 2]),
    ]);
    $responses = Http::pool(fn ($pool) => [
        $pool->get('https://example.com/1'),
        $pool->get('https://example.com/2'),
    ]);
    expect($responses[0]->json('id'))->toBe(1);
    expect($responses[1]->json('id'))->toBe(2);
});

// v12.5.0 - Http::preventStrayRequests
test('未フェイクURLで例外を投げる', function () {
    Http::preventStrayRequests();
    expect(fn () => Http::get('https://unfaked.test'))
        ->toThrow(StrayRequestException::class);
});

// v12.6.0 - Rules\Password::appliedRules
test('Rules\Password::appliedRules で適用ルールを取得できる', function () {
    $password = Password::min(8)->letters()->numbers();
    $rules = $password->appliedRules();
    expect($rules)->toHaveKey('min');
    expect($rules['min'])->toBe(8);
    expect($rules)->toHaveKey('letters');
    expect($rules)->toHaveKey('numbers');
});

// v12.6.0 - Model::fillAndInsert
test('Model::fillAndInsert で複数レコードを一括挿入できる', function () {
    DB::table('test_users')->insert(['id' => 1, 'name' => 'User', 'email' => 'test@example.com', 'created_at' => now(), 'updated_at' => now()]);
    $data = [
        ['title' => 'Post A', 'content' => 'Content A', 'test_user_id' => 1],
        ['title' => 'Post B', 'content' => 'Content B', 'test_user_id' => 1],
    ];
    $result = TestPost::fillAndInsert($data);
    expect($result)->toBeTrue();
    expect(TestPost::count())->toBe(2);
});

// v12.6.0 - Http::failedRequest
test('Http::failedRequest が直近の例外を返す', function () {
    Http::fake(['*' => Http::response('', 502)]);
    expect(fn () => Http::get('https://foo.com')->throw())
        ->toThrow(RequestException::class);
    expect(Http::failedRequest())->toBeInstanceOf(RequestException::class);
});

// v12.8.0 - Collection::fromJson
test('Collection::fromJson でJSONからコレクションを作成できる', function () {
    $json = '{"users": [{"name": "John"}, {"name": "Jane"}]}';
    $collection = Collection::fromJson($json);
    expect($collection)->toBeInstanceOf(Collection::class);
    expect($collection->get('users'))->toHaveCount(2);
    expect($collection->get('users')[0]['name'])->toBe('John');
});

// v12.10.0 - Fluent (uses Conditionable trait)
test('Fluent でConditionableトレイトが使用できる', function () {
    $fluent = new Fluent(['name' => 'John']);
    $result = $fluent
        ->when(true, function ($f) { $f['age'] = 30; return $f; })
        ->unless(false, function ($f) { $f['city'] = 'NYC'; return $f; });
    expect($result['age'])->toBe(30);
    expect($result['city'])->toBe('NYC');
});

// v12.13.0 - TestResponse::assertRedirectBack
test('TestResponse::assertRedirectBack でリダイレクトバックを検証できる', function () {
    Route::get('/test', fn () => redirect()->back());
    $response = $this->withHeader('referer', 'https://example.com/previous')->get('/test');
    $response->assertRedirectBack();
});

// v12.14.0 - Context::except
test('Context::except で指定キーを除外できる', function () {
    Context::add(['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3']);
    $result = Context::except(['key2']);
    expect($result)->toHaveKey('key1');
    expect($result)->not->toHaveKey('key2');
    expect($result)->toHaveKey('key3');
});

// v12.14.0 - Context::exceptHidden
test('隠しキーは exceptHidden で除外', function () {
    Context::addHidden('secret', 'shh');
    Context::addHidden('password', 'hidden');
    expect(Context::exceptHidden(['secret']))->toBe(['password' => 'hidden']); // 指定キーを除外した隠しキー配列
});

// v12.14.0 - Arr::from
test('Arr::from で配列変換ができる', function () {
    $result = Arr::from(['a' => 1, 'b' => 2]);
    expect($result)->toBe(['a' => 1, 'b' => 2]);
    $result2 = Arr::from(collect(['test']));
    expect($result2)->toBe([0 => 'test']); // コレクションは0始まりインデックス
});

// v12.15.0 - TestResponse::assertClientError
test('TestResponse::assertClientError でクライアントエラーを検証できる', function () {
    Route::get('/error', fn () => response('Bad Request', 400));
    $response = $this->get('/error');
    $response->assertClientError();
});

// v12.15.0 - TestResponse::assertRedirectToAction
test('アクション文字列へのリダイレクトを検証', function () {
    Route::get('/target', 'TestController@index')->name('target');
    Route::get('/jump', fn () => redirect()->action('TestController@index'));
    $this->get('/jump')->assertRedirectToAction('TestController@index');
});

// v12.16.0 - Arr::hasAll
test('Arr::hasAll で複数キーの存在を確認できる', function () {
    $array = ['name' => 'John', 'age' => 30, 'city' => 'NYC'];
    expect(Arr::hasAll($array, ['name', 'age']))->toBeTrue();
    expect(Arr::hasAll($array, ['name', 'email']))->toBeFalse();
});

// v12.16.0 - Rule::contains
test('Rule::contains で配列の値包含をバリデートできる', function () {
    $rule = Rule::contains('laravel');
    $validator = Validator::make(
        ['tags' => ['php', 'laravel', 'framework']],
        ['tags' => $rule]
    );
    expect($validator->passes())->toBeTrue();
    $validator2 = Validator::make(
        ['tags' => ['php', 'symfony']],
        ['tags' => $rule]
    );
    expect($validator2->fails())->toBeTrue();
});

// v12.16.0 - Stringable::toUri
test('Stringable::toUri で文字列をURI形式に変換できる', function () {
    $result = Str::of('Hello World!')->toUri();
    expect((string) $result)->toBe('Hello%20World!');
    $result2 = Str::of('PHP & Laravel')->toUri();
    expect((string) $result2)->toBe('PHP%20&%20Laravel');
});

// v12.19.0 - TestResponse::assertRedirectBackWithErrors
test('TestResponse::assertRedirectBackWithErrors でエラー付きリダイレクトバックを検証できる', function () {
    Route::post('/submit', function (Request $request) {
        $request->validate(['name' => 'required']);
        return 'success';
    });
    $response = $this->withHeader('referer', 'https://example.com/form')->post('/submit');
    $response->assertRedirectBackWithErrors(['name']);
});

class TestController
{
    public function index()
    {
        return 'test';
    }
}
