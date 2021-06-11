/**SW 1.0 */
/**Control de cache  */
const base_url= "/fastfood";
const nombreCache = "FastFoodCache";
const recursosParaCache = [
    base_url+"/assets/images/fastfood_wallpaper.jpg",
    base_url+"/assets/images/navbar-bg-1.jpg",
    base_url+"/assets/icons/burger_icon.png",
    base_url+"/assets/icons/atencion.png",
    base_url+"/assets/images/bg-screen.png",

    //js 
    base_url+"/assets/js/jquery.min.js",
    base_url+"/assets/bootstrap5/bootstrap.bundle.min.js",
    base_url+"/assets/js/custom.js?v=366298319000"
];

/**Filtrar peticiones, y utilizar la cache cuando es necesario */
self.addEventListener('fetch', (e) => {

   
    e.respondWith(
        caches.
            match(e.request).
            then(respuesta =>{ 
              
                return respuesta || fetch(e.request);
            } )


    );
 

});





/**Se tratara de guardar en la cache los recursos listados.
 * Si esta operacion es exitosa se instala el service worker
 */
self.addEventListener("install", (e) => {

 
    e.waitUntil(
         caches.
         open(nombreCache).
         then((theCache) => {

        theCache.addAll(recursosParaCache);

    }));

    
    console.log("Instalado ", e);

});




self.addEventListener("activate", (e) => { console.log("Activado ", e); });