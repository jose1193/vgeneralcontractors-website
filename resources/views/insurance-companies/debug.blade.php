<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Debug CRUD System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Debug CRUD System</h1>
        <div id="debug-output"></div>
        <button id="test-btn" class="btn btn-primary">Test System</button>
    </div>

    @vite(['resources/js/crud/index.js'])
    
    <script>
        console.log('Debug page loaded');
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            
            // Verificar si window.CrudSystem está disponible
            if (window.CrudSystem) {
                console.log('✅ CrudSystem is available:', window.CrudSystem);
                document.getElementById('debug-output').innerHTML += '<p class="text-success">✅ CrudSystem loaded successfully</p>';
                
                // Verificar componentes individuales
                const components = ['CrudManagerModal', 'ApiManager', 'TableManager', 'FormManager', 'ModalManager', 'ValidationManager', 'EventManager'];
                components.forEach(component => {
                    if (window.CrudSystem[component]) {
                        console.log(`✅ ${component} is available`);
                        document.getElementById('debug-output').innerHTML += `<p class="text-success">✅ ${component} loaded</p>`;
                    } else {
                        console.log(`❌ ${component} is NOT available`);
                        document.getElementById('debug-output').innerHTML += `<p class="text-danger">❌ ${component} NOT loaded</p>`;
                    }
                });
                
            } else {
                console.log('❌ CrudSystem is NOT available');
                document.getElementById('debug-output').innerHTML += '<p class="text-danger">❌ CrudSystem NOT loaded</p>';
            }
            
            // Test button
            document.getElementById('test-btn').addEventListener('click', function() {
                if (window.CrudSystem && window.CrudSystem.CrudManagerModal) {
                    try {
                        const testConfig = {
                            entityName: 'Test',
                            entityNamePlural: 'Tests',
                            routes: {
                                index: '/test',
                                store: '/test',
                                edit: '/test/:id/edit',
                                update: '/test/:id',
                                destroy: '/test/:id'
                            },
                            selectors: {
                                table: '#test-table',
                                search: '#test-search',
                                perPage: '#test-perpage'
                            },
                            formFields: []
                        };
                        
                        const testInstance = new window.CrudSystem.CrudManagerModal(testConfig);
                        console.log('✅ Test instance created:', testInstance);
                        document.getElementById('debug-output').innerHTML += '<p class="text-success">✅ Test instance created successfully</p>';
                    } catch (error) {
                        console.error('❌ Error creating test instance:', error);
                        document.getElementById('debug-output').innerHTML += `<p class="text-danger">❌ Error: ${error.message}</p>`;
                    }
                } else {
                    console.log('❌ Cannot test - CrudSystem not available');
                    document.getElementById('debug-output').innerHTML += '<p class="text-danger">❌ Cannot test - CrudSystem not available</p>';
                }
            });
        });
    </script>
</body>
</html>