<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\SalesChart;
use App\Filament\Widgets\ProductChart;
use App\Filament\Widgets\SalesByCategoryChart;
use App\Filament\Widgets\CustomerSegmentChart;
use App\Filament\Widgets\ProductStockOverview;
use App\Filament\Widgets\SalesStatistics;
use App\Filament\Widgets\LatestSales;
use App\Filament\Widgets\LowStockProductsWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')   
            ->login()
            ->brandName('Surya Gemilang')
            ->brandLogo(fn () => view('filament.admin.logo'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.svg'))
            ->globalSearch(false)
            ->colors([
                'primary' => '#1687d3',
                'success' => Color::Emerald,
                'danger'  => Color::Red,
                'warning' => Color::Amber,
                'info'    => Color::Blue,
            ])

    ->discoverResources(
        in: app_path('Filament/Resources'),
        for: 'App\\Filament\\Resources'
    )

    ->discoverPages(
        in: app_path('Filament/Pages'),
        for: 'App\\Filament\\Pages'
    )

    ->pages([
        Dashboard::class,
    ])

    ->discoverWidgets(
        in: app_path('Filament/Widgets'),
        for: 'App\\Filament\\Widgets'
    )

    ->widgets([
        SalesStatistics::class,
        SalesChart::class,
        ProductChart::class,
        SalesByCategoryChart::class,
        CustomerSegmentChart::class,
        ProductStockOverview::class,
        LowStockProductsWidget::class,
        LatestSales::class,
    ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
