<?php


use App\Models\Project;
use App\Models\Member;
use Illuminate\Support\Facades\DB;


test('member can be assigned to a project with metadata', function () {
    // 中間テーブルに pivot 情報付きで attach できるかを検証
    
    // 準備
    $project = Project::create(['name' => 'Mercury']);
    $member = Member::create(['name' => 'Kohaku']);

    // 実行
    $project->members()->attach($member->id, [
        'assigned_by' => 'Boss',
        'assigned_at' => now(),
        'role' => 'Lead',
    ]);

    // DBのデータを直接検証することもできるよ！
    
    // 検証
    $this->assertDatabaseHas('member_project', [
        'project_id' => $project->id,
        'member_id' => $member->id,
        'assigned_by' => 'Boss',
        'role' => 'Lead',
    ]);
});

test('syncWithoutDetaching does not duplicate member_project row', function () {
    // syncWithoutDetaching で中間テーブルに pivot 情報付きで attach できるかを検証 (1レコードのみ追加されることを検証)
    
    // 準備
    $project = Project::create(['name' => 'Venus']);
    $member = Member::create(['name' => 'Yuzu']);

    // 実行
    $project->members()->syncWithoutDetaching([
        $member->id => [
            'assigned_by' => 'Otwell',
            'assigned_at' => now(),
            'role' => 'Engineer',
        ]
    ]);

    $project->members()->syncWithoutDetaching([
        $member->id => [
            'assigned_by' => 'Otwell',
            'assigned_at' => now(),
            'role' => 'Engineer',
        ]
    ]);

    // 検証
    $this->assertDatabaseCount('member_project', 1);
});

test('plain relation does not expose pivot metadata', function () {
    // withPivot を定義していないリレーションでは pivot 情報が null になることを確認
    
    // 準備
    $project = Project::create(['name' => 'Split Test']);
    $member = Member::create(['name' => 'Shion']);

    // 実行
    $project->plainMembers()->attach($member->id, [
        'role' => 'Intern',
        'assigned_by' => 'Admin',
        'assigned_at' => now(),
    ]);
    
    // 検証
    $fetchedMember = $project->plainMembers()->first();
    expect($fetchedMember->pivot->assigned_by)->toBeNull(); // assigned_by 登録してるのにNullになってるよ！？
    expect($fetchedMember->pivot->role)->toBeNull(); // role 登録してるのにNullになってるよ！？
});

// --- ソフトデリートは地獄 ---

test('member can be detached from project', function () {
    // given
    $project = Project::create(['name' => 'Mars']);
    $member = Member::create(['name' => 'Akira']);

    // when
    $project->members()->attach($member->id);
    $project->members()->detach($member->id);

    // ここでdeleted_atがnullのものを探すことの意味を問いましょう
    // then
    $exists = DB::table('member_project')->where([
        'project_id' => $project->id,
        'member_id' => $member->id,
    ])->whereNull('deleted_at')->exists();
    expect($exists)->toBeFalse();
});


test('sync replaces all pivot rows with the given set', function () {
    // sync() の挙動として、指定されていないpivot行が削除されるかを検証
    // 3人のメンバーを持つプロジェクトを作成  
    $project = Project::create(['name' => 'Destruction Test']);

    $member1 = Member::create(['name' => 'Alpha']);
    $member2 = Member::create(['name' => 'Bravo']);
    $member3 = Member::create(['name' => 'Charlie']);

    // 初期状態：3人を追加
    $project->members()->sync([
        $member1->id => ['role' => 'Engineer'],
        $member2->id => ['role' => 'Manager'],
        $member3->id => ['role' => 'Lead'],
    ]);

    expect(DB::table('member_project')->count())->toBe(3);

    // syncで1人だけにしたら、他の2人が全削除される
    $project->members()->sync([
        $member2->id => ['role' => 'Manager'], // Bravoだけ残す
    ]);

    // 論理削除だと、Prodコードでもこれやらないといけないのでまぁまぁだるい（運用コストと相談すること）
    $alive = $project->members->filter(fn ($m) => $m->pivot->deleted_at === null);

    expect($alive->count())->toBe(1)
        ->and($alive->first()->pivot->role)->toBe('Manager')
        ->and($alive->first()->id)->toBe($member2->id);
});

test('syncWithoutDetaching replaces all pivot rows with the given set', function () {
    // syncWithoutDetaching() の挙動として、指定されていないpivot行が削除されないかを検証
    // given
    $project = Project::create(['name' => 'Destruction Test']);

    $member1 = Member::create(['name' => 'Alpha']);
    $member2 = Member::create(['name' => 'Bravo']);
    $member3 = Member::create(['name' => 'Charlie']);

    $project->members()->sync([
        $member1->id => ['role' => 'Engineer'],
        $member2->id => ['role' => 'Manager'],
        $member3->id => ['role' => 'Lead'],
    ]);

    expect(DB::table('member_project')->count())->toBe(3);

    // when
    $project->members()->syncWithoutDetaching([
        $member2->id => ['role' => null],
    ]);
    
    // then
    // 変化なしだけど、論理削除のため、有効レコード（NULLかどうか）を確認する必要がある
    $alive = $project->members->filter(fn ($m) => $m->pivot->deleted_at === null);
    expect($alive->count())->toBe(3)
        ->and($alive->get(1)->pivot->role)->toBe(null)
        ->and($alive->get(1)->id)->toBe($member2->id)
        ->and($alive->get(1)->name)->toBe('Bravo');
});
