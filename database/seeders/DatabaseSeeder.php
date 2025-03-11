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
use App\Models\StatuOptions;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;

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
            'name' => 'Victor Lara',
            'email' => 'info@vgeneralcontractors.com',
            'username' => 'vgeneralcontractors',
            'password' => bcrypt('info01='),
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $adminUser->assignRole($adminRole);

        // SECOND ADMIN
        $adminUser2 = User::factory()->create([
            'name' => 'Argenis Gonzalez',
            'email' => 'josegonzalezcr2794@gmail.com',
            'username' => 'argenis692',
            'password' => bcrypt('argenis01='),
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $adminUser2->assignRole($adminRole);
        // END SECOND ADMIN
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
        'phone' => '+13466920757',
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

    
    // END COMPANY SIGNATURE

    // EMAIL DATA
    $emailsData = [
        [
            'description' => 'Correo para colecciones y pagos',
            'email' => 'collections@vgeneralcontractors.com',
            'phone' => '+17133646240',
            'type' => 'collections',
            'user_id' => $adminUser->id,
        ],
        [
            'description' => 'Correo para información general',
            'email' => 'info@vgeneralcontractors.com',
            'phone' => '+13466920757',
            'type' => 'info',
            'user_id' => $adminUser->id,
        ],
        [
            'description' => 'Correo para citas y agendamiento',
            'email' => 'appointment@vgeneralcontractors.com',
            'phone' => '+13466920757',
            'type' => 'appointment',
            'user_id' => $adminUser->id,
        ]
    ];

    foreach ($emailsData as $emailData) {
        $emailData['uuid'] = Uuid::uuid4()->toString();
        \App\Models\EmailData::create($emailData);
    }
    // END EMAIL DATA

    // Project Types
    $projectTypes = [
        [
            'name' => 'Roof Repair',
            'description' => 'Services for fixing damaged or worn roofing components.',
            'status' => 'active'
        ],
        [
            'name' => 'Roof Installation',
            'description' => 'Complete roof installation and replacement services.',
            'status' => 'active'
        ],
        [
            'name' => 'Roof Maintenance',
            'description' => 'Preventive maintenance and regular roof inspections.',
            'status' => 'active'
        ],
        [
            'name' => 'Mitigation',
            'description' => 'Water damage mitigation and prevention services.',
            'status' => 'active'
        ],
        [
            'name' => 'Tarp Installation',
            'description' => 'Emergency roof covering with tarp to prevent further damage.',
            'status' => 'active'
        ],
        [
            'name' => 'Retarp Service',
            'description' => 'Replacement or adjustment of existing roof tarps.',
            'status' => 'active'
        ],
        [
            'name' => 'Mold Remediation',
            'description' => 'Treatment and removal of mold caused by roof leaks.',
            'status' => 'active'
        ]
    ];

    foreach ($projectTypes as $type) {
        \App\Models\ProjectType::create([
            'uuid' => (string) Str::uuid(),
            'name' => $type['name'],
            'description' => $type['description'],
            'status' => $type['status']
        ]);
    }

    // Service Categories - Updated to match project types
    $serviceCategories = [
        [
            'name' => 'Roof Repair',
            'type' => 'Roof Repair',
            'description' => 'Professional roof repair services for all types of damage',
            'status' => 'active',
        ],
        [
            'name' => 'New Roof Installation',
            'type' => 'New Roof',
            'description' => 'Complete new roof installation services',
            'status' => 'active',
        ],
        [
            'name' => 'Storm Damage Repair',
            'type' => 'Storm Damage',
            'description' => 'Emergency storm damage repair and assessment',
            'status' => 'active',
        ],
        [
            'name' => 'Mold Remediation',
            'type' => 'Mold Remediation',
            'description' => 'Professional mold removal and remediation services',
            'status' => 'active',
        ],
        [
            'name' => 'Water Damage Mitigation',
            'type' => 'Mitigation',
            'description' => 'Quick response water damage mitigation services',
            'status' => 'active',
        ],
        [
            'name' => 'Tarp Services',
            'type' => 'Tarp',
            'description' => 'Initial emergency roof tarping protection',
            'status' => 'active',
        ],
        [
            'name' => 'ReTarp Services',
            'type' => 'ReTarp',
            'description' => 'Roof re-tarping and tarp maintenance services',
            'status' => 'active',
        ]
    ];

    foreach ($serviceCategories as $category) {
        ServiceCategory::create([
            'uuid' => (string) Str::uuid(),
            'name' => $category['name'],
            'slug' => Str::slug($category['name']),
            'type' => $category['type'],
            'description' => $category['description'],
            'status' => $category['status'],
            'user_id' => $adminUser->id,
        ]);
    }
    }
}