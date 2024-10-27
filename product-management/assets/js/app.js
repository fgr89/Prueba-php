// product-management/assets/js/app.js

// Inicialización del módulo principal con sus dependencias
var productApp = angular.module('productApp', []);

// Configuración global de la aplicación
productApp.config(function($httpProvider) {
    // Agregar headers por defecto para todas las peticiones AJAX
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    
    // Configurar interceptors para manejar errores globalmente
    $httpProvider.interceptors.push(function($q) {
        return {
            'responseError': function(rejection) {
                console.error('Error en la petición:', rejection);
                return $q.reject(rejection);
            }
        };
    });
});

// Filtros globales de la aplicación
productApp.filter('currency', function() {
    return function(amount) {
        if (!amount) return '$0.00';
        return '$' + parseFloat(amount).toFixed(2);
    };
});

// Servicios compartidos
productApp.service('messageService', function() {
    return {
        showError: function(message) {
            console.error(message);
            // Aquí podrías implementar una mejor visualización de errores
        },
        showSuccess: function(message) {
            console.log(message);
            // Aquí podrías implementar una mejor visualización de mensajes de éxito
        }
    };
});

// Constantes de la aplicación
productApp.constant('API_ENDPOINTS', {
    getProducts: 'index.php?action=getProducts',
    deleteProduct: 'index.php?action=delete',
    createProduct: 'index.php?action=create',
    editProduct: 'index.php?action=edit'
});

// Directivas comunes
productApp.directive('loadingSpinner', function() {
    return {
        restrict: 'E',
        template: '<div class="loading-spinner" ng-show="loading">' +
                 '<div class="spinner-border text-primary" role="status">' +
                 '<span class="visually-hidden">Loading...</span>' +
                 '</div>' +
                 '</div>'
    };
});

// Manejador de errores global para AngularJS
productApp.run(function($rootScope) {
    $rootScope.$on('$exceptionHandler', function(event, exception) {
        console.error('Error en la aplicación:', exception);
    });
});