<?php

namespace Tests\Feature;

use App\Models\Session;
use App\Models\SessionStatus;
use App\Models\User;
use Tests\TestCase;

class CreateSessionTest extends TestCase
{

    /** @test */
    public function create_session()
    {

        $this->assertEquals(0, Session::count());

        $user = factory(User::class)->create();

        $datetime_start = now()->addDays(2)->toDateString();

        $post_data = [
            'name' => 'TESTE ABC',
            'user_id' => $user->id,
            'datetime_start' => $datetime_start
        ];

        $response = $this->post(route('api.session.store'), $post_data);

        $response->assertStatus(200);
        $this->assertEquals(1, Session::count());

        $session = Session::first();

        $this->assertNull($session->datetime_end);
        $this->assertEquals(SessionStatus::SESSION_STATUS_AGUARDANDO_VOTACAO, $session->session_status_id);
        $this->assertEquals('TESTE ABC', $session->name);
        $this->assertEquals($datetime_start, $session->datetime_start);

    }

    /** @test */
    public function session_name_is_required()
    {
        $this->assertEquals(0, Session::count());
        $post_data = [
            'user_id' => 1,
        ];

        $response = $this->post(route('api.session.store'), $post_data);

        $response->assertStatus(422);
        $this->assertEquals(0, Session::count());
        $this->assertNull(Session::first());
    }

    /** @test */
    public function user_id_is_required()
    {
        $this->assertEquals(0, Session::count());
        $post_data = [
            'name' => 'TESTE ABC',
        ];

        $response = $this->post(route('api.session.store'), $post_data);

        $response->assertStatus(422);
        $this->assertEquals(0, Session::count());
        $this->assertNull(Session::first());
    }
}
