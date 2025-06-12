<?php

use Illuminate\Support\Facades\Log;

if (!class_exists('User')) { // Mock User class for testing
    class User {
        public function save() {}
    }
}

describe('Collection::each の挙動検証', function () {
    it('does not break each with return', function () {
        $output = [];

        collect([1, 2, 3])->each(function ($item) use (&$output) {
            if ($item === 2) {
                return;
            }
            $output[] = $item;
        });

        // 想定出力: [1, 3]
        expect($output)->toBe([1, 3]);
    });
});

it('can break with foreach but not with each', function () {
    $items = collect([1, 2, 3, 4]);
    $output = [];

    foreach ($items as $item) {
        if ($item === 3) {
            break;
        }
        $output[] = $item;
    }

    // 想定出力: [1, 2]
    expect($output)->toBe([1, 2]);
});

it('throws and halts on save failure with each', function () {
    $users = collect([
        Mockery::mock(User::class)->shouldReceive('save')->andThrow(new Exception('save failed'))->getMock(),
        Mockery::mock(User::class)->shouldNotReceive('save')->getMock(),
    ]);

    expect(function () use ($users) {
        $users->each(fn($u) => $u->save());
    })->toThrow(Exception::class);
});

it('logs error and continues when using try-catch inside each', function () {
    Log::shouldReceive('error')->once()->with('保存失敗: 1');

    $users = collect([
        Mockery::mock(User::class)->shouldReceive('save')->andThrow(new Exception)->getMock(),
        Mockery::mock(User::class)->shouldReceive('save')->once()->getMock(),
    ]);

    $users->each(function ($user, $index) {
        try {
            $user->save();
        } catch (\Throwable $e) {
            Log::error("保存失敗: " . ($index + 1));
        }
    });

    expect(true)->toBeTrue();
});



it('maps collection to transformed values without side effects', function () {
    $users = collect([
        ['name' => 'Alice'],
        ['name' => 'Bob'],
    ]);

    $names = $users->map(fn($user) => $user['name']);

    expect($names->all())->toBe(['Alice', 'Bob']);
});