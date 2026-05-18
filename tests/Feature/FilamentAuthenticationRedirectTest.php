<?php

namespace Tests\Feature;

use Tests\TestCase;

class FilamentAuthenticationRedirectTest extends TestCase
{
    public function test_filament_login_route_redirects_to_the_default_login_page()
    {
        $response = $this->get(route('filament.auth.login'));

        $response->assertRedirect(route('login'));
    }

    public function test_unauthenticated_filament_pages_redirect_to_the_default_login_page()
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }
}
