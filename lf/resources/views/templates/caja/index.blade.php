 @extends("templates.app")

 @section("cssStyles")
 @yield("cssStyles")
 @endsection

 @section("menu")
 @include("templates.caja.menu")
 @endsection

 
 @section("contenido")
 <input type="hidden"  id="fast-food-identification-for-module-context"  value="CAJA">
 @yield("content")
 @endsection

 @section("Footer")
<footer class="ftco-footer ftco-section img">
         <div class="row">
             <div class="col-md-12 text-center">

                 <p style="color: #757e01;">
                     <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                     Pentalfa Informática
                     <script>
                         document.write(new Date().getFullYear());
                     </script>
                     <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                 </p>
             </div>
         </div>
     </footer>
@endsection