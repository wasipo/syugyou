## v12.0.0
* Prep Laravel v12
* Make `Str::is()` match multiline strings
* Use native MariaDB CLI commands
* Adds missing streamJson() to ResponseFactory contract
* Preserve numeric keys on the first level of the validator rules
* Test Improvements
* mergeIfMissing allows merging with nested arrays
* Fix chunked queries not honoring user-defined limits and offsets
* Replace md5 with much faster xxhash
* Switch models to UUID v7
* Improved algorithm for Number::pairs()
* Removed Duplicated Prefix on DynamoDbStore.php
* feat: configure default datetime precision on per-grammar basis
* Test Improvements
* Fix laravel/prompt dependency version constraint for illuminate/console
* Add generic return type to Container::instance()
* Map output of concurrecy calls to the index of the input
* Change Composer hasPackage to public
* force `Eloquent\Collection::partition` to return a base `Collection`
* Better support for multi-dbs in the `RefreshDatabase` trait
* Validate UUID's version optionally
* Validate UUID version 2 and max
* Add step parameter to LazyCollection range method
* Test Improvements
* Avoid breaking change `RefreshDatabase::usingInMemoryDatabase()`
* fix: container resolution order when resolving class dependencies
* Change the default for scheduled command `emailOutput()` to only send email if output exists
* Add `hasMorePages()` to `CursorPaginator` contract
* modernize `DatabaseTokenRepository` and make consistent with `CacheTokenRepository`
* chore: remove support for Carbon v2
* use promoted properties for Auth events
* use promoted properties for Database events
* use promoted properties for Console events
* use promoted properties for Mail events
* use promoted properties for Notification events
* use promoted properties for Routing events
* use promoted properties for Queue events
* Restore database token repository property documentation
* Use reject() instead of a negated filter()
* Use first-class callable syntax to improve static analysis
* add type declarations for Console Events
* use type declaration on property
* Update Symfony and PHPUnit dependencies
* Allow `when()` helper to accept Closure condition parameter
* Add test for collapse in collections
* Add test for benchmark utilities
* Fix once() cache when used in extended static class
* Ignore querystring parameters using closure when validating signed url
* Make `dropForeignIdFor` method complementary to `foreignIdFor`
* Allow scoped disks to be scoped from other scoped disks
* Add test for Util::getParameterClassName()
* Improve eloquent attach parameter consistency
* Enhance multi-database support
* Fix Session's `getCookieExpirationDate` incompatibility with Carbon 3
* Update minimum PHPUnit versions
* Prevent XSS vulnerabilities by excluding SVGs by default in image validation
* Convert interfaces from docblock to method
* Validate paths for UTF-8 characters
* Fix aggregate alias when using  expression
* Added flash method to Session interface to fix IDE issues
* Adding the withQueryString method to the paginator interface.
* feat: --memory=0 should mean skip memory exceeded verification (Breaking Change)
* Auto-discover nested policies following conventional, parallel hierarchy
* Reintroduce PHPUnit 10.5 supports
* Allow limiting bcrypt hashing to 72 bytes to prevent insecure hashes.
* Fix accessing `Connection` property in `Grammar` classes
* Configure connection on SQLite connector
* Introduce Job@resolveQueuedJobClass()
* Bind abstract from concrete's return type
* Query builder PDO fetch modes
* Fix Illuminate components `composer.json`
* Bump minimum `brick/math`
* [11.x] Fix parsing `PHP_CLI_SERVER_WORKERS` as `string` instead of `int`
* [11.x] Rename Redis parse connection for cluster test method to follow naming conventions
* [11.x] Allow `readAt` method to use in database channel
* [11.x] Fix: Custom Exceptions with Multiple Arguments does not properly rein…
* [11.x] Update ConcurrencyTest exception reference to use namespace
* [11.x] Deprecate `Factory::$modelNameResolver`
* Update `config/app.php` to reflect laravel/laravel change for compatibility
* [11x.] Improved typehints for `InteractsWithDatabase`
* [11.x] Improved typehints for `InteractsWithExceptionHandling` && `ExceptionHandlerFake`
* Add Env::extend to support custom adapters when loading environment variables
* Sync `filesystem.disk.local` configurations

## v12.0.1


## v12.1.0
* Test Improvements
* Fix incorrect typehints in `BuildsWhereDateClauses` traits
* Improve queries readablility
* Enhance eventStream to Support Custom Events and Start Messages
* Make the PendingCommand class tappable.
* Add missing union type in event stream docblock
* Change return types of `paginage()` methods to `\Illuminate\Pagination\LengthAwarePaginator`
* Check if internal `Hasher::verifyConfiguration()` method exists on driver before forwarding call
* [11.x] Fix using `AsStringable` cast on Notifiable's key
* Add Tests for Handling Null Primary Keys and Special Values in Unique Validation Rule
* Improve docblock for with() method to clarify it adds to existing eag…
* Fix dropping schema-qualified prefixed tables
* Add `Context::scope()`
* Allow Http requests to be recorded without requests being faked
* Adds a new method "getRawSql" (with embedded bindings) to the QueryException class
* Update Inspiring.php
* Correct use of named argument in `Date` facade and fix a return type.
* Add additional tests for Rule::array validation scenarios
* Remove return statement
* Fix typos
* Do not loop through middleware when excluded is empty
* Add test for Arr::reject method in Illuminate Support
* Feature: Array partition
* Introduce `ContextLogProcessor`

## v12.1.1
* [11.x] Add valid values to ensure method
* Fix attribute name used on `Validator` instance within certain rule classes
* [11.x] Fix `Application::interBasePath()` fails to resolve application when project name is "vendor"
* [11.x] Test improvements
* DocBlock: Changed typehint for `Arr::partition` method
* Enhance Email and Image Dimensions Validation Tests
* Apply default styling rules to the notification stub

## v12.2.0
* Add dates to allowed PHPDoc types of Builder::having()
* [11.x] Fix double negative in `whereNotMorphedTo()` query
* Add test for Arr::partition
* [11.x] Expose process checkTimeout method
* Compilable for Validation Contract
* [11.x] Backport "Change `paginate()` method return types to `\Illuminate\Pagination\LengthAwarePaginator`"
* [11.x] Revert faulty change to `EnumeratesValues::ensure()` doc block
* Ensure ValidationEmailRuleTest skips tests requiring the intl extension when unavailable
* ✅ Ensure Enum validation is case-sensitive by adding a new test case.
* Feature: Collection chunk without preserving keys
* Add test coverage for Uri::withQueryIfMissing method
* Fix issue with using RedisCluster with compression or serialization
* Add test coverage for Str::replaceMatches method
* Types: Collection chunk without preserving keys
* Add `ddBody` method to TestResponse for dumping various response payloads
* [11.x] Backport "Fix issue with using `RedisCluster` with compression or serialization"
* feat: add `CanBeOneOfMany` support to `HasOneThrough`
* Hotfix - Add function_exists check to ddBody in TestResponse
* Refactor: Remove unnecessary variables in Str class methods
* Add Tests for Str::pluralPascal Method
* Fix visibility of setUp and tearDown in tests
* Test Improvements
* Fix missing return in `assertOnlyInvalid`
* Handle case when migrate:install command is called and table exists
* [11.x] Fix callOnce in Seeder so it handles arrays properly
* Change "exceptoin" spelling mistake to "exception"
* Add test for after method in LazyCollection
* Add `increment` and `decrement` methods to `Context`
* Ensure ExcludeIf correctly rejects a null value as an invalid condition
* apply Pint rule "no_spaces_around_offset"
* apply Pint rule "single_line_comment_style"
* do not use mix of newline and inline formatting
* use single indent for multiline ternaries

## v12.3.0
* fixes https://github.com/laravel/octane/issues/1010
* Added the missing 'trashed' event to getObservablesEvents()
* Enhance PHPDoc for Manager classes with `@param-closure-this`
* Fix `PendingRequest` typehints for `post`, `patch`, `put`, `delete`
* Add test for untested methods in LazyCollection
* fix indentation
* apply final Pint fixes
* Enhance validation tests: Add test for connection name detection in Unique rule
* Add json:unicode cast to support JSON_UNESCAPED_UNICODE encoding
* Add “Storage Linked” to the `about` command
* Add support for native JSON/JSONB column types in SQLite Schema builder
* Fix `LogManager::configurationFor()` typehint
* Add missing tests for LazyCollection methods
* Refactor: Structural improvement for clarity
* Improve `toKilobytes` to handle spaces and case-insensitive units
* Fix mistake in `asJson` call in `HasAttributes.php` that was recently introduced
* reapply Pint style changes
* Add validation test for forEach with null and empty array values
* Types: EnumeratesValues Sum
* Ensure Consistent Formatting in Generated Invokable Classes
* Add element type to return array in Filesystem
* Add support for PostgreSQL "unique nulls not distinct"
* standardize multiline ternaries
* improved readability for `aliasedPivotColumns`
* remove progress bar from PHPStan output
* Fixes how the fluent Date rule builder handles `date_format`
* Adding SSL encryption and support for MySQL connection
* Revert "Adding SSL encryption and support for MySQL connection"
* Ensure queue property is nullable
* return `$this` for chaining
* prefer `new Collection` over `collect()`
* use "class-string" type for `using` pivot model
* multiline chaining on Collections

## v12.4.0
* Reset PHP’s peak memory usage when resetting scope for queue worker
* Add `AsHtmlString` cast
* Add `Arr::sole()` method
* Improve warning message in `ApiInstallCommand`
* use already determined `related` property
* use "class-string" where appropriate in relations
* `QueueFake::listenersPushed()`
* Added except() method to Model class for excluding attributes
* fix: add TPivotModel default and define pivot property in {Belongs,Morph}ToMany
* remove `@return` docblocks on constructors
* Add NamedScope attribute
* Improve syntax highlighting for stub type files
* Prefer `new Collection` over `Collection::make`
* Fix except() method to support casted values
* Add testcase for findSole method
* Types: PasswordBroker::reset
* assertThrowsNothing
* Fix type nullability on PasswordBroker.events property
* Fix return type annotation in decrementPendingJobs method
* Fix return type annotation in compile method
* feat: Add `whereNull` and `whereNotNull` to `Assertablejson`
* fix: use contextual bindings in class dependency resolution
* Better return types for `Illuminate\Queue\Jobs\Job::getJobId()` and `Illuminate\Queue\Jobs\DatabaseJob::getJobId()` methods
* Remove remaining @return tags from constructors
* Various URL generation bugfixes
* Add an optional `shouldRun` method to migrations.
* `Uri` prevent empty query string
* Only call the ob_flush function if there is active buffer in eventStream
* Add CacheFlushed Event
* Update DateFactory method annotations for Carbon v3 compatibility
* Improve docblocks for file related methods of InteractsWithInput
* Enhance `FileViewFinder` doc-blocks
* Support using null-safe operator with `null` value
* Fix: Make Paginated Queries Consistent Across Pages
* Add `pipe` method query builders
* fix: one of many subquery constraints
* fix(postgres): missing parentheses in whereDate/whereTime for json columns
* Fix factory creation through attributes
* Fix Concurrency::run to preserve callback result order
* Log: Add optional keys parameter to `Log::withoutContext` to remove selected context from future logs
* Add `Expression` type to param `$value` of `QueryBuilder` `having()` method
* Add flag to disable where clauses for `withAttributes` method on Eloquent Builder

## v12.4.1
* Add `Expression` type to param `$value` of `QueryBuilder` `orHaving()` method
* Fix URL generation with optional parameters (regression in #54811)
* Fix failing tests on windows OS

## v12.5.0
* Correct misspellings
* Add ability to flush state on Vite helper
* Support taggeable store flushed cache events
* Revert "[12.x] Support taggeable store flushed cache events"
* Allow configuration of retry period for RoundRobin and Failover mail transports
* Add --json option to EventListCommand

## v12.6.0
* Dont stop pruning if pruning one model fails
* Update Date Facade Docblocks
* Make `db:seed` command prohibitable
* Introducing `Rules\Password::appliedRules` Method
* Allowing merging model attributes before insert via `Model::fillAndInsert()`
* Fix type hints for DateTimeZone and DateTimeInterface on DateFactory
* Fix DateFactory docblock type hints
* List missing `migrate:rollback` in DB::prohibitDestructiveCommands PhpDoc
* Add `Http::requestException()`
* New: Uri `pathSegments()` helper method
* Do not require returning a Builder instance from a local scope method

## v12.7.0
* `AbstractPaginator` should implement `CanBeEscapedWhenCastToString`
* Add `whereAttachedTo()` Eloquent builder method
* Make Illuminate\Support\Uri Macroable
* Add resource helper functions to Model/Collections
* : Use char(36) for uuid type on MariaDB < 10.7.0
* Introducing `toArray` to `ComponentAttributeBag` class

## v12.7.1


## v12.7.2


## v12.8.0
* only check for soft deletes once when mass-pruning
* Add createMany mass-assignment variants to `HasOneOrMany` relation
* cosmetic: include is_array() case in match construct of getArrayableItems
* Add tests for InvokeSerializedClosureCommand
* Temporarily prevents PHPUnit 12.1
* Test Improvements
* Bump vite from 5.4.12 to 5.4.17 in /src/Illuminate/Foundation/resources/exceptions/renderer
* Test Improvements
* add generics to array types for Schema Grammars
* fix missing nullable for Query/Grammar::compileInsertGetId
* Adds `fromJson()` to Collection
* Fix `illuminate/database` usage as standalone package
* Correct array key in InteractsWithInput
* Fix support for adding custom observable events from traits
* Added Automatic Relation Loading (Eager Loading) Feature
* Modify PHPDoc for Collection::chunkWhile functions to support preserving keys
* Introduce Rule::anyOf() for Validating Against Multiple Rule Sets

## v12.8.1


## v12.9.0
* Add types to ViewErrorBag
* Add types to MessageBag
* add generics to commonly used methods in Schema/Builder
* Return frozen time for easier testing
* Enhance DetectsLostConnections to Support AWS Aurora Credential Rotation Scenario
* Rename test method of failedRequest()
* feat: Add a callback to be called on transaction failure
* Add withRelationshipAutoloading method to model
* Enable HTTP client retries when middleware throws an exception
* Fix Closure serialization error in automatic relation loading
* Add test for Unique validation rule with WhereIn constraints
* Add @throws in doc-blocks
* Update `propagateRelationAutoloadCallbackToRelation` method doc-block
* - Redis - Establish connection first, before set the options
* Fix translation FileLoader overrides with a missing key
* Fix pivot model events not working when using the `withPivotValue`
* Introduce memoized cache driver
* Add test for Filesystem::lastModified() method
* Supports `pda/pheanstalk` 7
* Add comprehensive filesystem operation tests to FilesystemTest
* Bump vite from 5.4.17 to 5.4.18 in /src/Illuminate/Foundation/resources/exceptions/renderer
* Add descriptive error messages to assertViewHas()
* Use Generic Types Annotations for LazyCollection Methods
* Add test coverage for Process sequence with multiple env variables
* Fix cc/bcc/replyTo address merging in `MailMessage`
* Add a `make` function in the `Fluent`

## v12.9.1
* Forward only passed arguments into Illuminate\Database\Eloquent\Collection::partition method
* Add test for complex context manipulation in Logger
* Remove unused var from `DumpCommand`
* Fix the serve command sometimes fails to destructure the request pool array
* Changes to `package-lock.json` should trigger `npm run build`

## v12.9.2
* Fixed a bug in using `illuminate/console` in external apps
* Disable SQLServer 2017 CI as `ubuntu-20.24` has been removed

## v12.10.0
* Use value() helper in 'when' method
* Test `@use` directive without quotes
* Enhance Broadcast Events Test Coverage
* Add `Conditionable` Trait to `Fluent`
* Fix relation auto loading with manually set relations
* Add missing types to RateLimiter
* Fix for global autoload relationships not working  in certain cases
* Fix adding `setTags` method on new cache flush events
* Fix: Unique lock not being released after transaction rollback in ShouldBeUnique jobs with afterCommit()
* Extends `AsCollection` to map items into objects or other values
* Fix group imports in Blade `@use` directive
* chore(tests): align test names with idiomatic naming style
* Update compiled views only if they actually changed
* Improve performance of Arr::dot method - 300x in some cases
* Add tests for `CacheBasedSessionHandler`
* Add tests for `FileSessionHandler`
* Add tests for `DatabaseSessionHandler`
* Fix many to many detach without IDs broken with custom pivot class
* Support nested relations on `relationLoaded` method
* Bugfix for Cache::memo()->many() returning the wrong value with an integer key type
* Allow Container to build `Migrator` from class name

## v12.10.1
* Revert "Use value() helper in 'when' method to simplify code" #55465
* Use xxh128 when comparing views for changes
* Ensure related models is iterable on `HasRelationships@relationLoaded()`
* Add Enum support for assertJsonPath in AssertableJsonString.php

## v12.10.2
* Address Model@relationLoaded when relation is null

## v12.11.0
* Add payload creation and original delay info to job payload
* Add config option to ignore view cache timestamps
* Dispatch NotificationFailed when sending fails
* Option to disable dispatchAfterResponse in a test
* Pass flags to custom Json::$encoder
* Use pendingAttributes of relationships when creating relationship models via model factories
* Fix double query in model relation serialization
* Improve circular relation check in Automatic Relation Loading
* Prevent relation autoload context from being serialized
* Remove `@internal` Annotation from `$components` Property in `InteractsWithIO`
* Ensure fake job implements job contract
* Fix `AnyOf` constructor parameter type
* Sync changes to Illuminate components before release
* Set class-string generics on `Enum` rule
* added detailed doc types to bindings related methods
* Improve `@use` directive to support function and const modifiers
* 12.x scheduled task failed not dispatched on scheduled task failing
* Introduce Reflector methods for accessing class attributes
* Typed getters for Arr helper

## v12.11.1
* Revert "[12.x]`ScheduledTaskFailed` not dispatched on scheduled task failing"
* Resolve issue with BelongsToManyRelationship factory

## v12.12.0
* Make Blueprint Resolver Statically
* Allow limiting number of assets to preload
* Set job instance on "failed" command instance

## v12.13.0
* fix no arguments return type in request class
* Add support for callback evaluation in containsOneItem method
* add generics to aggregate related methods and properties
* Fix typo in PHPDoc
* Allow naming queued closures
* Add `assertRedirectBack` assertion method
* Typehints for bindings
* add PHP Doc types to arrays for methods in Database\Grammar
* fix trim null arg deprecation
* Support predis/predis 3.x
* Bump vite from 5.4.18 to 5.4.19 in /src/Illuminate/Foundation/resources/exceptions/renderer
* Fix predis versions
* Bump minimum league/commonmark
* Fix typo in MemoizedStoreTest
* Queue event listeners with enum values
* Implement releaseAfter method in RateLimited middleware
* Improve Cache Tests
* Only pass model IDs to Eloquent `whereAttachedTo` method
* feat(bus): allow adding multiple jobs to chain
* add generics to QueryBuilder’s column related methods

## v12.14.0
* Support `useCurrent` on date and year column types
* Update "Number::fileSize" to use correct prefix and add prefix param
* Update PHPDoc for whereRaw to allow Expression as $sql
* Revert "[12.x] Make Blueprint Resolver Statically"
* Support Virtual Properties When Serializing Models
* [12.X] Fix `Http::preventStrayRequests` error propagation when using `Http::pool`
* incorrect use of generics in Schema\Builder
* Add option to disable MySQL ssl when restoring or squashing migrations
* Add `except` and `exceptHidden` methods to `Context` class
* Container `currentlyResolving` utility
* Container `currentlyResolving` test
* Fix handling of default values for route parameters with a binding field
* Move Timebox for Authentication and add to password resets
* perf: Optimize BladeCompiler
* perf: support iterables for event discovery paths
* Types: AuthorizesRequests::resourceAbilityMap
* Add flexible support to memoized cache store
* Introduce Arr::from()
* Fix the `getCurrentlyAttachedPivots` wrong `morphClass` for morph to many relationships
* Improve typehints for Http classes
* Add deleteWhen for throttle exceptions job middleware

## v12.14.1
* [10.x] Refine error messages for detecting lost connections (Debian bookworm compatibility)
* [10.x] Bump minimum `league/commonmark`
* [10.x] Backport 11.x PHP 8.4 fix for str_getcsv deprecation
* [10.x] Fix attribute name used on `Validator` instance within certain rule classes
* Add `Illuminate\Support\EncodedHtmlString`
* [11.x] Fix missing `return $this` for `assertOnlyJsonValidationErrors`
* [11.x] Fix `Illuminate\Support\EncodedHtmlString` from causing breaking change
* [11.x] Respect custom path for cached views by the `AboutCommand`
* [11.x] Include all invisible characters in Str::trim
* [11.x] Test Improvements
* [11.x] Remove incorrect syntax from mail's `message` template
* [11.x] Allows to toggle markdown email encoding
* [11.x] Fix `EncodedHtmlString` to ignore instance of `HtmlString`
* [11.x] Test Improvements
* [11.x] Install Passport 13.x
* [11.x] Bump minimum league/commonmark
* Backporting Timebox fixes to 11.x
* Test SQLServer 2017 on Ubuntu 22.04
* [11.x] Fix Symfony 7.3 deprecations
* Easily implement broadcasting in a React/Vue Typescript app (Starter Kits)

## v12.15.0
* Add locale-aware number parsing methods to Number class
* Add a default option when retrieving an enum from data
* Revert "[12.x] Update "Number::fileSize" to use correct prefix and add prefix param"
* Remove apc
* Add param type for `assertJsonStructure` & `assertExactJsonStructure` methods
* Fix type casting for environment variables in config files
* Preserve "previous" model state
* Passthru `getCountForPagination` on an Eloquent\Builder
* Add `assertClientError` method to `TestResponse`
* Install Broadcasting Command Fix for Livewire Starter Kit
* Clarify units for benchmark value for IDE accessibility
* Improved PHPDoc Return Types for Eloquent's Original Attribute Methods
* Prevent `preventsLazyLoading` exception when using `automaticallyEagerLoadRelationships`
* Add `hash` string helper
* Update `assertSessionMissing()` signature to match `assertSessionHas()`
* Fix: php artisan db command if no password
* Types: InteractsWithPivotTable::sync
* feat: Add `current_page_url` to Paginator
* Correct return type in PhpDoc for command fail method
* Add `assertRedirectToAction` method to test redirection to controller actions
* Add Context contextual attribute

## v12.16.0
* Change priority in optimize:clear
* Fix `TestResponse::assertSessionMissing()` when given an array of keys
* Allowing `Context` Attribute to Interact with Hidden
* Add support for sending raw (non-encoded) attachments in Resend mail driver
* Added option to always defer for flexible cache
* style: Use null coalescing assignment (??=) for cleaner code
* Introducing `Arr::hasAll`
* Restore lazy loading check
* Minor language update
* fix(cache/redis): use connectionAwareSerialize in RedisStore::putMany()
* Fix `ResponseFactory` should also accept `null` callback
* Add template variables to scope
* Introducing `toUri` to the `Stringable` Class
* Remove remaining @return tags from constructors
* Replace alias `is_integer()` with `is_int()` to comply with Laravel Pint
* Fix argument types for Illuminate/Database/Query/Builder::upsert()
* Add `in_array_keys` validation rule to check for presence of specified array keys
* Add `Rule::contains`

## v12.17.0
* [11.x] Backport `TestResponse::assertRedirectBack`
* Add support for sending raw (non-encoded) attachments in Resend mail
* chore: return Collection from timestamps methods
* fix: fully qualify collection return type
* Fix Blade nested default component resolution for custom namespaces
* Fix return types in console command handlers to void
* Ability to perform higher order static calls on collection items
* Adds Resource helpers to cursor paginator
* Add reorderDesc() to Query Builder
* [11.x] Fixes Symfony Console 7.3 deprecations on closure command
* Add `AsUri` model cast
* feat: Add Contextual Implementation/Interface Binding via PHP8 Attribute
* Add tests for the `AuthenticateSession` Middleware
* Allow brick/math ^0.13
* fix: Factory::state and ::prependState generics

## v12.18.0
* document `through()` method in interfaces to fix IDE warnings
* Add encrypt and decrypt Str helper methods
* Add a command option for making batchable jobs
* fix: intersect Authenticatable with Model in UserProvider phpdocs
* feat: create UsePolicy attribute
* `ScheduledTaskFailed` not dispatched on scheduled forground task fails
* Add generics to `Model::unguarded()`
* Fix SSL Certificate and Connection Errors Leaking as Guzzle Exceptions
* Fix deprecation warning in PHP 8.3 by ensuring string type in explode()
* revert: #55939
* feat: Add WorkerStarting event when worker daemon starts
* Allow setting the `RequestException` truncation limit per request
* feat: Make custom eloquent castings comparable for more granular isDirty check
* fix alphabetical order
* Use native named parameter instead of unused variable
* add generics to Model attribute related methods and properties
* Supports PHPUnit 12.2
* feat: Add ability to override SendQueuedNotifications job class
* Fix timezone validation test for PHP 8.3+
* Broadcasting Utilities
* Remove unused $guarded parameter from testChannelNameNormalization method
* Validate that `outOf` is greater than 0 in `Lottery` helper
* Allow retrieving all reported exceptions from `ExceptionHandlerFake`

## v12.19.0
* [11.x] Fix validation to not throw incompatible validation exception
* Correct testEncryptAndDecrypt to properly test new methods
* Check if file exists before trying to delete it
* Clear cast caches when discarding changes
* Handle Null Check in Str::contains
* Remove call to deprecated `getDefaultDescription` method
* Bump brace-expansion from 2.0.1 to 2.0.2 in /src/Illuminate/Foundation/resources/exceptions/renderer
* Enhance error handling in PendingRequest to convert TooManyRedirectsE…
* fix: remove Model intersection from UserProvider contract
* Remove the only @return tag left on a constructor
* Introduce `ComputesOnceableHashInterface`
* Add assertRedirectBackWithErrors to TestResponse
* collapseWithKeys - Prevent exception in base case
* Standardize size() behavior and add extended queue metrics support
* [11.x] Fix `symfony/console:7.4` compatibility
* Improve constructor PHPDoc for controller middleware definition
* Remove `@return` tags from constructors
* sort helper functions in alphabetic order
* add Attachment::fromUploadedFile method
* : Add UseEloquentBuilder attribute to register custom Eloquent Builder
* Improve PHPDoc for the Illuminate\Cache folder files
* Add a new model cast named asFluent
* Introduce `FailOnException` job middleware
* isSoftDeletable(), isPrunable(), and isMassPrunable() to model class

## v12.19.1
* Revert "[12.x] Check if file exists before trying to delete it"

## v12.19.2


## v12.19.3
* Fix model pruning when non model files are in the same directory

