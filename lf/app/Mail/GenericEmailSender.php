<?php

namespace App\Mail;

use App\Models\Ventas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericEmailSender extends Mailable
{
    use Queueable, SerializesModels;


    public $viewData;
    public $viewName;
    public $title;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    /**
     * 
     * array(   $data= ...,  $view= ... )
     */
    public function __construct(  $anyObject)
    {
        $this->viewData=  $anyObject['data'];
        $this->viewName=  $anyObject['view'];
$this->title= $anyObject['title'];
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
        ->subject(  $this->title)
        ->view( $this->viewName,  $this->viewData ); 
    }
}
