# 🏗️ V General Contractors - Technology Stack Roadmap

## 📋 Tabla de Contenidos

-   [Situación Actual](#situación-actual)
-   [Análisis de Arquitecturas](#análisis-de-arquitecturas)
-   [Comparación de Tecnologías](#comparación-de-tecnologías)
-   [Stack Recomendado](#stack-recomendado)
-   [Roadmap de Migración](#roadmap-de-migración)
-   [Ejemplos de Código](#ejemplos-de-código)

---

## 🎯 Situación Actual

### Contexto del Proyecto

-   **Empresa**: V General Contractors (Roofing Company)
-   **Sistema Actual**: Portal comercial Laravel + gestión de leads/blog
-   **Sistema en Desarrollo**: Sistema tipo Encircle para gestión de claims y scope sheets
-   **Decisión**: Unificar ambos sistemas en una sola plataforma

### Funcionalidades Actuales

-   ✅ Portal comercial de roofing
-   ✅ Gestión de leads y formularios de contacto
-   ✅ Sistema de blog con SEO
-   ✅ Call records management
-   ✅ Sistema CRUD genérico
-   ✅ Gestión de citas (appointments)

### Funcionalidades a Integrar

-   🔄 Gestión de claims de seguros
-   🔄 Scope sheets para proyectos
-   🔄 Flujo completo: Lead → Inspección → Claim → Proyecto

---

## 🏛️ Análisis de Arquitecturas

### 1. Clean Architecture

#### Estructura

```
├── Domain/
│   ├── Entities/          # Objetos de negocio puros
│   ├── UseCases/          # Casos de uso de la aplicación
│   └── Repositories/      # Interfaces de repositorios
├── Application/
│   ├── Services/          # Servicios de aplicación
│   └── DTOs/             # Objetos de transferencia de datos
└── Infrastructure/
    ├── Database/          # Implementaciones de repositorios
    ├── External/          # APIs externas
    └── Http/             # Controladores y rutas
```

#### Ventajas

-   ✅ **Independencia**: Lógica de negocio separada de framework
-   ✅ **Testabilidad**: Fácil testing unitario
-   ✅ **Mantenibilidad**: Código organizado y limpio
-   ✅ **Escalabilidad**: Fácil agregar nuevas funcionalidades

#### Desventajas

-   ❌ **Complejidad inicial**: Más código para proyectos simples
-   ❌ **Curva de aprendizaje**: Requiere disciplina arquitectónica

### 2. Hexagonal Architecture

#### Estructura

```
├── Domain/
│   ├── Models/           # Modelos de dominio
│   ├── Ports/            # Interfaces (puertos)
│   └── Services/         # Servicios de dominio
├── Application/
│   ├── UseCases/         # Casos de uso
│   └── Handlers/         # Manejadores de comandos
└── Infrastructure/
    ├── Adapters/         # Adaptadores (implementaciones)
    ├── Persistence/      # Persistencia de datos
    └── External/         # Servicios externos
```

#### Ventajas

-   ✅ **Desacoplamiento**: Máxima separación de responsabilidades
-   ✅ **Flexibilidad**: Fácil cambio de tecnologías
-   ✅ **Testing**: Excelente para testing

#### Desventajas

-   ❌ **Complejidad**: Más complejo que Clean Architecture
-   ❌ **Overhead**: Puede ser excesivo para aplicaciones medianas

### 🎯 Recomendación: Clean Architecture

**Razón**: Mejor balance entre organización y simplicidad para el tamaño del proyecto.

---

## 🔧 Comparación de Tecnologías

### Frontend: Blade vs React/Vue vs Next.js

#### Laravel Blade

```php
<!-- Ejemplo: Claim Card -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
        Claim #{{ $claim->id }}
    </h3>
    <p class="text-gray-600 dark:text-gray-400">
        {{ $claim->property_address }}
    </p>
    <div class="mt-4">
        <span class="px-2 py-1 text-xs rounded-full
            {{ $claim->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
            {{ ucfirst($claim->status) }}
        </span>
    </div>
</div>
```

**Ventajas:**

-   ✅ Desarrollo rápido
-   ✅ SEO nativo
-   ✅ Server-side rendering
-   ✅ Integración perfecta con Laravel

**Desventajas:**

-   ❌ Interactividad limitada
-   ❌ Refrescos de página
-   ❌ No reutilizable para móviles

#### React/Vue.js

```jsx
// Ejemplo: Claim Card Component
const ClaimCard = ({ claim }) => {
    const [status, setStatus] = useState(claim.status);

    const updateStatus = async (newStatus) => {
        await api.updateClaim(claim.id, { status: newStatus });
        setStatus(newStatus);
    };

    return (
        <div className="bg-white rounded-lg shadow-xl p-6">
            <h3 className="text-lg font-semibold text-gray-900">
                Claim #{claim.id}
            </h3>
            <p className="text-gray-600">{claim.property_address}</p>
            <StatusBadge
                status={status}
                onUpdate={updateStatus}
                interactive={true}
            />
        </div>
    );
};
```

**Ventajas:**

-   ✅ Interactividad completa
-   ✅ Componentes reutilizables
-   ✅ Estado local
-   ✅ Ecosistema rico

**Desventajas:**

-   ❌ SEO complejo
-   ❌ Configuración inicial
-   ❌ Doble desarrollo (frontend/backend)

#### Next.js

```jsx
// Ejemplo: Server Component + Client Component
// app/claims/[id]/page.tsx (Server Component)
export default async function ClaimPage({ params }) {
  const claim = await getClaim(params.id);

  return (
    <div className="container mx-auto py-8">
      <ClaimHeader claim={claim} />
      <ClaimDetails claim={claim} />
      <ClaimActions claimId={claim.id} /> {/* Client Component */}
    </div>
  );
}

// components/ClaimActions.tsx (Client Component)
'use client';
export default function ClaimActions({ claimId }) {
  const [loading, setLoading] = useState(false);

  const handleApprove = async () => {
    setLoading(true);
    await approveClaim(claimId);
    setLoading(false);
  };

  return (
    <Button onClick={handleApprove} disabled={loading}>
      {loading ? 'Processing...' : 'Approve Claim'}
    </Button>
  );
}
```

**Ventajas:**

-   ✅ SEO perfecto
-   ✅ Performance optimizada
-   ✅ Server + Client Components
-   ✅ Routing automático

**Desventajas:**

-   ❌ Curva de aprendizaje
-   ❌ Complejidad inicial

### Backend: Laravel vs NestJS vs Express.js

#### Laravel

```php
// Ejemplo: Claim Service con Clean Architecture
class ClaimService
{
    public function __construct(
        private ClaimRepositoryInterface $claimRepository,
        private InsuranceApiService $insuranceApi
    ) {}

    public function createClaim(CreateClaimDTO $dto): Claim
    {
        // Validar con insurance API
        $validationResult = $this->insuranceApi->validateClaim($dto);

        if (!$validationResult->isValid()) {
            throw new InvalidClaimException($validationResult->getErrors());
        }

        // Crear claim
        $claim = new Claim([
            'property_address' => $dto->propertyAddress,
            'damage_type' => $dto->damageType,
            'estimated_cost' => $dto->estimatedCost,
        ]);

        return $this->claimRepository->save($claim);
    }
}
```

**Ventajas:**

-   ✅ Desarrollo rápido
-   ✅ Eloquent ORM
-   ✅ Ecosistema maduro
-   ✅ Documentación excelente

**Desventajas:**

-   ❌ Monolítico
-   ❌ PHP puede ser limitante
-   ❌ Menos flexible para APIs puras

#### NestJS

```typescript
// Ejemplo: Claim Service con Clean Architecture
@Injectable()
export class ClaimService {
    constructor(
        @Inject("CLAIM_REPOSITORY")
        private claimRepository: ClaimRepositoryInterface,
        private insuranceApiService: InsuranceApiService
    ) {}

    async createClaim(createClaimDto: CreateClaimDto): Promise<Claim> {
        // Validar con insurance API
        const validationResult = await this.insuranceApiService.validateClaim(
            createClaimDto
        );

        if (!validationResult.isValid) {
            throw new BadRequestException(validationResult.errors);
        }

        // Crear claim
        const claim = new Claim({
            propertyAddress: createClaimDto.propertyAddress,
            damageType: createClaimDto.damageType,
            estimatedCost: createClaimDto.estimatedCost,
        });

        return this.claimRepository.save(claim);
    }
}

// Controller
@Controller("claims")
export class ClaimsController {
    constructor(private claimsService: ClaimService) {}

    @Post()
    @UsePipes(ValidationPipe)
    async create(@Body() createClaimDto: CreateClaimDto) {
        return this.claimsService.createClaim(createClaimDto);
    }
}
```

**Ventajas:**

-   ✅ TypeScript nativo
-   ✅ Arquitectura robusta
-   ✅ Dependency Injection
-   ✅ Escalabilidad excelente

**Desventajas:**

-   ❌ Curva de aprendizaje
-   ❌ Más complejo inicialmente

#### Express.js

```javascript
// Ejemplo: Claim Service básico
const express = require("express");
const app = express();

app.post("/claims", async (req, res) => {
    try {
        // Validación manual
        if (!req.body.propertyAddress) {
            return res.status(400).json({ error: "Property address required" });
        }

        // Lógica de negocio mezclada
        const validationResult = await insuranceApi.validateClaim(req.body);
        if (!validationResult.isValid) {
            return res.status(400).json({ errors: validationResult.errors });
        }

        // Crear claim
        const claim = await Claim.create({
            property_address: req.body.propertyAddress,
            damage_type: req.body.damageType,
            estimated_cost: req.body.estimatedCost,
        });

        res.json(claim);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});
```

**Ventajas:**

-   ✅ Simplicidad
-   ✅ Flexibilidad total
-   ✅ Performance

**Desventajas:**

-   ❌ Sin estructura
-   ❌ Configuración manual
-   ❌ Difícil de escalar

### UI Libraries: Shadcn/ui vs Material-UI

#### Shadcn/ui

```jsx
// Ejemplo: Claim Form
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";

export function ClaimCard({ claim }) {
    return (
        <Card className="border-l-4 border-l-amber-500 bg-slate-50">
            <CardHeader className="pb-3">
                <CardTitle className="text-slate-900 flex items-center gap-2">
                    <Home className="h-5 w-5" />
                    Storm Damage Claim #{claim.id}
                </CardTitle>
                <Badge
                    variant={
                        claim.status === "approved" ? "default" : "secondary"
                    }
                >
                    {claim.status}
                </Badge>
            </CardHeader>
            <CardContent>
                <div className="space-y-2">
                    <p className="text-sm text-slate-600">
                        {claim.propertyAddress}
                    </p>
                    <p className="text-lg font-semibold text-green-600">
                        ${claim.estimatedCost.toLocaleString()}
                    </p>
                </div>
                <Button className="mt-4 bg-amber-600 hover:bg-amber-700">
                    Process Insurance Claim
                </Button>
            </CardContent>
        </Card>
    );
}
```

**Ventajas:**

-   ✅ Ownership total del código
-   ✅ Tailwind CSS integrado
-   ✅ Customización infinita
-   ✅ Bundle size optimizado

**Desventajas:**

-   ❌ Más trabajo inicial
-   ❌ Mantenimiento manual

#### Material-UI

```jsx
// Ejemplo: Claim Form
import { Paper, Typography, Button, Chip } from "@mui/material";
import { styled } from "@mui/material/styles";

const StyledPaper = styled(Paper)(({ theme }) => ({
    padding: theme.spacing(3),
    borderLeft: `4px solid ${theme.palette.warning.main}`,
}));

export function ClaimCard({ claim }) {
    return (
        <StyledPaper elevation={2}>
            <Typography variant="h6" gutterBottom>
                Storm Damage Claim #{claim.id}
            </Typography>
            <Chip
                label={claim.status}
                color={claim.status === "approved" ? "success" : "default"}
                size="small"
            />
            <Typography variant="body2" color="text.secondary" sx={{ mt: 1 }}>
                {claim.propertyAddress}
            </Typography>
            <Typography variant="h6" color="success.main" sx={{ mt: 1 }}>
                ${claim.estimatedCost.toLocaleString()}
            </Typography>
            <Button
                variant="contained"
                color="warning"
                sx={{ mt: 2 }}
                fullWidth
            >
                Process Insurance Claim
            </Button>
        </StyledPaper>
    );
}
```

**Ventajas:**

-   ✅ Componentes completos
-   ✅ Design system establecido
-   ✅ Documentación excelente

**Desventajas:**

-   ❌ Look genérico
-   ❌ Bundle size mayor
-   ❌ Customización limitada

---

## 🎯 Stack Recomendado

### Stack Actual (2024)

```
┌─────────────────────────────────────┐
│           FRONTEND                  │
│  Laravel Blade + Alpine.js          │
│  Tailwind CSS                       │
└─────────────────────────────────────┘
                    │
┌─────────────────────────────────────┐
│           BACKEND                   │
│  Laravel 10+ with Clean Architecture│
│  PHP 8.2+                          │
│  MySQL/PostgreSQL                  │
└─────────────────────────────────────┘
                    │
┌─────────────────────────────────────┐
│         INFRASTRUCTURE              │
│  Docker                            │
│  Redis (Cache/Sessions)            │
│  Queue System                      │
└─────────────────────────────────────┘
```

#### Estructura de Carpetas Actual

```
app/
├── Domain/
│   ├── Claims/
│   │   ├── Entities/
│   │   │   ├── Claim.php
│   │   │   └── ScopeSheet.php
│   │   ├── Repositories/
│   │   │   └── ClaimRepositoryInterface.php
│   │   └── Services/
│   │       └── ClaimDomainService.php
│   ├── Appointments/
│   └── Posts/
├── Application/
│   ├── Claims/
│   │   ├── UseCases/
│   │   │   ├── CreateClaimUseCase.php
│   │   │   └── GenerateScopeSheetUseCase.php
│   │   └── DTOs/
│   │       └── ClaimDTO.php
│   └── Services/
├── Infrastructure/
│   ├── Repositories/
│   │   └── EloquentClaimRepository.php
│   ├── External/
│   │   ├── InsuranceAPIAdapter.php
│   │   └── WeatherAPIAdapter.php
│   └── Http/
│       └── Controllers/
└── Models/ (Eloquent - mantener compatibilidad)
```

### Stack Futuro (2025-2026)

```
┌─────────────────────────────────────┐
│           FRONTEND                  │
│  Next.js 14+ (App Router)          │
│  Shadcn/ui + Tailwind CSS          │
│  TypeScript                        │
└─────────────────────────────────────┘
                    │
                   API
                    │
┌─────────────────────────────────────┐
│           BACKEND                   │
│  NestJS with Clean Architecture     │
│  TypeScript                        │
│  PostgreSQL + Prisma ORM           │
└─────────────────────────────────────┘
                    │
┌─────────────────────────────────────┐
│         INFRASTRUCTURE              │
│  Docker + Kubernetes               │
│  Redis                             │
│  Message Queues (Bull/BullMQ)      │
└─────────────────────────────────────┘
```

#### Estructura de Carpetas Futura

```
backend/ (NestJS)
├── src/
│   ├── domain/
│   │   ├── claims/
│   │   │   ├── entities/
│   │   │   ├── repositories/
│   │   │   └── services/
│   │   └── appointments/
│   ├── application/
│   │   ├── claims/
│   │   │   ├── use-cases/
│   │   │   └── dtos/
│   │   └── common/
│   └── infrastructure/
│       ├── database/
│       ├── external/
│       └── http/

frontend/ (Next.js)
├── app/
│   ├── claims/
│   │   ├── page.tsx
│   │   └── [id]/
│   ├── dashboard/
│   └── components/
├── components/
│   ├── ui/ (shadcn/ui)
│   ├── claims/
│   └── common/
└── lib/
    ├── api/
    └── utils/
```

---

## 🗓️ Roadmap de Migración

### Fase 1: Consolidación Laravel (3-4 meses)

**Objetivo**: Unificar sistemas y implementar Clean Architecture

#### Mes 1-2: Setup y Migración

-   [ ] Crear estructura Clean Architecture
-   [ ] Migrar módulo Appointments como ejemplo
-   [ ] Implementar Claims module desde cero
-   [ ] Setup dependency injection

#### Mes 3-4: Funcionalidades Core

-   [ ] Sistema completo de Claims
-   [ ] Scope Sheets functionality
-   [ ] Integración con Insurance APIs
-   [ ] Weather API integration
-   [ ] Flujo completo: Lead → Claim → Project

#### Tecnologías

```bash
# Dependencies actuales
composer require league/fractal  # Para transformar datos
composer require spatie/laravel-data  # Para DTOs
composer require spatie/laravel-query-builder  # Para APIs
```

### Fase 2: Optimización y Preparación (2-3 meses)

**Objetivo**: Optimizar Laravel y preparar migración

#### Mes 5-6: APIs y Performance

-   [ ] Crear APIs REST robustas
-   [ ] Implementar caching estratégico
-   [ ] Optimizar queries y performance
-   [ ] Setup monitoring y logging

#### Mes 7: Preparación Frontend

-   [ ] Implementar componentes Vue.js críticos
-   [ ] Setup Inertia.js para páginas complejas
-   [ ] Preparar design system

#### Tecnologías

```bash
# APIs y Performance
composer require spatie/laravel-responsecache
composer require spatie/laravel-permission
npm install @inertiajs/vue3
```

### Fase 3: Migración a Stack Moderno (4-6 meses)

**Objetivo**: Migrar a NestJS + Next.js

#### Mes 8-10: Backend Migration

-   [ ] Setup NestJS con Clean Architecture
-   [ ] Migrar Claims module a TypeScript
-   [ ] Implementar authentication/authorization
-   [ ] Migrar APIs principales

#### Mes 11-13: Frontend Migration

-   [ ] Setup Next.js con App Router
-   [ ] Implementar Shadcn/ui design system
-   [ ] Migrar páginas críticas
-   [ ] Setup SEO y performance

#### Tecnologías

```bash
# NestJS Backend
npm install @nestjs/core @nestjs/common
npm install @nestjs/typeorm typeorm
npm install @nestjs/jwt @nestjs/passport

# Next.js Frontend
npx create-next-app@latest --typescript
npx shadcn-ui@latest init
npm install @tanstack/react-query
```

---

## 💡 Ejemplos de Código Detallados

### Ejemplo 1: Claim Entity (Clean Architecture)

#### Laravel (Actual)

```php
<?php

namespace App\Domain\Claims\Entities;

class Claim
{
    public function __construct(
        private ?int $id,
        private string $propertyAddress,
        private string $damageType,
        private float $estimatedCost,
        private ClaimStatus $status,
        private \DateTimeImmutable $createdAt,
        private ?string $insuranceCompany = null,
        private ?string $policyNumber = null,
    ) {}

    public function approve(): void
    {
        if ($this->status === ClaimStatus::PENDING) {
            $this->status = ClaimStatus::APPROVED;
        } else {
            throw new InvalidClaimStatusException(
                'Can only approve pending claims'
            );
        }
    }

    public function calculateScopeSheet(): ScopeSheet
    {
        return ScopeSheet::fromClaim($this);
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getPropertyAddress(): string { return $this->propertyAddress; }
    public function getDamageType(): string { return $this->damageType; }
    public function getEstimatedCost(): float { return $this->estimatedCost; }
    public function getStatus(): ClaimStatus { return $this->status; }
}

enum ClaimStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case COMPLETED = 'completed';
}
```

#### NestJS (Futuro)

```typescript
// domain/claims/entities/claim.entity.ts
export enum ClaimStatus {
    PENDING = "pending",
    APPROVED = "approved",
    REJECTED = "rejected",
    COMPLETED = "completed",
}

export class Claim {
    constructor(
        private readonly id: number | null,
        private readonly propertyAddress: string,
        private readonly damageType: string,
        private readonly estimatedCost: number,
        private status: ClaimStatus,
        private readonly createdAt: Date,
        private readonly insuranceCompany?: string,
        private readonly policyNumber?: string
    ) {}

    approve(): void {
        if (this.status === ClaimStatus.PENDING) {
            this.status = ClaimStatus.APPROVED;
        } else {
            throw new InvalidClaimStatusException(
                "Can only approve pending claims"
            );
        }
    }

    calculateScopeSheet(): ScopeSheet {
        return ScopeSheet.fromClaim(this);
    }

    // Getters
    getId(): number | null {
        return this.id;
    }
    getPropertyAddress(): string {
        return this.propertyAddress;
    }
    getDamageType(): string {
        return this.damageType;
    }
    getEstimatedCost(): number {
        return this.estimatedCost;
    }
    getStatus(): ClaimStatus {
        return this.status;
    }
}
```

### Ejemplo 2: Claim Repository

#### Laravel (Actual)

```php
<?php

namespace App\Domain\Claims\Repositories;

use App\Domain\Claims\Entities\Claim;

interface ClaimRepositoryInterface
{
    public function findById(int $id): ?Claim;
    public function findByStatus(ClaimStatus $status): array;
    public function save(Claim $claim): Claim;
    public function delete(int $id): bool;
}

// Infrastructure Implementation
namespace App\Infrastructure\Repositories;

use App\Domain\Claims\Repositories\ClaimRepositoryInterface;
use App\Models\Claim as ClaimModel;

class EloquentClaimRepository implements ClaimRepositoryInterface
{
    public function findById(int $id): ?Claim
    {
        $model = ClaimModel::find($id);

        if (!$model) {
            return null;
        }

        return new Claim(
            id: $model->id,
            propertyAddress: $model->property_address,
            damageType: $model->damage_type,
            estimatedCost: $model->estimated_cost,
            status: ClaimStatus::from($model->status),
            createdAt: $model->created_at->toDateTimeImmutable(),
            insuranceCompany: $model->insurance_company,
            policyNumber: $model->policy_number,
        );
    }

    public function save(Claim $claim): Claim
    {
        $model = $claim->getId()
            ? ClaimModel::find($claim->getId())
            : new ClaimModel();

        $model->fill([
            'property_address' => $claim->getPropertyAddress(),
            'damage_type' => $claim->getDamageType(),
            'estimated_cost' => $claim->getEstimatedCost(),
            'status' => $claim->getStatus()->value,
            'insurance_company' => $claim->getInsuranceCompany(),
            'policy_number' => $claim->getPolicyNumber(),
        ]);

        $model->save();

        return new Claim(
            id: $model->id,
            propertyAddress: $model->property_address,
            damageType: $model->damage_type,
            estimatedCost: $model->estimated_cost,
            status: ClaimStatus::from($model->status),
            createdAt: $model->created_at->toDateTimeImmutable(),
            insuranceCompany: $model->insurance_company,
            policyNumber: $model->policy_number,
        );
    }
}
```

#### NestJS (Futuro)

```typescript
// domain/claims/repositories/claim.repository.interface.ts
export interface ClaimRepositoryInterface {
    findById(id: number): Promise<Claim | null>;
    findByStatus(status: ClaimStatus): Promise<Claim[]>;
    save(claim: Claim): Promise<Claim>;
    delete(id: number): Promise<boolean>;
}

// infrastructure/database/repositories/typeorm-claim.repository.ts
import { Repository } from "typeorm";
import { Injectable } from "@nestjs/common";
import { InjectRepository } from "@nestjs/typeorm";

@Injectable()
export class TypeormClaimRepository implements ClaimRepositoryInterface {
    constructor(
        @InjectRepository(ClaimEntity)
        private claimRepository: Repository<ClaimEntity>
    ) {}

    async findById(id: number): Promise<Claim | null> {
        const entity = await this.claimRepository.findOne({ where: { id } });

        if (!entity) {
            return null;
        }

        return new Claim(
            entity.id,
            entity.propertyAddress,
            entity.damageType,
            entity.estimatedCost,
            entity.status as ClaimStatus,
            entity.createdAt,
            entity.insuranceCompany,
            entity.policyNumber
        );
    }

    async save(claim: Claim): Promise<Claim> {
        const entity = this.claimRepository.create({
            id: claim.getId(),
            propertyAddress: claim.getPropertyAddress(),
            damageType: claim.getDamageType(),
            estimatedCost: claim.getEstimatedCost(),
            status: claim.getStatus(),
            insuranceCompany: claim.getInsuranceCompany(),
            policyNumber: claim.getPolicyNumber(),
        });

        const saved = await this.claimRepository.save(entity);

        return new Claim(
            saved.id,
            saved.propertyAddress,
            saved.damageType,
            saved.estimatedCost,
            saved.status as ClaimStatus,
            saved.createdAt,
            saved.insuranceCompany,
            saved.policyNumber
        );
    }
}
```

### Ejemplo 3: Frontend Components

#### Blade (Actual)

```blade
{{-- resources/views/claims/show.blade.php --}}
<x-app-layout>
    <div class="min-h-screen bg-gray-900" style="background-color: #141414;">
        <div class="p-4 sm:p-6">
            <div class="mb-4 sm:mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">
                    Claim #{{ $claim->id }}
                </h2>
                <p class="text-gray-400">
                    {{ $claim->property_address }}
                </p>
            </div>

            <div class="max-w-7xl mx-auto">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                    Claim Details
                                </h3>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Damage Type
                                        </dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">
                                            {{ $claim->damage_type }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Estimated Cost
                                        </dt>
                                        <dd class="text-lg font-semibold text-green-600">
                                            ${{ number_format($claim->estimated_cost) }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Status
                                        </dt>
                                        <dd>
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $claim->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($claim->status) }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                    Actions
                                </h3>
                                @if($claim->status === 'pending')
                                    <div class="space-y-3">
                                        <form action="{{ route('claims.approve', $claim) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
                                                Approve Claim
                                            </button>
                                        </form>
                                        <form action="{{ route('claims.reject', $claim) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">
                                                Reject Claim
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

#### Next.js + Shadcn/ui (Futuro)

```tsx
// app/claims/[id]/page.tsx
import { getClaim } from "@/lib/api/claims";
import { ClaimDetails } from "@/components/claims/claim-details";
import { ClaimActions } from "@/components/claims/claim-actions";

interface ClaimPageProps {
    params: { id: string };
}

export default async function ClaimPage({ params }: ClaimPageProps) {
    const claim = await getClaim(params.id);

    if (!claim) {
        return <div>Claim not found</div>;
    }

    return (
        <div className="container mx-auto py-8">
            <div className="mb-8">
                <h1 className="text-3xl font-bold text-gray-900">
                    Claim #{claim.id}
                </h1>
                <p className="text-gray-600 mt-2">{claim.propertyAddress}</p>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div className="lg:col-span-2">
                    <ClaimDetails claim={claim} />
                </div>
                <div>
                    <ClaimActions claim={claim} />
                </div>
            </div>
        </div>
    );
}

// components/claims/claim-details.tsx
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Home, DollarSign, Calendar } from "lucide-react";

interface ClaimDetailsProps {
    claim: Claim;
}

export function ClaimDetails({ claim }: ClaimDetailsProps) {
    return (
        <Card>
            <CardHeader>
                <CardTitle className="flex items-center gap-2">
                    <Home className="h-5 w-5" />
                    Claim Details
                </CardTitle>
            </CardHeader>
            <CardContent className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label className="text-sm font-medium text-gray-500">
                            Damage Type
                        </label>
                        <p className="text-lg font-semibold text-gray-900">
                            {claim.damageType}
                        </p>
                    </div>
                    <div>
                        <label className="text-sm font-medium text-gray-500">
                            Status
                        </label>
                        <div className="mt-1">
                            <Badge
                                variant={
                                    claim.status === "approved"
                                        ? "default"
                                        : "secondary"
                                }
                            >
                                {claim.status}
                            </Badge>
                        </div>
                    </div>
                </div>

                <div>
                    <label className="text-sm font-medium text-gray-500 flex items-center gap-1">
                        <DollarSign className="h-4 w-4" />
                        Estimated Cost
                    </label>
                    <p className="text-2xl font-bold text-green-600">
                        ${claim.estimatedCost.toLocaleString()}
                    </p>
                </div>

                <div>
                    <label className="text-sm font-medium text-gray-500 flex items-center gap-1">
                        <Calendar className="h-4 w-4" />
                        Created
                    </label>
                    <p className="text-gray-900">
                        {new Date(claim.createdAt).toLocaleDateString()}
                    </p>
                </div>
            </CardContent>
        </Card>
    );
}

// components/claims/claim-actions.tsx
("use client");

import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { useRouter } from "next/navigation";
import { approveClaim, rejectClaim } from "@/lib/api/claims";

interface ClaimActionsProps {
    claim: Claim;
}

export function ClaimActions({ claim }: ClaimActionsProps) {
    const [loading, setLoading] = useState(false);
    const router = useRouter();

    const handleApprove = async () => {
        setLoading(true);
        try {
            await approveClaim(claim.id);
            router.refresh();
        } catch (error) {
            console.error("Error approving claim:", error);
        } finally {
            setLoading(false);
        }
    };

    const handleReject = async () => {
        setLoading(true);
        try {
            await rejectClaim(claim.id);
            router.refresh();
        } catch (error) {
            console.error("Error rejecting claim:", error);
        } finally {
            setLoading(false);
        }
    };

    if (claim.status !== "pending") {
        return (
            <Card>
                <CardHeader>
                    <CardTitle>Actions</CardTitle>
                </CardHeader>
                <CardContent>
                    <p className="text-gray-500">
                        No actions available for {claim.status} claims.
                    </p>
                </CardContent>
            </Card>
        );
    }

    return (
        <Card>
            <CardHeader>
                <CardTitle>Actions</CardTitle>
            </CardHeader>
            <CardContent className="space-y-3">
                <Button
                    onClick={handleApprove}
                    disabled={loading}
                    className="w-full bg-green-600 hover:bg-green-700"
                >
                    {loading ? "Processing..." : "Approve Claim"}
                </Button>
                <Button
                    onClick={handleReject}
                    disabled={loading}
                    variant="destructive"
                    className="w-full"
                >
                    {loading ? "Processing..." : "Reject Claim"}
                </Button>
            </CardContent>
        </Card>
    );
}
```

---

## 🎯 Conclusión

### Decisión Final

**Stack Actual**: Laravel + Blade + Clean Architecture  
**Stack Futuro**: NestJS + Next.js + Shadcn/ui

### Justificación

1. **Pragmatismo**: Blade permite desarrollo rápido y SEO nativo
2. **Arquitectura**: Clean Architecture prepara para futuras migraciones
3. **Escalabilidad**: NestJS + Next.js ofrecen máxima escalabilidad
4. **Consistencia**: Tailwind CSS en ambos stacks mantiene consistencia visual

### Próximos Pasos

1. Implementar Clean Architecture en Laravel
2. Desarrollar módulos Claims y Scope Sheets
3. Preparar APIs robustas para futura migración
4. Planificar migración gradual a stack moderno

---
