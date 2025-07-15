@php
    $routes = [
        'index' => secure_url(route('insurance-companies.index', [], false)),
        'store' => secure_url(route('insurance-companies.store', [], false)),
        'edit' => secure_url(route('insurance-companies.edit', ':id', false)),
        'update' => secure_url(route('insurance-companies.update', ':id', false)),
        'destroy' => secure_url(route('insurance-companies.destroy', ':id', false)),
        'restore' => secure_url(route('insurance-companies.restore', ':id', false)),
        'checkEmail' => secure_url(route('insurance-companies.check-email', [], false)),
        'checkPhone' => secure_url(route('insurance-companies.check-phone', [], false)),
        'checkName' => secure_url(route('insurance-companies.check-name', [], false)),
    ];
    $tableColumns = [
        ['field' => 'insurance_company_name', 'label' => __('company_name'), 'sortable' => true],
        ['field' => 'address', 'label' => __('address'), 'sortable' => false],
        ['field' => 'email', 'label' => __('email'), 'sortable' => true],
        ['field' => 'phone', 'label' => __('phone'), 'sortable' => false],
        ['field' => 'website', 'label' => __('website'), 'sortable' => false],
        ['field' => 'user_name', 'label' => __('created_by'), 'sortable' => true],
        ['field' => 'created_at', 'label' => __('created'), 'sortable' => true],
        ['field' => 'actions', 'label' => __('actions'), 'sortable' => false],
    ];
    $formFields = [
        [
            'name' => 'insurance_company_name',
            'type' => 'text',
            'label' => __('company_name'),
            'placeholder' => __('enter_company_name'),
            'required' => true,
            'validation' => [
                'required' => true,
                'minLength' => 2,
                'maxLength' => 255,
                'unique' => [
                    'url' => route('insurance-companies.check-name'),
                    'errorMessage' => __('name_already_in_use'),
                    'successMessage' => __('name_available'),
                ],
            ],
            'capitalize' => true,
        ],
        [
            'name' => 'address',
            'type' => 'textarea',
            'label' => __('address'),
            'placeholder' => __('enter_address'),
            'required' => false,
            'rows' => 3,
            'validation' => [
                'required' => false,
                'minLength' => 10,
                'maxLength' => 500,
            ],
            'capitalize' => true,
        ],
        [
            'name' => 'email',
            'type' => 'email',
            'label' => __('email'),
            'placeholder' => __('email'),
            'required' => false,
            'validation' => [
                'required' => false,
                'email' => true,
                'unique' => [
                    'url' => route('insurance-companies.check-email'),
                    'errorMessage' => __('email_already_in_use'),
                    'successMessage' => __('email_available'),
                ],
            ],
        ],
        [
            'name' => 'phone',
            'type' => 'tel',
            'label' => __('phone'),
            'placeholder' => __('enter_phone_number'),
            'required' => false,
            'validation' => [
                'required' => false,
                'unique' => [
                    'url' => route('insurance-companies.check-phone'),
                    'message' => __('phone_already_in_use'),
                ],
            ],
        ],
        [
            'name' => 'website',
            'type' => 'url',
            'label' => __('website'),
            'placeholder' => __('website_placeholder'),
            'required' => false,
            'validation' => [
                'required' => false,
                'url' => true,
            ],
        ],
        [
            'name' => 'user_id',
            'type' => 'hidden',
            'value' => auth()->id(),
        ],
    ];
    $searchFields = ['insurance_company_name', 'address', 'email', 'phone', 'website'];
    $entityConfig = [
        'identifierField' => 'insurance_company_name',
        'displayName' => __('insurance_company'),
        'fallbackFields' => ['email', 'address'],
        'detailFormat' => null, // JS function not supported in PHP config
    ];
    $tableHeaders = []; // Let the JS use default logic or extend as needed
    $translations = [
        'confirmDelete' => __('confirm_delete_entity'),
        'deleteMessage' => __('delete_element_question'),
        'confirmRestore' => __('confirm_restore_entity'),
        'restoreMessage' => __('restore_element_question'),
        'yesDelete' => __('yes_delete'),
        'yesRestore' => __('yes_restore'),
        'cancel' => __('cancel'),
        'deletedSuccessfully' => __('deleted_successfully'),
        'restoredSuccessfully' => __('restored_successfully'),
        'errorDeleting' => __('errorDeleting'),
        'errorRestoring' => __('errorRestoring'),
        'emailAlreadyInUse' => __('email_already_in_use'),
        'phoneAlreadyInUse' => __('phone_already_in_use'),
        'nameAlreadyInUse' => __('name_already_in_use'),
        'emailAvailable' => __('email_available'),
        'phoneAvailable' => __('phone_available'),
        'nameAvailable' => __('name_available'),
        'invalidEmail' => __('invalid_email'),
        'minimumCharacters' => __('minimum_characters'),
        'mustContainNumbers' => __('must_contain_numbers'),
        'usernameAlreadyInUse' => __('username_already_in_use'),
        'usernameAvailable' => __('username_available'),
        'pleaseCorrectErrors' => __('please_correct_errors'),
        'noRecordsFound' => __('no_data'),
        'createdBy' => __('created_by'),
        'editInsuranceCompany' => __('edit_insurance_company'),
        'restoreInsuranceCompany' => __('restore_insurance_company'),
        'deleteInsuranceCompany' => __('delete_insurance_company'),
        'noUserAssigned' => __('no_user_assigned'),
        'companyName' => __('company_name'),
        'address' => __('address'),
        'email' => __('email'),
        'phone' => __('phone'),
        'website' => __('website'),
        'actions' => __('actions'),
        'created' => __('created'),
    ];
@endphp

<x-crud.generic-index :title="__('insurance_companies_management')" :subtitle="__('manage_insurance_companies_subtitle')" :entity-name="__('insurance_company')" :entity-name-plural="__('insurance_companies')" :search-placeholder="__('search_insurance_companies')"
    :show-deleted-label="__('show_inactive_records')" :add-new-label="__('add_insurance_company')" :table-columns="$tableColumns" :routes="$routes" :form-fields="$formFields" :search-fields="$searchFields"
    :entity-config="$entityConfig" :table-headers="$tableHeaders" :translations="$translations" />
