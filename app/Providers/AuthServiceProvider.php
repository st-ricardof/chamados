<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->is_admin;
        });

        Gate::define('atendente', function ($user) {
            return $user->filas()->count() || $user->is_admin;
        });

        Gate::define('usuario', function ($user) {
            return $user;
        });

        # perfis
        # o perfil é o modo como o usuário se apresenta
        # ideal para mostrar os menus e a lista de chamados
        Gate::define('perfilAdmin', function ($user) {
            if (session('perfil') != 'admin') {
                return false;
            } else {
                return $user->is_admin;
            }
        });

        Gate::define('perfilAtendente', function ($user) {
            if (session('perfil') == 'atendente') {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('perfilUsuario', function ($user) {
            if (session('perfil') == 'usuario' || empty(session('perfil'))) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('trocarPerfil', function ($user) {
            if (Gate::allows('admin') || Gate::allows('atendente')) {
                return true;
            } else {
                return false;
            }
        });

        # policies
        Gate::resource('chamados', 'App\Policies\ChamadoPolicy');
    }
}
