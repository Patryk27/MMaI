<?php

namespace Tests\Unit\Services\Core\Language;

use App\Models\Language;
use App\Models\PageVariant;
use App\Models\Route;
use App\Repositories\LanguagesRepository;
use App\Repositories\RoutesRepository;
use App\Services\Core\Language\Detector as LanguageDetector;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Routing\Route as IlluminateRoute;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Unit\TestCase;

class DetectorTest extends TestCase
{

    /**
     * @var RoutesRepository|MockObject
     */
    private $routesRepositoryMock;

    /**
     * @var LanguagesRepository|MockObject
     */
    private $languagesRepositoryMock;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->routesRepositoryMock = $this->createMock(RoutesRepository::class);
        $this->languagesRepositoryMock = $this->createMock(LanguagesRepository::class);
    }

    /**
     * This test checks that the language detector correctly detect situation
     * when it is given an empty URL and immediately returns `null`.
     *
     * @return void
     */
    public function testFailsWhenGivenRouteWithoutUrl(): void
    {
        // Case 1: URL equal to `null`
        $detector = $this->buildDetector(null);

        $this->assertNull(
            $detector->getLanguage()
        );

        // Case 2: URL equal to an empty string
        $detector = $this->buildDetector('');

        $this->assertNull(
            $detector->getLanguage()
        );
    }

    /**
     * This test checks that the language detector correctly resolves URL to a
     * route and returns that route model's language, if that property is
     * present.
     *
     * @return void
     */
    public function testSucceedsWhenRouteResolvesToModelWithLanguageProperty(): void
    {
        // Create dummy language
        $language = new Language();

        // Create dummy page variant and assign it the language
        $pageVariant = new PageVariant();
        $pageVariant->setAttribute('id', 100);
        $pageVariant->setRelation('language', $language);

        // Create dummy route and make it point at the page variant
        $route = new Route([]);
        $route->setPointsAt($pageVariant);

        // Ensure repository returns our dummy route
        $this->routesRepositoryMock
            ->method('getByUrl')
            ->willReturn($route);

        // Execute the detector
        $detector = $this->buildDetector('some-route');

        $this->assertEquals($language, $detector->getLanguage());
    }

    /**
     * This test checks that the language detector correctly resolves URL to a
     * language, when given URL is in format "language-slug/resource-name".
     *
     * For example "en/something" should resolve to the English language, even
     * if the "something" resource itself contains no information about the
     * language.
     *
     * This is the case when @see \App\Models\InternalPage come into play,
     * because, on one hand, they are language-agnostic by the design, and
     * nonetheless we have to somehow detect that "en/home" and "pl/home" refer
     * to two different languages.
     *
     * @return void
     */
    public function testSucceedsWhenUrlContainsLanguageSlug(): void
    {
        // Create dummy language
        $language = new Language();

        // Ensure repository returns our dummy language
        $this->languagesRepositoryMock
            ->method('getBySlug')
            ->willReturn($language);

        // Execute the detector
        $detector = $this->buildDetector('en/something');

        $this->assertEquals($language, $detector->getLanguage());
    }

    /**
     * @param string|null $url
     * @return LanguageDetector
     */
    private function buildDetector(?string $url): LanguageDetector
    {
        return new LanguageDetector(
            $this->buildRequest($url),
            $this->routesRepositoryMock,
            $this->languagesRepositoryMock
        );
    }

    /**
     * Builds a fake HTTP request with a fake route, which will return given
     * value when asked for the "url" parameter.
     *
     * @param string|null $url
     * @return IlluminateRequest
     */
    private function buildRequest(?string $url): IlluminateRequest
    {
        $route = $this->createMock(IlluminateRoute::class);
        $route
            ->method('parameter')
            ->with('url')
            ->willReturn($url);

        $request = new IlluminateRequest();
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        return $request;
    }

}