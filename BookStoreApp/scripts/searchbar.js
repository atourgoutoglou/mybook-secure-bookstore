// Αναζήτηση Βιβλίων
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector(".main-header .search");
  const searchResults = document.querySelector(".main-header .search-results");
  let allBooks = [];

  // Φόρτωση βιβλίων
  fetch("getBooks.php")
    .then(response => response.json())
    .then(data => {
      allBooks = data; 
    })
    .catch(error => console.error("Σφάλμα φόρτωσης βιβλίων:", error));

  // Αναζήτηση σε τίτλο & συγγραφέα ενώ πληκτρολογεί ο χρήστης
  searchInput.addEventListener("input", () => {
    const query = searchInput.value.trim().toLowerCase();

    if (query.length < 2) {
      searchResults.style.display = "none";
      searchResults.innerHTML = "";
      return;
    }

    const filtered = allBooks.filter(book => 
      book.title.toLowerCase().includes(query) || 
      (book.author && book.author.toLowerCase().includes(query))
    );

    // Εμφάνιση αποτελεσμάτων
    searchResults.innerHTML = "";
    if (filtered.length === 0) {
      const emptyDiv = document.createElement("div");
      emptyDiv.classList.add("search-result-item");

      const msg = document.createElement("div");
      msg.textContent = "Δεν βρέθηκαν αποτελέσματα.";

      emptyDiv.appendChild(msg);
      searchResults.appendChild(emptyDiv);
    } else {
      filtered.forEach(book => {
        const item = document.createElement("div");
        item.classList.add("search-result-item");

        const img = document.createElement("img");
        img.src = book.img;
        img.alt = book.title;

        const info = document.createElement("div");
        info.classList.add("info");

        const title = document.createElement("div");
        title.classList.add("title");
        title.textContent = book.title;

        const author = document.createElement("div");
        author.classList.add("author");
        author.textContent = book.author || "Άγνωστος συγγραφέας";

        info.append(title, author);
        item.append(img, info);

        item.addEventListener("click", () => {
        localStorage.setItem("selectedBook", JSON.stringify(book));
        localStorage.setItem("bookAction", "view");
        window.location.href = "book.php";
        });

        searchResults.appendChild(item);
        /*const div = document.createElement("div");
        div.classList.add("search-result-item");

        const card = document.createElement("div");
        card.classList.add("product-card");

        const img = document.createElement("img");
        img.src = book.img;
        img.alt = book.title;
        img.className = "book-image";

        const title = document.createElement("h3");
        title.textContent = book.title;

        const rating = document.createElement("div");
        rating.className = "rating";
        rating.innerHTML = generateStars(book.rating);

        const price = document.createElement("p");
        price.className = "price";
        price.textContent = `€${book.price.toFixed(2)}`

        const button = document.createElement("button");
        button.className = "add-cart";
        button.textContent = "Προσθήκη στο Καλάθι";

        card.append(img, title, rating, price, button);
        div.appendChild(card);

        // Όταν κάνει κλικ σε κάποιο αποτέλεσμα, ανοίγει η σελίδα του βιβλίου
        div.addEventListener("click", () => {
          localStorage.setItem("selectedBook", JSON.stringify(book));
          localStorage.setItem("bookAction", "view");
          window.location.href = "book.php";
        });

        searchResults.appendChild(div);*/
      });
    }

    searchResults.style.display = "block";
  });

  // Απόκρυψη αποτελεσμάτων
  document.addEventListener("click", (e) => {
    if (!document.querySelector(".search-box").contains(e.target)) {
      searchResults.style.display = "none";
    }
  });
});


