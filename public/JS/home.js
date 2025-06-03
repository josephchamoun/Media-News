let timer;

document.getElementById("searchBar").addEventListener("input", function () {
  const query = this.value.trim();

  clearTimeout(timer);
  timer = setTimeout(() => {
    if (query.length > 0) {
        fetch("controllers/search_people.php?name=" + encodeURIComponent(query))
        .then(res => res.json())
        .then(users => {
          const resultsDiv = document.getElementById("results");
          resultsDiv.innerHTML = users
            .map(u => `<p>${u.name}</p>`)
            .join("");
        });
    } else {
      document.getElementById("results").innerHTML = "";
    }
  }, 300); 
});