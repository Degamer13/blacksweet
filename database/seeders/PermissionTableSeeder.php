<?php

        namespace Database\Seeders;

        use Illuminate\Database\Console\Seeds\WithoutModelEvents;
        use Illuminate\Database\Seeder;
        use Spatie\Permission\Models\Permission;

        class PermissionTableSeeder extends Seeder
        {
        /**
        * Run the database seeds.
        *
        * @return void
        */
        public function run()
        {
        $permissions = [
        'show-admin',
        'role-list',
        'role-show',
        'role-create',
        'role-edit',
        'role-delete',
        'user-list',
        'user-show',
        'user-create',
        'user-edit',
        'user-delete',
        'permission-list',
        'permission-show',
        'permission-create',
        'permission-edit',
        'permission-delete',
        'remate-list',
        'remate-show',
        'remate-search',
        'remate-total',
        'remate-create',
        'remate-edit',
        'remate-delete',
        'remate-pdf',
        'race-list',
        'race-show',
        'race-create',
        'race-edit',
        'race-delete',
        'parametro-list',
        'parametro-show',
        'parametro-create',
        'parametro-edit',
        'parametro-delete',
        'porcentaje-show',
        'pote-show'


        ];

        foreach ($permissions as $permission) {
        Permission::create(['name' => $permission]);
        }
        }
        }

