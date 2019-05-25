<?php
# exemplo de uso:
# http:://localhost/projeto/contato
# Route::set('contato', 'index', 'contato');
Route::set('Login', 'Login', 'login');
Route::set('Logout', 'Login', 'logout');

Route::set('Perfil', 'Usuario', 'perfil');
Route::set('Admin', 'Admin', 'admin');
