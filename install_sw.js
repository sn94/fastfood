//Service worker

if ("serviceWorker" in navigator) {
    let version= Math.random();
    navigator.serviceWorker.register("/fastfood/sw.js?v="+version,  {scope: './'} )
        .then(e => console.log("sw instalado", e))
        .catch(e => console.log("Error al instalar sw", e));
}