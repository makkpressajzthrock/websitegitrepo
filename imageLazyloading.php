<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lazy Load Images</title>
<style>
  .lazy {
    opacity: 0;
    transition: opacity 0.3s;
  }
  .lazy.loaded {
    opacity: 1;
  }
</style>
</head>
<body>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<img class="lazy" data-src="aaa.jpeg" alt="Lazy Loaded Image" height="500" width="500">
<img class="lazy" data-src="aaa.jpeg" alt="Lazy Loaded Image" height="500" width="500">
<img class="lazy" data-src="aaa.jpeg" alt="Lazy Loaded Image" height="500" width="500">
<img class="lazy" data-src="aaa.jpeg" alt="Lazy Loaded Image" height="500" width="500">

<script>
  document.addEventListener("DOMContentLoaded", function() {
    var lazyImages = document.querySelectorAll('.lazy');
   
    var lazyLoad = function() { alert('0');
      lazyImages.forEach(function(image) {
        if (image.getBoundingClientRect().top < window.innerHeight && !image.classList.contains('loaded')) {
          image.src = image.dataset.src;
          image.classList.add('loaded');
        }
      });
    };
    
    if ('IntersectionObserver' in window) {  
      var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {alert('11aa1');
            var lazyImage = entry.target;
            lazyImage.src = lazyImage.dataset.src;
            lazyImage.classList.add('loaded');
            observer.unobserve(lazyImage);
          }
        });
      });
      
      lazyImages.forEach(function(image) {
        observer.observe(image);
      }); 
    } else {  alert('2');
      lazyLoad();
      window.addEventListener('scroll', lazyLoad);
      window.addEventListener('resize', lazyLoad);
    }
  });
</script>

</body>
</html>
