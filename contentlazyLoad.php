<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lazy Load Content on Scroll</title>
<style>
  #content {
    height: 2000px; /* Just for demonstration, replace with your actual content height */
  }
  #loading {
    display: none;
    text-align: center;
    padding: 20px;
  }
</style>
</head>
<body>

<div id="content">
  <!-- Initial content -->
  <p>This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the initial content. This is the in </p>
</div>

<div id="loading">Loading more content...</div>

<script>
  window.addEventListener('scroll', function() {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
      loadMoreContent();
    }
  });

  function loadMoreContent() {  
    // Show loading indicator
    document.getElementById('loading').style.display = 'block';

    // Simulate loading delay (replace with actual content loading)
    setTimeout(function() {
      // Append new content
      var newContent = document.createElement('p');
      newContent.textContent = 'Additional content loaded.';
      document.getElementById('content').appendChild(newContent);

      // Hide loading indicator
      document.getElementById('loading').style.display = 'none';
    }, 2000); // Adjust loading delay as needed
  }
</script>

</body>
</html>
