<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {

        User::create([
            'name' => 'AI',
            'username' => 'OLAF',
            'email' => 'OLAF@ostfalia.de', // todo! Mail-Adresse (Liste) anlegen und hier eintragen
            'employeetype' => 'AI',
            'publicKey' => '0',
            'avatar_id' => 'hawkiAvatar.jpg'
        ]);

    }
}
