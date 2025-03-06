<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\MainCategories;
use App\Models\BlogCategory;
use Ramsey\Uuid\Uuid;
use App\Models\CompanyData;





class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [
            Permission::create(['name' => 'Admin', 'guard_name' => 'web', 'uuid' => Uuid::uuid4()->toString()]),
            Permission::create(['name' => 'User', 'guard_name' => 'web', 'uuid' => Uuid::uuid4()->toString()]),
            Permission::create(['name' => 'Other', 'guard_name' => 'web', 'uuid' => Uuid::uuid4()->toString()]),
        ];

        // MANAGER ADMIN
        $adminRole = Role::create([
            'name' => 'Admin', 
            'guard_name' => 'web',
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $adminRole->syncPermissions($permissions);

        $adminUser = User::factory()->create([
            'name' => 'Victor  Lara',
            'email' => 'info@vgeneralcontractors.com',
            'username' => 'vgeneralcontractors',
            'password' => bcrypt('info01='),
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $adminUser->assignRole($adminRole);
        // END MANAGER ADMIN

        // MANAGER USER
        $userRole = Role::create([
            'name' => 'User', 
            'guard_name' => 'web',
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $userRole->syncPermissions([$permissions[1]]);

        $userUser = User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'username' => 'user01',
            'password' => bcrypt('user01='),
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $userUser->assignRole($userRole);
        // END MANAGER USER

        // OTHERS ROLES
        $othersRole = Role::create([
            'name' => 'Others', 
            'guard_name' => 'web',
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $othersRole->syncPermissions([$permissions[2]]);
        // END OTHERS ROLES

      // Crear la categoría "General" para blog
       BlogCategory::create([
            'uuid' => Uuid::uuid4()->toString(),
            'blog_category_name' => 'Roofing',
            'blog_category_description' => 'Valor por defecto',
            'blog_category_image' => 'Valor por defecto',
            'user_id' => 1,
            'status' => 'active',
        ]);
    // end      

    // COMPANY DATA
    $companyData = [
        'company_name' => 'V General Contractors',
        'signature_path' => '/signatures/acme_signature.png',
        'phone' => '+13466155393',
        'email' => 'info@vgeneralcontractors.com',
        'address' => '1302 Waugh Dr # 810 Houston TX 77019',
        'website' => 'https://vgeneralcontractors.com',
        'latitude' => 29.75516,
        'longitude' => -95.3984135,
        'user_id' => 1,
        'uuid' => Uuid::uuid4()->toString()
    ];

    CompanyData::create($companyData);
    // END COMPANY DATA

    // SIGNATURE COMPANY
    $companySignature = [
        [
            'company_name' => 'V General Contractors',
            'signature_path' => '/signatures/acme_signature.png',
            'phone' => '+13466920757',
            'email' => 'info@vgeneralcontractors.com',
            'address' => '1302 Waugh Dr # 810 Houston TX 77019',
            'website' => 'https://vgeneralcontractors.com',
            'latitude' => 29.75516,
            'longitude' => -95.3984135,
            'user_id' => 1, 
        ]
    ];

    foreach ($companySignature as $companyData) {
        $companyData['uuid'] = Uuid::uuid4()->toString();
        CompanyData::create($companyData);
    }
    // END COMPANY SIGNATURE

    // EMAIL DATA
    $emailsData = [
        [
            'description' => 'Correo para colecciones y pagos',
            'email' => 'collections@vgeneralcontractors.com',
            'phone' => '+13466155393',
            'type' => 'collections',
            'user_id' => $adminUser->id,
        ],
        [
            'description' => 'Correo para información general',
            'email' => 'info@vgeneralcontractors.com',
            'phone' => '+13466155393',
            'type' => 'info',
            'user_id' => $adminUser->id,
        ],
        [
            'description' => 'Correo para citas y agendamiento',
            'email' => 'appointment@vgeneralcontractors.com',
            'phone' => '+13466155393',
            'type' => 'appointment',
            'user_id' => $adminUser->id,
        ]
    ];

    foreach ($emailsData as $emailData) {
        $emailData['uuid'] = Uuid::uuid4()->toString();
        \App\Models\EmailData::create($emailData);
    }
    // END EMAIL DATA
    }
}