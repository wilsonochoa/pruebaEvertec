<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EvertecTest extends TestCase
{
    /**
     * prueba de respuesta con codigo 200 al listado de ordenes
     *
     * @return void
     */
    public function test_lst_order()
    {
        $response = $this->get('/lstorder');
        $response->assertStatus(200);
    }

    /**
     * prueba de respuesta con codigo 200 al index
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * prueba de respuesta con codigo 200 al metodo de crear la orden
     *
     * @return void
     */
    public function test_create()
    {
        $response = $this->get('/create');
        $response->assertStatus(200);

        $order = Orders::find(DB::table('orders')->max('id'));
        if($order !== null){
            $response = $this->get('/create/' . $order->id);
            $response->assertStatus(200);
        }
    }

    /**
     * prueba de respuesta con codigo 302 al metodo de crear la orden enviando parametros al formulario
     *
     * @return void
     */
    public function test_create_process()
    {
        //prueba de carga
        $data = [
            'name' => '',
            'email' => '',
            'mobile' => ''
        ];
        $response = $this->post('/createprocess', $data);
        $response->assertStatus(302);

        //prueba registro nuevo
        $data = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'mobile' => '312456789',
        ];
        $response = $this->post('/createprocess', $data);
        $order = Orders::find(DB::table('orders')->max('id'));
        $response->assertStatus(302);
        $response->assertRedirect('/preview/' . $order->id);

        //prueba actualizar registro
        $order = Orders::find(DB::table('orders')->max('id'));
        $data = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'mobile' => '312456789',
            'id_order' => $order->id
        ];
        $response = $this->post('/createprocess', $data);
        $order = Orders::find(DB::table('orders')->max('id'));
        $response->assertStatus(302);
        $response->assertRedirect('/preview/' . $order->id);
    }


    /**
     * prueba de respuesta con codigo 200 al metodo de resumen de la orden enviando parametros al formulario
     *
     * @return void
     */
    public function test_preview()
    {
        //Si envian un id que no se encuentra registrado
        $responseFail = $this->get('/preview/' . fake()->uuid());
        $responseFail->assertStatus(302);
        $responseFail->assertRedirect('/create');

        //envio con id correcto
        $order = Orders::find(DB::table('orders')->max('id'));
        $response = $this->get('/preview/' . $order->id);
        $response->assertStatus(200);
    }

    /**
     * prueba de respuesta al metodo preview_process la orden enviando parametros al formulario
     *
     * @return void
     */
    public function test_preview_process()
    {
        //envio con id erroneo
        $data = [
            'id_orden' => fake()->uuid()
        ];
        $responseFail = $this->post('/previewprocess', $data);
        $responseFail->assertStatus(302);
        $responseFail->assertRedirect('/create');

        //envio con id correcto
        $order = Orders::find(DB::table('orders')->max('id'));
        $data = [
            'id_orden' => $order->id
        ];
        $response = $this->post('/previewprocess', $data);
        $response->assertStatus(302);
        $response->assertRedirectContains('https://checkout-co.placetopay.dev/session/');
    }

    /**
     * prueba de respuesta al metodo que renderiza la vista del estado del producto
     *
     * @return void
     */
    public function test_state_order()
    {
        //Si envian un id que no se encuentra registrado
        $responseFail = $this->get('/viewstateorden/' . fake()->uuid());
        $responseFail->assertStatus(302);
        $responseFail->assertRedirect('/lstorder');
        //caso normal
        $order = Orders::find(DB::table('orders')->max('id'));
        $response = $this->get('/viewstateorden/' . $order->id);
        $response->assertStatus(200);
    }

    /**
     * prueba de respuesta al metodo de reintentar pago de la orden enviando parametros al formulario
     *
     * @return void
     */
    public function test_retry_pay()
    {
        $responseFail = $this->get('/retrypayorder/' . fake()->uuid());
        $responseFail->assertStatus(302);
        $responseFail->assertRedirect('/lstorder');

        //envio con id correcto
        $order = Orders::find(DB::table('orders')->max('id'));
        $response = $this->get('/retrypayorder/' . $order->id);
        $response->assertStatus(302);
        $response->assertRedirectContains('https://checkout-co.placetopay.dev/session/');
    }
}
