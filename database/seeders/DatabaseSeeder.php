<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\Review;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Staff;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        Plan::create([
            'name' => 'Super Admin',
            'description' => 'admin@appointy.com',
            'services_limit' => 10,
            'staff_limit' => 10,
            'appointments_limit' => 10,
            'storage_limit' => 10,
        ]);

        PlanPrice::create([
            'plan_id' => 1,
            'price_id' => 'price_1TiedlAtlausncUWIzLSVQqd',
            'price' => 10,
            'interval' => 'monthly',
        ]);

        // ========== CREATE USERS ==========

        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@appointy.com',
            'phone' => '+1234567890',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Sample Vendors
        $vendors = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah@medicalclinic.com',
                'phone' => '+15551234567',
                'business_name' => 'Wellness Medical Clinic',
                'description' => 'Comprehensive medical care for the whole family.',
                'city' => 'New York',
                'state' => 'NY',
                'address' => '123 Medical Blvd, Manhattan',
                'zip' => '10001',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael@chicsalon.com',
                'phone' => '+15552345678',
                'business_name' => 'Chic Hair & Beauty Salon',
                'description' => 'Premium hair styling, coloring, and beauty services.',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'address' => '456 Fashion Ave, Beverly Hills',
                'zip' => '90210',
            ],
            [
                'name' => 'Emma Wilson',
                'email' => 'emma@serenityspa.com',
                'phone' => '+15553456789',
                'business_name' => 'Serenity Day Spa',
                'description' => 'Relax and rejuvenate with our massage therapy and facials.',
                'city' => 'Chicago',
                'state' => 'IL',
                'address' => '789 Relaxation Rd, Downtown',
                'zip' => '60601',
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david@fitpro.com',
                'phone' => '+15554567890',
                'business_name' => 'FitPro Personal Training',
                'description' => 'Certified personal trainers helping you achieve your fitness goals.',
                'city' => 'Miami',
                'state' => 'FL',
                'address' => '321 Fitness Way, South Beach',
                'zip' => '33139',
            ],
            [
                'name' => 'Jennifer Martinez',
                'email' => 'jennifer@mathmentors.com',
                'phone' => '+15555678901',
                'business_name' => 'Math Mentors Tutoring',
                'description' => 'Expert math tutoring for all levels.',
                'city' => 'Austin',
                'state' => 'TX',
                'address' => '654 Learning Ln',
                'zip' => '78701',
            ],
        ];

        foreach ($vendors as $vendorData) {
            $user = User::create([
                'name' => $vendorData['name'],
                'email' => $vendorData['email'],
                'phone' => $vendorData['phone'],
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'status' => 'active',
                'email_verified_at' => now(),
                'address' => $vendorData['address'],
                'city' => $vendorData['city'],
                'state' => $vendorData['state'],
                'zip' => $vendorData['zip'],
            ]);

            Vendor::create([
                'user_id' => $user->id,
                'business_name' => $vendorData['business_name'],
                'description' => $vendorData['description'],
                'slug' => \Str::slug($vendorData['business_name']),
                'status' => 'active',
                'is_verified' => true,
                'phone' => $vendorData['phone'],
                'address' => $vendorData['address'],
                'city' => $vendorData['city'],
                'state' => $vendorData['state'],
                // 'zip' => $vendorData['zip'],
                'timezone' => 'America/New_York',
            ]);
        }

        // Sample Customers
        $customers = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '+15556789012', 'city' => 'New York'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '+15557890123', 'city' => 'Los Angeles'],
            ['name' => 'Bob Johnson', 'email' => 'bob@example.com', 'phone' => '+15558901234', 'city' => 'Chicago'],
            ['name' => 'Alice Brown', 'email' => 'alice@example.com', 'phone' => '+15559012345', 'city' => 'Miami'],
            ['name' => 'Charlie Davis', 'email' => 'charlie@example.com', 'phone' => '+15550123456', 'city' => 'Austin'],
        ];

        foreach ($customers as $customer) {
            User::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
                'password' => Hash::make('password'),
                'role' => 'customer',
                'status' => 'active',
                'email_verified_at' => now(),
                'city' => $customer['city'],
            ]);
        }

        // ========== CREATE CATEGORIES ==========

        $categories = [
            ['name' => 'Medical & Healthcare', 'slug' => 'medical-healthcare', 'description' => 'Doctors, dentists, clinics', 'icon' => '🏥', 'order' => 1],
            ['name' => 'Beauty & Salons', 'slug' => 'beauty-salons', 'description' => 'Hair salons, nail spas', 'icon' => '💇', 'color' => '#e91e63', 'order' => 2],
            ['name' => 'Spa & Wellness', 'slug' => 'spa-wellness', 'description' => 'Massage, facials', 'icon' => '🧖', 'color' => '#9b59b6', 'order' => 3],
            ['name' => 'Fitness & Training', 'slug' => 'fitness-training', 'description' => 'Personal trainers, gyms', 'icon' => '💪', 'color' => '#3498db', 'order' => 4],
            ['name' => 'Education & Tutoring', 'slug' => 'education-tutoring', 'description' => 'Tutors, lessons', 'icon' => '📚', 'color' => '#f39c12', 'order' => 5],
            ['name' => 'Consulting', 'slug' => 'consulting', 'description' => 'Business, career coaches', 'icon' => '💼', 'color' => '#1abc9c', 'order' => 6],
            ['name' => 'Home Services', 'slug' => 'home-services', 'description' => 'Plumbers, cleaners', 'icon' => '🏠', 'color' => '#e67e22', 'order' => 7],
            ['name' => 'Pet Care', 'slug' => 'pet-care', 'description' => 'Vets, groomers', 'icon' => '🐾', 'color' => '#2ecc71', 'order' => 8],
        ];

        foreach ($categories as $category) {
            ServiceCategory::create($category);
        }

        // ========== CREATE SERVICES ==========

        $vendors = Vendor::all();
        $servicesByVendor = [
            'Wellness Medical Clinic' => [
                ['name' => 'General Checkup', 'description' => 'Complete physical examination', 'duration' => 30, 'price' => 150.00],
                ['name' => 'Vaccination', 'description' => 'Routine immunizations', 'duration' => 20, 'price' => 75.00],
                ['name' => 'Consultation', 'description' => 'Specialist consultation', 'duration' => 45, 'price' => 200.00],
            ],
            'Chic Hair & Beauty Salon' => [
                ['name' => 'Haircut & Styling', 'description' => 'Professional haircut', 'duration' => 60, 'price' => 85.00],
                ['name' => 'Hair Coloring', 'description' => 'Full hair coloring', 'duration' => 120, 'price' => 150.00],
                ['name' => 'Manicure & Pedicure', 'description' => 'Complete nail care', 'duration' => 90, 'price' => 65.00],
            ],
            'Serenity Day Spa' => [
                ['name' => 'Swedish Massage', 'description' => 'Relaxing massage', 'duration' => 60, 'price' => 120.00],
                ['name' => 'Deep Tissue Massage', 'description' => 'Therapeutic massage', 'duration' => 90, 'price' => 160.00],
                ['name' => 'Facial Treatment', 'description' => 'Customized facial', 'duration' => 60, 'price' => 95.00],
            ],
            'FitPro Personal Training' => [
                ['name' => 'Personal Training', 'description' => 'One-on-one training', 'duration' => 60, 'price' => 80.00],
                ['name' => 'Group Fitness', 'description' => 'Group training', 'duration' => 45, 'price' => 30.00],
                ['name' => 'Nutrition Consultation', 'description' => 'Nutrition planning', 'duration' => 60, 'price' => 100.00],
            ],
            'Math Mentors Tutoring' => [
                ['name' => 'Algebra Tutoring', 'description' => 'Algebra help', 'duration' => 60, 'price' => 60.00],
                ['name' => 'Calculus Tutoring', 'description' => 'Calculus instruction', 'duration' => 60, 'price' => 75.00],
                ['name' => 'SAT Prep', 'description' => 'SAT preparation', 'duration' => 90, 'price' => 90.00],
            ],
        ];

        foreach ($vendors as $vendor) {
            $vendorServices = $servicesByVendor[$vendor->business_name] ?? [];
            $categoryMap = [
                'Wellness Medical Clinic' => 1,
                'Chic Hair & Beauty Salon' => 2,
                'Serenity Day Spa' => 3,
                'FitPro Personal Training' => 4,
                'Math Mentors Tutoring' => 5,
            ];
            $categoryId = $categoryMap[$vendor->business_name] ?? rand(1, 8);

            foreach ($vendorServices as $serviceData) {
                Service::create([
                    'vendor_id' => $vendor->id,
                    'category_id' => $categoryId,
                    'name' => $serviceData['name'],
                    'slug' => \Str::slug($serviceData['name']),
                    'description' => $serviceData['description'],
                    'duration' => $serviceData['duration'],
                    'price' => $serviceData['price'],
                    'is_active' => true,
                ]);
            }
        }

        // ========== CREATE STAFF ==========

        $vendors = Vendor::all();
        $staffByVendor = [
            'Wellness Medical Clinic' => [
                ['name' => 'Dr. Sarah Johnson', 'position' => 'Primary Care Physician', 'email' => 'sarah@medicalclinic.com'],
                ['name' => 'Dr. Michael Lee', 'position' => 'Pediatrician', 'email' => 'michael@medicalclinic.com'],
                ['name' => 'Nurse Emily Brown', 'position' => 'Registered Nurse', 'email' => 'emily@medicalclinic.com'],
            ],
            'Chic Hair & Beauty Salon' => [
                ['name' => 'Lisa Wong', 'position' => 'Senior Stylist', 'email' => 'lisa@chicsalon.com'],
                ['name' => 'Maria Garcia', 'position' => 'Color Specialist', 'email' => 'maria@chicsalon.com'],
                ['name' => 'Jessica Taylor', 'position' => 'Nail Technician', 'email' => 'jessica@chicsalon.com'],
            ],
            'Serenity Day Spa' => [
                ['name' => 'Amanda White', 'position' => 'Massage Therapist', 'email' => 'amanda@serenityspa.com'],
                ['name' => 'Rachel Green', 'position' => 'Esthetician', 'email' => 'rachel@serenityspa.com'],
                ['name' => 'Olivia Smith', 'position' => 'Spa Manager', 'email' => 'olivia@serenityspa.com'],
            ],
            'FitPro Personal Training' => [
                ['name' => 'David Thompson', 'position' => 'Head Trainer', 'email' => 'david@fitpro.com'],
                ['name' => 'Chris Evans', 'position' => 'Yoga Instructor', 'email' => 'chris@fitpro.com'],
                ['name' => 'Sarah Connor', 'position' => 'Nutritionist', 'email' => 'sarah@fitpro.com'],
            ],
            'Math Mentors Tutoring' => [
                ['name' => 'Jennifer Martinez', 'position' => 'Lead Math Tutor', 'email' => 'jennifer@mathmentors.com'],
                ['name' => 'Robert Chen', 'position' => 'Calculus Specialist', 'email' => 'robert@mathmentors.com'],
                ['name' => 'Laura Wilson', 'position' => 'Algebra Tutor', 'email' => 'laura@mathmentors.com'],
            ],
        ];

        foreach ($vendors as $vendor) {
            $staffMembers = $staffByVendor[$vendor->business_name] ?? [];
            foreach ($staffMembers as $staffData) {
                Staff::create([
                    'vendor_id' => $vendor->id,
                    'name' => $staffData['name'],
                    'email' => $staffData['email'],
                    'position' => $staffData['position'],
                    'is_active' => true,
                ]);
            }
        }

        // ========== CREATE SCHEDULES ==========

        $vendors = Vendor::all();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        foreach ($vendors as $vendor) {
            foreach ($days as $day) {
                Schedule::create([
                    'vendor_id' => $vendor->id,
                    'staff_id' => null,
                    'day' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_active' => true,
                ]);
            }
            // Saturday hours
            Schedule::create([
                'vendor_id' => $vendor->id,
                'staff_id' => null,
                'day' => 'saturday',
                'start_time' => '10:00:00',
                'end_time' => '14:00:00',
                'is_active' => true,
            ]);
        }

        // ========== CREATE APPOINTMENTS ==========

        $customers = User::where('role', 'customer')->get();
        $vendors = Vendor::all();
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        for ($i = 0; $i < 50; $i++) {
            $vendor = $vendors->random();
            $customer = $customers->random();
            $service = $vendor->services()->inRandomOrder()->first();
            $staff = $vendor->staff()->inRandomOrder()->first();

            if (!$service) continue;

            $date = Carbon::now()->addDays(rand(-30, 30))->setTime(rand(9, 16), [0, 30][rand(0, 1)]);

            Appointment::create([
                'booking_number' => 'BK' . strtoupper(uniqid()),
                'customer_id' => $customer->id,
                'vendor_id' => $vendor->id,
                'service_id' => $service->id,
                'staff_id' => $staff?->id,
                'start_time' => $date,
                'end_time' => $date->copy()->addMinutes($service->duration),
                'status' => $statuses[array_rand($statuses)],
                'customer_notes' => rand(0, 1) ? 'Looking forward to my appointment!' : null,
                'price' => $service->price,
                'payment_status' => rand(0, 1) ? 'paid' : 'pending',
                'total_amount' => $service->price,
            ]);
        }

        // ========== CREATE ROLES AND PERMISSIONS ==========

        $this->createRolesAndPermissions();

        $this->command->info('Database seeding completed successfully!');
    }

    private function createRolesAndPermissions(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ========== DEFINE ALL PERMISSIONS ==========

        $allPermissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'suspend users',
            'activate users',
            // Vendor Management
            'view vendors',
            'create vendors',
            'edit vendors',
            'delete vendors',
            'approve vendors',
            'suspend vendors',
            'verify vendors',
            'view vendor earnings',
            'view vendor statistics',
            // Appointment Management
            'view appointments',
            'create appointments',
            'edit appointments',
            'delete appointments',
            'cancel appointments',
            'reschedule appointments',
            'approve appointments',
            'complete appointments',
            'view all appointments',
            // Service Management
            'view services',
            'create services',
            'edit services',
            'delete services',
            'manage service categories',
            'view categories',
            // Staff Management
            'view staff',
            'create staff',
            'edit staff',
            'delete staff',
            'manage staff schedules',
            // Schedule Management
            'view schedules',
            'manage schedules',
            'manage holidays',
            'manage breaks',
            // Payment & Subscription
            'view payments',
            'process payments',
            'refund payments',
            'view subscriptions',
            'manage subscription plans',
            'cancel subscriptions',
            // Review Management
            'view reviews',
            'create reviews',
            'edit reviews',
            'delete reviews',
            'respond to reviews',
            'approve reviews',
            // Reports
            'view reports',
            'generate reports',
            'export reports',
            'view revenue reports',
            'view appointment reports',
            // System Settings
            'manage settings',
            'manage cms',
            'manage notifications',
            'view audit logs',
            'manage email settings',
            'manage sms settings',
            // Coupon Management
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
            // Additional
            'view customers',
            'view own appointments',
            'manage own schedule',
            'update appointment status',
            'view customer details',
            'add appointment notes',
            // Module specific
            'appointment.view',
            'appointment.create',
            'appointment.edit',
            'appointment.delete',
            'appointment.cancel',
            'appointment.reschedule',
            'appointment.confirm',
            'appointment.complete',
            'appointment.no-show',
            'vendor.view',
            'vendor.create',
            'vendor.edit',
            'vendor.delete',
            'vendor.approve',
            'vendor.suspend',
            'vendor.verify',
            'service.view',
            'service.create',
            'service.edit',
            'service.delete',
            'service.category.view',
            'service.category.create',
            'service.category.edit',
            'service.category.delete',
            'staff.view',
            'staff.create',
            'staff.edit',
            'staff.delete',
            'staff.schedule.view',
            'staff.schedule.edit',
            'customer.view',
            'customer.create',
            'customer.edit',
            'customer.delete',
            'customer.suspend',
            'customer.activate',
            'payment.view',
            'payment.process',
            'payment.refund',
            'payment.export',
            'subscription.view',
            'subscription.create',
            'subscription.cancel',
            'subscription.plan.manage',
            'report.view',
            'report.generate',
            'report.export',
            'report.sales',
            'report.appointments',
            'report.vendors',
            'report.customers',
            'settings.view',
            'settings.general',
            'settings.email',
            'settings.sms',
            'settings.payment',
            'settings.notification',
            'settings.cms',
        ];

        // Create permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $this->command->info('Permissions created successfully!');

        // ========== CREATE ROLES ==========

        // Super Admin Role - All permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions($allPermissions);

        // Admin Role
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
            'view users',
            'edit users',
            'suspend users',
            'activate users',
            'view vendors',
            'edit vendors',
            'delete vendors',
            'approve vendors',
            'suspend vendors',
            'verify vendors',
            'view vendor earnings',
            'view vendor statistics',
            'view appointments',
            'view all appointments',
            'manage service categories',
            'view payments',
            'view subscriptions',
            'manage subscription plans',
            'view reviews',
            'approve reviews',
            'view reports',
            'generate reports',
            'export reports',
            'view revenue reports',
            'view appointment reports',
            'manage settings',
            'manage cms',
            'manage notifications',
            'view audit logs',
            'manage email settings',
            'manage sms settings',
        ]);

        // Vendor Role
        $vendorRole = Role::firstOrCreate(['name' => 'vendor', 'guard_name' => 'web']);
        $vendorRole->syncPermissions([
            'view appointments',
            'create appointments',
            'edit appointments',
            'cancel appointments',
            'reschedule appointments',
            'approve appointments',
            'complete appointments',
            'view categories',
            'view services',
            'create services',
            'edit services',
            'delete services',
            'view staff',
            'create staff',
            'edit staff',
            'delete staff',
            'manage staff schedules',
            'view schedules',
            'manage schedules',
            'manage holidays',
            'manage breaks',
            'view payments',
            'process payments',
            'view subscriptions',
            'view reviews',
            'respond to reviews',
            'view reports',
            'generate reports',
            'export reports',
            'view revenue reports',
            'view appointment reports',
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
        ]);

        // Staff Role
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staffRole->syncPermissions([
            'view appointments',
            'edit appointments',
            'complete appointments',
            'view services',
            'view schedules',
            'view customers',
            'view own appointments',
            'manage own schedule',
            'update appointment status',
            'view customer details',
            'add appointment notes',
        ]);

        // Customer Role
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $customerRole->syncPermissions([
            'view appointments',
            'create appointments',
            'cancel appointments',
            'reschedule appointments',
            'create reviews',
            'edit reviews',
            'delete reviews',
        ]);

        $this->command->info('Roles created successfully!');

        // ========== ASSIGN ROLES TO USERS ==========

        // Assign Super Admin
        $adminUser = User::where('email', 'admin@appointy.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('super_admin');
        }

        // Assign Vendor roles
        $vendors = User::where('role', 'vendor')->get();
        foreach ($vendors as $vendor) {
            if (!$vendor->hasRole('vendor')) {
                $vendor->assignRole('vendor');
            }
        }

        // Assign Customer roles
        $customers = User::where('role', 'customer')->get();
        foreach ($customers as $customer) {
            if (!$customer->hasRole('customer')) {
                $customer->assignRole('customer');
            }
        }

        // Assign Staff roles
        $staffMembers = Staff::all();
        foreach ($staffMembers as $staff) {
            if ($staff->email) {
                $user = User::where('email', $staff->email)->first();
                if ($user && !$user->hasRole('staff')) {
                    $user->assignRole('staff');
                } elseif (!$user) {
                    $newUser = User::create([
                        'name' => $staff->name,
                        'email' => $staff->email,
                        'password' => Hash::make('password'),
                        'role' => 'staff',
                        'status' => 'active',
                    ]);
                    $newUser->assignRole('staff');
                }
            }
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
