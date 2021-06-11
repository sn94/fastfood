//Service worker

if ("serviceWorker" in navigator) {
//    let version= Math.random(); ?v="+version
    navigator.serviceWorker.register("/fastfood/sw.js?V=5",  {scope: './'} )
        .then(e => { console.log("Registrado", e);})
        .catch(e => console.log("Error al instalar sw", e));
}