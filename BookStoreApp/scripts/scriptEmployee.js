// Index.html και Book.html: Φόρτωση Καρτών 
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

	const button1 = document.createElement("button");
	button1.className = "add-cart";
	button1.textContent = "Προσθήκη στο Καλάθι";

	const button2 = document.createElement("button");
	button2.className = "modify-book";
	button2.textContent = "Τροποποίηση Βιβλίου";


    img.addEventListener("click", () =>{
      localStorage.setItem("selectedBook", JSON.stringify(product));
      window.location.href = "book.php";
    });

    card.append(img, title, rating, price, button1, button2);

    card.querySelector(".add-cart").addEventListener("click", () => {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        cart.push(product);
        localStorage.setItem("cart", JSON.stringify(cart));
        alert("Το βιβλίο προστέθηκε στο καλάθι 🛒");
    });


    card.querySelector(".modify-book").addEventListener("click", () => {
        localStorage.setItem("selectedBook", JSON.stringify(product));
        localStorage.setItem("bookAction", "modify");
        window.location.href = "addBook.php";
    });

    const image = card.querySelector(".book-image");
    image.addEventListener("click", () => {
      localStorage.setItem("selectedBook", JSON.stringify(product));
      localStorage.setItem("bookAction", "view");
      window.location.href = "book.php";
    });

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

document.addEventListener("DOMContentLoaded", () => {
  const generateBtn = document.getElementById("generate-id-btn");
  const employeeIdInput = document.getElementById("generator-input");

  if (generateBtn && employeeIdInput) {
    generateBtn.addEventListener("click", () => {
      const randomId = Math.floor(10000 + Math.random() * 90000);
      employeeIdInput.value = randomId;
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
    // Φόρτωση Βιβλίων από τη βάση
    fetch("getBooks.php")
  .then(response => response.json())
  .then(data => {

    renderProducts(data, ".products .viral-product-grid");

    const latestBooks = data
      .sort((a, b) => b.id - a.id)   // ταξινόμηση φθίνουσα κατά id
      .slice(0, 4);                  // πάρε τα πρώτα 4

    renderProducts(latestBooks, ".viral-products .viral-product-grid");
  })
  .catch(error => console.error("Σφάλμα:", error));
});




document.addEventListener("DOMContentLoaded", () => {
  const selectedBookJSON = localStorage.getItem("selectedBook");
  const bookAction = localStorage.getItem("bookAction");
  if (selectedBookJSON) {
    const selectedBook = JSON.parse(selectedBookJSON);

    const inputs = document.querySelectorAll(".book-info-section input, .book-info-section textarea");
    const bookImage = document.getElementById("book-image");
    const removeBtn = document.getElementById("remove-btn");
    const addButton = document.getElementById("add-to-cart-btn");
    const headerH2 = document.getElementById("h2");

    if (inputs.length >= 5) {
      inputs[0].value = selectedBook.title || "";
      inputs[1].value = selectedBook.author || "";
      inputs[2].value = selectedBook.publisher || "";
      inputs[3].value = selectedBook.pages || "";
      inputs[4].value = selectedBook.price || "";
      inputs[5].value = selectedBook.rating || "";
      inputs[6].value = selectedBook.summary || "";
    }

    if (selectedBook.img) {
      bookImage.src = selectedBook.img;
      bookImage.style.display = "block";
      removeBtn.style.display = "inline-block";
    }

    addButton.textContent = "Αποθήκευση Αλλαγών";
    headerH2.textContent = "Τροποποίηση Βιβλίου";

    
    
  }
});


