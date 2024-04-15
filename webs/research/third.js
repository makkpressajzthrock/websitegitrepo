/*Responsive Image Sizes Code Added by Ajay*/

setTimeout(resizeAndLoadImageAA, 1000);

function resizeAndLoadImageAA(){ 
  var all = document.getElementsByTagName("img");
  for(var i = 0, max = all.length; i < max; i++) 
  {
      //alert(all[i].src);

       resizeAndLoadImage(all[i]);
       
  }
}
 
function resizeAndLoadImage(img) {
    const datasrc = img.dataset.src;
    const datasizes = img.dataset.sizes || 'auto';
    if (datasrc) {
      const cachedData = localStorage.getItem(datasrc);
      if (cachedData) {
 
        img.setAttribute('src', cachedData);
        img.setAttribute('sizes', datasizes);
      } else {
        // Preload the image
        const preloadImage = new Image();
        preloadImage.src = datasrc;
        preloadImage.onload = function() {  console.log('NAHIAA HUA');
          const newWidth = 800; // Set your desired width here
          const newHeight = Math.floor(newWidth * (preloadImage.height / preloadImage.width));
          const canvas = document.createElement('canvas');
          const ctx = canvas.getContext('2d');
          canvas.width = newWidth;
          canvas.height = newHeight;
          ctx.drawImage(preloadImage, 0, 0, newWidth, newHeight);
          const resizedDataURL = canvas.toDataURL('image/jpeg', 0.8); // Adjust quality if needed
          img.setAttribute('src', resizedDataURL);
          img.setAttribute('sizes', datasizes);
          // Cache the resized image
          console.log(resizedDataURL);
          localStorage.setItem(datasrc, resizedDataURL);
        };
      }
    }
  }
/*Responsive Image Sizes Code Added by Ajay*/