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
        // Crear los roles principales (Capitalize format)
        $adminRole = Role::create([
            'name' => 'Admin',
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $userRole = Role::create([
            'name' => 'User',
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $otherRole = Role::create([
            'name' => 'Other',
            'uuid' => Uuid::uuid4()->toString()
        ]);

        // Definir modelos para permisos
        $models = [
            'USER', 'EMAIL_DATA', 'SERVICE_CATEGORY', 'PORTFOLIO', 'COMPANY_DATA', 
            'PROJECT_TYPE', 'APPOINTMENT', 'BLOG_CATEGORY', 'POST', 'SEO'
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

        // Permisos para gestionar usuarios
        $userManagementPermissions = [
            'CREATE_USER', 'READ_USER', 'UPDATE_USER', 'DELETE_USER', 'RESTORE_USER'
        ];

        // Permisos para "Other" (ajusta según tus necesidades)
        $otherPermissions = [
            'READ_POST', 'READ_PORTFOLIO', 'READ_SERVICE_CATEGORY'
            // Añade más según sea necesario
        ];

        // Permisos solo para appointment
        $appointmentPermissions = [
            'CREATE_APPOINTMENT', 'READ_APPOINTMENT', 'UPDATE_APPOINTMENT', 'DELETE_APPOINTMENT', 'RESTORE_APPOINTMENT'
        ];

        // Todos los permisos excepto los de gestión de usuarios
        $nonUserManagementPermissions = array_diff($allPermissions, $userManagementPermissions);

        // Crear usuarios para cada rol
        $adminUser = User::factory()->create([
            'name' => 'Victor Lara',
            'email' => 'info@vgeneralcontractors.com',
            'username' => 'vgeneralcontractors',
            'password' => bcrypt('info01='),
            'uuid' => Uuid::uuid4()->toString(),
            'terms_and_conditions' => true
        ]);
        $adminUser->assignRole('Admin');

        // SECOND ADMIN
        $adminUser2 = User::factory()->create([
            'name' => 'Argenis Gonzalez',
            'email' => 'josegonzalezcr2794@gmail.com',
            'username' => 'argenis692',
            'password' => bcrypt('argenis01='),
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $adminUser2->assignRole('Admin');
        // END SECOND ADMIN

        // APPOINTMENT ADMIN USER
        $adminAppointmentUser = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@vgeneralcontractors.com',
            'username' => 'adminAppointment',
            'password' => bcrypt('admin01='),
            'uuid' => Uuid::uuid4()->toString(),
            'terms_and_conditions' => true
        ]);
        $adminAppointmentUser->assignRole('Admin');
        $adminAppointmentUser->syncPermissions($appointmentPermissions);
        // END APPOINTMENT ADMIN USER

        $userUser = User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'username' => 'user01',
            'password' => bcrypt('user01='),
            'uuid' => Uuid::uuid4()->toString(),
            'terms_and_conditions' => true
        ]);
        $userUser->assignRole('User');

        $otherUser = User::factory()->create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => bcrypt('password'),
            'uuid' => Uuid::uuid4()->toString()
        ]);
        $otherUser->assignRole('Other');

        // Asignar permisos a los roles
        // Para Admin: todos los permisos
        $adminRole->givePermissionTo($allPermissions);
        
        // Para User: todos excepto gestión de usuarios
        $userRole->givePermissionTo($nonUserManagementPermissions);
        
        // Para Other: solo permisos específicos
        $otherRole->givePermissionTo($otherPermissions);

        // Crear la categoría "General" para blog
        BlogCategory::create([
            'uuid' => Uuid::uuid4()->toString(),
            'blog_category_name' => 'Roofing',
            'blog_category_description' => 'Valor por defecto',
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
                'email' => 'collections@vgeneralcontractors.com',
                'phone' => '+17133646240',
                'type' => 'Collections',
                'user_id' => $adminUser->id,
            ],
            [
                'description' => 'Correo para información general',
                'email' => 'info@vgeneralcontractors.com',
                'phone' => '+13466920757',
                'type' => 'Info',
                'user_id' => $adminUser->id,
            ],
            [
                'description' => 'Correo para citas y agendamiento',
                'email' => 'admin@vgeneralcontractors.com',
                'phone' => '+13466920757',
                'type' => 'Admin',
                'user_id' => $adminAppointmentUser->id,
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
            'Roof Paint'
        ];

        foreach ($serviceCategories as $category) {
            ServiceCategory::create([
                'uuid' => (string) Str::uuid(),
                'category' => $category,
                'user_id' => $adminUser->id,
            ]);
        }
    }
}