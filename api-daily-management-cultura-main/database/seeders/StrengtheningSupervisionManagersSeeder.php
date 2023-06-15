<?php

namespace Database\Seeders;

use App\Models\StrengtheningSupervisionManagers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StrengtheningSupervisionManagersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StrengtheningSupervisionManagers::create([
        'consecutive' => 'SGT1',
        'revision_date' => '2023-02-06',
        'address' => 'text',
        'methodological_instruction_reached_target'=> 1,
        'frequency'=> 3,
        'binnacle_registered_plataform'=> 3,
        'description'=> 'test1',
        'start_time'=> '13:09:00',
        'final_time'=> '14:09:00',
        'comments'=> 'test1',
        'development_activity_image'=> 'strengthening_super_mangs/1/63fd7087102d9.webp',
        'evidence_participation_image'=> 'strengthening_super_mangs/1/63fd7087102d9.webp',
        'nac_id'=> 2,
        'user_associate_id'=> 2,
        'methodological_instruction_id'=> 1,
        'created_by'=> 1,
        'super_coordinator_id'=> 10,
        'reject_message'=> 'ok ok',
        'status'=> 'ENREV',
        ]);
    }
}
