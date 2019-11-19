<?php

use Illuminate\Database\Seeder;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Core\Models\User;

/**
 * Class AccountsTableSeeder
 * @package Partymeister\Accounting\Database\Seeds
 */
class TransitionsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transitions = [
            [
                'name'             => 'Random',
                'identifier'       => 255,
                'default_duration' => 2000,
            ],
            [
                'name'             => 'Crossfade',
                'identifier'       => 0,
                'default_duration' => 2000,
            ],
            [
                'name'             => 'Glitch',
                'identifier'       => 1,
                'default_duration' => 2000,
            ],
            [
                'name'             => 'Revision Logo',
                'identifier'       => 2,
                'default_duration' => 2000,
            ],
            [
                'name'             => 'Turn page',
                'identifier'       => 3,
                'default_duration' => 2000,
            ],
            [
                'name'             => 'Crash zoom',
                'identifier'       => 4,
                'default_duration' => 2000,
            ],
            [
                'name'             => 'Cube world',
                'identifier'       => 5,
                'default_duration' => 2000,
            ],
            [
                'name'             => 'Revision 2018 NO TRANSITION',
                'identifier'       => 6,
                'default_duration' => 2000,
            ],
        ];

        foreach ($transitions as $transition) {
            DB::table('transitions')->insert([
                'name'             => $transition[ 'name' ],
                'identifier'       => $transition[ 'identifier' ],
                'default_duration' => $transition[ 'default_duration' ],
                'created_by'       => User::get()->first()->id,
                'updated_by'       => User::get()->first()->id,
            ]);
        }
    }
}
