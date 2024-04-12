<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lazy Load Video</title>
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
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<video class="lazy" controls muted autoplay loop>
  <source data-src="abcd.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    var lazyVideos = document.querySelectorAll('.lazy');
    
    var lazyLoad = function() {
      lazyVideos.forEach(function(video) {
        if (video.getBoundingClientRect().top < window.innerHeight && !video.classList.contains('loaded')) {
          var source = video.querySelector('source');
          source.src = source.dataset.src;
          video.load();
          video.classList.add('loaded');
        }
      });
    };
    
    if ('IntersectionObserver' in window) { 
      var observer = new IntersectionObserver(function(entries) { 
        entries.forEach(function(entry) {  
          if (entry.isIntersecting) { 
            var lazyVideo = entry.target;
            var source = lazyVideo.querySelector('source');
            source.src = source.dataset.src;
            lazyVideo.load();
            lazyVideo.classList.add('loaded');
            observer.unobserve(lazyVideo);
          }
        });
      });
      
      lazyVideos.forEach(function(video) {
        observer.observe(video);
      });
    } else {
      lazyLoad();
      window.addEventListener('scroll', lazyLoad);
      window.addEventListener('resize', lazyLoad);
    }
  });
</script>

</body>
</html>
