# Invoice Demo CRUD System

A comprehensive Laravel-based CRUD system for managing invoice demonstrations with modern UI/UX, real-time validation, and advanced features.

## 🚀 Features

### Core Functionality
- **Complete CRUD Operations**: Create, Read, Update, Delete, and Restore invoice demos
- **Real-time Validation**: Live form validation with debounced API calls
- **Auto-generation**: Automatic invoice number generation with sequential formatting
- **Advanced Search & Filtering**: Multi-field search with status and priority filters
- **Pagination**: Efficient data loading with customizable page sizes
- **Soft Deletes**: Safe deletion with restore capability

### Technical Features
- **Permission System**: Role-based access control integration
- **Transaction Management**: Database transactions for data integrity
- **Caching**: Intelligent caching for improved performance
- **API Resources**: Structured JSON responses with computed properties
- **Form Requests**: Comprehensive validation with custom rules
- **Service Layer**: Business logic separation for maintainability

### UI/UX Features
- **Modern Interface**: Clean, responsive design with Tailwind CSS
- **Alpine.js Integration**: Reactive components without page reloads
- **Loading States**: Visual feedback during operations
- **Toast Notifications**: Success and error message system
- **Modal Forms**: Seamless create/edit experience
- **Mobile Responsive**: Optimized for all device sizes

## 📁 File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── InvoiceDemoController.php      # Main controller
│   ├── Requests/
│   │   └── InvoiceDemoRequest.php         # Form validation
│   └── Resources/
│       └── InvoiceDemoResource.php        # API response formatting
├── Models/
│   └── InvoiceDemo.php                    # Eloquent model
└── Services/
    └── InvoiceDemoService.php             # Business logic

database/
├── migrations/
│   └── 2024_01_01_000000_create_invoice_demos_table.php
└── seeders/
    └── InvoiceDemoSeeder.php              # Sample data

resources/
├── views/
│   └── invoice-demos/
│       ├── index.blade.php                # Main view
│       └── partials/
│           └── invoice-modal.blade.php    # Modal component
└── js/
    └── invoice-demos.js                   # Frontend logic

routes/
└── web.php                                # Route definitions
```

## 🛠️ Installation & Setup

### 1. Database Migration
```bash
php artisan migrate
```

### 2. Seed Sample Data (Optional)
```bash
php artisan db:seed --class=InvoiceDemoSeeder
```

### 3. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### 4. Permissions Setup
Ensure your permission system includes these permissions:
- `invoice-demos.view`
- `invoice-demos.create`
- `invoice-demos.edit`
- `invoice-demos.delete`
- `invoice-demos.restore`

## 🎯 Usage

### Accessing the System
Navigate to `/invoice-demos` to access the main interface.

### Creating an Invoice
1. Click the "New Invoice" button
2. Fill in the required information:
   - **Invoice Info**: Number (auto-generated), status, priority, estimated amount
   - **Client Details**: Name, email, phone, address
   - **Property Info**: Address, damage type, insurance company, dates, description
   - **Additional Notes**: Optional notes field
3. Click "Save" to create the invoice

### Managing Invoices
- **Search**: Use the search bar to find specific invoices
- **Filter**: Filter by status and priority
- **Sort**: Click column headers to sort data
- **Edit**: Click the edit icon to modify an invoice
- **Delete**: Click the delete icon to soft-delete an invoice
- **Restore**: Use the "Include Deleted" toggle to view and restore deleted invoices

## 🔧 API Endpoints

### Main CRUD Operations
- `GET /invoice-demos` - List invoices (supports AJAX)
- `POST /invoice-demos` - Create new invoice
- `GET /invoice-demos/{id}/edit` - Get invoice for editing
- `PUT /invoice-demos/{id}` - Update invoice
- `DELETE /invoice-demos/{id}` - Delete invoice
- `POST /invoice-demos/{id}/restore` - Restore deleted invoice

### Utility Endpoints
- `GET /invoice-demos/form-data` - Get form dropdown data
- `GET /invoice-demos/check-invoice-number` - Check invoice number availability
- `GET /invoice-demos/generate-invoice-number` - Generate new invoice number

## 📊 Data Structure

### Invoice Demo Fields

#### Client Information
- `client_name` (required): Client's full name
- `client_email` (required): Valid email address
- `client_phone` (required): Phone number (auto-formatted)
- `client_address` (required): Client's address

#### Property Information
- `property_address` (required): Property location
- `damage_description` (required): Detailed damage description
- `type_damage_id` (required): Reference to damage type
- `insurance_company_id` (required): Reference to insurance company
- `insurance_claim_number` (optional): Insurance claim reference

#### Financial & Status
- `invoice_number` (required, unique): Auto-generated or manual
- `estimated_amount` (required): Estimated repair cost
- `status` (required): draft, pending, approved, rejected, completed
- `priority` (required): low, medium, high, urgent

#### Dates
- `date_of_loss` (required): When damage occurred
- `inspection_date` (optional): Scheduled inspection date

#### Additional
- `notes` (optional): Additional information
- `user_id` (auto-set): Creating user reference

## 🎨 Customization

### Styling
The system uses Tailwind CSS classes. Modify the Blade templates to customize the appearance:
- `resources/views/invoice-demos/index.blade.php`
- `resources/views/invoice-demos/partials/invoice-modal.blade.php`

### Validation Rules
Customize validation in `app/Http/Requests/InvoiceDemoRequest.php`:
```php
public function rules(): array
{
    return [
        'client_name' => 'required|string|max:255',
        // Add or modify rules as needed
    ];
}
```

### Business Logic
Modify business logic in `app/Services/InvoiceDemoService.php`:
```php
public function createInvoice(array $data, int $userId): InvoiceDemo
{
    // Custom business logic here
}
```

## 🔍 Advanced Features

### Caching Strategy
- Form data cached for 300 seconds
- Cache automatically cleared on data changes
- Configurable cache times in controller

### Real-time Validation
- Invoice number uniqueness check
- Debounced API calls (500ms delay)
- Visual feedback for validation states

### Computed Properties
The API resource includes computed fields:
- `days_since_loss`: Days since damage occurred
- `days_until_inspection`: Days until scheduled inspection
- `is_overdue`: Whether inspection is overdue
- `completion_percentage`: Estimated completion based on status
- `requires_attention`: Whether invoice needs immediate attention

### Error Handling
- Comprehensive try-catch blocks
- Detailed error logging
- User-friendly error messages
- Graceful degradation for failed operations

## 🚨 Security Features

- **Permission Checks**: All operations require appropriate permissions
- **CSRF Protection**: Laravel's built-in CSRF protection
- **Input Validation**: Comprehensive server-side validation
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Prevention**: Blade template escaping

## 📈 Performance Optimizations

- **Eager Loading**: Related models loaded efficiently
- **Pagination**: Large datasets handled with pagination
- **Caching**: Frequently accessed data cached
- **Debouncing**: API calls optimized with debouncing
- **Lazy Loading**: JavaScript components loaded as needed

## 🐛 Troubleshooting

### Common Issues

1. **Permission Denied Errors**
   - Ensure user has required permissions
   - Check permission middleware configuration

2. **Cache Issues**
   - Clear application cache: `php artisan cache:clear`
   - Clear config cache: `php artisan config:clear`

3. **Database Errors**
   - Ensure migrations are run: `php artisan migrate`
   - Check foreign key constraints

4. **JavaScript Errors**
   - Check browser console for errors
   - Ensure Alpine.js is loaded
   - Verify API endpoints are accessible

### Debug Mode
Enable debug logging by setting `LOG_LEVEL=debug` in your `.env` file.

## 🤝 Contributing

When contributing to this system:

1. Follow Laravel coding standards
2. Add appropriate tests for new features
3. Update documentation for any changes
4. Ensure backward compatibility
5. Test thoroughly before submitting

## 📝 License

This Invoice Demo CRUD system is part of the VGeneralContractors web application.

---

**Built with Laravel 2025 best practices, featuring modern UI/UX and comprehensive functionality.**