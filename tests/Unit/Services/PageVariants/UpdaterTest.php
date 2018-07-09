<?php

namespace Tests\Unit\Services\PageVariants;

use App\Exceptions\Exception as AppException;
use App\Models\Page;
use App\Models\PageVariant;
use App\Models\Route;
use App\Services\PageVariants\Updater as PageVariantsUpdater;
use App\Services\PageVariants\Validator as PageVariantsValidator;
use Carbon\Carbon;
use Tests\Unit\TestCase;

final class UpdaterTest extends TestCase
{

    /**
     * @var PageVariantsUpdater
     */
    private $updater;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->updater = new PageVariantsUpdater(
            new PageVariantsValidator()
        );
    }

    /**
     * This test checks that the `update` method properly fills basic fields of
     * the page variant.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testFillsBasicProperties(): void
    {
        $pageVariant = $this->buildPageVariant([
            'status' => PageVariant::STATUS_DRAFT,
            'title' => 'some title',
            'lead' => 'some lead',
            'content' => 'some content',
        ]);

        $this->updater->update($pageVariant, [
            'status' => PageVariant::STATUS_DELETED,
            'title' => 'some other title',
            'lead' => 'some other lead',
            'content' => 'some other content',
        ]);

        $this->assertEquals(PageVariant::STATUS_DELETED, $pageVariant->status);
        $this->assertEquals('some other title', $pageVariant->title);
        $this->assertEquals('some other lead', $pageVariant->lead);
        $this->assertEquals('some other content', $pageVariant->content);
    }

    /**
     * This test checks that the `update` method does not allow to change
     * language of an already existing page variant.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testDoesNotAllowToChangeLanguage(): void
    {
        $pageVariant = $this->buildPageVariant([
            'language_id' => 100,
        ]);

        $this->updater->update($pageVariant, [
            'language_id' => 200,
        ]);

        $this->assertEquals(100, $pageVariant->language_id);
    }

    /**
     * This test checks that the `update` method automatically sets the
     * `published_at` date when page variant is being published.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testSetsPublishedAt(): void
    {
        $pageVariant = $this->buildPageVariant([
            'status' => PageVariant::STATUS_DRAFT,
        ]);

        $this->updater->update($pageVariant, [
            'status' => PageVariant::STATUS_PUBLISHED,
            'route' => 'somewhere',
        ]);

        $this->assertNotNull($pageVariant->published_at);
    }

    /**
     * This test checks that the `update` method does not overwrite the
     * `published_at` date of an already published page variant.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testDoesNotOverwritePublishedAt(): void
    {
        $now = Carbon::yesterday();

        $pageVariant = $this->buildPageVariant([
            'status' => PageVariant::STATUS_PUBLISHED,
            'published_at' => $now,

            'route' => [
                'url' => 'somewhere',
            ],
        ]);

        $this->updater->update($pageVariant, [
            'status' => PageVariant::STATUS_PUBLISHED,
            'route' => 'somewhere',
        ]);

        $this->assertEquals(
            $pageVariant->published_at->format('Y-m-d H:i:s'),
            $now->format('Y-m-d H:i:s')
        );
    }

    /**
     * This test checks that the `update` method does not allow to manually
     * overwrite the `published_at` date.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testDoesNotAllowToOverwritePublishedAt(): void
    {
        $now = Carbon::yesterday();

        $pageVariant = $this->buildPageVariant([
            'status' => PageVariant::STATUS_PUBLISHED,
            'published_at' => $now,

            'route' => [
                'url' => 'somewhere',
            ],
        ]);

        $this->updater->update($pageVariant, [
            'route' => 'somewhere',
            'published_at' => Carbon::today(),
        ]);

        $this->assertEquals(
            $pageVariant->published_at->format('Y-m-d H:i:s'),
            $now->format('Y-m-d H:i:s')
        );
    }

    /**
     * This test checks that the `update` method properly creates a new route
     * when page variant contains none.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testCreatesRoute(): void
    {
        $pageVariant = $this->buildPageVariant([]);

        $this->updater->update($pageVariant, [
            'route' => 'somewhere',
        ]);

        $this->assertNotNull($pageVariant->route);
        $this->assertEquals('somewhere', $pageVariant->route->url);
    }

    /**
     * This test checks that the `update` method properly creates a new
     * redirection-route and does not try to touch the already existing one.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testUpdatesRouteWhenRoutesAreDifferent(): void
    {
        // Create a dummy route
        $route = new Route([
            'url' => 'somewhere',
        ]);

        // Create a dummy page variant
        $pageVariant = new PageVariant();
        $pageVariant->setRelations([
            'page' => new Page(),
            'route' => $route,
        ]);

        // Update the page variant
        $this->updater->update($pageVariant, [
            'route' => 'somewhere-else',
        ]);

        // Assertion #1: Make sure the old route has its URL unchanged;
        // otherwise redirections wouldn't work.
        $this->assertEquals('somewhere', $route->url);

        // Assertion #2 & #3: Make sure page variant actually has a route and it
        // points at correct url.
        $this->assertNotNull($pageVariant->route);
        $this->assertEquals('somewhere-else', $pageVariant->route->url);
    }

    /**
     * This test checks that the `update` method properly not touches the
     * original route at all, if it does not have to.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testDoesNotUpdateRouteWhenRoutesAreTheSame(): void
    {
        // Create a dummy route
        $route = new Route([
            'url' => 'somewhere',
        ]);

        // Create a dummy page variant
        $pageVariant = new PageVariant();
        $pageVariant->setRelations([
            'page' => new Page(),
            'route' => $route,
        ]);

        // Update the page variant
        $this->updater->update($pageVariant, [
            'route' => 'somewhere',
        ]);

        // Assertion #1; Make sure the new page variant has any route at all.
        $this->assertNotNull($pageVariant->route);

        // Assertion #2 & #3: Make sure it's the same object and that it
        // contains the same URL.
        $this->assertEquals($pageVariant->route, $route);
        $this->assertEquals($pageVariant->route->url, $route->url);
    }

    /**
     * This test checks that the `update` method properly removes route when
     * it is told to.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testRemovesRoute(): void
    {
        // Create a dummy route
        $route = new Route([
            'url' => 'somewhere',
        ]);

        // Create a dummy page variant
        $pageVariant = new PageVariant();
        $pageVariant->setRelations([
            'page' => new Page(),
            'route' => $route,
        ]);

        // Update the page variant
        $this->updater->update($pageVariant, [
            'route' => '',
        ]);

        $this->assertNull($pageVariant->route);
    }

    /**
     * @return void
     *
     * @throws AppException
     */
    public function testDoesNotAllowToPublishPageWithoutRoute(): void
    {
        $this->expectExceptionMessage('Published page must have a route.');

        $pageVariant = $this->buildPageVariant([]);

        $this->updater->update($pageVariant, [
            'status' => PageVariant::STATUS_PUBLISHED,
        ]);
    }

    /**
     * @param array $data
     * @return PageVariant
     */
    private function buildPageVariant(array $data): PageVariant
    {
        $page = new Page();

        $pageVariant = new PageVariant($data);

        // Assign page to the page variant
        $pageVariant->setRelation('page', $page);

        // Add route to the page variant, if it's present
        if (array_has($data, 'route')) {
            $route = new Route($data['route']);

            $pageVariant->setRelation('route', $route);
        }

        return $pageVariant;
    }

}