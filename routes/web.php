<?php
// Note: No need for use statements in this simple router setup
// Controllers are loaded directly by their class names

// Create a new Router instance
$router = new Router();

// Public routes
$router->get('', 'Pages@index');
$router->get('historia', 'Pages@historia');
$router->get('directiva', 'Pages@directiva');
$router->get('noticias', 'Pages@noticias');
$router->get('blog', 'Pages@blog');
$router->get('calendario', 'Pages@calendario');
$router->get('galeria', 'Pages@galeria');
$router->get('musica', 'Pages@musica');
$router->get('libro', 'Pages@libro');
$router->get('descargas', 'Pages@descargas');
$router->get('tienda', 'Pages@tienda');
$router->get('patrocinadores', 'Pages@patrocinadores');
$router->get('hermanamientos', 'Pages@hermanamientos');
$router->get('login', 'Pages@login');
$router->post('login', 'Pages@login');
$router->get('registro', 'Pages@registro');
$router->post('registro', 'Pages@registro');

// Socios routes
$router->group('socios', function($router) {
    $router->get('', 'SociosController@index');
    $router->post('login', 'SociosController@login');
    $router->get('dashboard', 'SociosController@dashboard');
    $router->get('logout', 'SociosController@logout');
});

// Auth routes
$router->group('auth', function($router) {
    $router->get('login', 'AuthController@loginForm');
    $router->post('login', 'AuthController@login');
    $router->get('register', 'AuthController@registerForm');
    $router->post('register', 'AuthController@register');
    $router->get('logout', 'AuthController@logout');
    $router->get('forgot-password', 'AuthController@forgotPasswordForm');
    $router->post('forgot-password', 'AuthController@forgotPassword');
    $router->get('reset-password/{token}', 'AuthController@resetPasswordForm');
    $router->post('reset-password', 'AuthController@resetPassword');
});

// Authenticated user routes
$router->group('', function($router) {
    $router->get('dashboard', 'UserController@dashboard');
    $router->get('profile', 'UserController@profile');
    $router->post('profile/update', 'UserController@updateProfile');
    $router->post('profile/change-password', 'UserController@changePassword');
    $router->post('events/{id}/register', 'EventController@register');
    $router->post('events/{id}/cancel', 'EventController@cancelRegistration');
});

// API routes
$router->group('api', function($router) {
    $router->get('events', 'ApiController@getEvents');
    $router->get('events/{id}', 'ApiController@getEvent');
    $router->post('events', 'ApiController@createEvent');
    $router->put('events/{id}', 'ApiController@updateEvent');
    $router->delete('events/{id}', 'ApiController@deleteEvent');
    $router->get('stats', 'ApiController@getStats');
    $router->options('*', 'ApiController@handleOptions');
});

// Admin routes
$router->group('admin', function($router) {
    $router->get('', 'Admin\AdminController@dashboard');
    
    // User management
    $router->group('users', function($router) {
        $router->get('', 'Admin\UserController@index');
        $router->get('create', 'Admin\UserController@create');
        $router->post('store', 'Admin\UserController@store');
        $router->get('{id}/edit', 'Admin\UserController@edit');
        $router->post('{id}/update', 'Admin\UserController@update');
        $router->post('{id}/delete', 'Admin\UserController@delete');
        $router->post('{id}/toggle-status', 'Admin\UserController@toggleStatus');
    });
    
    // Admin user management (AdminController methods)
    $router->get('crearUsuario', 'Admin\AdminController@crearUsuario');
    $router->post('crearUsuario', 'Admin\AdminController@crearUsuario');
    $router->get('usuarios', 'Admin\AdminController@usuarios');
    $router->get('editarUsuario/{id}', 'Admin\AdminController@editarUsuarioForm');
    $router->post('editarUsuario/{id}', 'Admin\AdminController@editarUsuario');
    $router->post('desactivarUsuario/{id}', 'Admin\AdminController@desactivarUsuario');
    $router->post('activarUsuario/{id}', 'Admin\AdminController@activarUsuario');
    $router->post('toggleUserStatus/{id}', 'Admin\AdminController@toggleUserStatus');
    $router->post('resetearPassword/{id}', 'Admin\AdminController@resetearPassword');
    $router->get('export/dashboard', 'Admin\AdminController@exportDashboard');
    $router->post('eliminarUsuario/{id}', 'Admin\AdminController@eliminarUsuario');
    
    // Event management
    $router->group('events', function($router) {
        $router->get('', 'Admin\EventController@index');
        $router->get('create', 'Admin\EventController@create');
        $router->post('store', 'Admin\EventController@store');
        $router->get('{id}/edit', 'Admin\EventController@edit');
        $router->post('{id}/update', 'Admin\EventController@update');
        $router->post('{id}/delete', 'Admin\EventController@delete');
        $router->get('{id}/registrations', 'Admin\EventController@registrations');
        $router->post('{id}/registrations/export', 'Admin\EventController@exportRegistrations');
    });
    
    // Gallery management
    $router->get('galeria', 'Admin\AdminController@galeria');
    $router->post('subirMedia', 'Admin\AdminController@subirMedia');
    $router->get('eliminarMedia/{fileName}', 'Admin\AdminController@eliminarMedia');
    $router->post('subirCarousel', 'Admin\AdminController@subirCarousel');
    $router->get('eliminarCarousel/{fileName}', 'Admin\AdminController@eliminarCarousel');
    $router->post('actualizarDescripcionGaleria', 'Admin\AdminController@actualizarDescripcionGaleria');
    $router->post('actualizarDescripcionCarousel', 'Admin\AdminController@actualizarDescripcionCarousel');
    
    // Settings
    $router->group('settings', function($router) {
        $router->get('', 'Admin\SettingsController@index');
        $router->post('update', 'Admin\SettingsController@update');
    });
});

// Return the router
return $router;
