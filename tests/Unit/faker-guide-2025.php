<?php

use Faker\Factory;

test('ロケールを変えて世界旅行', function () {
    $faker = Factory::create('fr_FR');
    expect($faker->country())->toBeString();
    
    $fakerJP = Factory::create('ja_JP');
    expect($fakerJP->prefecture())->toBeString();
    expect($fakerJP->phoneNumber())->toMatch('/^0\d{1,4}-?\d{1,4}-?\d{4}$/');
});

test('標準プロバイダ逆引きカタログ', function () {
    $faker = Factory::create('ja_JP');
    
    expect($faker->name())->toBeString();
    expect($faker->firstName())->toBeString();
    expect($faker->lastName())->toBeString();
    
    expect($faker->postcode())->toMatch('/^\d{3}-?\d{4}$/');
    expect($faker->prefecture())->toBeString();
    
    expect($faker->safeEmail())->toContain('@example.');
    expect($faker->ipv4())->toMatch('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/');
    expect($faker->url())->toStartWith('http');
    
    expect($faker->sentence())->toEndWith('.');
    expect($faker->paragraph())->toBeString();
    expect(strlen($faker->text(200)))->toBeLessThanOrEqual(200);
    
    expect($faker->date('Y-m-d'))->toMatch('/^\d{4}-\d{2}-\d{2}$/');
    expect($faker->dateTimeBetween('-1 year', 'now'))->toBeInstanceOf(DateTime::class);
    
    expect($faker->numberBetween(1, 100))->toBeBetween(1, 100);
    expect($faker->randomFloat(2, 0, 999.99))->toBeBetween(0, 999.99);
});

test('日本語住所・電話番号のリアリティ爆盛り', function () {
    $faker = Factory::create('ja_JP');
    $title = $faker->prefecture() . $faker->city() . $faker->streetAddress();
    
    expect($title)->toBeString();
    expect($title)->toMatch('/[都府県道]/u');
    
    $trimmed = mb_substr($title, 0, 30);
    expect(mb_strlen($trimmed))->toBeLessThanOrEqual(30);
});

test('Custom Provider で独自ダミー', function () {
    $faker = Factory::create();
    
    class MedicalProvider extends \Faker\Provider\Base
    {
        protected static $diseases = ['インフルエンザ', '糖尿病', '高血圧'];

        public function disease(): string
        {
            return static::randomElement(self::$diseases);
        }
    }
    
    $faker->addProvider(new MedicalProvider($faker));
    expect($faker->disease())->toBeIn(['インフルエンザ', '糖尿病', '高血圧']);
});

test('unique(), optional(), randomElement() の使い分け', function () {
    $faker = Factory::create();
    
    $emails = [];
    for ($i = 0; $i < 10; $i++) {
        $emails[] = $faker->unique()->safeEmail();
    }
    expect(count(array_unique($emails)))->toBe(10);
    
    $faker->unique(false);
    
    $values = [];
    for ($i = 0; $i < 100; $i++) {
        $values[] = $faker->optional(0.3)->randomElement(['draft', 'published']);
    }
    $nullCount = count(array_filter($values, fn($v) => $v === null));
    expect($nullCount)->toBeGreaterThan(5);
    expect($nullCount)->toBeLessThan(95);
});

test('シード値固定でテストを再現可能に', function () {
    $faker1 = Factory::create();
    $faker1->seed(12345);
    $name1 = $faker1->name();
    $email1 = $faker1->email();
    
    $faker2 = Factory::create();
    $faker2->seed(12345);
    $name2 = $faker2->name();
    $email2 = $faker2->email();
    
    expect($name1)->toBe($name2);
    expect($email1)->toBe($email2);
});

test('よくある落とし穴 - unique() が枯渇', function () {
    $faker = Factory::create();
    
    expect(function () use ($faker) {
        for ($i = 0; $i < 1000; $i++) {
            $faker->unique()->numberBetween(1, 10);
        }
    })->toThrow(\OverflowException::class);
});

test('よくある落とし穴 - ja_JP の住所が長い', function () {
    $faker = Factory::create('ja_JP');
    $address = $faker->address();
    
    expect(mb_strlen($address))->toBeGreaterThan(10);
    
    $trimmed = mb_strimwidth($address, 0, 50, '...');
    expect(mb_strlen($trimmed))->toBeLessThanOrEqual(50);
});

test('よくある落とし穴 - ロケールごちゃ混ぜ', function () {
    $fakerJP = Factory::create('ja_JP');
    $fakerUS = Factory::create('en_US');
    
    $nameJP = $fakerJP->name();
    
    $nameUS = $fakerUS->name();
    
    expect($nameJP)->toMatch('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}]/u');
    expect($nameUS)->toMatch('/^[a-zA-Z\s\.]+$/');
});