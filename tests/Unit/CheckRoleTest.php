<?php

namespace Tests\Unit;

use App\Http\Middleware\CheckRole;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CheckRoleTest extends TestCase
{
    public function test_redirects_to_login_when_no_user()
    {
        $middleware = new CheckRole;

        $request = Request::create('/admin', 'GET');

        $response = $middleware->handle($request, function () {
            return new Response('OK');
        }, 'admin');

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertStringContainsString('login', $response->headers->get('Location'));
    }

    public function test_aborts_with_403_when_user_has_not_role()
    {
        $this->expectException(HttpException::class);

        $middleware = new CheckRole;
        $request = Request::create('/admin', 'GET');

        $fakeUser = new class
        {
            public function isA(string $role): bool
            {
                return false;
            }
        };

        $request->setUserResolver(fn () => $fakeUser);

        $middleware->handle($request, function () {
            return new Response('OK');
        }, 'admin');
    }

    public function test_allows_request_when_user_has_role()
    {
        $middleware = new CheckRole;
        $request = Request::create('/admin', 'GET');

        $fakeUser = new class
        {
            public function isA(string $role): bool
            {
                return true;
            }
        };

        $request->setUserResolver(fn () => $fakeUser);

        $response = $middleware->handle($request, function () {
            return new Response('OK');
        }, 'admin');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getContent());
    }
}
