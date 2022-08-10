<?php

    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;

    uses(RefreshDatabase::class);

    beforeEach(function () {
        //User::truncate();
        for ($i = 0; $i < 2; $i++) {
            User::factory()->create();
        }
    });

    it('it doesn\'t have specific user', function () {
        expect(User::all())->toBeCollection();
    });
