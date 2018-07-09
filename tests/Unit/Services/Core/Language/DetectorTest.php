<?php

namespace Tests\Unit\Services\Core\Language;

use App\Exceptions\RepositoryException;
use App\Models\Language;
use App\Models\PageVariant;
use App\Models\Route;
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
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->routesRepositoryMock = $this->createMock(RoutesRepository::class);
    }

    /**
     * @return void
     *
     * @throws RepositoryException
     */
    public function testFailsWhenGivenRouteWithoutUrl(): void
    {
        $detector = $this->buildDetector(null);

        $this->assertNull(
            $detector->getLanguage()
        );
    }

    /**
     * @return void
     *
     * @throws RepositoryException
     */
    public function testFailsWhenRouteResolvesToModelWithoutLanguageProperty(): void
    {
        $route = new Route([]);
        $route->setAttribute('id', 100);
        $route->setPointsAt($route);

        $this->routesRepositoryMock
            ->method('getByUrlOrFail')
            ->willReturn($route);

        $detector = $this->buildDetector('some-route');

        $this->assertNull(
            $detector->getLanguage()
        );
    }

    /**
     * @return void
     *
     * @throws RepositoryException
     */
    public function testSucceedsWhenRouteResolvesToModelWithLanguageProperty(): void
    {
        $language = new Language();

        $pageVariant = new PageVariant();
        $pageVariant->setAttribute('id', 100);
        $pageVariant->setRelation('language', $language);

        $route = new Route([]);
        $route->setPointsAt($pageVariant);

        $this->routesRepositoryMock
            ->method('getByUrlOrFail')
            ->willReturn($route);

        $detector = $this->buildDetector('some-route');

        $this->assertEquals($language, $detector->getLanguage());
    }

    /**
     * @param string|null $urlParameterValue
     * @return LanguageDetector
     */
    private function buildDetector(?string $urlParameterValue): LanguageDetector
    {
        $request = $this->buildRequest($urlParameterValue);

        return new LanguageDetector($request, $this->routesRepositoryMock);
    }

    /**
     * Builds a fake HTTP request with a fake route, which will return given
     * value when asked for the "url" parameter.
     *
     * @param string|null $urlParameterValue
     * @return IlluminateRequest
     */
    private function buildRequest(?string $urlParameterValue): IlluminateRequest
    {
        $route = $this->createMock(IlluminateRoute::class);
        $route
            ->method('parameter')
            ->with('url')
            ->willReturn($urlParameterValue);

        $request = new IlluminateRequest();

        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        return $request;
    }

}