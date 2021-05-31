self.addEventListener('fetch',(e) => console.log("fetch", e));
self.addEventListener( "install", (e)=>console.log("Instalado ", e) );
self.addEventListener( "activate", (e)=>console.log("Activado ", e) );