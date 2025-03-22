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
        'role-search',
        'role-create',
        'role-edit',
        'role-delete',
        'user-list',
        'user-show',
        'user-search',
        'user-create',
        'user-edit',
        'user-delete',
        'permission-list',
        'permission-show',
        'permission-search',
        'permission-create',
        'permission-edit',
        'permission-delete',
        'remate-list',
        'remate-logros',
        'remate-show',
        'remate-search',
        'remate-create',
        'remate-edit',
        'remate-delete',
        'race-list',
        'race-show',
        'race-search',
        'race-create',
        'race-edit',
        'race-delete',
        'parametro-list',
        'parametro-show',
        'parametro-search',
        'parametro-create',
        'parametro-edit',
        'parametro-delete',
        'bitacora-list',
        'bitacora-search',
        'bitacora-pdf'


        ];

        foreach ($permissions as $permission) {
        Permission::create(['name' => $permission]);
        }
        }
        }

