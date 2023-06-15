<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
            // Super - root
            [
                'name' => 'Super admin',
                'email' => '5676797567',
                'password' => Hash::make('root_2023@+.')
            ],
            // Root
            [
                'name' => 'Admin',
                'email' => '5465468786',
                'password' => Hash::make('admin_alex_espana23')
            ],
            // // Apoyo metodológico
            // [
            //     'name' => 'Apoyo metodológico 1',
            //     'email' => '657568857',
            //     'password' => Hash::make('apoyometo1')
            // ],
            // // Apoyo al seguimiento y al monitoreo
            // [
            //     'name' => 'Apoyo al seguimiento y al monitoreo 1',
            //     'email' => '8567567567',
            //     'password' => Hash::make('apoyomeseg1')
            // ],
            // // Coordinador metodológico
            // [
            //     'name' => 'Coordinador metodológico',
            //     'email' => '546541787',
            //     'password' => Hash::make('coordinadormetodológico1')
            // ],
            // // Subdirección
            // [
            //     'name' => 'Subdirección',
            //     'email' => '514541787',
            //     'password' => Hash::make('subdireccion1')
            // ],
            // // Dirección
            // [
            //     'name' => 'Dirección',
            //     'email' => '546541788',
            //     'password' => Hash::make('cdireccion1')
            // ],
            // // Psicosocial
            // [
            //     'name' => 'Psicosocial 1',
            //     'email' => '2312312312',
            //     'password' => Hash::make('sico1')
            // ],
            // // Coordinador psicosocial
            // [
            //     'name' => 'Coordinador psicosocial',
            //     'email' => '514541720',
            //     'password' => Hash::make('coordinadorpsicosocial1')
            // ],
            // // Coordinador de Supervisión
            // [
            //     'name' => 'Coordinador de Supervisión',
            //     'email' => '546541710',
            //     'password' => Hash::make('coordinadorsupervision1')
            // ],
            // // Apoyo de supervisión
            // [
            //     'name' => 'Apoyo a la Supervisión',
            //     'email' => '546541712',
            //     'password' => Hash::make('apoyosupervision1')
            // ],
            // // Secretaria cultural
            // [
            //     'name' => 'Secretaria cultural',
            //     'email' => '512511720',
            //     'password' => Hash::make('secretariacultural1')
            // ],
            // // Gestor
            // [
            //     'name' => 'Gestor 1',
            //     'email' => '12123123',
            //     'password' => Hash::make('gestor1')
            // ],
            // // Monitor
            // [
            //     'name' => 'Monitor 1',
            //     'email' => '213123132',
            //     'password' => Hash::make('monitor1')
            // ],
            // // Embajador
            // [
            //     'name' => 'Embajador 1',
            //     'email' => '123456789',
            //     'password' => Hash::make('ambassador1')
            // ],
            // // Instructor
            // [
            //     'name' => 'Instructor1',
            //     'email' => '78543434',
            //     'password' => Hash::make('instructor1')
            // ],
            // // Lider Embajador
            // [
            //     'name' => 'Lider Embajador 1',
            //     'email' => '89789756',
            //     'password' => Hash::make('leaderambassador1')
            // ],
            // // Lider Instructor
            // [
            //     'name' => 'Lider Instructor1',
            //     'email' => '78565256',
            //     'password' => Hash::make('leaderinstructor1')
            // ],
            // // Coordinador de seguimiento
            // [
            //     'name' => 'Coordinador de seguimiento ',
            //     'email' => '541541786',
            //     'password' => Hash::make('coordinadorseguimiento1')
            // ],

            // // Lider Metodológico
            // [
            //     'name' => 'Lider Metodológico',
            //     'email' => '541501786',
            //     'password' => Hash::make('lidermetodologico1')
            // ],

            // // Coordinador Administrativo
            // [
            //     'name' => 'Coordinador Administrativo',
            //     'email' => '541501788',
            //     'password' => Hash::make('lideradmin1')
            // ],
        ]);
    }
}
