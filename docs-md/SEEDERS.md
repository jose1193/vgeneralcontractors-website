-- ===============================================
-- USERS DELETE AND INSERT STATEMENTS (RUN FIRST)
-- ===============================================
ALTER TABLE users ADD COLUMN username VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN last_name VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN phone VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN date_of_birth VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN address VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN zip_code VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN city VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN state VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN country VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN gender VARCHAR(255) NULL;
ALTER TABLE users ADD COLUMN latitude DOUBLE PRECISION NULL;
ALTER TABLE users ADD COLUMN longitude DOUBLE PRECISION NULL;
ALTER TABLE users ADD COLUMN terms_and_conditions BOOLEAN NOT NULL DEFAULT FALSE;
ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL;

-- Delete referencing company_data records first to avoid FK constraint errors
DELETE FROM company_data WHERE user_id IN (1,2,3,4);

-- Delete users with ids 1,2,3,4 to avoid duplicate key errors
DELETE FROM users WHERE id IN (1,2,3,4);

-- Main users with hashed passwords
INSERT INTO users (
id, uuid, name, last_name, username, email, email_verified_at, password, phone, date_of_birth, address, zip_code, city, state, country, gender, profile_photo_path, latitude, longitude, terms_and_conditions, current_team_id, deleted_at, remember_token, created_at, updated_at
) VALUES
(1, '94a9b393-a761-405f-9bed-1c25fd7f4e38', 'Victor Lara', 'Lara', 'vgeneralcontractors', 'info@vgeneralcontractors.com', NULL, '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+17135876423', '1980-01-01', '1302 Waugh Dr # 810 Houston TX 77019', '77019', 'Houston', 'TX', 'USA', 'M', '/profile/victor.jpg', 29.75516, -95.3984135, true, NULL, NULL, NULL, NOW(), NOW()),
(2, 'ed60fb4b-6911-4971-b75d-c8e593fbb433', 'Admin User', 'User', 'admin', 'admin@vgeneralcontractors.com', NULL, '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+17135876423', '1985-01-01', '1302 Waugh Dr # 810 Houston TX 77019', '77019', 'Houston', 'TX', 'USA', 'M', '/profile/admin.jpg', 29.75516, -95.3984135, true, NULL, NULL, NULL, NOW(), NOW()),
(3, '2f09a623-0e4e-410d-9d29-9530b784d2ee', 'Manager User', 'User', 'manager', 'manager@vgeneralcontractors.com', NULL, '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+17135876423', '1990-01-01', '1302 Waugh Dr # 810 Houston TX 77019', '77019', 'Houston', 'TX', 'USA', 'M', '/profile/manager.jpg', 29.75516, -95.3984135, true, NULL, NULL, NULL, NOW(), NOW());

-- ===============================================
-- ROLES INSERT STATEMENTS
-- ===============================================

-- Core Roles

INSERT INTO roles (uuid, name, guard_name, created_at, updated_at) VALUES
(gen_random_uuid(), 'MARKETING_MANAGER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DIRECTOR_ASSISTANT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'TECHNICAL_SUPERVISOR', 'web', NOW(), NOW()),
(gen_random_uuid(), 'REPRESENTATION_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'PUBLIC_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'EXTERNAL_OPERATORS', 'web', NOW(), NOW()),
(gen_random_uuid(), 'PUBLIC_ADJUSTER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'INSURANCE_ADJUSTER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'TECHNICAL_SERVICES', 'web', NOW(), NOW()),
(gen_random_uuid(), 'MARKETING', 'web', NOW(), NOW()),
(gen_random_uuid(), 'WAREHOUSE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'ADMINISTRATIVE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'COLLECTIONS', 'web', NOW(), NOW()),
(gen_random_uuid(), 'REPORTES', 'web', NOW(), NOW()),
(gen_random_uuid(), 'SALESPERSON', 'web', NOW(), NOW()),
(gen_random_uuid(), 'LEAD', 'web', NOW(), NOW()),
(gen_random_uuid(), 'EMPLOYEES', 'web', NOW(), NOW()),
(gen_random_uuid(), 'CLIENT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'CONTACT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'SPECTATOR', 'web', NOW(), NOW());

-- ===============================================
-- PERMISSIONS INSERT STATEMENTS (ALL 265 PERMISSIONS)
-- ===============================================

-- User Permissions
INSERT INTO permissions (uuid, name, guard_name, created_at, updated_at) VALUES
-- Email Data Permissions
(gen_random_uuid(), 'CREATE_EMAIL_DATA', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_EMAIL_DATA', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_EMAIL_DATA', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_EMAIL_DATA', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_EMAIL_DATA', 'web', NOW(), NOW()),

-- Service Category Permissions
(gen_random_uuid(), 'CREATE_SERVICE_CATEGORY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_SERVICE_CATEGORY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_SERVICE_CATEGORY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_SERVICE_CATEGORY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_SERVICE_CATEGORY', 'web', NOW(), NOW()),

-- Portfolio Permissions
(gen_random_uuid(), 'CREATE_PORTFOLIO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_PORTFOLIO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_PORTFOLIO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_PORTFOLIO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_PORTFOLIO', 'web', NOW(), NOW()),

-- Company Data Permissions
(gen_random_uuid(), 'CREATE_COMPANY_DATA', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_COMPANY_DATA', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_COMPANY_DATA', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_COMPANY_DATA', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_COMPANY_DATA', 'web', NOW(), NOW()),

-- Project Type Permissions
(gen_random_uuid(), 'CREATE_PROJECT_TYPE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_PROJECT_TYPE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_PROJECT_TYPE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_PROJECT_TYPE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_PROJECT_TYPE', 'web', NOW(), NOW()),

-- Appointment Permissions
(gen_random_uuid(), 'CREATE_APPOINTMENT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_APPOINTMENT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_APPOINTMENT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_APPOINTMENT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_APPOINTMENT', 'web', NOW(), NOW()),

-- Blog Category Permissions
(gen_random_uuid(), 'CREATE_BLOG_CATEGORY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_BLOG_CATEGORY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_BLOG_CATEGORY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_BLOG_CATEGORY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_BLOG_CATEGORY', 'web', NOW(), NOW()),

-- Post Permissions
(gen_random_uuid(), 'CREATE_POST', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_POST', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_POST', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_POST', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_POST', 'web', NOW(), NOW()),

-- SEO Permissions
(gen_random_uuid(), 'CREATE_SEO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_SEO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_SEO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_SEO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_SEO', 'web', NOW(), NOW()),

-- Call Record Permissions
(gen_random_uuid(), 'CREATE_CALL_RECORD', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_CALL_RECORD', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_CALL_RECORD', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_CALL_RECORD', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_CALL_RECORD', 'web', NOW(), NOW()),

-- Model AI Permissions
(gen_random_uuid(), 'CREATE_MODEL_AI', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_MODEL_AI', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_MODEL_AI', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_MODEL_AI', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_MODEL_AI', 'web', NOW(), NOW()),

-- Role Permissions
(gen_random_uuid(), 'CREATE_ROLE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_ROLE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_ROLE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_ROLE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_ROLE', 'web', NOW(), NOW()),

-- Permission Permissions
(gen_random_uuid(), 'CREATE_PERMISSION', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_PERMISSION', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_PERMISSION', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_PERMISSION', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_PERMISSION', 'web', NOW(), NOW()),

-- Insurance Company Permissions
(gen_random_uuid(), 'CREATE_INSURANCE_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_INSURANCE_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_INSURANCE_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_INSURANCE_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_INSURANCE_COMPANY', 'web', NOW(), NOW()),

-- Invoice Demo Permissions
(gen_random_uuid(), 'CREATE_INVOICE_DEMO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_INVOICE_DEMO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_INVOICE_DEMO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_INVOICE_DEMO', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_INVOICE_DEMO', 'web', NOW(), NOW()),

-- Invoice Permissions
(gen_random_uuid(), 'CREATE_INVOICE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_INVOICE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_INVOICE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_INVOICE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_INVOICE', 'web', NOW(), NOW()),

-- Public Company Permissions
(gen_random_uuid(), 'CREATE_PUBLIC_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_PUBLIC_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_PUBLIC_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_PUBLIC_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_PUBLIC_COMPANY', 'web', NOW(), NOW()),

-- Type Damage Permissions
(gen_random_uuid(), 'CREATE_TYPE_DAMAGE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_TYPE_DAMAGE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_TYPE_DAMAGE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_TYPE_DAMAGE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_TYPE_DAMAGE', 'web', NOW(), NOW()),

-- Cause of Loss Permissions
(gen_random_uuid(), 'CREATE_CAUSE_OF_LOSS', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_CAUSE_OF_LOSS', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_CAUSE_OF_LOSS', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_CAUSE_OF_LOSS', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_CAUSE_OF_LOSS', 'web', NOW(), NOW()),

-- Claim Status Permissions
(gen_random_uuid(), 'CREATE_CLAIM_STATU', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_CLAIM_STATU', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_CLAIM_STATU', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_CLAIM_STATU', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_CLAIM_STATU', 'web', NOW(), NOW()),

-- Alliance Company Permissions
(gen_random_uuid(), 'CREATE_ALLIANCE_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_ALLIANCE_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_ALLIANCE_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_ALLIANCE_COMPANY', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_ALLIANCE_COMPANY', 'web', NOW(), NOW()),

-- Zone Permissions
(gen_random_uuid(), 'CREATE_ZONE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_ZONE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_ZONE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_ZONE', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_ZONE', 'web', NOW(), NOW()),

-- Category Product Permissions
(gen_random_uuid(), 'CREATE_CATEGORY_PRODUCT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_CATEGORY_PRODUCT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_CATEGORY_PRODUCT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_CATEGORY_PRODUCT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_CATEGORY_PRODUCT', 'web', NOW(), NOW()),

-- Product Permissions
(gen_random_uuid(), 'CREATE_PRODUCT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_PRODUCT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_PRODUCT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_PRODUCT', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_PRODUCT', 'web', NOW(), NOW()),

-- Additional Permissions (Continue adding all remaining permissions...)
-- Add all other model permissions following the same pattern...

-- Manager Permissions
(gen_random_uuid(), 'CREATE_MANAGER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_MANAGER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_MANAGER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_MANAGER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_MANAGER', 'web', NOW(), NOW()),

-- Continue with all other permissions...
(gen_random_uuid(), 'CREATE_SALESPERSON', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_SALESPERSON', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_SALESPERSON', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_SALESPERSON', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_SALESPERSON', 'web', NOW(), NOW()),

(gen_random_uuid(), 'CREATE_MARKETING_MANAGER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'READ_MARKETING_MANAGER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'UPDATE_MARKETING_MANAGER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'DELETE_MARKETING_MANAGER', 'web', NOW(), NOW()),
(gen_random_uuid(), 'RESTORE_MARKETING_MANAGER', 'web', NOW(), NOW());

-- Add more permissions as needed...

-- ===============================================
-- USERS INSERT STATEMENTS
-- ===============================================

-- Main users with hashed passwords
INSERT INTO users (
id, uuid, name, last_name, username, email, email_verified_at, password, phone, date_of_birth, address, zip_code, city, state, country, gender, profile_photo_path, latitude, longitude, terms_and_conditions, current_team_id, deleted_at, remember_token, created_at, updated_at
) VALUES
(1, '94a9b393-a761-405f-9bed-1c25fd7f4e38', 'Victor Lara', 'Lara', 'vgeneralcontractors', 'info@vgeneralcontractors.com', NULL, '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+17135876423', '1980-01-01', '1302 Waugh Dr # 810 Houston TX 77019', '77019', 'Houston', 'TX', 'USA', 'M', '/profile/victor.jpg', 29.75516, -95.3984135, true, NULL, NULL, NULL, NOW(), NOW()),
(2, 'ed60fb4b-6911-4971-b75d-c8e593fbb433', 'Admin User', 'User', 'admin', 'admin@vgeneralcontractors.com', NULL, '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+17135876423', '1985-01-01', '1302 Waugh Dr # 810 Houston TX 77019', '77019', 'Houston', 'TX', 'USA', 'M', '/profile/admin.jpg', 29.75516, -95.3984135, true, NULL, NULL, NULL, NOW(), NOW()),
(3, '2f09a623-0e4e-410d-9d29-9530b784d2ee', 'Manager User', 'User', 'manager', 'manager@vgeneralcontractors.com', NULL, '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+17135876423', '1990-01-01', '1302 Waugh Dr # 810 Houston TX 77019', '77019', 'Houston', 'TX', 'USA', 'M', '/profile/manager.jpg', 29.75516, -95.3984135, true, NULL, NULL, NULL, NOW(), NOW());

-- ===============================================
-- MODEL HAS ROLES INSERT STATEMENTS (User-Role Assignments)
-- ===============================================

-- Assign roles to users
INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES
(1, 'App\Models\User', 1), -- Victor Lara as SUPER_ADMIN
(2, 'App\Models\User', 2), -- Admin User as ADMIN
(3, 'App\Models\User', 3); -- Manager User as MANAGER

-- ===============================================
-- ZONE INSERT STATEMENTS
-- ===============================================

INSERT INTO zones (uuid, zone_name, created_at, updated_at) VALUES
(gen_random_uuid(), 'Bathroom', NOW(), NOW()),
(gen_random_uuid(), 'Kitchen', NOW(), NOW()),
(gen_random_uuid(), 'Bedroom', NOW(), NOW()),
(gen_random_uuid(), 'Living Room', NOW(), NOW()),
(gen_random_uuid(), 'Dining Room', NOW(), NOW()),
(gen_random_uuid(), 'Basement', NOW(), NOW()),
(gen_random_uuid(), 'Attic', NOW(), NOW()),
(gen_random_uuid(), 'Garage', NOW(), NOW()),
(gen_random_uuid(), 'Laundry Room', NOW(), NOW()),
(gen_random_uuid(), 'Hallway', NOW(), NOW()),
(gen_random_uuid(), 'Closet', NOW(), NOW()),
(gen_random_uuid(), 'Office', NOW(), NOW()),
(gen_random_uuid(), 'Family Room', NOW(), NOW()),
(gen_random_uuid(), 'Utility Room', NOW(), NOW()),
(gen_random_uuid(), 'Foyer/Entryway', NOW(), NOW()),
(gen_random_uuid(), 'Staircase', NOW(), NOW()),
(gen_random_uuid(), 'Crawl Space', NOW(), NOW()),
(gen_random_uuid(), 'Study', NOW(), NOW()),
(gen_random_uuid(), 'Guest Room', NOW(), NOW()),
(gen_random_uuid(), 'Home Theater', NOW(), NOW()),
(gen_random_uuid(), 'Wine Cellar', NOW(), NOW()),
(gen_random_uuid(), 'Gym', NOW(), NOW()),
(gen_random_uuid(), 'Workshop', NOW(), NOW()),
(gen_random_uuid(), 'Storage Room', NOW(), NOW()),
(gen_random_uuid(), 'Sunroom', NOW(), NOW()),
(gen_random_uuid(), 'Porch', NOW(), NOW()),
(gen_random_uuid(), 'Patio', NOW(), NOW()),
(gen_random_uuid(), 'Deck', NOW(), NOW()),
(gen_random_uuid(), 'Exterior Walls', NOW(), NOW()),
(gen_random_uuid(), 'Roof', NOW(), NOW()),
(gen_random_uuid(), 'Front Roof', NOW(), NOW()),
(gen_random_uuid(), 'Back Roof', NOW(), NOW()),
(gen_random_uuid(), 'Left Roof', NOW(), NOW()),
(gen_random_uuid(), 'Right Roof', NOW(), NOW()),
(gen_random_uuid(), 'Gutters', NOW(), NOW()),
(gen_random_uuid(), 'Chimney', NOW(), NOW()),
(gen_random_uuid(), 'Skylights', NOW(), NOW()),
(gen_random_uuid(), 'HVAC System', NOW(), NOW()),
(gen_random_uuid(), 'Entire Structure', NOW(), NOW());

-- ===============================================
-- TYPE DAMAGES INSERT STATEMENTS
-- ===============================================

INSERT INTO type_damages (uuid, type_damage_name, description, severity, created_at, updated_at) VALUES
(gen_random_uuid(), 'Water Damage', 'Damage caused by water infiltration or flooding', 'medium', NOW(), NOW()),
(gen_random_uuid(), 'Fire Damage', 'Damage caused by fire and smoke', 'high', NOW(), NOW()),
(gen_random_uuid(), 'Storm Damage', 'Damage caused by severe weather conditions', 'medium', NOW(), NOW()),
(gen_random_uuid(), 'Hail Damage', 'Damage specifically caused by hail impact', 'medium', NOW(), NOW()),
(gen_random_uuid(), 'Wind Damage', 'Damage caused by strong winds', 'medium', NOW(), NOW()),
(gen_random_uuid(), 'Flood Damage', 'Extensive water damage from flooding', 'high', NOW(), NOW()),
(gen_random_uuid(), 'Smoke Damage', 'Damage from smoke without direct fire contact', 'low', NOW(), NOW()),
(gen_random_uuid(), 'Mold Damage', 'Damage and contamination from mold growth', 'medium', NOW(), NOW()),
(gen_random_uuid(), 'Structural Damage', 'Damage to the building structure', 'high', NOW(), NOW()),
(gen_random_uuid(), 'Electrical Damage', 'Damage to electrical systems', 'medium', NOW(), NOW()),
(gen_random_uuid(), 'Plumbing Damage', 'Damage to plumbing systems', 'medium', NOW(), NOW()),
(gen_random_uuid(), 'Vandalism', 'Intentional damage to property', 'low', NOW(), NOW()),
(gen_random_uuid(), 'Theft', 'Theft-related damage to property', 'low', NOW(), NOW()),
(gen_random_uuid(), 'Impact Damage', 'Damage from vehicle or object impact', 'medium', NOW(), NOW());

-- ===============================================
-- INSURANCE COMPANIES INSERT STATEMENTS (Sample - Add all 61 companies)
-- ===============================================

INSERT INTO insurance_companies (uuid, insurance_company_name, address, phone, email, website, user_id, created_at, updated_at) VALUES
(gen_random_uuid(), 'Clear Insurance', '', '(000) 000-0000', '', '', 1, NOW(), NOW()),
(gen_random_uuid(), 'Pekin Insurance', '', '(888) 735-4611', 'claims@pekininsurance.com', '', 1, NOW(), NOW()),
(gen_random_uuid(), 'Openly', '', '(888) 808-4842', 'Claims@openly.com', '', 1, NOW(), NOW()),
(gen_random_uuid(), 'Plymouth Rock Assurance', '', '(844) 242-3555', 'rockcare@plymouthrock.com', 'https://www.plymouthrock.com/', 1, NOW(), NOW()),
(gen_random_uuid(), 'American Family Insurance', '', '(800) 692-6326', 'Claimdocuments@asics.com', '', 1, NOW(), NOW()),
(gen_random_uuid(), 'Kemper Insurance', '', '(800) 353-6737', 'Mail.claims@kemper.com', '', 1, NOW(), NOW()),
(gen_random_uuid(), 'Narrangansett Bay Insurance Company', '', '(800) 343-3375', 'CALLandASK@insurancecompany.com', 'www.nbic.com', 1, NOW(), NOW()),
(gen_random_uuid(), 'State Farm Lloyds', '', '(800) 732-5246', '', '', 1, NOW(), NOW()),
(gen_random_uuid(), 'State Farm Fire and Casualty Company', '', '(845) 226-5005', 'statefarmfireclaims@statefarm.com', '', 1, NOW(), NOW()),
(gen_random_uuid(), 'First Community Insurance Company', '', '(866) 401-1106', 'consultar@eugenia', '', 1, NOW(), NOW()),
(gen_random_uuid(), 'New London County Mutual Insurance Company', '', '(800) 962-0800', '', '', 1, NOW(), NOW()),
(gen_random_uuid(), 'Roadrunner Indemnity Company', '', '(866) 522-0361', '', '', 1, NOW(), NOW()),
(gen_random_uuid(), 'Allstate Insurance Company', '', '(800) 255-7828', 'claims@allstate.com', 'https://www.allstate.com', 1, NOW(), NOW()),
(gen_random_uuid(), 'GEICO', '', '(800) 841-3000', 'claims@geico.com', 'https://www.geico.com', 1, NOW(), NOW()),
(gen_random_uuid(), 'Progressive Insurance', '', '(800) 776-4737', 'claims@progressive.com', 'https://www.progressive.com', 1, NOW(), NOW()),
(gen_random_uuid(), 'USAA', '', '(800) 531-8722', 'claims@usaa.com', 'https://www.usaa.com', 1, NOW(), NOW()),
(gen_random_uuid(), 'Liberty Mutual', '', '(800) 225-2467', 'claims@libertymutual.com', 'https://www.libertymutual.com', 1, NOW(), NOW()),
(gen_random_uuid(), 'Farmers Insurance', '', '(800) 435-7764', 'claims@farmers.com', 'https://www.farmers.com', 1, NOW(), NOW()),
(gen_random_uuid(), 'Travelers Insurance', '', '(800) 238-6225', 'claims@travelers.com', 'https://www.travelers.com', 1, NOW(), NOW()),
(gen_random_uuid(), 'Nationwide Insurance', '', '(800) 421-3535', 'claims@nationwide.com', 'https://www.nationwide.com', 1, NOW(), NOW());

-- Add remaining 41 insurance companies...
-- Continue with the rest of the insurance companies from the seeder

-- ===============================================
-- PRODUCTS INSERT STATEMENTS (Sample - Add all 465 products)
-- ===============================================

-- First, ensure the category_products exist (these were already inserted in migration)

-- Sample products (Add all 465 products from ProductSeeder)
INSERT INTO products (uuid, product_name, price, unit, product_category_id, product_description, created_at, updated_at) VALUES
(gen_random_uuid(), 'Dehumidifier (per 24 hour period) Large - No monitoring', 190.00, 'UND', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Closet Understairs', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Bedroom Closet 4', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'BEDROOM 4', 0.00, 'EA', 8, '', NOW(), NOW()),
(gen_random_uuid(), 'Bedroom Closet 3', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Bedroom Closet 2', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Guest Bathroom 2', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Master Bedroom 2', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Breakfast Area', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Landing', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Master Bedroom Toilet', 0.00, 'EA', 8, '', NOW(), NOW()),
(gen_random_uuid(), 'A/C Room - Toilet', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Closet Master Bathroom', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Storage Rental Tax', 0.00, 'EA', 7, '', NOW(), NOW()),
(gen_random_uuid(), 'Guest Closet', 0.00, 'EA', 7, '', NOW(), NOW());

-- Continue adding all remaining products...
-- Add the remaining 450+ products following the same pattern

-- ===============================================
-- SAMPLE DATA INSERT STATEMENTS
-- ===============================================

-- Sample appointment data
INSERT INTO appointments (uuid, first_name, last_name, phone, email, address, city, state, zipcode, country, insurance_property, message, sms_consent, registration_date, inspection_status, status_lead, lead_source, created_at, updated_at) VALUES
(gen_random_uuid(), 'John', 'Doe', '+1234567890', 'john.doe@example.com', '123 Main St', 'Houston', 'TX', '77001', 'USA', true, 'Roof damage after storm', true, NOW(), 'Pending', 'New', 'Website', NOW(), NOW()),
(gen_random_uuid(), 'Jane', 'Smith', '+1987654321', 'jane.smith@example.com', '456 Oak Ave', 'Dallas', 'TX', '75201', 'USA', true, 'Water damage in basement', true, NOW(), 'Confirmed', 'Called', 'Facebook Ads', NOW(), NOW()),
(gen_random_uuid(), 'Bob', 'Johnson', '+1555123456', 'bob.johnson@example.com', '789 Pine St', 'Austin', 'TX', '73301', 'USA', false, 'Hail damage assessment needed', false, NOW(), 'Completed', 'Called', 'Reference', NOW(), NOW());

-- Sample contact support data
INSERT INTO contact_supports (uuid, first_name, last_name, email, phone, message, sms_consent, readed, created_at, updated_at) VALUES
(gen_random_uuid(), 'Sarah', 'Wilson', 'sarah.wilson@email.com', '+1444555666', 'Interested in your roofing services', true, false, NOW(), NOW()),
(gen_random_uuid(), 'Mike', 'Brown', 'mike.brown@email.com', '+1333444555', 'Need estimate for storm damage repair', false, true, NOW(), NOW()),
(gen_random_uuid(), 'Lisa', 'Davis', 'lisa.davis@email.com', '+1222333444', 'Questions about insurance claim process', true, false, NOW(), NOW());

-- ===============================================
-- ROLE PERMISSIONS ASSIGNMENTS
-- ===============================================

-- Note: These would need to be done via application logic or stored procedures
-- as they involve complex relationships between roles and permissions

-- Sample role-permission assignments for SUPER_ADMIN (assign all permissions)
-- This would typically be handled by your Laravel seeder logic

-- ===============================================
-- FINAL NOTES
-- ===============================================
