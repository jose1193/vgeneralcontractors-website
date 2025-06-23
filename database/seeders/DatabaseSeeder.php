<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\BlogCategory;
use Ramsey\Uuid\Uuid;
use App\Models\CompanyData;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear los roles principales
        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'uuid' => Uuid::uuid4()->toString()
        ]);
        
        $adminRole = Role::create([
            'name' => 'Admin',
            'uuid' => Uuid::uuid4()->toString()
        ]);
        
        $userRole = Role::create([
            'name' => 'User',
            'uuid' => Uuid::uuid4()->toString()
        ]);

        // Definir modelos para permisos
        $models = [
            'USER', 'EMAIL_DATA', 'SERVICE_CATEGORY', 'PORTFOLIO', 'COMPANY_DATA', 
            'PROJECT_TYPE', 'APPOINTMENT', 'BLOG_CATEGORY', 'POST', 'SEO', 'CALL_RECORD', 'MODEL_AI'
        ];

        // Definir acciones para permisos
        $actions = ['CREATE', 'READ', 'UPDATE', 'DELETE', 'RESTORE'];

        // Crear todos los permisos (UPPERCASE format)
        $allPermissions = [];
        foreach ($models as $model) {
            foreach ($actions as $action) {
                $permissionName = "{$action}_{$model}";
                Permission::create([
                    'name' => $permissionName,
                    'uuid' => Uuid::uuid4()->toString()
                ]);
                $allPermissions[] = $permissionName;
            }
        }

        // Permisos para Super Admin (acceso completo a todo)
        $superAdminPermissions = $allPermissions; // Todos los permisos

        // Permisos para Admin (solo Appointments y Call Records)
        $adminPermissions = [
            'CREATE_APPOINTMENT', 'READ_APPOINTMENT', 'UPDATE_APPOINTMENT', 'DELETE_APPOINTMENT', 'RESTORE_APPOINTMENT',
            'CREATE_CALL_RECORD', 'READ_CALL_RECORD', 'UPDATE_CALL_RECORD', 'DELETE_CALL_RECORD', 'RESTORE_CALL_RECORD'
        ];

        // Permisos para User (solo Call Records)
        $userPermissions = [
            'CREATE_CALL_RECORD', 'READ_CALL_RECORD', 'UPDATE_CALL_RECORD', 'DELETE_CALL_RECORD', 'RESTORE_CALL_RECORD'
        ];

        // Crear usuarios para cada rol
        $superAdminUser = User::factory()->create([
            'name' => 'Victor Lara',
            'email' => 'info@vgeneralcontractors.com',
            'username' => 'vgeneralcontractors',
            'password' => bcrypt('info01='),
            'uuid' => Uuid::uuid4()->toString(),
            'terms_and_conditions' => true
        ]);
        $superAdminUser->assignRole('Super Admin');

        // SECOND SUPER ADMIN
        $superAdminUser2 = User::factory()->create([
            'name' => 'Argenis Gonzalez',
            'email' => 'josegonzalezcr2794@gmail.com',
            'username' => 'argenis692',
            'password' => bcrypt('argenis01='),
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $superAdminUser2->assignRole('Super Admin');
        // END SECOND SUPER ADMIN

        // ADMIN USER - Solo appointments y call records
        $adminUser = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@vgeneralcontractors.com',
            'username' => 'adminAppointment',
            'password' => bcrypt('admin01='),
            'uuid' => Uuid::uuid4()->toString(),
            'terms_and_conditions' => true
        ]);
        $adminUser->assignRole('Admin');
        // END ADMIN USER

        // USER - Solo call records
        $userUser = User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'username' => 'user01',
            'password' => bcrypt('user01='),
            'uuid' => Uuid::uuid4()->toString(),
            'terms_and_conditions' => true
        ]);
        $userUser->assignRole('User');
        // END USER

        // Asignar permisos a los roles
        $superAdminRole->givePermissionTo($superAdminPermissions);
        $adminRole->givePermissionTo($adminPermissions);
        $userRole->givePermissionTo($userPermissions);

        // Crear permisos específicos para MODEL_AI si no existen
        $modelAIPermissions = ['CREATE_MODEL_AI', 'READ_MODEL_AI', 'UPDATE_MODEL_AI', 'DELETE_MODEL_AI', 'RESTORE_MODEL_AI'];
        foreach ($modelAIPermissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create([
                    'name' => $permission,
                    'uuid' => Uuid::uuid4()->toString()
                ]);
            }
        }
        
        // Asegurar que Super Admin tenga permisos de MODEL_AI
        $superAdminRole->givePermissionTo($modelAIPermissions);

        // Crear categorías para blog
        BlogCategory::create([
            'uuid' => Uuid::uuid4()->toString(),
            'blog_category_name' => 'Roofing',
            'blog_category_description' => 'Valor por defecto',
            'blog_category_image' => 'Valor por defecto',
            'user_id' => 1,
           
        ]);

        BlogCategory::create([
            'uuid' => Uuid::uuid4()->toString(),
            'blog_category_name' => 'Water Mitigation',
            'blog_category_description' => 'Categoría para contenido relacionado con mitigación de agua',
            'blog_category_image' => 'Valor por defecto',
            'user_id' => 1,
           
        ]);
        // end      

        // COMPANY DATA
        $companyData = [
            'company_name' => 'V General Contractors',
            'name' => 'Victor Lara',
            'signature_path' => '/signatures/acme_signature.png',
            'phone' => '+13466920757',
            'email' => 'info@vgeneralcontractors.com',
            'address' => '1302 Waugh Dr # 810 Houston TX 77019',
            'website' => 'https://vgeneralcontractors.com',
            'latitude' => 29.75516,
            'longitude' => -95.3984135,
            'user_id' => 1,
            'uuid' => Uuid::uuid4()->toString(),
            'facebook_link' => 'https://www.facebook.com/vgeneralcontractors/',
            'instagram_link' => 'https://www.instagram.com/vgeneralcontractors/',
            'linkedin_link' => 'https://www.linkedin.com/company/v-general-contractors/',
            'twitter_link' => 'https://twitter.com/vgeneralcontractors'
        ];

        CompanyData::create($companyData);
        // END COMPANY DATA

        // EMAIL DATA
        $emailsData = [
            [
                'description' => 'Correo para colecciones y pagos',
                'email' => 'collection@vgeneralcontractors.com',
                'phone' => '+17133646240',
                'type' => 'Collections',
                'user_id' => $superAdminUser->id,
            ],
            [
                'description' => 'Correo para información general',
                'email' => 'info@vgeneralcontractors.com',
                'phone' => '+13466920757',
                'type' => 'Info',
                'user_id' => $superAdminUser->id,
            ],
            [
                'description' => 'Correo para citas y agendamiento',
                'email' => 'admin@vgeneralcontractors.com',
                'phone' => '+13466920757',
                'type' => 'Admin',
                'user_id' => $adminUser->id,
            ]
        ];

        foreach ($emailsData as $emailData) {
            $emailData['uuid'] = Uuid::uuid4()->toString();
            \App\Models\EmailData::create($emailData);
        }
        // END EMAIL DATA

        // Service Categories
        $serviceCategories = [
            'Roof Repair',
            'New Roof',
            'Storm Damage',
            'Mold Remediation',
            'Mitigation',
            'Tarp',
            'ReTarp',
            'Rebuild',
            'Roof Paint',
            'Inspección Tarp'
        ];

        foreach ($serviceCategories as $category) {
            ServiceCategory::create([
                'uuid' => (string) Str::uuid(),
                'category' => $category,
                'user_id' => $superAdminUser->id,
            ]);
        }
    }
}