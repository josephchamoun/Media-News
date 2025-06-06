  function saveScrollAndGoToComments(postId) {
    localStorage.setItem('homeScroll', window.scrollY);
    window.location.href = '?page=comments&post_id=' + postId;
  }

    let timer;

    document.getElementById("searchBar").addEventListener("input", function () {
      const query = this.value.trim();

      clearTimeout(timer);
      timer = setTimeout(() => {
        if (query.length > 0) {
          
          fetch("?action=search_people&name=" + encodeURIComponent(query))
            .then(res => {
              if (!res.ok) {
                throw new Error('Network response was not ok');
              }
              return res.json();
            })
            .then(users => {
              const resultsDiv = document.getElementById("results");
              if (users.length > 0) {
                resultsDiv.innerHTML = users
                  .map(u => `
                    <div class="search-result">
                      <a href="?page=userprofile&user_id=${u.id}" class="user-link">
                        ${u.name}
                      </a>
                    </div>
                  `)
                  .join("");
              } else {
                resultsDiv.innerHTML = "<p>No users found</p>";
              }
            })
            .catch(error => {
              console.error('Search error:', error);
              document.getElementById("results").innerHTML = "<p>Search error occurred</p>";
            });
        } else {
          document.getElementById("results").innerHTML = "";
        }
      }, 300); 
    });

    // Optional: Hide search results when clicking outside
    document.addEventListener('click', function(event) {
      const searchBar = document.getElementById('searchBar');
      const results = document.getElementById('results');
      
      if (!searchBar.contains(event.target) && !results.contains(event.target)) {
        results.innerHTML = '';
      }
    });


      window.addEventListener('DOMContentLoaded', function() {
    const scroll = localStorage.getItem('homeScroll');
    if (scroll !== null) {
      window.scrollTo(0, parseInt(scroll, 10));
      localStorage.removeItem('homeScroll');
    }
  });