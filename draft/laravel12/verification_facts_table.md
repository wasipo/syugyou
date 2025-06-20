# Laravel 12.19.3 検証事実表

## 1. Http::requestException → Http::failedRequest の検証

**結果**: v12系では最初から`failedRequest()`のみ。`requestException`は実装されず。

| 検証項目 | 結果 | 詳細 |
|---------|------|------|
| requestException履歴 | 記録なし | `git log -S "requestException"`で該当ゼロ |
| failedRequest履歴 | ✅ 初登場確認 | PR #55332 (2025-02-28) で初導入 |
| 現在の実装 | ✅ failedRequest | Factory.php:318で確認済み |
| 今後の追加予定 | 不明 | ローカルソースでは判断不可 |

## 2. PR #55465 の merge → revert 経緯

| 項目 | 詳細情報 |
|------|----------|
| 元PR番号 | #55465 |
| 元PRタイトル | "Use value() helper in 'when' method" |
| 元PRマージ日 | 2025-04-18T12:44:14Z |
| Revert PR番号 | #55514 |
| Revert PRタイトル | "Revert \"Use value() helper in 'when' method to simplify code\" #55465" |
| Revert PR日 | 2025-04-23T12:59:28Z |
| 差分残存 | ❌ なし | `value($default)`はrevert前から存在 |

## 3. Class::method パターン存在確認テーブル

### Laravel 12新機能 (31個)

| Class | Method | バージョン | ファイル | 行番号 |
|-------|--------|------------|----------|--------|
| Str | is | v12.0.0 | Support/Str.php | 500 |
| Arr | partition | v12.1.0 | Collections/Arr.php | 1139 |
| Context | scope | v12.1.0 | Log/Context/Repository.php | 509 |
| Eloquent\Collection | partition | v12.1.0 | Database/Eloquent/Collection.php | 712 |
| Hasher | verifyConfiguration | v12.1.0 | Hashing/HashManager.php | 120 |
| Context | increment | v12.2.0 | Log/Context/Repository.php | 401 |
| Context | decrement | v12.2.0 | Log/Context/Repository.php | 418 |
| LogManager | configurationFor | v12.3.0 | - | - |
| Arr | sole | v12.4.0 | Collections/Arr.php | 959 |
| QueueFake | listenersPushed | v12.4.0 | Testing/Fakes/QueueFake.php | 359 |
| Http | pool | v12.4.0 | Http/Client/PendingRequest.php | 868 |
| Http | preventStrayRequests | v12.5.0 | Http/Client/PendingRequest.php | 1469 |
| Rules\Password | appliedRules | v12.6.0 | Validation/Rules/Password.php | 388 |
| Model | fillAndInsert | v12.6.0 | Database/Eloquent/Builder.php | 456 |
| Http | failedRequest | v12.6.0 | Http/Client/Factory.php | 318 |
| Collection | fromJson | v12.8.0 | Collections/Traits/EnumeratesValues.php | 187 |
| Fluent | Conditionable | v12.10.0 | - | - |
| TestResponse | assertRedirectBack | v12.13.0 | Testing/TestResponse.php | 236 |
| Context | except | v12.14.0 | Log/Context/Repository.php | - |
| Context | exceptHidden | v12.14.0 | Log/Context/Repository.php | - |
| Arr | from | v12.14.0 | Collections/Arr.php | - |
| TestResponse | assertClientError | v12.15.0 | Testing/TestResponse.php | - |
| TestResponse | assertRedirectToAction | v12.15.0 | Testing/TestResponse.php | - |
| Arr | hasAll | v12.16.0 | Collections/Arr.php | 505 |
| TestResponse | assertSessionMissing | v12.16.0 | Testing/TestResponse.php | 1698 |
| Rule | contains | v12.16.0 | Validation/Rule.php | 269 |
| Stringable | toUri | v12.16.0 | Support/Stringable.php | - |
| encrypt | - | v12.18.0 | Foundation/helpers.php | 489 |
| decrypt | - | v12.18.0 | Foundation/helpers.php | 503 |
| Model | unguarded | v12.18.0 | Database/Eloquent/Concerns/GuardsAttributes.php | 148 |
| TestResponse | assertRedirectBackWithErrors | v12.19.0 | Testing/TestResponse.php | - |

### 以前から存在 (4個)

| Class | Method | 理由（30字以内） |
|-------|--------|-----------------|
| Collection | make | v9から継承メソッドとして存在 |
| EnumeratesValues | ensure | v9からトレイトメソッドとして存在 |
| RefreshDatabase | usingInMemoryDatabase | v10で追加済み |
| LogManager | configurationFor | v11で型ヒント修正のみ |

### 記載ミス (2個)

| Class | Method | 理由（30字以内） |
|-------|--------|-----------------|
| Application | interBasePath | ソースコードに該当メソッド存在せず |
| Arr | mergeIfMissing | ソースコードに該当メソッド存在せず |

## 検証制限

- gitコミット履歴はローカル環境の制限により詳細追跡不可
- PR詳細情報はGitHub APIによる基本情報のみ
- メソッド存在確認は静的ソースコード解析のみ実施

  12.0.0

    - Str::is() - パターンマッチングのテスト

  12.1.0

    - Arr::partition() - 配列の分割テスト
    - Context::scope() - コンテキストスコープのテスト
    - Eloquent\Collection::partition() - Eloquentコレクションの分割テスト
    - Hasher::verifyConfiguration() - ハッシュ設定検証テスト

  12.2.0

    - Context::increment() - コンテキストのインクリメントテスト
    - Context::decrement() - コンテキストのデクリメントテスト

  12.4.0

    - Arr::sole() - 単一要素取得のテスト
    - QueueFake::listenersPushed() - キューリスナーのプッシュテスト
    - Http::pool() - HTTPプールリクエストのテスト

  12.5.0-12.6.0

    - Http::preventStrayRequests() - 不正なHTTPリクエスト防止テスト
    - Rules\Password::appliedRules() - パスワードルール適用テスト
    - Model::fillAndInsert() - 一括挿入テスト
    - Http::failedRequest() - HTTPリクエスト失敗時のコールバックテスト

  12.8.0-12.10.0

    - Collection::fromJson() - JSONからコレクション生成テスト
    - Fluent::Conditionable - Fluent条件付きメソッドテスト

  12.13.0-12.15.0

    - TestResponse::assertRedirectBack() - リダイレクトバックアサーション
    - TestResponse::assertClientError() - クライアントエラーアサーション
    - TestResponse::assertRedirectToAction() - アクションへのリダイレクトアサーション

  12.16.0

    - Arr::hasAll() - 複数キー存在確認テスト
    - TestResponse::assertSessionMissing() - セッション不在アサーション
    - Rule::contains() - バリデーションルール包含テスト
    - Stringable::toUri() - URI変換テスト

  12.18.0-12.19.0

    - encrypt()/decrypt() - 暗号化ヘルパー関数テスト
    - Model::unguarded() - ガード解除メソッドテスト
    - TestResponse::assertRedirectBackWithErrors() - エラー付きリダイレクトバックアサーション


