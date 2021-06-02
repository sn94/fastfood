/**SW 1.0 */
/**Control de cache  */
const nombreCache = "FastFoodCache";
const recursosParaCache = [
    "/fastfood/assets/images/fastfood_wallpaper.jpg",
    "/fastfood/assets/images/navbar-bg-1.jpg"
];

/**Filtrar peticiones, y utilizar la cache cuando es necesario */
self.addEventListener('fetch', (e) => {

    console.log(" Datos del Fetch", e.request);
    e.respondWith(
        caches.
            match(e.request).
            then(respuesta =>{ 
                console.log(" Respuesta de la cache",respuesta ? "de la cache" : "con fetch");

                return respuesta || fetch(e.request);
            } )


    );
 

});





/**Se tratara de guardar en la cache los recursos listados.
 * Si esta operacion es exitosa se instala el service worker
 */
self.addEventListener("install", (e) => {

 
    e.waitUntil( caches.open(nombreCache).then((theCache) => {
        theCache.addAll(recursosParaCache);
    }));
    console.log("Instalado ", e);

});




self.addEventListener("activate", (e) => { console.log("Activado ", e); });