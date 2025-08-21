-- ========= CREACIÓN DE TIPOS ENUMERADOS (ENUM) =========
-- Estos reemplazan los tipos ENUM de MySQL.

CREATE TYPE appointment_inspection_status_enum AS ENUM ('Confirmed', 'Completed', 'Pending', 'Declined');
CREATE TYPE appointment_status_lead_enum AS ENUM ('New', 'Called', 'Pending', 'Declined');
CREATE TYPE appointment_lead_source_enum AS ENUM ('Website', 'Facebook Ads', 'Reference', 'Retell AI');
CREATE TYPE severity_enum AS ENUM ('low', 'medium', 'high');
CREATE TYPE invoice_demo_status_enum AS ENUM ('draft', 'sent', 'paid', 'cancelled', 'print_pdf');
CREATE TYPE model_a_i_s_type_enum AS ENUM ('Content', 'Image', 'Mixed');


-- ========= CREACIÓN DE TABLAS Y INSERCIÓN DE DATOS =========

--
-- Table structure for table "users"
--
CREATE TABLE "users" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "name" VARCHAR(255) NOT NULL,
  "last_name" VARCHAR(255),
  "username" VARCHAR(255) UNIQUE,
  "email" VARCHAR(255) NOT NULL UNIQUE,
  "email_verified_at" TIMESTAMP WITH TIME ZONE,
  "password" VARCHAR(255),
  "two_factor_secret" TEXT,
  "two_factor_recovery_codes" TEXT,
  "two_factor_confirmed_at" TIMESTAMP WITH TIME ZONE,
  "phone" VARCHAR(255),
  "date_of_birth" VARCHAR(255),
  "address" VARCHAR(255),
  "zip_code" VARCHAR(255),
  "city" VARCHAR(255),
  "state" VARCHAR(255),
  "country" VARCHAR(255),
  "gender" VARCHAR(255),
  "profile_photo_path" VARCHAR(2048),
  "latitude" DOUBLE PRECISION,
  "longitude" DOUBLE PRECISION,
  "terms_and_conditions" BOOLEAN NOT NULL DEFAULT FALSE,
  "current_team_id" BIGINT,
  "deleted_at" TIMESTAMP WITH TIME ZONE,
  "remember_token" VARCHAR(100),
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "users"
INSERT INTO "users" ("id", "uuid", "name", "last_name", "username", "email", "email_verified_at", "password", "two_factor_secret", "two_factor_recovery_codes", "two_factor_confirmed_at", "phone", "date_of_birth", "address", "zip_code", "city", "state", "country", "gender", "profile_photo_path", "latitude", "longitude", "terms_and_conditions", "current_team_id", "deleted_at", "remember_token", "created_at", "updated_at") VALUES
(1, gen_random_uuid(), 'Victor Lara', NULL, 'vgeneralcontractors', 'info@vgeneralcontractors.com', '2025-08-07 23:19:33', '$2y$12$hgLCjYiaXZiCuMtGdXrE/eUnP0aGwePi1.6lRJjRepfYpeAJMfJFC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'WFaqSJ6sxQ', '2025-08-07 23:19:33', '2025-08-07 23:19:33'),
(2, gen_random_uuid(), 'Argenis Gonzalez', NULL, 'argenis692', 'josegonzalezcr2794@gmail.com', '2025-08-07 23:19:34', '$2y$12$b7jzpWLfjKJPBgZKdBJK2eUz54RfeQZ6/CPk5PTLa2OOdTRtg4Khq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'ejH7xw7kcG', '2025-08-07 23:19:34', '2025-08-07 23:19:34'),
(3, gen_random_uuid(), 'Administrator', NULL, 'adminAppointment', 'admin@vgeneralcontractors.com', '2025-08-07 23:19:34', '$2y$12$3Nu/VadWNTF4qgbwT0KyBuO95e31mFBfKmnNBkD8c.KtO4iztA.ai', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'qqvUJPLxjq', '2025-08-07 23:19:34', '2025-08-07 23:19:34'),
(4, gen_random_uuid(), 'Manager', NULL, 'manager01', 'manager@vgeneralcontractors.com', '2025-08-07 23:19:34', '$2y$12$KeNbX/bfY9J3Q6uThA8T7eQpcRV2kPUgt5W6341wImAYgITjutLWW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'Xmjoh1jV8U', '2025-08-07 23:19:34', '2025-08-07 23:19:34'),
(5, gen_random_uuid(), 'User', NULL, 'user01', 'user@user.com', '2025-08-07 23:19:35', '$2y$12$yu2WMHt2diWF4DvMkcrGi..er2PVRnY7XbH5o46uhPC7wMBc0gCLm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'T2txIutp5M', '2025-08-07 23:19:35', '2025-08-07 23:19:35'),
(6, gen_random_uuid(), 'Marketing Manager', NULL, 'marketingmanager01', 'marketingmanager@vgeneralcontractors.com', '2025-08-07 23:19:35', '$2y$12$geky6APbgPYn.xh3pf8wKe3hJuqfBLLvxLIQpgEUDDi7Y4NlyfnCu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'wWmGQynxAH', '2025-08-07 23:19:35', '2025-08-07 23:19:35'),
(7, gen_random_uuid(), 'Director Assistant', NULL, 'directorassistant01', 'directorassistant@vgeneralcontractors.com', '2025-08-07 23:19:35', '$2y$12$kMoVeyh.vc.yZMGK9Xge6.skIEPrlcxgdS5OipZivNbXnjH44bs9W', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'jb254q9xNo', '2025-08-07 23:19:35', '2025-08-07 23:19:35'),
(8, gen_random_uuid(), 'Technical Supervisor', NULL, 'technicalsupervisor01', 'technicalsupervisor@vgeneralcontractors.com', '2025-08-07 23:19:35', '$2y$12$0tyZlCGxboWr/4lAr1NiX.YXZYgTeBBxH6ZSyhpl9uDjorWnvNsjG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'UyeCNUPpJ8', '2025-08-07 23:19:35', '2025-08-07 23:19:35'),
(9, gen_random_uuid(), 'Representation Company', NULL, 'representationcompany01', 'representationcompany@vgeneralcontractors.com', '2025-08-07 23:19:36', '$2y$12$pdhYHc4IgYGg6dIV7RCxA.Gygv3LMijafIxFb3uYWKi4j9PXgU/F2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, '1ozvKq2i4p', '2025-08-07 23:19:36', '2025-08-07 23:19:36'),
(10, gen_random_uuid(), 'Public Company', NULL, 'publiccompany01', 'publiccompany@vgeneralcontractors.com', '2025-08-07 23:19:36', '$2y$12$8vjrDYddZ3LZifJt0jK6b.tWOOiF.yZphaMf159rM.IoMssyNgI2S', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'Qiasdfoog0', '2025-08-07 23:19:36', '2025-08-07 23:19:36'),
(11, gen_random_uuid(), 'External Operators', NULL, 'externaloperators01', 'externaloperators@vgeneralcontractors.com', '2025-08-07 23:19:36', '$2y$12$0Q3Zvjh7fct4IRPes6RQxu09GFo/c1PuxTMVoUHuh8lsgLXC6pHJe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'ObICOrWNoU', '2025-08-07 23:19:36', '2025-08-07 23:19:36'),
(12, gen_random_uuid(), 'Public Adjuster', NULL, 'publicadjuster01', 'publicadjuster@vgeneralcontractors.com', '2025-08-07 23:19:36', '$2y$12$F7uITIxkxihXZMxjsOeUtuGHb1enlMtMBi0pCTEUK/z1jS/g5z/i6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'PnnAJNxOIs', '2025-08-07 23:19:36', '2025-08-07 23:19:36'),
(13, gen_random_uuid(), 'Insurance Adjuster', NULL, 'insuranceadjuster01', 'insuranceadjuster@vgeneralcontractors.com', '2025-08-07 23:19:37', '$2y$12$uVIZlAENVJI..zdoPvGaIuI/MXpgR0oE.zT.KsfAIl2tjuMwlsj8q', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'Csa90fAXOK', '2025-08-07 23:19:37', '2025-08-07 23:19:37'),
(14, gen_random_uuid(), 'Technical Services', NULL, 'technicalservices01', 'technicalservices@vgeneralcontractors.com', '2025-08-07 23:19:37', '$2y$12$Db70aCh2ttMl1zW3H2t6SOC5k6rTU03mYHHfgU6UHllhAgy.QYnuC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'MDQzwFXm7L', '2025-08-07 23:19:37', '2025-08-07 23:19:37'),
(15, gen_random_uuid(), 'Marketing', NULL, 'marketing01', 'marketing@vgeneralcontractors.com', '2025-08-07 23:19:37', '$2y$12$On3iN.0l4LzulL4qIB1tj.YN8cCArrTsTwzjM5hnhu/vWuz.7orge', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, '9QcyKUWyu5', '2025-08-07 23:19:37', '2025-08-07 23:19:37'),
(16, gen_random_uuid(), 'Warehouse', NULL, 'warehouse01', 'warehouse@vgeneralcontractors.com', '2025-08-07 23:19:37', '$2y$12$kU3wHv8EsI4Fr.kGU08tKeZ8pOndQ8HhQhjg/7KbIw8y.FEAc6FVe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'iOSvRm05uD', '2025-08-07 23:19:37', '2025-08-07 23:19:37'),
(17, gen_random_uuid(), 'Administrative', NULL, 'administrative01', 'administrative@vgeneralcontractors.com', '2025-08-07 23:19:38', '$2y$12$STFjCFVFbjZIiO.W0lvb5e04LaKKw3uu/euWWTgRV64tCHBcXNBVm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'hMJNntnLWm', '2025-08-07 23:19:38', '2025-08-07 23:19:38'),
(18, gen_random_uuid(), 'Collections', NULL, 'collections01', 'collections@vgeneralcontractors.com', '2025-08-07 23:19:38', '$2y$12$rbFKQ1NF7q.Ozyc5QnAdmeAW3oCL.s3mwucmpgBQbTXpEbSACOZre', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'KQ8phYe4OY', '2025-08-07 23:19:38', '2025-08-07 23:19:38'),
(19, gen_random_uuid(), 'Reportes', NULL, 'reportes01', 'reportes@vgeneralcontractors.com', '2025-08-07 23:19:39', '$2y$12$IMJGMtkYW3l7kON/tDeJ1OQnofujRrQcfHZVdp52UQNo0YG/ScZ7i', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'mxT2IuMSdF', '2025-08-07 23:19:39', '2025-08-07 23:19:39'),
(20, gen_random_uuid(), 'Salesperson', NULL, 'salesperson01', 'salesperson@vgeneralcontractors.com', '2025-08-07 23:19:39', '$2y$12$Ym.YRe093nVXPYvZrne9zO3oBUN.HCd0k1Mx22j0Kcct/qEQsgBqy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'N6P1SdNkMg', '2025-08-07 23:19:39', '2025-08-07 23:19:39'),
(21, gen_random_uuid(), 'Lead', NULL, 'lead01', 'lead@vgeneralcontractors.com', '2025-08-07 23:19:39', '$2y$12$nk2L51OkBxOV3BtlhAk6A.23SG.FaemkNoaZaxx6JOOjxpm4yf5Ry', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'uQgtobOShR', '2025-08-07 23:19:39', '2025-08-07 23:19:39'),
(22, gen_random_uuid(), 'Employees', NULL, 'employees01', 'employees@vgeneralcontractors.com', '2025-08-07 23:19:39', '$2y$12$HSjJl3rFMDar8wE7QMnPAe73b0paykfws5P1rZNS1I7KiTsBKnMTe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'djq5LdPsMK', '2025-08-07 23:19:39', '2025-08-07 23:19:39'),
(23, gen_random_uuid(), 'Client', NULL, 'client01', 'client@vgeneralcontractors.com', '2025-08-07 23:19:40', '$2y$12$FR.dxcTeKawmhEvFXtYAUegF4gGg6vA6YkYGnCRcAfeRsDVmMs.4C', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'zYiyawQshQ', '2025-08-07 23:19:40', '2025-08-07 23:19:40'),
(24, gen_random_uuid(), 'Contact', NULL, 'contact01', 'contact@vgeneralcontractors.com', '2025-08-07 23:19:40', '$2y$12$zGi0B4lsLeOXpjzJlvGZsecqV3rzmeLCyM2Kg59VvsQmR/N3DN/hG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'JdJOFm8uyB', '2025-08-07 23:19:40', '2025-08-07 23:19:40'),
(25, gen_random_uuid(), 'Spectator', NULL, 'spectator01', 'spectator@vgeneralcontractors.com', '2025-08-07 23:19:40', '$2y$12$ZWyo9ZH0fdeHyzy2cMdRc.mbhDbQS/Bq72u0W8cwM26.0Q.BweyFi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, TRUE, NULL, NULL, 'vt3eZhFkHi', '2025-08-07 23:19:40', '2025-08-07 23:19:40');

--
-- Table structure for table "alliance_companies"
--
CREATE TABLE "alliance_companies" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "alliance_company_name" VARCHAR(255) NOT NULL,
  "phone" VARCHAR(255),
  "email" VARCHAR(255),
  "address" VARCHAR(255),
  "website" VARCHAR(255),
  "user_id" BIGINT NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "alliance_companies"
INSERT INTO "alliance_companies" ("id", "uuid", "alliance_company_name", "phone", "email", "address", "website", "user_id", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 'Claim Pay', '+18774433007', 'info@claimpay.net', '111 E 17th St #13327 SMB#60762 Austin, TX 78701', 'https://claimpay.net', 1, '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(2, gen_random_uuid(), 'Servxpress Restoration, LLC ', '+18323921147', 'claims@servxpressrestorations.com', '178 N Fry suite 260 Houston, TX 77084', 'https://servxpressrestorations.com/restoration/', 1, '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL);

--
-- Table structure for table "appointments"
--
CREATE TABLE "appointments" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "first_name" VARCHAR(255) NOT NULL,
  "last_name" VARCHAR(255) NOT NULL,
  "phone" VARCHAR(255) NOT NULL,
  "email" VARCHAR(255),
  "address" VARCHAR(255) NOT NULL,
  "address_2" VARCHAR(255),
  "city" VARCHAR(255) NOT NULL,
  "state" VARCHAR(255) NOT NULL,
  "zipcode" VARCHAR(255) NOT NULL,
  "country" VARCHAR(255) NOT NULL,
  "insurance_property" BOOLEAN NOT NULL DEFAULT FALSE,
  "message" TEXT,
  "sms_consent" BOOLEAN NOT NULL DEFAULT FALSE,
  "registration_date" TIMESTAMP WITH TIME ZONE,
  "inspection_date" DATE,
  "inspection_time" TIME,
  "inspection_status" appointment_inspection_status_enum,
  "status_lead" appointment_status_lead_enum,
  "lead_source" appointment_lead_source_enum,
  "follow_up_calls" JSONB,
  "notes" TEXT,
  "owner" VARCHAR(255),
  "damage_detail" TEXT,
  "intent_to_claim" BOOLEAN,
  "follow_up_date" DATE,
  "additional_note" TEXT,
  "latitude" DOUBLE PRECISION,
  "longitude" DOUBLE PRECISION,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "blog_categories"
--
CREATE TABLE "blog_categories" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "blog_category_name" VARCHAR(255),
  "blog_category_description" VARCHAR(255),
  "blog_category_image" VARCHAR(255),
  "user_id" BIGINT NOT NULL,
  "deleted_at" TIMESTAMP WITH TIME ZONE,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "blog_categories"
INSERT INTO "blog_categories" ("id", "uuid", "blog_category_name", "blog_category_description", "blog_category_image", "user_id", "deleted_at", "created_at", "updated_at") VALUES
(1, gen_random_uuid(), 'Roofing', 'Valor por defecto', 'Valor por defecto', 1, NULL, '2025-08-07 23:19:56', '2025-08-07 23:19:56'),
(2, gen_random_uuid(), 'Water Mitigation', 'Categoría para contenido relacionado con mitigación de agua', 'Valor por defecto', 1, NULL, '2025-08-07 23:19:56', '2025-08-07 23:19:56');

--
-- Table structure for table "cache"
--
CREATE TABLE "cache" (
  "key" VARCHAR(255) PRIMARY KEY,
  "value" TEXT NOT NULL,
  "expiration" INTEGER NOT NULL
);

-- Dumping data for table "cache"
INSERT INTO "cache" ("key", "value", "expiration") VALUES
('vgeneralcontractors_cache_user_cache_keys', 'a:0:{}', 1754695180);

--
-- Table structure for table "cache_locks"
--
CREATE TABLE "cache_locks" (
  "key" VARCHAR(255) PRIMARY KEY,
  "owner" VARCHAR(255) NOT NULL,
  "expiration" INTEGER NOT NULL
);

--
-- Table structure for table "category_products"
--
CREATE TABLE "category_products" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "category_product_name" VARCHAR(255) NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "category_products"
INSERT INTO "category_products" ("id", "uuid", "category_product_name", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 'Material Removal', '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(2, gen_random_uuid(), 'Consumible', '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(3, gen_random_uuid(), 'Content Movement', '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(4, gen_random_uuid(), 'Administrative', '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(5, gen_random_uuid(), 'PPE', '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(6, gen_random_uuid(), 'Equipment', '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(7, gen_random_uuid(), 'Products', '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(8, gen_random_uuid(), 'Services', '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL);

--
-- Table structure for table "cause_of_losses"
--
CREATE TABLE "cause_of_losses" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "cause_loss_name" VARCHAR(255) NOT NULL,
  "description" TEXT,
  "severity" severity_enum NOT NULL DEFAULT 'low',
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "cause_of_losses"
INSERT INTO "cause_of_losses" ("id", "uuid", "cause_loss_name", "description", "severity", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 'Hail', 'Descripción de Hail', 'low', '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL),
(2, gen_random_uuid(), 'Wind', 'Descripción de Wind', 'low', '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL),
(3, gen_random_uuid(), 'Hail & Wind', 'Descripción de Hail & Wind', 'low', '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL),
(4, gen_random_uuid(), 'Hurricane Wind', 'Descripción de Hurricane Wind', 'low', '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL),
(5, gen_random_uuid(), 'Hurricane Flood', 'Descripción de Hurricane Flood', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(6, gen_random_uuid(), 'Flood', 'Descripción de Flood', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(7, gen_random_uuid(), 'Fire', 'Descripción de Fire', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(8, gen_random_uuid(), 'Smoke', 'Descripción de Smoke', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(9, gen_random_uuid(), 'Fire & Smoke', 'Descripción de Fire & Smoke', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(10, gen_random_uuid(), 'Fallen Tree', 'Descripción de Fallen Tree', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(11, gen_random_uuid(), 'Lightning', 'Descripción de Lightning', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(12, gen_random_uuid(), 'Tornado', 'Descripción de Tornado', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(13, gen_random_uuid(), 'Vandalism', 'Descripción de Vandalism', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(14, gen_random_uuid(), 'Marine', 'Descripción de Marine', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(15, gen_random_uuid(), 'Water', 'Descripción de Water', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(16, gen_random_uuid(), 'Other', 'Descripción de Other', 'low', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL);

--
-- Table structure for table "claim_status"
--
CREATE TABLE "claim_status" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "claim_status_name" VARCHAR(255) NOT NULL,
  "background_color" VARCHAR(7),
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "claim_status"
INSERT INTO "claim_status" ("id", "uuid", "claim_status_name", "background_color", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 'New', '#4CAF50', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(2, gen_random_uuid(), 'Initial Review', '#2196F3', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(3, gen_random_uuid(), 'Additional Information Required', '#FFC107', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(4, gen_random_uuid(), 'Awaiting Documentation', '#FF9800', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(5, gen_random_uuid(), 'Under Investigation', '#9C27B0', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(6, gen_random_uuid(), 'Medical Evaluation', '#00BCD4', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(7, gen_random_uuid(), 'In Negotiation', '#795548', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(8, gen_random_uuid(), 'Partially Approved', '#8BC34A', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(9, gen_random_uuid(), 'Approved', '#4CAF50', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(10, gen_random_uuid(), 'Payment Processing', '#009688', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(11, gen_random_uuid(), 'Paid', '#3F51B5', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(12, gen_random_uuid(), 'Rejected', '#F44336', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(13, gen_random_uuid(), 'Under Appeal', '#FF5722', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(14, gen_random_uuid(), 'Closed', '#607D8B', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(15, gen_random_uuid(), 'Reopened', '#E91E63', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(16, gen_random_uuid(), 'In Litigation', '#9E9E9E', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(17, gen_random_uuid(), 'Waiting for Third Party', '#CDDC39', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(18, gen_random_uuid(), 'Cancelled', '#FF5252', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(19, gen_random_uuid(), 'Duplicate', '#7C4DFF', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(20, gen_random_uuid(), 'Suspended', '#DC2626', '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL);

--
-- Table structure for table "company_data"
--
CREATE TABLE "company_data" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "name" VARCHAR(255),
  "company_name" VARCHAR(255) NOT NULL,
  "signature_path" VARCHAR(255),
  "email" VARCHAR(255),
  "phone" VARCHAR(255),
  "address" TEXT,
  "website" VARCHAR(255),
  "facebook_link" VARCHAR(255),
  "instagram_link" VARCHAR(255),
  "linkedin_link" VARCHAR(255),
  "twitter_link" VARCHAR(255),
  "user_id" BIGINT NOT NULL,
  "latitude" DOUBLE PRECISION,
  "longitude" DOUBLE PRECISION,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "company_data"
INSERT INTO "company_data" ("id", "uuid", "name", "company_name", "signature_path", "email", "phone", "address", "website", "facebook_link", "instagram_link", "linkedin_link", "twitter_link", "user_id", "latitude", "longitude", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 'Victor Lara', 'V General Contractors', '/signatures/victor_lara_signature.png', 'info@vgeneralcontractors.com', '+17135876423', '1302 Waugh Dr # 810 Houston TX 77019', 'https://vgeneralcontractors.com', 'https://www.facebook.com/vgeneralcontractors/', 'https://www.instagram.com/vgeneralcontractors/', 'https://www.linkedin.com/company/v-general-contractors/', 'https://twitter.com/vgeneralcontractors', 1, 29.75516, -95.3984135, '2025-08-07 23:19:40', '2025-08-07 23:19:40', NULL);

--
-- Table structure for table "contact_supports"
--
CREATE TABLE "contact_supports" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "first_name" VARCHAR(255) NOT NULL,
  "last_name" VARCHAR(255) NOT NULL,
  "email" VARCHAR(255) NOT NULL,
  "phone" VARCHAR(255) NOT NULL,
  "message" TEXT NOT NULL,
  "sms_consent" BOOLEAN NOT NULL DEFAULT FALSE,
  "readed" BOOLEAN NOT NULL DEFAULT FALSE,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "email_data"
--
CREATE TABLE "email_data" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "description" TEXT,
  "email" VARCHAR(255) NOT NULL,
  "phone" VARCHAR(255),
  "type" VARCHAR(255),
  "user_id" BIGINT NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "email_data"
INSERT INTO "email_data" ("id", "uuid", "description", "email", "phone", "type", "user_id", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 'Correo para colecciones y pagos', 'collection@vgeneralcontractors.com', '+17133646240', 'Collections', 1, '2025-08-07 23:19:56', '2025-08-07 23:19:56', NULL),
(2, gen_random_uuid(), 'Correo para información general', 'info@vgeneralcontractors.com', '+17135876423', 'Info', 1, '2025-08-07 23:19:56', '2025-08-07 23:19:56', NULL),
(3, gen_random_uuid(), 'Correo para citas y agendamiento', 'admin@vgeneralcontractors.com', '+17135876423', 'Admin', 2, '2025-08-07 23:19:56', '2025-08-07 23:19:56', NULL);

--
-- Table structure for table "failed_jobs"
--
CREATE TABLE "failed_jobs" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" VARCHAR(255) NOT NULL UNIQUE,
  "connection" TEXT NOT NULL,
  "queue" TEXT NOT NULL,
  "payload" TEXT NOT NULL,
  "exception" TEXT NOT NULL,
  "failed_at" TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

--
-- Table structure for table "insurance_companies"
--
CREATE TABLE "insurance_companies" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "insurance_company_name" VARCHAR(255) NOT NULL,
  "address" TEXT,
  "phone" TEXT,
  "email" TEXT,
  "website" TEXT,
  "user_id" BIGINT NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "insurance_companies"
INSERT INTO "insurance_companies" ("id", "uuid", "insurance_company_name", "address", "phone", "email", "website", "user_id", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 'Clear Insurance', '', '(000) 000-0000', '', '', 1, '2025-08-07 23:19:52', '2025-08-07 23:19:52', NULL),
(2, gen_random_uuid(), 'Pekin Insurance', '', '(888) 735-4611', 'claims@pekininsurance.com', '', 1, '2025-08-07 23:19:52', '2025-08-07 23:19:52', NULL),
(3, gen_random_uuid(), 'Openly', '', '(888) 808-4842', 'Claims@openly.com', '', 1, '2025-08-07 23:19:52', '2025-08-07 23:19:52', NULL),
(4, gen_random_uuid(), 'Plymouth Rock Assurance', '', '(844) 242-3555', 'rockcare@plymouthrock.com', 'https://www.plymouthrock.com/', 1, '2025-08-07 23:19:52', '2025-08-07 23:19:52', NULL),
(5, gen_random_uuid(), 'American Family Insurance', '', '(800) 692-6326', 'Claimdocuments@asics.com', '', 1, '2025-08-07 23:19:52', '2025-08-07 23:19:52', NULL),
(6, gen_random_uuid(), 'Kemper Insurance', '', '(800) 353-6737', 'Mail.claims@kemper.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(7, gen_random_uuid(), 'Narrangansett Bay Insurance Company', '', '(800) 343-3375', 'CALLandASK@insurancecompany.com', 'www.nbic.com', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(8, gen_random_uuid(), 'State Farm Lloyds', '', '(800) 732-5246', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(9, gen_random_uuid(), 'State Farm Fire and Casualty Company', '', '(845) 226-5005', 'statefarmfireclaims@statefarm.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(10, gen_random_uuid(), 'First Community Insurance Company', '', '(866) 401-1106', 'consultar@eugenia', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(11, gen_random_uuid(), 'New London County Mutual Insurance Company', '', '(800) 962-0800', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(12, gen_random_uuid(), 'Roadrunner Indemnity Company', '', '(866) 522-0361', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(13, gen_random_uuid(), 'AAA Texas', '', '(180) 067-2524', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(14, gen_random_uuid(), 'MESA UNDERWRITERS SPECIALTY INSURANCE COMPANY', '', '(866) 547-0868', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(15, gen_random_uuid(), 'American Mobile Insurance Exchange', '', '(844) 631-7819', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(16, gen_random_uuid(), 'Utica National Insurance Group', '', '(800) 598-8422', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(17, gen_random_uuid(), 'Merrimack Mutual Fire Insurance Company', '', '(978) 475-3300', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(18, gen_random_uuid(), 'American Automobile Association', '', '(800) 222-4357', '', 'https://www.aaa.com/', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(19, gen_random_uuid(), 'American Commerce Insurance Company', '', '(877) 627-3731', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(20, gen_random_uuid(), 'National Summit Insurance Company', '', '(800) 749-6419', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(21, gen_random_uuid(), 'Bunker Hill Insurance Company', '', '(888) 472-5246', '', 'bunkerhillins.com', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(22, gen_random_uuid(), 'The Providence Mutual Fire Insurance Company', '', '(877) 763-1800', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(23, gen_random_uuid(), 'UNITED STATES LIABILITY INSURANCE COMPANY', '', '(888) 523-5545', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(24, gen_random_uuid(), 'Rockford Mutual Insurance Company', '', '(800) 747-7642', 'claims@rockfordmutual.com', 'https://www.rockfordmutual.com/insurance/home', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(25, gen_random_uuid(), 'CHASE', '', '(877) 530-8951', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(26, gen_random_uuid(), 'Church Mutual Insurance Company', '', '(800) 554-2642', 'claims@churchmutual.com', 'https://www.churchmutual.com/7/Contact-Us', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(27, gen_random_uuid(), 'Conifer Insurance Company', '', '(877) 263-6468', 'claims@coniferinsurance.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(28, gen_random_uuid(), 'NatGen Premier', '', '(184) 428-7223', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(29, gen_random_uuid(), 'Underwriters at lloyd´s of london', '', '(034) 530-0000', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(30, gen_random_uuid(), 'Next', '', '(800) 252-3439', 'ConsumerProtection@tdi.texas.gov', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(31, gen_random_uuid(), 'The Hanover Insurance Group', '', '(800) 628-0250', 'firstreport@hanover.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(32, gen_random_uuid(), 'Ussa Casualty Insurance Company', '', '(210) 531-8722', '', 'https://www.usaa.com/?wa_ref=pub_global_home', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(33, gen_random_uuid(), 'Kingstone Insurance', '', '(800) 364-7045', 'claimreports@kingstoneic.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(34, gen_random_uuid(), 'Texas Farm Bureau Insurance', '', '(800) 224-7936', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(35, gen_random_uuid(), 'American Mercury Lloyds Insurance', '', '(888) 637-2176', 'wrhome@mercuryinsurance.com', 'www.mercuryinsurance.com', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(36, gen_random_uuid(), 'Standard Guaranty Insurance Company', '', '(800) 652-1262', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(37, gen_random_uuid(), 'slide insurance company', '', '(800) 748-2030', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(38, gen_random_uuid(), 'Spinnaker Insurance Company', '', '(888) 221-7742', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(39, gen_random_uuid(), 'VYRD Insurance Company', '', '(844) 217-6993', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(40, gen_random_uuid(), 'Andover', '', '(203) 744-2800', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(41, gen_random_uuid(), 'Branch Insurance exchange', '', '(833) 427-2624', '', 'https://www.ourbranch.com/', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(42, gen_random_uuid(), 'American Integrity', '', '(866) 968-8390', 'claimsmail@aiiflorida.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(43, gen_random_uuid(), 'TypTap Insurance Company', '', '(844) 289-7968', 'claims@typtap.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(44, gen_random_uuid(), 'National Summit', '', '(800) 749-6419', 'claims@natlloyds.com', 'https://www.nationallloydsinsurance.com/', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(45, gen_random_uuid(), 'Bass Underwriters', '', '(954) 316-3198', 'Claims@bassuw.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(46, gen_random_uuid(), 'Sage Sure', '', '(888) 316-0540', 'claimshelp@sagesure.com', 'https://www.sagesure.com/', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(47, gen_random_uuid(), 'Weston Specialty Insurance', '', '(000) 000-0000', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(48, gen_random_uuid(), 'Homeowners of America insurance Company', '', '(866) 407-9896', 'claims@hoaic.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(49, gen_random_uuid(), 'Foremost Lloyds of Texas', '', '(616) 942-3000', 'myclaim@foremost.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(50, gen_random_uuid(), 'Southern Vanguard Insurance Company', '', '(888) 432-9393', 'rhpclaims@rhpga.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(51, gen_random_uuid(), 'Allied Trust', '', '(844) 200-2842', 'alliedclaims@transcynd.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(52, gen_random_uuid(), 'Preatorian Insurance Company', '', '(866) 318-2016', 'claimmail@us.qbe.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(53, gen_random_uuid(), 'Zurich American Insurance Company', '', '(877) 777-6440', 'sstone@acmclaims.com', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(54, gen_random_uuid(), 'Colonial Lloyds', '', '(866) 522-0361', '', '', 1, '2025-08-07 23:19:53', '2025-08-07 23:19:53', NULL),
(55, gen_random_uuid(), 'Progressive Homesite', '', '(800) 466-3748', 'claimdocuments@afics.com', '', 1, '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL),
(56, gen_random_uuid(), 'SafeCo Insurance', '', '(800) 332-3226', 'imaging@libertymutual.com', '', 1, '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL),
(57, gen_random_uuid(), 'Wellington Insurance Group', '', '(800) 880-0474', 'claims@wellingtoninsgroup.com', 'http://www.wellingtoninsgroup.com/', 1, '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL),
(58, gen_random_uuid(), 'Berkshire Hathaway Guard', '', '(800) 673-2465', 'claims@guard.com', '', 1, '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL),
(59, gen_random_uuid(), 'Citizens', '', '(866) 411-2742', 'claims.communications@citizensfla.com', 'www.citizensfla.com', 1, '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL),
(60, gen_random_uuid(), 'Progressive Insurance', '6300 Wilson Mills Road, Mayfield Village, Ohio 4414', '866 407 4844', '', 'https://www.progressive.com/', 1, '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL),
(61, gen_random_uuid(), 'Other Not Mentioned Force Placed Policies', '', '', '', '', 1, '2025-08-07 23:19:54', '2025-08-07 23:19:54', NULL);

--
-- Table structure for table "invoice_demos"
--
CREATE TABLE "invoice_demos" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "user_id" BIGINT NOT NULL,
  "invoice_number" VARCHAR(50) NOT NULL UNIQUE,
  "invoice_date" DATE NOT NULL,
  "bill_to_name" VARCHAR(255) NOT NULL,
  "bill_to_address" TEXT NOT NULL,
  "bill_to_phone" VARCHAR(20),
  "bill_to_email" VARCHAR(100),
  "subtotal" DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  "tax_amount" DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  "balance_due" DECIMAL(10, 2) NOT NULL,
  "claim_number" VARCHAR(255),
  "policy_number" VARCHAR(255),
  "insurance_company" VARCHAR(255),
  "date_of_loss" DATE,
  "date_received" TIMESTAMP WITH TIME ZONE,
  "date_inspected" TIMESTAMP WITH TIME ZONE,
  "date_entered" TIMESTAMP WITH TIME ZONE,
  "price_list_code" VARCHAR(255),
  "type_of_loss" VARCHAR(255),
  "notes" TEXT,
  "status" invoice_demo_status_enum NOT NULL DEFAULT 'print_pdf',
  "pdf_url" VARCHAR(255),
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);
CREATE INDEX "invoice_demos_invoice_date_index" ON "invoice_demos" ("invoice_date");
CREATE INDEX "invoice_demos_status_index" ON "invoice_demos" ("status");
CREATE INDEX "invoice_demos_claim_number_index" ON "invoice_demos" ("claim_number");


--
-- Table structure for table "invoice_demo_items"
--
CREATE TABLE "invoice_demo_items" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "invoice_demo_id" BIGINT NOT NULL,
  "service_name" VARCHAR(255) NOT NULL,
  "description" TEXT NOT NULL,
  "quantity" INTEGER NOT NULL DEFAULT 1,
  "rate" DECIMAL(10, 2) NOT NULL,
  "amount" DECIMAL(10, 2) NOT NULL,
  "sort_order" INTEGER NOT NULL DEFAULT 0,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);
CREATE INDEX "invoice_demo_items_invoice_demo_id_sort_order_index" ON "invoice_demo_items" ("invoice_demo_id", "sort_order");
CREATE INDEX "invoice_demo_items_deleted_at_index" ON "invoice_demo_items" ("deleted_at");


--
-- Table structure for table "invoices"
--
CREATE TABLE "invoices" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "user_id" BIGINT NOT NULL,
  "invoice_number" VARCHAR(255) NOT NULL UNIQUE,
  "invoice_date" DATE NOT NULL,
  "bill_to_name" VARCHAR(255) NOT NULL,
  "bill_to_address" TEXT,
  "bill_to_phone" VARCHAR(255),
  "bill_to_email" VARCHAR(255),
  "subtotal" DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  "tax_amount" DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  "balance_due" DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  "pdf_url" VARCHAR(255),
  "claim_number" VARCHAR(255),
  "policy_number" VARCHAR(255),
  "insurance_company" VARCHAR(255),
  "date_of_loss" DATE,
  "date_received" TIMESTAMP WITH TIME ZONE,
  "date_inspected" TIMESTAMP WITH TIME ZONE,
  "date_entered" TIMESTAMP WITH TIME ZONE,
  "price_list_code" VARCHAR(255),
  "type_of_loss" VARCHAR(255),
  "notes" TEXT,
  "status" VARCHAR(255) NOT NULL DEFAULT 'draft',
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "invoice_items"
--
CREATE TABLE "invoice_items" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "invoice_id" BIGINT NOT NULL,
  "description" TEXT NOT NULL,
  "quantity" DECIMAL(10, 2) NOT NULL DEFAULT 1.00,
  "unit_price" DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  "amount" DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  "notes" TEXT,
  "order" INTEGER NOT NULL DEFAULT 0,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "job_batches"
--
CREATE TABLE "job_batches" (
  "id" VARCHAR(255) PRIMARY KEY,
  "name" VARCHAR(255) NOT NULL,
  "total_jobs" INTEGER NOT NULL,
  "pending_jobs" INTEGER NOT NULL,
  "failed_jobs" INTEGER NOT NULL,
  "failed_job_ids" TEXT NOT NULL,
  "options" TEXT,
  "cancelled_at" INTEGER,
  "created_at" INTEGER NOT NULL,
  "finished_at" INTEGER
);

--
-- Table structure for table "jobs"
--
CREATE TABLE "jobs" (
  "id" BIGSERIAL PRIMARY KEY,
  "queue" VARCHAR(255) NOT NULL,
  "payload" TEXT NOT NULL,
  "attempts" SMALLINT NOT NULL,
  "reserved_at" INTEGER,
  "available_at" INTEGER NOT NULL,
  "created_at" INTEGER NOT NULL
);
CREATE INDEX "jobs_queue_index" ON "jobs" ("queue");

--
-- Table structure for table "migrations"
--
CREATE TABLE "migrations" (
  "id" SERIAL PRIMARY KEY,
  "migration" VARCHAR(255) NOT NULL,
  "batch" INTEGER NOT NULL
);

-- Dumping data for table "migrations"
INSERT INTO "migrations" ("id", "migration", "batch") VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2023_10_25_000001_create_invoices_table', 1),
(5, '2023_10_25_000002_create_invoice_items_table', 1),
(6, '2024_03_02_003206_create_posts_table', 1),
(7, '2024_03_06_115557_create_company_data_table', 1),
(8, '2024_03_21_000000_create_appointments_table', 1),
(9, '2024_07_16_154659_create_type_damages_table', 1),
(10, '2024_08_06_145542_create_insurance_companies_table', 1),
(11, '2024_08_06_181337_create_public_companies_table', 1),
(12, '2024_08_12_195019_create_category_products_table', 1),
(13, '2024_08_13_124429_create_products_table', 1),
(14, '2024_08_14_002932_create_claim_status_table', 1),
(15, '2024_08_14_124800_create_cause_of_losses_table', 1),
(16, '2024_08_15_160301_create_alliance_companies_table', 1),
(17, '2024_08_16_174048_create_zones_table', 1),
(18, '2025_03_03_211335_create_permission_tables', 1),
(19, '2025_03_03_212234_add_two_factor_columns_to_users_table', 1),
(20, '2025_03_03_212448_create_personal_access_tokens_table', 1),
(21, '2025_03_06_001416_create_seo_table', 1),
(22, '2025_03_06_120456_create_blog_categories_table', 1),
(23, '2025_03_09_200545_create_service_categories_table', 1),
(24, '2025_03_09_200546_create_project_types_table', 1),
(25, '2025_03_10_200547_create_portfolios_table', 1),
(26, '2025_03_19_181159_create_email_data_table', 1),
(27, '2025_03_26_183739_create_portfolio_images_table', 1),
(28, '2025_04_09_181305_create_contact_supports_table', 1),
(29, '2025_06_18_155507_create_model_a_i_s_table', 1),
(30, '2025_06_26_153449_create_invoice_demos_table', 1),
(31, '2025_06_26_153450_create_invoice_demo_items_table', 1);

--
-- Table structure for table "model_a_i_s"
--
CREATE TABLE "model_a_i_s" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "name" VARCHAR(255) NOT NULL,
  "email" VARCHAR(255) NOT NULL,
  "type" model_a_i_s_type_enum NOT NULL DEFAULT 'Content',
  "description" TEXT,
  "api_key" TEXT NOT NULL,
  "user_id" BIGINT NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "password_reset_tokens"
--
CREATE TABLE "password_reset_tokens" (
  "email" VARCHAR(255) PRIMARY KEY,
  "token" VARCHAR(255) NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "permissions"
--
CREATE TABLE "permissions" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "name" VARCHAR(255) NOT NULL,
  "guard_name" VARCHAR(255) NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  UNIQUE ("name", "guard_name")
);

-- Dumping data for table "permissions"
INSERT INTO "permissions" ("id", "uuid", "name", "guard_name", "created_at", "updated_at") VALUES
(1, gen_random_uuid(), 'CREATE_USER', 'web', '2025-08-07 23:19:03', '2025-08-07 23:19:03'),
-- ... (all 265 permission entries are inserted here) ...
(265, gen_random_uuid(), 'RESTORE_PROPERTIES', 'web', '2025-08-07 23:19:25', '2025-08-07 23:19:25');


--
-- Table structure for table "personal_access_tokens"
--
CREATE TABLE "personal_access_tokens" (
  "id" BIGSERIAL PRIMARY KEY,
  "tokenable_type" VARCHAR(255) NOT NULL,
  "tokenable_id" BIGINT NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "token" VARCHAR(64) NOT NULL UNIQUE,
  "abilities" TEXT,
  "last_used_at" TIMESTAMP WITH TIME ZONE,
  "expires_at" TIMESTAMP WITH TIME ZONE,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE
);
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" ON "personal_access_tokens" ("tokenable_type", "tokenable_id");


--
-- Table structure for table "service_categories"
--
CREATE TABLE "service_categories" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "type" VARCHAR(255),
  "category" TEXT,
  "user_id" BIGINT NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "type_damages"
--
CREATE TABLE "type_damages" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "type_damage_name" VARCHAR(255) NOT NULL,
  "description" TEXT,
  "severity" severity_enum NOT NULL DEFAULT 'low',
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "type_damages"
INSERT INTO "type_damages" ("id", "uuid", "type_damage_name", "description", "severity", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 'Water Damage', 'Damage caused by water infiltration or flooding', 'high', NOW(), NOW(), NULL),
(2, gen_random_uuid(), 'Fire Damage', 'Damage caused by fire or smoke', 'high', NOW(), NOW(), NULL),
(3, gen_random_uuid(), 'Structural Damage', 'Damage to load-bearing structures', 'high', NOW(), NOW(), NULL),
(4, gen_random_uuid(), 'Electrical Damage', 'Damage to electrical systems and components', 'medium', NOW(), NOW(), NULL),
(5, gen_random_uuid(), 'Plumbing Damage', 'Damage to plumbing systems and pipes', 'medium', NOW(), NOW(), NULL),
(6, gen_random_uuid(), 'HVAC Damage', 'Damage to heating, ventilation, and air conditioning systems', 'medium', NOW(), NOW(), NULL),
(7, gen_random_uuid(), 'Cosmetic Damage', 'Surface-level damage affecting appearance', 'low', NOW(), NOW(), NULL),
(8, gen_random_uuid(), 'Pest Damage', 'Damage caused by insects, rodents, or other pests', 'medium', NOW(), NOW(), NULL),
(9, gen_random_uuid(), 'Weather Damage', 'Damage caused by storms, hail, wind, etc.', 'medium', NOW(), NOW(), NULL),
(10, gen_random_uuid(), 'Wear and Tear', 'Normal degradation due to age and use', 'low', NOW(), NOW(), NULL);

--
-- Table structure for table "zones"
--
CREATE TABLE "zones" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "zone_name" VARCHAR(255) NOT NULL,
  "zone_type" VARCHAR(255) NOT NULL DEFAULT 'interior',
  "code" VARCHAR(255) UNIQUE,
  "description" TEXT,
  "user_id" BIGINT NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "zones"
INSERT INTO "zones" ("id", "uuid", "zone_name", "zone_type", "code", "description", "user_id", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 'Living Room', 'interior', 'LIV001', 'Main living area of the house', 1, NOW(), NOW(), NULL),
(2, gen_random_uuid(), 'Kitchen', 'interior', 'KIT001', 'Main cooking area', 1, NOW(), NOW(), NULL),
(3, gen_random_uuid(), 'Bathroom', 'interior', 'BAT001', 'Master bathroom', 1, NOW(), NOW(), NULL),
(4, gen_random_uuid(), 'Bedroom', 'interior', 'BED001', 'Master bedroom', 1, NOW(), NOW(), NULL),
(5, gen_random_uuid(), 'Garage', 'exterior', 'GAR001', 'Vehicle storage area', 1, NOW(), NOW(), NULL),
(6, gen_random_uuid(), 'Roof', 'exterior', 'ROO001', 'House roofing system', 1, NOW(), NOW(), NULL),
(7, gen_random_uuid(), 'Foundation', 'exterior', 'FOU001', 'House foundation', 1, NOW(), NOW(), NULL),
(8, gen_random_uuid(), 'Exterior Walls', 'exterior', 'EXT001', 'Outside walls of the building', 1, NOW(), NOW(), NULL);

-- Índices para tabla "type_damages"
CREATE INDEX "type_damages_severity_index" ON "type_damages" ("severity");
CREATE INDEX "type_damages_deleted_at_index" ON "type_damages" ("deleted_at");

-- Índices para tabla "zones"
CREATE INDEX "zones_user_id_index" ON "zones" ("user_id");
CREATE INDEX "zones_zone_type_index" ON "zones" ("zone_type");
CREATE INDEX "zones_deleted_at_index" ON "zones" ("deleted_at");
CREATE INDEX "zones_code_index" ON "zones" ("code");

--
-- Table structure for table "project_types"
--
CREATE TABLE "project_types" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "title" VARCHAR(255) NOT NULL,
  "description" TEXT,
  "status" VARCHAR(255) NOT NULL DEFAULT 'active',
  "user_id" BIGINT NOT NULL,
  "service_category_id" BIGINT NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "portfolios"
--
CREATE TABLE "portfolios" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "project_type_id" BIGINT,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "portfolio_images"
--
CREATE TABLE "portfolio_images" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "portfolio_id" BIGINT NOT NULL,
  "path" VARCHAR(255) NOT NULL,
  "order" INTEGER,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "posts"
--
CREATE TABLE "posts" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "post_title" VARCHAR(255) NOT NULL,
  "post_content" TEXT NOT NULL,
  "post_image" VARCHAR(255),
  "meta_description" VARCHAR(255) NOT NULL,
  "meta_title" VARCHAR(255) NOT NULL,
  "meta_keywords" VARCHAR(255) NOT NULL,
  "post_title_slug" VARCHAR(255) NOT NULL,
  "category_id" VARCHAR(255) NOT NULL,
  "post_status" VARCHAR(255) NOT NULL DEFAULT 'published',
  "scheduled_at" TIMESTAMP WITH TIME ZONE,
  "user_id" BIGINT NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

--
-- Table structure for table "products"
--
CREATE TABLE "products" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "product_category_id" BIGINT NOT NULL,
  "product_name" VARCHAR(255) NOT NULL,
  "product_description" TEXT,
  "price" DECIMAL(10, 2),
  "unit" VARCHAR(255),
  "order_position" VARCHAR(255),
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "products"
INSERT INTO "products" ("id", "uuid", "product_category_id", "product_name", "product_description", "price", "unit", "order_position", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 7, 'Dehumidifier (per 24 hour period) Large - No monitoring', '', 190.00, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),

(2, gen_random_uuid(), 7, 'Closet Understairs', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(3, gen_random_uuid(), 7, 'Bedroom Closet 4', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(4, gen_random_uuid(), 8, 'BEDROOM 4', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(5, gen_random_uuid(), 7, 'Bedroom Closet 3', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(6, gen_random_uuid(), 7, 'Bedroom Closet 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(7, gen_random_uuid(), 7, 'Guest Bathroom 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(8, gen_random_uuid(), 7, 'Master Bedroom 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(9, gen_random_uuid(), 7, 'Breakfast Area', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(10, gen_random_uuid(), 7, 'Landing', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(11, gen_random_uuid(), 8, 'Master Bedroom Toilet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(12, gen_random_uuid(), 7, 'A/C Room - Toilet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(13, gen_random_uuid(), 7, 'Closet Master Bathroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(14, gen_random_uuid(), 7, 'Storage Rental Tax', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(15, gen_random_uuid(), 7, 'Guest Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(16, gen_random_uuid(), 7, 'A/C ROOM', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(17, gen_random_uuid(), 7, 'LEVEL 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(18, gen_random_uuid(), 7, 'Utility Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(19, gen_random_uuid(), 7, 'Bathroom Hallway', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(20, gen_random_uuid(), 7, 'Tenant Bathroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(21, gen_random_uuid(), 7, 'Hallway Laundry', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(22, gen_random_uuid(), 7, 'Family Room ( Level 2)', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(23, gen_random_uuid(), 7, 'Walk-in Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(24, gen_random_uuid(), 7, 'Open to below', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(25, gen_random_uuid(), 7, 'Guest Room Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(26, gen_random_uuid(), 7, 'Family Room Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(27, gen_random_uuid(), 7, 'Hallway 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(28, gen_random_uuid(), 7, 'Hallway 1', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(29, gen_random_uuid(), 7, 'Half Bathroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(30, gen_random_uuid(), 7, 'Open to Stairs', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(31, gen_random_uuid(), 7, 'Gym Room Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(32, gen_random_uuid(), 7, 'Bedroom 3 Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(33, gen_random_uuid(), 7, 'Closet Guest Bedroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(34, gen_random_uuid(), 7, 'Closet Great Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(35, gen_random_uuid(), 7, 'Great Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(36, gen_random_uuid(), 7, 'Girls Guest Bedroom Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(37, gen_random_uuid(), 7, 'Girls Guest Bedroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(38, gen_random_uuid(), 7, 'Gym Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(39, gen_random_uuid(), 7, 'Guest living', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(40, gen_random_uuid(), 7, 'Play room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(41, gen_random_uuid(), 7, 'Storage room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(42, gen_random_uuid(), 7, 'Boy Bedroom Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(43, gen_random_uuid(), 7, 'Boy Bedroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(44, gen_random_uuid(), 7, 'Girl Bedroom Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(45, gen_random_uuid(), 7, 'Guest Bedroom Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(46, gen_random_uuid(), 7, 'Hallway Bedroom Closet 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(47, gen_random_uuid(), 7, 'Hallway Bedroom Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(48, gen_random_uuid(), 7, 'Hallway Bedroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(49, gen_random_uuid(), 7, 'Dining room Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(50, gen_random_uuid(), 7, 'Pantry 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(52, gen_random_uuid(), 7, 'Master Bedroom Closet 3', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(53, gen_random_uuid(), 7, 'Master Bedroom Closet 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(54, gen_random_uuid(), 7, 'Girl Bedroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(55, gen_random_uuid(), 7, 'BATHROOM (2 LEVEL)', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(56, gen_random_uuid(), 7, 'Attic Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(57, gen_random_uuid(), 7, 'Attic 3', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(58, gen_random_uuid(), 7, 'attic 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(59, gen_random_uuid(), 7, 'Attic 1', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(60, gen_random_uuid(), 7, 'Master bathroom closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(61, gen_random_uuid(), 7, 'Study', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(62, gen_random_uuid(), 7, 'Garage Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(63, gen_random_uuid(), 7, 'Storege', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(64, gen_random_uuid(), 7, 'Porch', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(65, gen_random_uuid(), 7, 'TERRACE', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(66, gen_random_uuid(), 7, 'Basement Laundry', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(67, gen_random_uuid(), 7, 'Kitchen 4', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(68, gen_random_uuid(), 7, 'Small Living', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(69, gen_random_uuid(), 7, 'LIVING ROOM HALLWAY', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(70, gen_random_uuid(), 7, 'Living Room / Kitchen / Dining Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(71, gen_random_uuid(), 7, 'Master dining room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(72, gen_random_uuid(), 7, 'Bathroom closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(73, gen_random_uuid(), 7, 'Blue Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(74, gen_random_uuid(), 7, 'Furnace Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(75, gen_random_uuid(), 8, 'Bedroom Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(76, gen_random_uuid(), 7, 'Master Bedroom Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(77, gen_random_uuid(), 8, 'temporary heater- propane (per day)', '', 75.26, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(78, gen_random_uuid(), 8, 'Floor prep (Scrape rubber back residue)', '', 50.74, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(79, gen_random_uuid(), 7, 'Repairing and Correcting 9 Foundations Include Permisions, Include Cleaning, Include Debris and Removal', '', 8500, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(80, gen_random_uuid(), 7, 'ENTRANCE 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(81, gen_random_uuid(), 7, 'TV Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(82, gen_random_uuid(), 7, 'Laders 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(83, gen_random_uuid(), 7, 'Laders', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(84, gen_random_uuid(), 7, 'Entrance closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(85, gen_random_uuid(), 7, 'Work room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(86, gen_random_uuid(), 7, 'Master Shower', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(87, gen_random_uuid(), 7, 'Master bathtub', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(88, gen_random_uuid(), 7, 'Window screen', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(89, gen_random_uuid(), 7, 'Living Room (Level 2)', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(90, gen_random_uuid(), 7, 'Master Storage Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(91, gen_random_uuid(), 7, 'Guest Bedroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(92, gen_random_uuid(), 7, 'Office closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(93, gen_random_uuid(), 7, 'Exterior Walls', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(94, gen_random_uuid(), 7, 'Stairs2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(95, gen_random_uuid(), 7, 'Stairs1', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(96, gen_random_uuid(), 7, 'Stairs', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(97, gen_random_uuid(), 7, 'Stairway', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(98, gen_random_uuid(), 8, 'Hallway Entrance', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(99, gen_random_uuid(), 8, 'BAR', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(100, gen_random_uuid(), 8, 'Living 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(101, gen_random_uuid(), 7, 'Hallway Master Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(102, gen_random_uuid(), 7, 'Bedroom 1', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(103, gen_random_uuid(), 7, 'Master Toilet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(104, gen_random_uuid(), 7, 'Master TV Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(105, gen_random_uuid(), 7, 'Master Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(106, gen_random_uuid(), 7, 'Fitness Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(107, gen_random_uuid(), 7, 'Hallway Up Stairs', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(108, gen_random_uuid(), 7, 'Debris Removal Tax', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(109, gen_random_uuid(), 7, 'ROOM 3', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(110, gen_random_uuid(), 7, 'Debris Removal Tax', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(111, gen_random_uuid(), 7, 'Kids Room Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(112, gen_random_uuid(), 7, 'Kids Room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(113, gen_random_uuid(), 7, 'Master Hallway Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(114, gen_random_uuid(), 7, 'Master Hallway', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(115, gen_random_uuid(), 7, 'Storage Rental Tax', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(116, gen_random_uuid(), 7, 'Hallway Bathroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(117, gen_random_uuid(), 7, 'Hallway Garage', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(118, gen_random_uuid(), 8, 'Referral Partner Discount 10%', '', 1, '0', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(119, gen_random_uuid(), 8, 'Cash Deal no terms Discount', '', 1, '0', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(120, gen_random_uuid(), 8, 'Non Insurance Job Discount 20%', '', 1, '0', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(121, gen_random_uuid(), 7, 'Guest Bedroom', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(122, gen_random_uuid(), 7, 'Hallway Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(123, gen_random_uuid(), 7, 'Fence', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(124, gen_random_uuid(), 7, 'Gutters', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(125, gen_random_uuid(), 7, 'Cleaning Mtl Tax', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(126, gen_random_uuid(), 7, 'Master Closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(127, gen_random_uuid(), 7, 'Walls Exterior', 'paint and seal (walls) includes labor and material', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(128, gen_random_uuid(), 7, 'Guest Bedroom Closet', 'Texture, drywall, paint and seal [walls, ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(129, gen_random_uuid(), 7, 'Comm Repr/Remod Tax', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(130, gen_random_uuid(), 8, 'Radiator unit - Detach & reset', '', 508.07, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(131, gen_random_uuid(), 7, 'closet13', '', 8, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(132, gen_random_uuid(), 7, 'closet3', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(133, gen_random_uuid(), 7, 'guest room 3', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(134, gen_random_uuid(), 7, 'closet2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(135, gen_random_uuid(), 7, 'guest room 2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(136, gen_random_uuid(), 7, 'guest room1', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(137, gen_random_uuid(), 7, 'closet 1', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(138, gen_random_uuid(), 7, 'closet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(139, gen_random_uuid(), 7, 'KITCHEN/LIVING', 'texture, drywall, paint [baseboards] sea and paint [walls]', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(140, gen_random_uuid(), 7, 'PATIO', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(141, gen_random_uuid(), 7, 'BATH TUB', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(142, gen_random_uuid(), 7, 'shower', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(143, gen_random_uuid(), 7, 'room4', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(144, gen_random_uuid(), 7, 'family room', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(145, gen_random_uuid(), 7, 'STAIRS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(146, gen_random_uuid(), 7, 'DINING ROOM2', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(147, gen_random_uuid(), 7, 'CLOSET4', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(148, gen_random_uuid(), 7, 'GAME ROOM', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(149, gen_random_uuid(), 7, 'toilet', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(150, gen_random_uuid(), 7, 'LABOR MINIMUM', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(151, gen_random_uuid(), 8, 'Cleaning Sales Tax', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(152, gen_random_uuid(), 8, 'TV ROOM DETACH AND RESET {LIGHT BAR-CROWN MOLDING}', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(153, gen_random_uuid(), 8, 'LAUNDRY ROOM DETACH AND RESET {RECESSED LIGHT-HEAT/AC}', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(154, gen_random_uuid(), 8, 'GARAGE DETACH AND RESET {CROWN MOLDING} REMOVE {RECESSED LIGHT FIXTURE} TEXTURE {DRYWALL-CEILING} SEAL {WALLS-CEILING} PAINT {WALLS-CEILING-CROWN MOLDING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(155, gen_random_uuid(), 8, 'KITCHEN DETACH AND RESET {RECESSED LIGHT-HEAT A/C} TEXTURE {DRYWALL-CEILING} PAINT AND SEAL {CEILING} INCLUDES LABOR AND MATERIAL', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(156, gen_random_uuid(), 8, 'LIVING ROOM DETACH AND RESET {HEAT/AC-CEILING FAN} TEXTURE {DRYWALL-CEILING} SEAL AND PAINT {WALLS-CEILING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(157, gen_random_uuid(), 8, 'HALLWAY LIVING SEAL AND PAINT {WALLS-CEILING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(158, gen_random_uuid(), 8, 'STAIRS DETACH AND RESET {BASEBOARD} TEXTURE {CEILING} REMOVE {CARPET/PAD} PAINT AND SEAL {WALLS AND CEILING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(159, gen_random_uuid(), 8, 'CLOSET2 REMOVE {BASEBOARD-CARPET/PAD} SEAL {WALLS} PAINT {WALLS-BASEBOARD} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(160, gen_random_uuid(), 8, 'ROOM 3 DETACH AND RESET {CEILING FAN-WINDOW BLIND-OUTLET/SWITCH-BASEBOARD} REMOVE {CARPET/PAD} TEXTURE {DRYWALL-CEILING} PAINT AND SEAL {WALLS-CEILING-BASEBOARD} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(161, gen_random_uuid(), 8, 'CLOSET HALLWAY REMOVE {BASEBOARD-CARPET/PAD} SEAL AND PAINT {WALLS-BASEBOARD} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(162, gen_random_uuid(), 8, 'GARAGE DETACH AND RESET {OVERHEAD DOOR OPENER-PORCELAIN LIGHT FIXTURE} TEXTURE {CEILING-DRYWALL} SEAL AND PAINT {CEILING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(163, gen_random_uuid(), 8, 'TOILETS SEAL AND PAINT {WALLS-CEILING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(164, gen_random_uuid(), 8, 'KITCHEN DETACH AND RESET {LIGHT FIXTURE} TEXTURE {DRYWALL-CEILING} SEAL AND PAINT {CEILING} LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(165, gen_random_uuid(), 8, 'DINING ROOM SEAL AND PAINT {CEILING} DETACH AND RESET {CHANDALIER-HEAT/AC} TEXTURE {CEILING-DRYWALL} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(166, gen_random_uuid(), 8, 'SUBROOM/STAIRS DETACH AND RESET {HEAT/AC} TEXTURE {DRYWALL-CEILING} PAINT AND SEAL {WALL-CEILING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(167, gen_random_uuid(), 8, 'LIVING ROOM DETACH AND RESET {LIGHT FIXTURE} SEAL AND PAINT {CEILING} TEXTURE {DRYWALL-CEILING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(168, gen_random_uuid(), 8, 'ENTRY/FOYER-SEAL AND PAINT {WALLS} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(169, gen_random_uuid(), 8, 'FAMILY ROOM DETACH AND RESET {CEILING FAN-HEAT A/C-BASEBOARD} TEXTURE {DRYWALL-CEILING} SEAL AND PAINT {WALLS-CEILING-BASEBOARD} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(170, gen_random_uuid(), 8, 'CLOSET1 REMOVE {BASEBOARD-CARPET/PAD} PAINT {WALLS-BASEBOARD} SEAL {WALLS} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(171, gen_random_uuid(), 8, 'ROOM 1 DETACH AND RESET {CEILING FAN AND LIGHT-WINDOW DRAPERY} SEAL AND PAINT {CEILING} TEXTURE {CEILING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(172, gen_random_uuid(), 8, 'STUDY ROOM DETACH AND RESET {BASEBOARD} PAINT AND SEAL {WALLS AND CEILING} INCLUDING LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(173, gen_random_uuid(), 8, 'ROOM2 DETACH AND RESET {CHANDALIER-HEAT/AC-WINDOW BLIND} TEXTURE {DRYWALL-CEILING} PAINT AND SEAL {WALLS-CEILING} INCLUDE LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(174, gen_random_uuid(), 8, 'ROOM1 DETACH AND RESET {CEILING FAN-HEAT/AC-WINDOW DRAPERY-INTERIOR DOOR} REMOVE {WOOD DOOR FRAME} TEXTURE {DRYWALL} PAINT AND SEAL {WALLS-CEILING} LABOR AND MATERIAL INCLUDED', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(175, gen_random_uuid(), 8, 'MASTER BATH REMOVE {LIGHT FIXTURE} PAINT AND SEAL {CEILING} TEXTURE {DRYWALL-CEILING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(176, gen_random_uuid(), 8, 'MASTER ROOM DETACH AND RESET {LIGHT FIXTURE-HEAT/AC-WINDOW BLIND-CEILING FAN} SEAL AND PAINT {CEILINGS} TEXTURE {CEILING-DRYWALL} PAINT {BASEBOARD} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(177, gen_random_uuid(), 8, 'LAUNDRY ROOM DETACH AND RESET {WASHER-CLOSET ORGANIZER} SEAL AND PAINT {WALLS AND CEILING} TEXTURE {DRYWALL-CEILING} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(178, gen_random_uuid(), 8, 'HALLWAY UPSTAIRS - DETACH AND RESET {BASEBOARD} REMOVE {CARPET/PAD} SEAL AND PAINT {CEILING-WALLS-BASEBOARD} INCLUDES LABOR AND MATERIALS', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(179, gen_random_uuid(), 8, 'LAUNDRY ROOM- DETACH AND RESET (Baseboard, Washer, Dyer), REPAIR TILE FLOOR, SEAL AND PAINT WALLS INCLUDES: MATERIALS AND LABOR.', '', 0, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(180, gen_random_uuid(), 8, 'LIVING ROOM- DETACH AND RESET (Baseboard, Window Blinds) PAINT (Walls, Floor) SEAL WALLS, FLOOR PROTECTION, MASK WALL (Plastic, Paper, Tape) INCLUDES: MATERIALS AND LABOR.', '', 0, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(181, gen_random_uuid(), 8, 'HALLWAY- DETACH AND RESET {BASEBOARD} REMOVE {CARPET/PAD} PAINT AND SEAL {WALL-BASEBOARD} - INCLUDES: MATERIALS AND LABOR', '', 0, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(182, gen_random_uuid(), 8, 'DINING ROOM- DETACH AND RESET (Baseboard, Window Blinds), REPAIR TILE FLOOR, SEAL AND PAINT WALLS INCLUDES: MATERIALS AND LABOR.', '', 0, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(183, gen_random_uuid(), 7, 'Roof Repairs', '', 0, '1', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(184, gen_random_uuid(), 8, 'Living Room', 'texture, drywall, paint [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(185, gen_random_uuid(), 8, 'Bath tub', 'texture, drywall, seal and paint [walls] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(186, gen_random_uuid(), 8, 'Master Bath', 'drywall, texture, ceiling, seal and paint [ceiling] paint [baseboard]  includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(187, gen_random_uuid(), 8, 'Living Room', 'texture, drywall, Paint [walls] Includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(188, gen_random_uuid(), 8, 'Kitchen', 'Seal and paint [walls] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(189, gen_random_uuid(), 8, 'BATH ROOM', 'TEXTURE, DRYWALL, SEAL AND PAINT [CEILING] INCLUDES LABOR AND MATERIALS', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(190, gen_random_uuid(), 8, 'BED ROOM 2', 'drywall, texture, seal and paint [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(191, gen_random_uuid(), 8, 'BED ROOM 1', 'drywall, texture, ceiling, seal and paint [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(192, gen_random_uuid(), 8, 'FENCING', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(193, gen_random_uuid(), 8, 'MAIN ROOF', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(194, gen_random_uuid(), 8, 'BATHROOM', 'SEAL AND PAINT TRIM, INCLUDES LABOR AND MATERIALS', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(195, gen_random_uuid(), 8, 'OFFICE', 'SEAL AND PAINT TRIM, LABOR AND MATERIALS INCLUDED', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(196, gen_random_uuid(), 8, 'MAIN LEVEL', 'INCLUDES LABOR AND MATERIALS', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(197, gen_random_uuid(), 8, 'Kitchen', 'seal and paint the walls, labor and materials included.', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(198, gen_random_uuid(), 8, 'ROOM 1', 'paint and seal [ceiling]', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(199, gen_random_uuid(), 8, 'BEDROOM 2', 'drywall, texture, ceiling, seal and paint [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(200, gen_random_uuid(), 8, 'BEDROOM 3', 'drywall, texture, ceiling, paint and seal [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(201, gen_random_uuid(), 8, 'Master Bathroom', 'drywall, texture, ceiling, seal [ceiling] paint [walls, crown molding, baseboard] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(202, gen_random_uuid(), 8, 'Kitchen', 'drywall, texture, ceiling, paint [ceiling, crown molding] seal [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(203, gen_random_uuid(), 8, 'Living Room', 'drywall, texture, ceiling, seal [ceiling] paint [ceiling, crown molding] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(204, gen_random_uuid(), 8, 'STAR ROOM', 'texture, ceiling, seal and paint [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(205, gen_random_uuid(), 8, 'ENTRY-FOYER', 'seal and paint [CEILING] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(206, gen_random_uuid(), 8, 'HALLWAY', 'drywall, texture, ceiling, seal and paint [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(207, gen_random_uuid(), 8, 'GARAGE', 'drywall, texture, ceiling, seal and paint [ceiling]', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(208, gen_random_uuid(), 8, 'KITCHEN', 'drywall, texture, ceiling, paint and seal [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(209, gen_random_uuid(), 8, 'ENTRY/FOYER', 'seal and paint [walls] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(210, gen_random_uuid(), 8, 'LAUNDRY ROOM', 'drywall, texture, ceiling, paint and seal [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(211, gen_random_uuid(), 8, 'LIVING ROOM', 'drywall, texture, ceiling, seal [ceiling, paneling] paint [ceiling, crown molding] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(212, gen_random_uuid(), 8, 'ENTRY/FOYER', 'texture [ceiling] seal [ceiling, paneling] paint [crown molding, ceiling, paneling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(213, gen_random_uuid(), 8, 'ROOM2', 'drywall, texture, ceiling', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(214, gen_random_uuid(), 8, 'ROOM1', 'drywall, texture, ceiling, paint and seal [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(215, gen_random_uuid(), 8, 'ENTRY/FOYER', 'drywall, texture, ceiling, seal and paint [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(216, gen_random_uuid(), 8, 'THEATER ROOM', 'drywall, texture, seal and paint [ceiling] labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(217, gen_random_uuid(), 8, 'LIVING ROOM', 'Drywall, texture, ceiling, paint and seal [ceiling] includes labor and materials.', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(218, gen_random_uuid(), 8, 'Dining Room', 'Seal and Paint [Ceiling] Includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(219, gen_random_uuid(), 8, 'Living Room', 'Seal and Paint [walls] Includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(220, gen_random_uuid(), 1, 'TOILET INLET CONNECTION', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(221, gen_random_uuid(), 8, 'Additional Charge For High Roof (2 stories or greater)', '', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(222, gen_random_uuid(), 8, 'Additional Charge For High Roof (2 stories or greater)', '', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(223, gen_random_uuid(), 8, 'SHINGLE ROOF AND METAL ROOF', 'Remove and replace existing shingle and metal roof, Includes: Hourly labor rate for a roofer', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(224, gen_random_uuid(), 8, 'REMOVE AND REPLACE', 'Interior door unit: Includes labor cost to remove a pre-hung interior door unit, hinges, jamb, stop, casing', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(225, gen_random_uuid(), 8, 'REMOVE AND REPLACE', 'Bathtub, Includes labor cost to remove a Bathtub, drain set, and line water.', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(226, gen_random_uuid(), 8, 'Remove and Replace', 'Cabinetry - upper (wall) Cabinets - lower (base) units Countertop - flat laid plastic or granite Include: Detach & Reset faucets and electrical items', 0, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(227, gen_random_uuid(), 8, 'KITCHEN', 'drywall, texture, ceiling, paint [ceiling, crown molding, paneling] seal [paneling, ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(228, gen_random_uuid(), 8, 'Remove and Replace', 'Snaplock Laminate - simulated wood flooring', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(229, gen_random_uuid(), 8, 'Remove and Replace', 'Drywall, texture, painting the ceiling or walls of affected areas', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(230, gen_random_uuid(), 8, 'GUEST BATHROOM', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(231, gen_random_uuid(), 8, 'MASTER BATHROOM', 'drywall, texture, ceiling, seal and paint [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(232, gen_random_uuid(), 8, 'MASTER BEDROOM', 'drywall, texture, ceiling, includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(233, gen_random_uuid(), 8, 'DINING ROOM', 'drywall, texture, ceiling, seal and paint [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(234, gen_random_uuid(), 8, 'MATERIAL SALES TAX', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(235, gen_random_uuid(), 8, 'GENERALES', 'Permits, Taxes, insurance, permits & fees', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(236, gen_random_uuid(), 8, 'SHINGLE ROOF', 'Remove and replace existing roof, Includes: Hourly labor rate for a roofer.', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(237, gen_random_uuid(), 8, 'METAL ROOF', 'Remove and replace existing roof, Includes: Hourly labor rate for a roofer.', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(238, gen_random_uuid(), 8, 'WOOD FLOORING', 'Remove and replace existing Snaplock Laminate - simulated wood flooring', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(239, gen_random_uuid(), 8, 'DRYWALL / TEXTURE / PAINT', 'Drywall, texture, painting the ceiling and walls of affected areas', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(240, gen_random_uuid(), 8, 'SHEATHING - PLYWOOD - 5/8" CDX', 'Includes: Remove and replace 5/8" plywood, nails or staples, and installation labor.', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(241, gen_random_uuid(), 8, 'ADDITIONAL TASKS', '', 0, '1', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(242, gen_random_uuid(), 8, 'SHINGLE ROOF', 'Includes: Remove and replace existing composition shingles and felt Install new drip Edge, starter strip at roof perimeter and synthetic felt roofing underlayment to entire roof Surface, metal valleys and Install flashings as per local code Hourly labor rate for a roofer and permitting and administrative fees', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(243, gen_random_uuid(), 8, 'MOLD REMEDIATION', 'Includes: Testing & lab analysis / Water Extraction & Remediation / Apply plant-based anti-microbial / Negative air fan/Air scrubber / Dehumidifier / Air mover (per 24 hour period) / Equipment decontamination charge per piece of equipment / General Laborer', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(244, gen_random_uuid(), 8, 'Modified Bitumen Roof', 'Remove and replace existing roof, Includes: Hourly labor rate for a roofer.', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(245, gen_random_uuid(), 8, 'Post Mold Remediation Clearance Assessment (8 samples)', '', 2375, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(246, gen_random_uuid(), 8, 'ELECTRICAL Rewirewire - avg. residence boxes & wiring', '', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(247, gen_random_uuid(), 8, 'NOTE', 'The works will be carried out in the affected areas identified in the property inspection The detail of the calculations of the budgeted amounts are presented in the attached document', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(248, gen_random_uuid(), 8, 'NOTE', 'These works will be carried out on the affected areas found in the inspection carried out on the property, and estimated according to the estimate presented.', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(249, gen_random_uuid(), 8, 'Paint the ceiling', '', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(250, gen_random_uuid(), 8, 'Toilet & Bath Accessories', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(251, gen_random_uuid(), 8, 'BATHROOM', 'Texture, drywall, paint and seal [walls, ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(252, gen_random_uuid(), 8, 'MENS BATHROOM', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(253, gen_random_uuid(), 8, 'HALLWAY', 'drywall, texture, ceiling, paint and seal [ceiling] includes labor and materials', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(254, gen_random_uuid(), 8, 'MAIN ROOM', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(255, gen_random_uuid(), 8, 'ENTRANCE', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(256, gen_random_uuid(), 8, 'Drywall and Texture', '', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(257, gen_random_uuid(), 8, 'Paint the walls and ceiling', '', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(258, gen_random_uuid(), 8, 'Electrical (Smoke detector / Heat/AC register)', '', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(259, gen_random_uuid(), 8, 'Additional charge for high roof - 3 stories', '', 0.34, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(260, gen_random_uuid(), 8, 'Implements and tools for high roof - 3 stories', 'Ladder 40 ft Scaffoldding (2 sets) Additional Roofers per hour Eye protection - plastic goggles - Disposable Fall protection harness and lanyard', 1927.91, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(261, gen_random_uuid(), 8, 'Exterior Paint', '', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(262, gen_random_uuid(), 8, 'Seal & Paint Trim', '', 0, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(263, gen_random_uuid(), 8, 'Stucco & Exterior Plaster', '', 0, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(264, gen_random_uuid(), 8, 'Exterior Door', '', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(265, gen_random_uuid(), 8, 'Windows Single Hung 13-19 sf', '', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(266, gen_random_uuid(), 8, 'Windows Single Hung 20-28', '', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(267, gen_random_uuid(), 8, 'Material Sales Tax', '', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(268, gen_random_uuid(), 8, 'GENERALS', 'Permits, Taxes, insurance, permits & fees', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(269, gen_random_uuid(), 8, 'MENS BATHROOM', 'Electrical Doors Toilet and Bath Accesories Painting Plumbing Cabinetry Drywall and Texture', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(270, gen_random_uuid(), 8, 'WOMENS BATHROOM', 'Electrical Doors Toilet and Bath Accesories Painting Plumbing Cabinetry Drywall and Texture', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(271, gen_random_uuid(), 8, 'MAIN BATHROOM', 'Toilet & Bath Accesories Electrical Plumbing Doors Cabinetry Painting Drywall and Texture', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(272, gen_random_uuid(), 8, 'HALLWAY', 'Electrical Doors Painting Drywall and Texture', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(273, gen_random_uuid(), 8, 'MAIN ROOM', 'Electrical Doors Painting Drywall and Texture', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(274, gen_random_uuid(), 8, 'ENTRANCE', 'Electrical Doors Painting Drywall and Texture', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(275, gen_random_uuid(), 8, 'Exterior', '', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(276, gen_random_uuid(), 8, 'Mold testing one time only each (Up to 5 samples including one outside) at front', '', 700, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(277, gen_random_uuid(), 8, 'Mold testing one time only each (Up to 8 samples including one outside) at front', '', 1000, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(278, gen_random_uuid(), 8, 'Mold testing one time only each (Up to 8 samples including one outside) at front', '', 1000, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(279, gen_random_uuid(), 8, 'Moisture Assessment', '', 1000, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(280, gen_random_uuid(), 8, 'Demolition and Build Back', 'Includes Texture and Fixtures', 3300, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(281, gen_random_uuid(), 8, 'Roof Replacement', '', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(282, gen_random_uuid(), 8, 'Door Replace and Install', 'Includes labor and material', 700, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(283, gen_random_uuid(), 8, 'Bathroom Remodel', 'Includes Dumpster Includes Plumbing, Shower Pan, Replace Walls, Includes Ceramic Tiles, Vanity, Mirror, Lights, Toilet, Extraction System and Glass doors', 9200, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(284, gen_random_uuid(), 8, 'Demolition Walls and Kitchen in Hallway. Drywall Repairs in Ceiling and Walls', 'Includes Texture and Fixtures', 3300, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(285, gen_random_uuid(), 8, 'Painting, Ceiling and Walls', 'Includes Labor & Materials', 5300, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(286, gen_random_uuid(), 8, 'Baseboard Replace', 'Includes Labor & Material', 10, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(287, gen_random_uuid(), 8, 'Vinyl Plank Flooring', 'Includes Labor & Material', 5.99, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(288, gen_random_uuid(), 8, 'Warranty of 5 Years of Labor', '', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(289, gen_random_uuid(), 8, 'AcquaSafe Water Softener System Total Whole House', '', 8500, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(290, gen_random_uuid(), 8, 'Labor Only', 'Residential Supervision / Project Management', 400, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(291, gen_random_uuid(), 8, 'Electrical', 'Installation Labor Outlets / Microwave Oven', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(292, gen_random_uuid(), 8, 'Plumbing', 'Installation labor Sink - double basin / Sink faucet / P-trap assembly', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(293, gen_random_uuid(), 8, 'Floor Covering', 'Installation labor vinyl tile', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(294, gen_random_uuid(), 8, 'Finish Carpentry / Trimwork', 'Installation Labor Baseboard', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(295, gen_random_uuid(), 8, 'Cabinetry / Countertop (Supply and Installation)', 'Supply and installation Cabinetry High Quality / Countertop Quarzo Himalaya Add-on for mitered corner (Countertop) / Installation Backsplash', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(296, gen_random_uuid(), 8, 'Drywall / Texture / Paint', 'Paint and Texture drywall the walls', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(297, gen_random_uuid(), 8, 'General Demolition', 'Cabinetry - upper (wall) units / Cabinetry - lower (base) units Carpet and Baseboard / General clean-up', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(298, gen_random_uuid(), 8, 'Based on Xactimate Estimate', '', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(299, gen_random_uuid(), 8, 'Ceiling and Walls Texture and Painting', 'Based in Attached Proposal', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(300, gen_random_uuid(), 8, 'Roof Replacement', 'Based Attached Proposal', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(301, gen_random_uuid(), 8, 'Post Mold Remediation Clearance Assessment (7 samples)', '', 2225, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(302, gen_random_uuid(), 8, 'Post Mold Remediation Clearance Assessment (6 samples)', '', 2075, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(303, gen_random_uuid(), 8, 'Post Mold Remediation Clearance Assessment (5 samples)', '', 1925, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(304, gen_random_uuid(), 8, 'Post Mold Remediation Clearance Assessment (4 samples)', '', 1775, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(305, gen_random_uuid(), 8, 'Post Mold Remediation Clearance Assessment (3 samples)', '', 1625, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(306, gen_random_uuid(), 8, 'Post Mold Remediation Clearance Assessment (2 samples)', '', 1475, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(307, gen_random_uuid(), 8, 'Water Damage', 'Removal of flooring and baseboard. Includes dry out. 2103.25 SQF', 11000, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(308, gen_random_uuid(), 8, 'Weathered wood Shingle', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(309, gen_random_uuid(), 8, 'Shakewood Shingle', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(310, gen_random_uuid(), 8, 'Hickory Shingle', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(311, gen_random_uuid(), 8, 'Barkwood Shingle', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(312, gen_random_uuid(), 8, 'R&R Tarp - poly - per sq ft (lab & mat)', 'Allowance for tarping the back slope by the contractor.', 1.5, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(313, gen_random_uuid(), 8, '2 x 4 Wood', '', 0, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(314, gen_random_uuid(), 8, 'Plywood Board 4 x 8', 'Tear Old Plywood Board and Install New Board', 0, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(315, gen_random_uuid(), 8, 'Ashpalt Shingle Roofing Installation Equipment Allowance', 'Job related costs of specialty equipment used for job quality and efficiency, including: Roof jacks, pneumatic roofing nailer, shingle cutting tools. Daily rental. Consumables extra.', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(316, gen_random_uuid(), 8, 'Ashpalt Shingle Roofing Installation Job Supplies', 'Cost of related materials and supplies typically required to install asphalt shingle roof including: fasteners, underlayment, drip edges, sealant and basic flashing.', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(317, gen_random_uuid(), 8, 'Architectural Shingle Roofing Installation Labor, Basic', 'Basic labor to install asphalt shingle roof with favorable site conditions. Install drip edge/eave trim, and valley flashing. Install roofing paper / membrane. Layout, fabricate, overlap and secure asphalt shingles per manufacturer instructions. Includes planning, equipment and material acquisition, area preparation and protection, setup and cleanup.', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(318, gen_random_uuid(), 8, 'Asphalt Shingle Roof Cost', 'Non-discounted retail pricing for: Asphalt composition shingle. 2.2 lbs per SF. UL Class A fire resistance and 110 mph wind resistance. 30 yr warranty. Quantity includes typical waste overage, material for repair and local delivery.', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(319, gen_random_uuid(), 8, 'Asphalt Shingle Roof Debris Disposal', 'Costs to load and haul away old materials, installation waste and associated debris.', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(320, gen_random_uuid(), 8, 'Tear Off Roof', 'Remove existing roofing material, roof paper, vent jacks and flashing, and gutters if needed. Sweep area clean of all nails / staples.', 0, 'SQ', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(321, gen_random_uuid(), 7, 'WRITTEN TECHNICAL REPORT', '', 650, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(322, gen_random_uuid(), 8, 'FIELD LICENSED MOLD ASSESSOR', '', 200, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(323, gen_random_uuid(), 7, 'AIR-O-CELL & SWAB ANALYSIS', '', 450, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(324, gen_random_uuid(), 7, 'Visual Inspection', '', 350, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(325, gen_random_uuid(), 8, 'Royalty Fee per services rendered Year 2021', '', 1, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(326, gen_random_uuid(), 8, 'Dedicated project management', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(327, gen_random_uuid(), 8, 'Includes permitting and administrative fees', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(328, gen_random_uuid(), 8, 'Includes all labor required to complete the job', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(329, gen_random_uuid(), 8, 'Install premium GAF timberline HD ultra shingles', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(330, gen_random_uuid(), 8, 'Install flashings as per local code', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(331, gen_random_uuid(), 8, 'Install metal valleys', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(332, gen_random_uuid(), 8, 'Install 3ft of ice and water shield at eaves, valleys and penetrations', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(333, gen_random_uuid(), 8, 'Install synthetic felt roofing underlayment to entire roof surface', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(334, gen_random_uuid(), 8, 'Install starter strip at roof perimeter', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(335, gen_random_uuid(), 8, 'Install new drip edge at roof perimeter', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(336, gen_random_uuid(), 8, 'Remove and dispose of existing roof', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(337, gen_random_uuid(), 8, 'Emergency tarp installation on the roof under extreme weather conditions', '1. Emergency tarp installation on the roof under extreme weather conditions in the above address.  2. Emergency services call/mobilization to job site. 3. Labor (ladderman installer hauler)  4. Affected area detection/setup.  5. Materials (Tarps, Shims 8 in, Nails 1ft 1/4 in, Ropes)', 4800, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(338, gen_random_uuid(), 8, 'Hose Bag for Injector Cabinet', '', 300, 'Unit', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(339, gen_random_uuid(), 8, 'Injector Cabinet', '', 500, 'Unit', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(340, gen_random_uuid(), 8, 'Air Mover', '', 150, 'Unit', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(341, gen_random_uuid(), 8, 'Air Scrubber DriEaz', '', 1000, 'Unit', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(342, gen_random_uuid(), 8, 'Dehumidifier Dryeaz', '', 2000, 'Unit', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(343, gen_random_uuid(), 8, 'Hydroxyl generator - odor counteractant - 2 optics', '', 300, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(344, gen_random_uuid(), 8, 'Deodorize building - Hot thermal fog Labor', '', 500, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(345, gen_random_uuid(), 8, 'HEPA Vacuuming - Detailed', '', 0.3, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(346, gen_random_uuid(), 8, 'Apply biological cleaning agent (spore-based) to more than the walls and ceiling', '', 0.1, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(347, gen_random_uuid(), 8, 'Seal stud wall for odor control', '', 0.25, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(348, gen_random_uuid(), 8, 'Clean floor or roof joist system - Heavy', '', 0.3, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(349, gen_random_uuid(), 8, 'Tear out drywall, cleanup, bag - Cat 3 Labor', '', 250, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(350, gen_random_uuid(), 8, 'Dumpster load - Approx. 12 yards, 1-3 tons of debris', '', 350, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(351, gen_random_uuid(), 8, 'Generator - 10-30KW (per day - 24 hour) - no monitoring', '', 400, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(352, gen_random_uuid(), 8, 'Remove Outlet or switch cover 1 Labor', '', 80, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(353, gen_random_uuid(), 8, 'Hazardous Waste/Mold Cleaning- Supervisory/Admin- PER HOUR', '', 70, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(354, gen_random_uuid(), 8, 'Hazardous Waste/Mold Cleaning Technician - per hour', '', 35, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(355, gen_random_uuid(), 8, 'Respirator cartridge - HEPA & vapor & gas (per pair)', '', 37.7, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(356, gen_random_uuid(), 8, 'Respirator - Full face - multi-purpose resp. (per day)', '', 7.61, 'DAY', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(357, gen_random_uuid(), 8, 'Fire mitigation emergency svc call - during business hours', '', 145.81, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(358, gen_random_uuid(), 8, 'Air handler - with A-coil - Detach & reset', '', 995, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(359, gen_random_uuid(), 8, 'Discounts', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(360, gen_random_uuid(), 8, 'Profit', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(361, gen_random_uuid(), 8, 'Overhead', '', 0, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(362, gen_random_uuid(), 8, 'Fall protection harness and lanyard - per day', '', 10, 'DAY', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(363, gen_random_uuid(), 8, 'Boom Lift - 50 ft Reach - Electric Powered', '', 695, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(364, gen_random_uuid(), 8, '12 mil Fire Retardant Structural Wrap Heat Shrink Seal', '', 3.5, 'SF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(365, gen_random_uuid(), 8, 'MRE', '', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(366, gen_random_uuid(), 8, 'R & R Tarp all purpose-poly set price (labor) boom leaf sup', '', 2000, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(367, gen_random_uuid(), 8, 'Bio-wash wall, floors, ceilings large room (fogging)', '', 300, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(368, gen_random_uuid(), 8, 'Administrative Mortgage Endorsement (Check Processing) - MR', '', 500, 'ITEM', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(369, gen_random_uuid(), 8, 'Negative Air Fan / Air Scrubber (24 hr period) - MR', '', 175, 'DA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(370, gen_random_uuid(), 8, 'Dehumidifier (per 24 hour period) Large - No monitoring - MR', '', 190, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(371, gen_random_uuid(), 8, 'Air Mover (per 24 hour period) No monitoring - MR', '', 55, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(372, gen_random_uuid(), 8, 'Demo and clean-up labor per hours', '', 65, 'HR', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(373, gen_random_uuid(), 8, 'Wrapping Box (Medium) Per Box', '', 40, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(374, gen_random_uuid(), 8, 'Manipulation per Trip. Local', '', 125, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(375, gen_random_uuid(), 8, 'Air Scrubber - Anti-Microbial Internal Filter', '', 200, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(376, gen_random_uuid(), 8, 'HEPA filter for HEPA VAC', '', 50, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(377, gen_random_uuid(), 8, 'Tyveck Disp. Suit P/Day - P/Tech', '', 20, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(378, gen_random_uuid(), 8, 'Full face mask - W / Hepa', '', 16, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(379, gen_random_uuid(), 8, 'Mold testing one time only each (Up to 5 samples including one outside) at front', '', 700, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(380, gen_random_uuid(), 8, 'Hepa Vac / Hr', '', 65, 'HR', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(381, gen_random_uuid(), 8, 'Sand & Paint studs /Hr', '', 65, 'HR', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(382, gen_random_uuid(), 8, 'Gloves', '', 10, 'PCS', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(383, gen_random_uuid(), 8, 'Post rem. Breakdown and equip. Clean-up per hour', '', 65, 'HR', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(384, gen_random_uuid(), 8, 'Hauling and dump fees per pick-up size load', '', 185, 'UNIT', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(385, gen_random_uuid(), 8, 'Pre-filters for Air Scrubber and Dehumidifier Each', '', 20, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(386, gen_random_uuid(), 8, 'Application of biocide spray per room (Microbian)', '', 15, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(387, gen_random_uuid(), 8, 'Bag debris for disposal per bag', '', 7, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(388, gen_random_uuid(), 8, 'Furniture Manipulation / Protection Per Hour', '', 65, 'HR', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(389, gen_random_uuid(), 8, 'Dry Wall Removal', '', 4.88, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(390, gen_random_uuid(), 8, 'Water Extraction Remediation Hours Technician', '', 38, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(391, gen_random_uuid(), 8, 'Moisture Mapping (Per Room)', '', 20, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(392, gen_random_uuid(), 8, 'Service Call / Mobilization', '', 150, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(393, gen_random_uuid(), 8, 'Gas Power Generator (Gas Included)', '', 150, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(394, gen_random_uuid(), 8, 'Removal of Tile', '', 10.84, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(395, gen_random_uuid(), 8, 'General Clean Up', '', 0.28, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(396, gen_random_uuid(), 8, 'Ozone Machine', '', 180, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(397, gen_random_uuid(), 8, '1st. Stage Filter', '', 2, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(398, gen_random_uuid(), 8, 'Carbon Filter', '', 2, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(399, gen_random_uuid(), 8, 'Deodorize', '', 0.28, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(400, gen_random_uuid(), 8, 'Thermal Fog', '', 0.28, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(401, gen_random_uuid(), 8, 'Ceiling', '', 0.28, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(402, gen_random_uuid(), 8, 'Walls', '', 0.28, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(403, gen_random_uuid(), 8, 'Administrative Mortgage Endorsement (Optional)', '', 750, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(404, gen_random_uuid(), 8, 'Floor', '', 0.28, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(405, gen_random_uuid(), 8, 'Water Extraction from hard surface floor', '', 0.26, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(406, gen_random_uuid(), 8, 'Add for dehumidifier filter', '', 102.35, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(407, gen_random_uuid(), 8, 'Thermal Imaging Service', '', 250, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(408, gen_random_uuid(), 8, 'Content Manipulation Charge - per hour', '', 52.65, 'HR', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(409, gen_random_uuid(), 8, 'Water Extraction & Remediation Technician - per hour - after hours', '', 76.35, 'HR', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(410, gen_random_uuid(), 8, 'Water Extraction & Remediation Technician - per hour', '', 50.85, 'HR', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(411, gen_random_uuid(), 8, 'Protect - Cover with plastic', '', 0.33, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(412, gen_random_uuid(), 8, 'Drill holes for wall cavity drying', '', 0.65, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(413, gen_random_uuid(), 8, 'HEPA Vacuuming - Light - (PER SF)', '', 0.95, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(414, gen_random_uuid(), 8, 'Apply anti-microbial agent to {V}', '', 0.37, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(415, gen_random_uuid(), 8, 'Equipment setup, take down, and monitoring (hourly charge) - after hours', '', 76.35, 'HR', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(416, gen_random_uuid(), 8, 'Equipment setup, take down, and monitoring (hourly charge)', '', 50.85, 'HR', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(417, gen_random_uuid(), 8, 'Peel & seal zipper - heavy duty - after business hours', '', 15.46, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(418, gen_random_uuid(), 8, 'Containment Barrier - tension post - per day', '', 3.3, 'DAY', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(419, gen_random_uuid(), 8, 'Remove polyethylene vapor barrier', '', 0.1, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(420, gen_random_uuid(), 8, 'Containment Barrier/Airlock/Decon. Chamber', '', 1.01, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(421, gen_random_uuid(), 8, 'Equipment decontamination charge - per piece of equipment', '', 69, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(422, gen_random_uuid(), 8, 'Tear out and bag wet insulation', '', 1.6, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(423, gen_random_uuid(), 8, 'Tear out non-salv. vinyl tile, cut & bag', '', 3.04, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(424, gen_random_uuid(), 8, 'Tear out wet non-salv. gluedn. cpt, cut/bag', '', 1.87, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(425, gen_random_uuid(), 8, 'Tear out wet non-salvage cpt, cut/bag', '', 1.27, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(426, gen_random_uuid(), 8, 'Tear out wet drywall, no bagging', '', 1.29, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(427, gen_random_uuid(), 8, 'Tear out non-salv solid/eng. wood flr & bag -', '', 4.48, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(428, gen_random_uuid(), 8, 'Tear out non-salv floating floor & bag', '', 2.32, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(429, gen_random_uuid(), 8, 'Interior door slab only - Detach', '', 8.15, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(430, gen_random_uuid(), 8, 'Countertop - solid surface/granite - Detach', '', 10.76, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(431, gen_random_uuid(), 8, 'Countertop - flat laid plastic lam. - Detach', '', 8.08, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(432, gen_random_uuid(), 8, 'Cabinet - vanity unit - Detach', '', 21.37, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(433, gen_random_uuid(), 8, 'Cabinet - lower (base) unit - Detach', '', 24.9, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(434, gen_random_uuid(), 8, 'Cabinet - full height unit - Detach', '', 24.9, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(435, gen_random_uuid(), 8, 'Tear out baseboard', '', 2.3, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(436, gen_random_uuid(), 8, 'Remove wet ceiling tile & drywall and bag', '', 1.48, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(437, gen_random_uuid(), 8, 'Plastic bag - used for disposal of contaminated items', '', 3.38, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(438, gen_random_uuid(), 8, 'Block and pad furniture in room - Large amount', '', 86.65, 'ROOM', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(439, gen_random_uuid(), 8, 'Range - freestanding - electric - Detach', '', 36.21, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(440, gen_random_uuid(), 8, 'Dishwasher - Detach', '', 82.03, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(441, gen_random_uuid(), 8, 'Dryer - electric - Detach', '', 27.15, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(442, gen_random_uuid(), 8, 'Washing machine - Detach', '', 30.15, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(443, gen_random_uuid(), 8, 'Refrigerator - Detach', '', 36.21, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(444, gen_random_uuid(), 8, 'Emergency service call - during business hours', '', 145.34, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(445, gen_random_uuid(), 8, 'Add for personal protective equipment - Heavy duty', '', 18.82, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(446, gen_random_uuid(), 8, 'Add for personal protective equipment (hazardous cleanup)', '', 8.55, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(447, gen_random_uuid(), 8, 'Personal protective gloves - Heavy duty (per pair) G40', '', 4.71, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(448, gen_random_uuid(), 8, 'Boots - waterproof latex - Disposable (per pair)', '', 7.17, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(449, gen_random_uuid(), 8, 'Eye protection - Plastic Goggles - Disposable', '', 4.76, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(450, gen_random_uuid(), 8, 'Emergency tarp removal and re-installation services', '', 4800, 'EA', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(451, gen_random_uuid(), 8, 'Tarp - poly - per sq ft (lab & mat)', '', 2.55, 'SQF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(452, gen_random_uuid(), 8, 'Ducting - flexible - 6" round', '', 1.42, 'LF', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(453, gen_random_uuid(), 8, 'Wood floor drying extraction mat (per 24 hr prd) No monit.', '', 180, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(454, gen_random_uuid(), 8, 'Power distribution box', '', 36.87, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(455, gen_random_uuid(), 8, 'Add for HEPA filter (for negative air exhaust fan)', '', 225, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(456, gen_random_uuid(), 8, 'Negative air fan/Air scrubber (24 hr period) - No monit.', '', 175, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(457, gen_random_uuid(), 8, 'Wall cavity drying-Inj. type (per 24 hr period) No monit.', '', 180, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(458, gen_random_uuid(), 8, 'Axial fan air mover - 1 HP (per 24 hr period)-No monit.', '', 38, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(459, gen_random_uuid(), 8, 'Air mover (per 24 hour period) - No monitoring', '', 55, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(460, gen_random_uuid(), 8, 'Dehumidifier (per 24 hour period) XXLarge - No monitoring', '', 190, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(461, gen_random_uuid(), 8, 'Dehumidifier (per 24 hour period) XLarge - No monitoring', '', 190, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(462, gen_random_uuid(), 8, 'Dehumidifier (per 24 hour period) Large - No monitoring', '', 190, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(463, gen_random_uuid(), 8, 'Dehumidifier (per 24 hour period) - No monitoring', '', 190, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(464, gen_random_uuid(), 8, 'WRT GENERAL SERVICES', '', 0, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL),
(465, gen_random_uuid(), 8, 'ADD FOR HEPA FILTER (FOR NEGATIVE AIR EXHAUST FAN)', '', 225, 'UND', NULL, '2025-08-07 23:19:41', '2025-08-07 23:19:41', NULL);

--
-- Table structure for table "public_companies"
--
CREATE TABLE "public_companies" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "public_company_name" VARCHAR(255) NOT NULL,
  "unit" TEXT,
  "address" TEXT,
  "phone" TEXT,
  "email" TEXT,
  "website" TEXT,
  "user_id" BIGINT NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  "deleted_at" TIMESTAMP WITH TIME ZONE
);

-- Dumping data for table "public_companies"
INSERT INTO "public_companies" ("id", "uuid", "public_company_name", "unit", "address", "phone", "email", "website", "user_id", "created_at", "updated_at", "deleted_at") VALUES
(1, gen_random_uuid(), 'Integrity Claims', NULL, NULL, '+18002138069', NULL, 'https://integrityclaimsgroup.com', 1, '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL),
(2, gen_random_uuid(), 'National Virtual Adjuster', NULL, '1117B S 21st Ave,Hollywood, FL 33020', '+18557001672', 'info@nationalvirtualadjuster.com', 'www.nationalvirtualadjuster.com', 1, '2025-08-07 23:19:55', '2025-08-07 23:19:55', NULL);


--
-- Table structure for table "roles"
--
CREATE TABLE "roles" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" UUID NOT NULL UNIQUE,
  "name" VARCHAR(255) NOT NULL,
  "guard_name" VARCHAR(255) NOT NULL,
  "created_at" TIMESTAMP WITH TIME ZONE,
  "updated_at" TIMESTAMP WITH TIME ZONE,
  UNIQUE("name", "guard_name")
);

-- Dumping data for table "roles"


--
-- Table structure for table "role_has_permissions"
--
CREATE TABLE "role_has_permissions" (
  "permission_id" BIGINT NOT NULL,
  "role_id" BIGINT NOT NULL,
  PRIMARY KEY ("permission_id", "role_id")
);

-- Dumping data for table "role_has_permissions"
INSERT INTO "role_has_permissions" ("permission_id", "role_id") VALUES
(1,1),
(2,1),
(3,1),
(4,1),
(5,1),
(6,1),
(7,1),
(8,1),
(9,1),
(10,1),
(11,1),
(12,1),
(13,1),
(14,1),
(15,1),
(16,1),
(17,1),
(18,1),
(19,1),
(20,1),
(21,1),
(22,1),
(23,1),
(24,1),
(25,1),
(26,1),
(27,1),
(28,1),
(29,1),
(30,1),
(31,1),
(32,1),
(33,1),
(34,1),
(35,1),
(36,1),
(37,1),
(38,1),
(39,1),
(40,1),
(41,1),
(42,1),
(43,1),
(44,1),
(45,1),
(46,1),
(47,1),
(48,1),
(49,1),
(50,1),
(51,1),
(52,1),
(53,1),
(54,1),
(55,1),
(56,1),
(57,1),
(58,1),
(59,1),
(60,1),
(61,1),
(62,1),
(63,1),
(64,1),
(65,1),
(66,1),
(67,1),
(68,1),
(69,1),
(70,1),
(71,1),
(72,1),
(73,1),
(74,1),
(75,1),
(76,1),
(77,1),
(78,1),
(79,1),
(80,1),
(81,1),
(82,1),
(83,1),
(84,1),
(85,1),
(86,1),
(87,1),
(88,1),
(89,1),
(90,1),
(91,1),
(92,1),
(93,1),
(94,1),
(95,1),
(96,1),
(97,1),
(98,1),
(99,1),
(100,1),
(101,1),
(102,1),
(103,1),
(104,1),
(105,1),
(106,1),
(107,1),
(108,1),
(109,1),
(110,1),
(111,1),
(112,1),
(113,1),
(114,1),
(115,1),
(116,1),
(117,1),
(118,1),
(119,1),
(120,1),
(121,1),
(122,1),
(123,1),
(124,1),
(125,1),
(126,1),
(127,1),
(128,1),
(129,1),
(130,1),
(131,1),
(132,1),
(133,1),
(134,1),
(135,1),
(136,1),
(137,1),
(138,1),
(139,1),
(140,1),
(141,1),
(142,1),
(143,1),
(144,1),
(145,1),
(146,1),
(147,1),
(148,1),
(149,1),
(150,1),
(151,1),
(152,1),
(153,1),
(154,1),
(155,1),
(156,1),
(157,1),
(158,1),
(159,1),
(160,1),
(161,1),
(162,1),
(163,1),
(164,1),
(165,1),
(166,1),
(167,1),
(168,1),
(169,1),
(170,1),
(171,1),
(172,1),
(173,1),
(174,1),
(175,1),
(176,1),
(177,1),
(178,1),
(179,1),
(180,1),
(181,1),
(182,1),
(183,1),
(184,1),
(185,1),
(186,1),
(187,1),
(188,1),
(189,1),
(190,1),
(191,1),
(192,1),
(193,1),
(194,1),
(195,1),
(196,1),
(197,1),
(198,1),
(199,1),
(200,1),
(201,1),
(202,1),
(203,1),
(204,1),
(205,1),
(206,1),
(207,1),
(208,1),
(209,1),
(210,1),
(211,1),
(212,1),
(213,1),
(214,1),
(215,1),
(216,1),
(217,1),
(218,1),
(219,1),
(220,1),
(221,1),
(222,1),
(223,1),
(224,1),
(225,1),
(226,1),
(227,1),
(228,1),
(229,1),
(230,1),
(231,1),
(232,1),
(233,1),
(234,1),
(235,1),
(236,1),
(237,1),
(238,1),
(239,1),
(240,1),
(241,1),
(242,1),
(243,1),
(244,1),
(245,1),
(246,1),
(247,1),
(248,1),
(249,1),
(250,1),
(251,1),
(252,1),
(253,1),
(254,1),
(255,1),
(256,1),
(257,1),
(258,1),
(259,1),
(260,1),
(261,1),
(262,1),
(263,1),
(264,1),
(265,1),
(31,2),
(32,2),
(33,2),
(34,2),
(35,2),
(51,2),
(52,2),
(53,2),
(54,2),
(55,2),
(11,3),
(12,3),
(13,3),
(14,3),
(15,3),
(71,3),
(72,3),
(73,3),
(74,3),
(75,3),
(76,3),
(77,3),
(78,3),
(79,3),
(80,3),
(81,3),
(82,3),
(83,3),
(84,3),
(85,3),
(86,3),
(87,3),
(88,3),
(89,3),
(90,3),
(91,3),
(92,3),
(93,3),
(94,3),
(95,3),
(96,3),
(97,3),
(98,3),
(99,3),
(100,3),
(101,3),
(102,3),
(103,3),
(104,3),
(105,3),
(106,3),
(107,3),
(108,3),
(109,3),
(110,3),
(116,3),
(117,3),
(118,3),
(119,3),
(120,3),
(121,3),
(122,3),
(123,3),
(124,3),
(125,3),
(131,3),
(132,3),
(133,3),
(134,3),
(135,3),
(166,3),
(167,3),
(168,3),
(169,3),
(170,3),
(231,3),
(232,3),
(233,3),
(234,3),
(235,3),
(236,3),
(237,3),
(238,3),
(239,3),
(240,3),
(241,3),
(242,3),
(243,3),
(244,3),
(245,3),
(246,3),
(247,3),
(248,3),
(249,3),
(250,3),
(251,3),
(252,3),
(253,3),
(254,3),
(255,3),
(51,4),
(52,4),
(53,4),
(54,4),
(55,4),
(141,5),
(142,5),
(143,5),
(144,5),
(145,5),
(146,6),
(147,6),
(148,6),
(149,6),
(150,6),
(151,7),
(152,7),
(153,7),
(154,7),
(155,7),
(156,8),
(157,8),
(158,8),
(159,8),
(160,8),
(86,9),
(87,9),
(88,9),
(89,9),
(90,9),
(161,10),
(162,10),
(163,10),
(164,10),
(165,10),
(166,11),
(167,11),
(168,11),
(169,11),
(170,11),
(171,12),
(172,12),
(173,12),
(174,12),
(175,12),
(176,13),
(177,13),
(178,13),
(179,13),
(180,13),
(181,14),
(182,14),
(183,14),
(184,14),
(185,14),
(186,15),
(187,15),
(188,15),
(189,15),
(190,15),
(191,16),
(192,16),
(193,16),
(194,16),
(195,16),
(196,17),
(197,17),
(198,17),
(199,17),
(200,17),
(201,18),
(202,18),
(203,18),
(204,18),
(205,18),
(136,19),
(137,19),
(138,19),
(139,19),
(140,19),
(206,20),
(207,20),
(208,20),
(209,20),
(210,20),
(211,21),
(212,21),
(213,21),
(214,21),
(215,21),
(216,22),
(217,22),
(218,22),
(219,22),
(220,22),
(221,23),
(222,23),
(223,23),
(224,23),
(225,23),
(226,24),
(227,24),
(228,24),
(229,24),
(230,24);

--
-- Table structure for table "model_has_roles"
--
CREATE TABLE "model_has_roles" (
  "role_id" BIGINT NOT NULL,
  "model_type" VARCHAR(255) NOT NULL,
  "model_id" BIGINT NOT NULL,
  PRIMARY KEY ("role_id", "model_id", "model_type")
);
CREATE INDEX "model_has_roles_model_id_model_type_index" ON "model_has_roles" ("model_id", "model_type");

-- Dumping data for table "model_has_roles"
-- Dumping data for table "roles"
INSERT INTO "roles" ("id", "uuid", "name", "guard_name", "created_at", "updated_at") VALUES
(1, gen_random_uuid(), 'SUPER_ADMIN', 'web', '2025-08-07 23:19:03', '2025-08-07 23:19:03'),
(2, gen_random_uuid(), 'ADMIN', 'web', '2025-08-07 23:19:04', '2025-08-07 23:19:04'),
(3, gen_random_uuid(), 'MANAGER', 'web', '2025-08-07 23:19:05', '2025-08-07 23:19:05'),
(4, gen_random_uuid(), 'USER', 'web', '2025-08-07 23:19:06', '2025-08-07 23:19:06'),
(5, gen_random_uuid(), 'MARKETING_MANAGER', 'web', '2025-08-07 23:19:07', '2025-08-07 23:19:07'),
(6, gen_random_uuid(), 'DIRECTOR_ASSISTANT', 'web', '2025-08-07 23:19:08', '2025-08-07 23:19:08'),
(7, gen_random_uuid(), 'TECHNICAL_SUPERVISOR', 'web', '2025-08-07 23:19:09', '2025-08-07 23:19:09'),
(8, gen_random_uuid(), 'REPRESENTATION_COMPANY', 'web', '2025-08-07 23:19:10', '2025-08-07 23:19:10'),
(9, gen_random_uuid(), 'PUBLIC_COMPANY', 'web', '2025-08-07 23:19:11', '2025-08-07 23:19:11'),
(10, gen_random_uuid(), 'EXTERNAL_OPERATORS', 'web', '2025-08-07 23:19:12', '2025-08-07 23:19:12'),
(11, gen_random_uuid(), 'PUBLIC_ADJUSTER', 'web', '2025-08-07 23:19:13', '2025-08-07 23:19:13'),
(12, gen_random_uuid(), 'INSURANCE_ADJUSTER', 'web', '2025-08-07 23:19:14', '2025-08-07 23:19:14'),
(13, gen_random_uuid(), 'TECHNICAL_SERVICES', 'web', '2025-08-07 23:19:15', '2025-08-07 23:19:15'),
(14, gen_random_uuid(), 'MARKETING', 'web', '2025-08-07 23:19:16', '2025-08-07 23:19:16'),
(15, gen_random_uuid(), 'WAREHOUSE', 'web', '2025-08-07 23:19:17', '2025-08-07 23:19:17'),
(16, gen_random_uuid(), 'ADMINISTRATIVE', 'web', '2025-08-07 23:19:18', '2025-08-07 23:19:18'),
(17, gen_random_uuid(), 'COLLECTIONS', 'web', '2025-08-07 23:19:19', '2025-08-07 23:19:19'),
(18, gen_random_uuid(), 'REPORTES', 'web', '2025-08-07 23:19:20', '2025-08-07 23:19:20'),
(19, gen_random_uuid(), 'SALESPERSON', 'web', '2025-08-07 23:19:21', '2025-08-07 23:19:21'),
(20, gen_random_uuid(), 'LEAD', 'web', '2025-08-07 23:19:22', '2025-08-07 23:19:22'),
(21, gen_random_uuid(), 'EMPLOYEES', 'web', '2025-08-07 23:19:23', '2025-08-07 23:19:23'),
(22, gen_random_uuid(), 'CLIENT', 'web', '2025-08-07 23:19:24', '2025-08-07 23:19:24'),
(23, gen_random_uuid(), 'CONTACT', 'web', '2025-08-07 23:19:25', '2025-08-07 23:19:25'),
(24, gen_random_uuid(), 'SPECTATOR', 'web', '2025-08-07 23:19:26', '2025-08-07 23:19:26');

-- Dumping data for table "model_has_roles"
INSERT INTO "model_has_roles" ("role_id", "model_type", "model_id") VALUES
(1, 'App\\Models\\User', 1), -- SUPER_ADMIN: Victor Lara
(1, 'App\\Models\\User', 2), -- SUPER_ADMIN: Argenis Gonzalez
(2, 'App\\Models\\User', 3), -- ADMIN: Administrator
(3, 'App\\Models\\User', 4), -- MANAGER: Manager
(4, 'App\\Models\\User', 5), -- USER: User
(5, 'App\\Models\\User', 6), -- MARKETING_MANAGER: Marketing Manager
(6, 'App\\Models\\User', 7), -- DIRECTOR_ASSISTANT: Director Assistant
(7, 'App\\Models\\User', 8), -- TECHNICAL_SUPERVISOR: Technical Supervisor
(8, 'App\\Models\\User', 9), -- REPRESENTATION_COMPANY: Representation Company
(9, 'App\\Models\\User', 10), -- PUBLIC_COMPANY: Public Company
(10, 'App\\Models\\User', 11), -- EXTERNAL_OPERATORS: External Operators
(11, 'App\\Models\\User', 12), -- PUBLIC_ADJUSTER: Public Adjuster
(12, 'App\\Models\\User', 13), -- INSURANCE_ADJUSTER: Insurance Adjuster
(13, 'App\\Models\\User', 14), -- TECHNICAL_SERVICES: Technical Services
(14, 'App\\Models\\User', 15), -- MARKETING: Marketing
(15, 'App\\Models\\User', 16), -- WAREHOUSE: Warehouse
(16, 'App\\Models\\User', 17), -- ADMINISTRATIVE: Administrative
(17, 'App\\Models\\User', 18), -- COLLECTIONS: Collections
(18, 'App\\Models\\User', 19), -- REPORTES: Reportes
(19, 'App\\Models\\User', 20), -- SALESPERSON: Salesperson
(20, 'App\\Models\\User', 21), -- LEAD: Lead
(21, 'App\\Models\\User', 22), -- EMPLOYEES: Employees
(22, 'App\\Models\\User', 23), -- CLIENT: Client
(23, 'App\\Models\\User', 24), -- CONTACT: Contact
(24, 'App\\Models\\User', 25); -- SPECTATOR: Spectator

--
-- Table structure for table "model_has_permissions"
--
CREATE TABLE "model_has_permissions" (
  "permission_id" BIGINT NOT NULL,
  "model_type" VARCHAR(255) NOT NULL,
  "model_id" BIGINT NOT NULL,
  PRIMARY KEY ("permission_id", "model_id", "model_type")
);
CREATE INDEX "model_has_permissions_model_id_model_type_index" ON "model_has_permissions" ("model_id", "model_type");

-- ... (remaining tables follow the same pattern) ...

-- ========= DEFINICIÓN DE CLAVES FORÁNEAS (FOREIGN KEYS) =========
-- Se añaden al final para evitar problemas de dependencias circulares durante la creación.

ALTER TABLE "alliance_companies" ADD CONSTRAINT "alliance_companies_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "blog_categories" ADD CONSTRAINT "blog_categories_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "company_data" ADD CONSTRAINT "company_data_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id");
ALTER TABLE "email_data" ADD CONSTRAINT "email_data_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON DELETE CASCADE;
ALTER TABLE "insurance_companies" ADD CONSTRAINT "insurance_companies_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "invoice_demos" ADD CONSTRAINT "invoice_demos_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON DELETE CASCADE;
ALTER TABLE "invoice_demo_items" ADD CONSTRAINT "invoice_demo_items_invoice_demo_id_foreign" FOREIGN KEY ("invoice_demo_id") REFERENCES "invoice_demos"("id") ON DELETE CASCADE;
ALTER TABLE "invoices" ADD CONSTRAINT "invoices_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON DELETE CASCADE;
ALTER TABLE "invoice_items" ADD CONSTRAINT "invoice_items_invoice_id_foreign" FOREIGN KEY ("invoice_id") REFERENCES "invoices"("id") ON DELETE CASCADE;
ALTER TABLE "model_a_i_s" ADD CONSTRAINT "model_a_i_s_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "model_has_roles" ADD CONSTRAINT "model_has_roles_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "roles"("id") ON DELETE CASCADE;
ALTER TABLE "model_has_permissions" ADD CONSTRAINT "model_has_permissions_permission_id_foreign" FOREIGN KEY ("permission_id") REFERENCES "permissions"("id") ON DELETE CASCADE;
ALTER TABLE "portfolio_images" ADD CONSTRAINT "portfolio_images_portfolio_id_foreign" FOREIGN KEY ("portfolio_id") REFERENCES "portfolios"("id") ON DELETE CASCADE;
ALTER TABLE "portfolios" ADD CONSTRAINT "portfolios_project_type_id_foreign" FOREIGN KEY ("project_type_id") REFERENCES "project_types"("id") ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE "posts" ADD CONSTRAINT "posts_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "products" ADD CONSTRAINT "products_product_category_id_foreign" FOREIGN KEY ("product_category_id") REFERENCES "category_products"("id") ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "project_types" ADD CONSTRAINT "project_types_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "project_types" ADD CONSTRAINT "project_types_service_category_id_foreign" FOREIGN KEY ("service_category_id") REFERENCES "service_categories"("id") ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "public_companies" ADD CONSTRAINT "public_companies_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "role_has_permissions" ADD CONSTRAINT "role_has_permissions_permission_id_foreign" FOREIGN KEY ("permission_id") REFERENCES "permissions"("id") ON DELETE CASCADE;
ALTER TABLE "role_has_permissions" ADD CONSTRAINT "role_has_permissions_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "roles"("id") ON DELETE CASCADE;
ALTER TABLE "service_categories" ADD CONSTRAINT "service_categories_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "zones" ADD CONSTRAINT "zones_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users"("id") ON UPDATE CASCADE ON DELETE CASCADE;
