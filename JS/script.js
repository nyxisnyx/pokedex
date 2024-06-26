const favs = document.querySelectorAll(".fav");
for (let fav of favs) {
  fav.addEventListener("click", (e) => {
    let classes = e.target.classList[1];
    let id = classes.split("_")[1];

    const data = { id: id };

    // Send data to PHP
    fetch("../partials/favourites.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then((response) => {
        console.log(response.json());
        if (response.ok) {
          return response.json();
        }
        throw new Error("Network response was not ok.");
      })
      .then((data) => {
        console.log(data);
      })
      .catch((error) => {
        console.error("Fetch error:", error);
      });
  });
}
