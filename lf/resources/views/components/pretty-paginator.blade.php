
@if( method_exists($datos, "links"))
{{ $datos->links('vendor.pagination.default',  ['customCallbackName'=> $callback]  ) }}
@if( sizeof( $datos ) == 0)
<p class="text-center p-2 bg-warning text-dark">Sin registros</p>
@endif
@endif