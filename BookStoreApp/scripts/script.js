// Index.html: Πίνακας βιβλίων

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

    const button = document.createElement("button");
    button.className = "add-cart";
    button.textContent = "Προσθήκη στο Καλάθι";

    img.addEventListener("click", () =>{
      localStorage.setItem("selectedBook", JSON.stringify(product));
      window.location.href = "book.php";
    });

    card.append(img, title, rating, price, button);

    card.querySelector(".add-cart").addEventListener("click", () => {
      let cart = JSON.parse(localStorage.getItem("cart")) || [];
      cart.push(product);
      localStorage.setItem("cart", JSON.stringify(cart));
      alert("Το βιβλίο προστέθηκε στο καλάθι 🛒");
    });

    const image = card.querySelector(".book-image");
    image.addEventListener("click", () => {
      localStorage.setItem("selectedBook", JSON.stringify(product));
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

// Φόρτωση Βιβλίων από τη βάση
document.addEventListener("DOMContentLoaded", () => {
  fetch("getBooks.php")
  .then(response => response.json())
  .then(data => {

    renderProducts(data, ".products .viral-product-grid");

    // ➤ Τα 4 τελευταία με βάση το id
    const latestBooks = data
      .sort((a, b) => b.id - a.id)   // ταξινόμηση φθίνουσα κατά id
      .slice(0, 4);                  // πάρε τα πρώτα 4

    renderProducts(latestBooks, ".viral-products .viral-product-grid");
  })
  .catch(error => console.error("Σφάλμα:", error));
});


//SignUp_LogIn.html

// Φορτωση Modal από αρχείο Index.html 
// Άνοιγμα του modal SignUp_LogIn.html
document.addEventListener("DOMContentLoaded", () => {
const openBtn = document.getElementById("open-login-modal");
fetch('signUp_logIn.php')
  .then(response => response.text())
  .then(data => {
    document.body.insertAdjacentHTML('beforeend', data);

    const modal = document.getElementById("login-modal");
    const closeBtn = modal.querySelector(".close-btn");
    const createAccountBtn = modal.querySelector(".create-account-btn"); // <-- Εδώ μέσα!

     const roleSelect = modal.querySelector("#role-select");
    const roleExtraField = modal.querySelector("#extra-role-field");

    roleSelect.addEventListener("change", () => {
      roleExtraField.innerHTML = "";
      if (roleSelect.value === "employee" || roleSelect.value === "admin") {
        const input = document.createElement("input");
        input.type = "text";
        input.placeholder = "Κωδικός Αναγνώρισης*";
        input.name = "number_id";
        input.required = true;
        input.classList.add("signup-form-input");
        roleExtraField.appendChild(input);
      }
    });

    // Άνοιγμα login modal
    openBtn.addEventListener("click", (e) => {
      e.preventDefault();
      modal.style.display = "flex";
      document.body.style.overflow = "hidden";
    });

    // Κλείσιμο login modal
    closeBtn.addEventListener("click", () => {
      modal.style.display = "none";
      document.body.style.overflow = "auto";
    });

    // Κλείσιμο αν πατήσεις έξω
    window.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
      }
    });

    // Άνοιγμα του modal signUp.html από το κουμπί 'Δημιουργία Λογαριασμού' μέσα από το modal SignUp_LogIn.html
    createAccountBtn.addEventListener("click", () => {
      modal.style.display = "none"; // κλείνει login modal

      fetch('signUp.php')
        .then(res => res.text())
        .then(data => {
          document.body.insertAdjacentHTML('beforeend', data);
        

          //Εμφάνιση και Απόκρυψη Εξτρα Πέδιου 
          const signupModal = document.getElementById("signup-modal");
          const roleSelect = signupModal.querySelector("#role-select");
          const roleExtraField = signupModal.querySelector("#extra-role-field");
          roleSelect.addEventListener("change", () => { 
            roleExtraField.innerHTML = ""; 
            // Εμφάνιση μόνο αν δεν είναι πελάτης 
            if (roleSelect.value === "employee" || roleSelect.value === "admin") { 
              const input = document.createElement("input"); 
              input.type = "text"; 
              input.placeholder = "Κωδικός Αναγνώρισης*"; 
              input.required = true; 
              input.classList.add("signup-form-input"); // για styling 
              roleExtraField.appendChild(input); 
            } 
          });


        // Κουμπί 'Πίσω'
        const backToLoginBtn = document.getElementById("back-to-login");
        const loginModal = document.getElementById("login-modal"); // πρέπει να υπάρχει στο DOM

        backToLoginBtn.addEventListener("click", () => {
          const signupModal = document.getElementById("signup-modal");
          signupModal.style.display = "none";      // κλείνει το signup modal
          loginModal.style.display = "flex";       // ανοίγει ξανά το login modal
          document.body.style.overflow = "hidden";
        });

        //const signupModal = document.getElementById("signup-modal");
        const signupCloseBtn = signupModal.querySelector(".close-btn");

        signupModal.style.display = "flex";
        document.body.style.overflow = "hidden";

        // Κλείσιμο signup modal
        signupCloseBtn.addEventListener("click", () => {
          signupModal.style.display = "none";
          document.body.style.overflow = "auto";
        });

          window.addEventListener("click", (e) => {
            if (e.target === signupModal) {
              signupModal.style.display = "none";
              document.body.style.overflow = "auto";
            }
          });
        });
    });
  })
  .catch(err => console.error(err));
});


