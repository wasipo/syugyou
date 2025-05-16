<?php

test('map + filter + values to transform and filter objects', function () {
    $users = collect([
        (object)['id' => 1, 'name' => 'kai', 'active' => true],
        (object)['id' => 2, 'name' => 'rei', 'active' => false],
        (object)['id' => 3, 'name' => 'asuka', 'active' => true],
    ]);

    $names = $users
        ->filter(fn ($u) => $u->active)
        ->map(fn ($u) => $u->name)
        ->values();

    dump($names->all());

    expect($names->all())->toEqual(['kai', 'asuka']);
});

test('intersectByKeys + mapWithKeys for filtered keyed output', function () {
    $allowedIds = collect([
        2 => true,
        3 => true,
    ]);

    $users = collect([
        1 => (object)['id' => 1, 'name' => 'kai'],
        2 => (object)['id' => 2, 'name' => 'rei'],
        3 => (object)['id' => 3, 'name' => 'shinji'],
    ]);

    $filtered = $users
        ->intersectByKeys($allowedIds)
        ->mapWithKeys(fn($u) => [$u->id => (object)[
            'id' => $u->id,
            'name' => $u->name,
        ]]);
    
    dump($filtered->all());

    expect($filtered->keys()->all())->toEqual([2, 3]);
    expect($filtered[2]->name)->toBe('rei');
});


test('reduce to calculate total from object values', function () {
    $orders = collect([
        (object)['id' => 1, 'total' => 1200],
        (object)['id' => 2, 'total' => 800],
        (object)['id' => 3, 'total' => 1500],
    ]);

    $sum = $orders->reduce(fn($carry, $order) => $carry + $order->total, 0);

    expect($sum)->toBe(1200 + 800 + 1500);
});

test('groupBy + sortBy + keyBy to structure users by role', function () {
    $users = collect([
        (object)['id' => 1, 'name' => 'kai', 'role' => 'admin'],
        (object)['id' => 2, 'name' => 'rei', 'role' => 'user'],
        (object)['id' => 3, 'name' => 'asuka', 'role' => 'user'],
    ]);

    $grouped = $users
        ->groupBy('role')
        ->map(fn($group) => $group
            ->sortBy('name')
            ->keyBy('id')
        );

    dump($grouped->all());
    
    expect($grouped['user']->keys()->all())->toEqual([3, 2]); // asuka → rei
    expect($grouped['admin'][1]->name)->toBe('kai');
});


test('chunk splits collection into batches', function () {
    $users = collect(range(1, 25))->map(fn($i) => (object)['id' => $i]);

    $chunks = $users->chunk(10);

    expect($chunks)->toHaveCount(3);
    expect($chunks[0]->count())->toBe(10);
    expect($chunks[1]->count())->toBe(10);
    expect($chunks[2]->count())->toBe(5);
    expect($chunks[2]->pluck('id')->last())->toBe(25);

    // $[[0]=> []*10 , [1]=> []*10 , [2]=> []*5]となるので、戻す場合は以下

    $flattened = $chunks->flatten(1); // これで戻る
    expect($flattened)->toHaveCount(25);
    expect($flattened->first()->id)->toBe(1);
    expect($flattened->last()->id)->toBe(25);
});

test('partition splits collection by condition', function () {
    $users = collect([
        (object)['name' => 'kai', 'active' => true],
        (object)['name' => 'rei', 'active' => false],
        (object)['name' => 'asuka', 'active' => true],
    ]);

    [$active, $inactive] = $users->partition(fn($u) => $u->active);

    expect($active->pluck('name')->all())->toEqual(['kai', 'asuka']);
    expect($inactive->pluck('name')->all())->toEqual(['rei']);
});


test('partition truthy vs falsy', function () {
    $numbers = collect([1, 2, 3, 4, 5]);

    [$even, $odd] = $numbers->partition(fn($n) => $n % 2 === 0);

    expect($even->values()->all())->toEqual([2, 4]);
    expect($odd->values()->all())->toEqual([1, 3, 5]);
});