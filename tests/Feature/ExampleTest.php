<?php


use App\Models\MemberProject;
use App\Models\Project;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;


test('member can be manually attached to project', function () {
    $project = Project::create(['name' => 'Mercury']);
    $member = Member::create(['name' => 'Kohaku']);

    $project->members()->attach($member->id, [
        'assigned_by' => 'root',
        'assigned_at' => now(),
        'role' => 'architect',
    ]);

    $this->assertDatabaseHas('member_project', [
        'project_id' => $project->id,
        'member_id' => $member->id,
        'role' => 'architect',
    ]);
});


test('member can be assigned to a project with metadata', function () {
    $project = Project::create(['name' => 'Mercury']);
    $member = Member::create(['name' => 'Kohaku']);

    $project->members()->attach($member->id, [
        'assigned_by' => 'Boss',
        'assigned_at' => now(),
        'role' => 'Lead',
    ]);

    $this->assertDatabaseHas('member_project', [
        'project_id' => $project->id,
        'member_id' => $member->id,
        'assigned_by' => 'Boss',
        'role' => 'Lead',
    ]);
});

test('syncWithoutDetaching does not duplicate member_project row', function () {
    $project = Project::create(['name' => 'Venus']);
    $member = Member::create(['name' => 'Yuzu']);

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

    $this->assertDatabaseCount('member_project', 1);
});

test('soft deleted member_project relation can be restored', function () {
    $project = Project::create(['name' => 'Jupiter']);
    $member = Member::create(['name' => 'Tsubasa']);

    $project->members()->attach($member->id, [
        'assigned_by' => 'Dev Lead',
        'assigned_at' => now(),
        'role' => 'Designer',
    ]);

    $project->members()->updateExistingPivot($member->id, ['deleted_at' => now()]);

    $this->assertSoftDeleted('member_project', [
        'project_id' => $project->id,
        'member_id' => $member->id,
    ]);

    $project->members()->updateExistingPivot($member->id, ['deleted_at' => null]);
    
    $this->assertDatabaseHas('member_project', [
        'project_id' => $project->id,
        'member_id' => $member->id,
        'deleted_at' => null,
    ]);
});


test('pivot fields are available when withPivot is defined', function () {
    $project = Project::create(['name' => 'Voyager']);
    $member = Member::create(['name' => 'Otwell Jr.']);

    $project->members()->attach($member->id, [
        'assigned_by' => 'Zebra',
        'assigned_at' => now(),
        'role' => 'Explorer',
    ]);

    $fetchedMember = $project->members()->first();

    expect($fetchedMember->pivot->assigned_by)->toBe('Zebra');
});

test('sync preserves pivot metadata when explicitly passed', function () {
    $project = Project::create(['name' => 'Gemini']);
    $member = Member::create(['name' => 'Buzz']);

    $project->members()->sync([
        $member->id => [
            'assigned_by' => 'HQ',
            'assigned_at' => now(),
            'role' => 'Pilot',
        ]
    ]);

    $pivot = DB::table('member_project')->where([
        'project_id' => $project->id,
        'member_id' => $member->id,
    ])->first();

    expect($pivot->assigned_by)->toBe('HQ');
    expect($pivot->role)->toBe('Pilot');
});


// 論理削除ついてない場合
test('sync replaces all pivot rows with the given set', function () {
    
    //  3人のメンバーを持つプロジェクトを作成  
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

    $project->load('members'); // ← eager loadする（念のため）
    $alive = $project->members->filter(fn ($m) => $m->pivot->deleted_at === null);

    expect($alive->count())->toBe(1);
    expect($alive->first()->pivot->role)->toBe('Manager');
    expect($alive->first()->id)->toBe($member2->id);
});
