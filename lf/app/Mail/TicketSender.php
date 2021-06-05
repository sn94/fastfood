<?php

namespace App\Mail;

use App\Models\Ventas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketSender extends Mailable
{
    use Queueable, SerializesModels;


    public $ventas;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( Ventas $venta)
    {
        $this->ventas=  $venta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //  Mail::to($request->user()   )->send(new OrderShipped($order));
        return $this->from(  env("MAIL_FROM_ADDRESS") , 'Al Estilo Pecchi')
        ->subject("Hola!")
        ->view(  "ventas.proceso.ticket.version_impresa",  [  'VENTA'=>  $this->ventas, 'DETALLE'=> $this->ventas->detalle ]);
        // ->view(  "emails.TicketSender",  ['VENTA'=>  $this->ventas, 'DETALLE'=> $this->ventas->detalle ]); 
    }
}
