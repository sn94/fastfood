<?php

namespace App\Providers;
 
use App\View\Components\Alert;
use App\View\Components\CityChooser;
use App\View\Components\FamiliaStockChooser;
use App\View\Components\FastFoodModal;
use App\View\Components\FastFoodNavbar;
use App\View\Components\FormaPagoChooser;
use App\View\Components\OrigenVentaList;
use App\View\Components\PrettyCheckbox;
use App\View\Components\PrettyPaginator;
use App\View\Components\PrettyRadioButton;
use App\View\Components\ProveedorChooser; 
use App\View\Components\SearchReportDownloader;
use App\View\Components\ServiciosList;
use App\View\Components\SucursalChooser;
use App\View\Components\TipoStockChooser;
use App\View\Components\UserInfoBox;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       
   /*    
        $this->app->bind('path.public', function() {
        
            return base_path().DIRECTORY_SEPARATOR.'..';
          });*/
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceScheme('https');

        Paginator::useBootstrap();
        Blade::component('alert-component', Alert::class);
        Blade::component('pretty-checkbox', PrettyCheckbox::class);
        Blade::component('city-chooser', CityChooser::class);
        Blade::component('search-report-downloader', SearchReportDownloader::class);
        Blade::component('tipo-stock-chooser', TipoStockChooser::class);
        Blade::component('familia-stock-chooser', FamiliaStockChooser::class);
        Blade::component('sucursal-chooser', SucursalChooser::class);
        Blade::component('fast-food-modal', FastFoodModal::class);
        Blade::component('pretty-radio-button', PrettyRadioButton::class);
        Blade::component('fast-food-navbar', FastFoodNavbar::class);
        Blade::component('proveedor-chooser', ProveedorChooser::class);
        Blade::component('forma-pago-chooser', FormaPagoChooser::class);
        Blade::component('user-info-box', UserInfoBox::class);
        Blade::component('pretty-paginator', PrettyPaginator::class);
        Blade::component('origen-venta-list', OrigenVentaList::class);
        Blade::component('servicio-list', ServiciosList::class);
    }
}
