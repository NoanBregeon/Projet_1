<?php

namespace Tests\Feature;

use Tests\TestCase;

class LanguageControllerTest extends TestCase
{
    public function test_switch_to_valid_locale_sets_session_and_redirects_back()
    {
        $response = $this->from('/')
            ->get(route('language.switch', ['locale' => 'en']));

        $response->assertRedirect('/');

        $this->assertEquals('en', session('locale'));
    }

    public function test_switch_to_invalid_locale_returns_400()
    {
        $response = $this->get('/language/xx');

        $response->assertStatus(400);
    }
}
