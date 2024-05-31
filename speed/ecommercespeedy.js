window.onpaint = loadesscript();

function loadesscript() { 

const webspeedy = document.currentScript;
let store = webspeedy.getAttribute('id');
console.log(store) ;

// var ess1 = document.createElement("script") ; 
// ess1.type = "text/javascript" ;
// ess1.defer = "defer" ;
// ess1.src = "https://ecommerceseotools.com/ecommercespeedy/es-var-theme.js" ;
// document.body.appendChild(ess1);

var ess3 = document.createElement("script") ; 
ess3.type = "text/javascript" ;
ess3.defer = "defer" ;
ess3.src = "https://ecommerceseotools.com/ecommercespeedy/es-vc1.js?theme=swasticlothing.myshopify.com" ;
document.head.appendChild(ess3);


var ess1 = document.createElement("script") ; 
ess1.type = "text/javascript" ;
ess1.defer = "defer" ;
ess1.src = "https://ecommerceseotools.com/ecommercespeedy/es-gc.js?theme=swasticlothing.myshopify.com" ;
document.body.appendChild(ess1);


var ess2 = document.createElement("script") ; 
ess2.type = "text/javascript" ;
ess2.defer = "defer" ;
ess2.src = "https://ecommerceseotools.com/ecommercespeedy/es-vc.js?theme=swasticlothing.myshopify.com" ;
document.body.appendChild(ess2);




}