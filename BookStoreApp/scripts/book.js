// Section 'Δειτε Επισης'
let action =null;
document.addEventListener("DOMContentLoaded", () => {
    // Φόρτωση Βιβλίων από τη βάση
    fetch("getBooks.php")
  .then(response => response.json())
  .then(data => {
    renderProducts(data, ".products .viral-product-grid");
  })
  .catch(error => console.error("Σφάλμα:", error));
});

document.addEventListener("DOMContentLoaded", () => {
  const book = JSON.parse(localStorage.getItem("selectedBook"));
  const action = localStorage.getItem("bookAction");

  if (!book) {
    document.body.innerHTML = "<h2>Δεν βρέθηκε βιβλίο!</h2>";
    return;
  }

  // Φόρτωση Πεδίων με τα στοιχεία των βιβλίων από τη βάση
  book.author = book.author || "Ανώνυμος";
  book.publisher = book.publisher || "Εκδόσεις myBOOK";
  book.pages = book.pages || Math.floor(Math.random() * 200 + 150);
  book.summary = book.summary || "Ένα συναρπαστικό βιβλίο που σε ταξιδεύει σε κόσμους φαντασίας και πραγματικότητας.";

  document.getElementById("book-image").src = book.img;
  document.getElementById("book-title").textContent = book.title;
  document.getElementById("book-author").textContent = book.author;
  document.getElementById("book-publisher").textContent = book.publisher;
  document.getElementById("book-pages").textContent = book.pages;
  document.getElementById("book-price").textContent = `€${book.price.toFixed(2)}`;
  const ratingDiv = document.getElementById("book-rating");
  if(ratingDiv && book.rating){
    ratingDiv.innerHTML = generateStars(book.rating);
  }
  document.getElementById("book-description").textContent = book.summary;

  document.getElementById("add-to-cart-btn").addEventListener("click", () => {
    alert(`${book.title} προστέθηκε στο καλάθι!`);
  });
  
});

  window.addEventListener("pagehide", () => {
    // Αν δεν πρόκειται για τροποποίηση, καθάρισε τα πάντα
    if (action !== "modify") {
      //localStorage.removeItem("selectedBook");
      //localStorage.removeItem("bookAction");
    }
  });

  


function renderProducts(products, containerSelector) {
  const grid = document.querySelector(containerSelector);
  if (!grid) return;

  grid.innerHTML = "";

  products.forEach(product => {
    const card = document.createElement("div");
    card.classList.add("product-card");

    const img = document.createElement("img");
    img.src = product.img;
    img.alt = product.title;
    img.className = "book-image";

    const title = document.createElement("h3");
    title.textContent = product.title;

    const rating = document.createElement("div");
    rating.className = "rating";
    rating.innerHTML = generateStars(product.rating);

    const price = document.createElement("p");
    price.className = "price";
    price.textContent = `€${product.price.toFixed(2)}`

    const description = document.createElement("p");
    description.className = "product-description";
    description.textContent = product.summary;

    const button = document.createElement("button");
    button.className = "add-cart";
    button.textContent = "Προσθήκη στο Καλάθι";

    img.addEventListener("click", () =>{
      console.log(product); // debug
      localStorage.setItem("selectedBook", JSON.stringify(product));
      window.location.href = "book.php";
    });

    card.append(img, title, rating, price, description, button);
    grid.appendChild(card);
  });
}

// Συνάρτηση για δημιουργία αστέρων αξιολόγησης
function generateStars(rating) {
  let stars = "";
  for (let i = 1; i <= 5; i++) {
    if (i <= Math.floor(rating)) stars += "<i class='bx bxs-star'></i>";
    else if (i - rating < 1) stars += "<i class='bx bxs-star-half'></i>";
    else stars += "<i class='bx bx-star'></i>";
  }
  return stars;
}


