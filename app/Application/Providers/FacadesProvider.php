<?php

namespace App\Application\Providers;

use App\Attachments\AttachmentsFacade;
use App\Attachments\AttachmentsFactory;
use App\Attachments\Implementation\Repositories\EloquentAttachmentsRepository;
use App\Grid\GridFacade;
use App\Languages\Implementation\Repositories\EloquentLanguagesRepository;
use App\Languages\LanguagesFacade;
use App\Languages\LanguagesFactory;
use App\Menus\Implementation\Repositories\EloquentMenuItemsRepository;
use App\Menus\MenusFacade;
use App\Menus\MenusFactory;
use App\Pages\Implementation\Repositories\EloquentPagesRepository;
use App\Pages\Implementation\Services\Grid\Sources\EloquentPagesGridSource;
use App\Pages\PagesFacade;
use App\Pages\PagesFactory;
use App\Routes\Implementation\Repositories\EloquentRoutesRepository;
use App\Routes\RoutesFacade;
use App\Routes\RoutesFactory;
use App\Tags\Implementation\Repositories\EloquentTagsRepository;
use App\Tags\TagsFacade;
use App\Tags\TagsFactory;
use App\Websites\Implementation\Repositories\EloquentWebsitesRepository;
use App\Websites\WebsitesFacade;
use App\Websites\WebsitesFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\Support\ServiceProvider;

final class FacadesProvider extends ServiceProvider {

    private const FACADES = [
        AttachmentsFacade::class,
        LanguagesFacade::class,
        MenusFacade::class,
        PagesFacade::class,
        RoutesFacade::class,
        TagsFacade::class,
        WebsitesFacade::class,
    ];

    /**
     * @return void
     */
    public function register(): void {
        // Unit tests instantiate facades on their own, so we might as well just
        // skip this step
        if ($this->app->runningUnitTests()) {
            return;
        }

        // == Attachments == //
        $this->app->singleton(AttachmentsFacade::class, function (): AttachmentsFacade {
            return AttachmentsFactory::build(
                $this->app->make(FilesystemFactory::class)->disk('attachments'),
                $this->app->make(EloquentAttachmentsRepository::class)
            );
        });

        // == Languages == //
        $this->app->singleton(LanguagesFacade::class, function (): LanguagesFacade {
            return LanguagesFactory::build(
                $this->app->make(EloquentLanguagesRepository::class)
            );
        });

        // == Menus == //
        $this->app->singleton(MenusFacade::class, function (): MenusFacade {
            return MenusFactory::build(
                $this->app->make(EloquentMenuItemsRepository::class)
            );
        });

        // == Pages == //
        $this->app->singleton(PagesFacade::class, function (): PagesFacade {
            return PagesFactory::build(
                $this->app->make(EventsDispatcher::class),
                $this->app->make(EloquentPagesRepository::class),
                $this->app->make(EloquentPagesGridSource::class),
                $this->app->make(AttachmentsFacade::class),
                $this->app->make(GridFacade::class),
                $this->app->make(TagsFacade::class),
                $this->app->make(WebsitesFacade::class)
            );
        });

        // == Routes == //
        $this->app->singleton(RoutesFacade::class, function (): RoutesFacade {
            return RoutesFactory::build(
                $this->app->make(EloquentRoutesRepository::class)
            );
        });

        // == Tags == //
        $this->app->singleton(TagsFacade::class, function (): TagsFacade {
            return TagsFactory::build(
                $this->app->make(EventsDispatcher::class),
                $this->app->make(EloquentTagsRepository::class)
            );
        });

        // == Websites == //
        $this->app->singleton(WebsitesFacade::class, function (): WebsitesFacade {
            return WebsitesFactory::build(
                $this->app->make(EloquentWebsitesRepository::class)
            );
        });
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void {
        // Unit tests instantiate facades on their own, so we might as well just
        // skip this step
        if ($this->app->runningUnitTests()) {
            return;
        }

        foreach (self::FACADES as $facadeClass) {
            $facade = $this->app->make($facadeClass);

            if (method_exists($facade, 'boot')) {
                $facade->boot();
            }
        }
    }

}
