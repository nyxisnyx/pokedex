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
        if (response.ok) {
          return response.json();
        }
        throw new Error("Network response was not ok.");
      })
      .then((data) => {
        console.log(data);
        if (data.status == "success") {
          if (data.message.includes("added")) {
            fav.src = "../../../assets/images/star.svg";
          } else {
            fav.src = "../../../assets/images/star_void.svg";
          }
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
      });
  });
}

const darkModeBtn = document.querySelector("#dark_mode");
darkModeBtn.addEventListener("click", (e) => {
  const data = { darkMode: "switch" };
  fetch("../partials/dark_mode.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => {
      if (response.ok) {
        return response.json();
      }
      throw new Error("Network response was not ok.");
    })
    .then((data) => {
      if (data.status == "success") {
        console.log(data);
        // if (data.message.includes("added")) {
        //   fav.src = "../../../assets/images/star.svg";
        // } else {
        //   fav.src = "../../../assets/images/star_void.svg";
        // }
      }
    })
    .catch((error) => {
      console.error("Fetch error:", error);
    });
});
