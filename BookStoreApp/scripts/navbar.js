
// Κουμπί αναζήτησης στο nav bar
const searchBtn = document.getElementById('search-btn');
const searchPopup = document.getElementById('search-popup');
const closeSearch = document.getElementById('close-search');
const searchInput = document.getElementById('search-input');

// Εμφάνιση popup
searchBtn.addEventListener('click', (e) => {
  e.preventDefault();
  searchPopup.classList.toggle('active');
  if (searchPopup.classList.contains('active')) {
    searchInput.focus();
  }
});

// Κλείσιμο με το κουμπί Χ
closeSearch.addEventListener('click', () => {
  searchPopup.classList.remove('active');
});

// Κλείσιμο αν κάνεις κλικ εκτός popup
document.addEventListener('click', (e) => {
  if (!searchPopup.contains(e.target) && !searchBtn.contains(e.target)) {
    searchPopup.classList.remove('active');
  }
});

// Κλείσιμο με Escape
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    searchPopup.classList.remove('active');
  }
});

// === 🔍 Έξυπνη αναζήτηση βιβλίων ===
document.addEventListener("DOMContentLoaded", () => {
  const resultsBox = document.getElementById("navbar-search-results");
  const searchInput = document.getElementById("search-input");

  let allBooks = [];

  // 🔹 Φόρτωση βιβλίων από τη βάση (μία φορά)
  fetch("getBooks.php")
    .then(res => res.json())
    .then(data => { allBooks = data; })
    .catch(err => console.error("Σφάλμα φόρτωσης βιβλίων:", err));

  // 🔹 Αναζήτηση καθώς πληκτρολογεί ο χρήστης
  searchInput.addEventListener("input", () => {
    const query = searchInput.value.trim().toLowerCase();
    if (query.length < 2) {
      resultsBox.style.display = "none";
      resultsBox.innerHTML = "";
      return;
    }

    const filtered = allBooks.filter(book =>
      book.title.toLowerCase().includes(query) ||
      (book.author && book.author.toLowerCase().includes(query))
    );

    resultsBox.innerHTML = "";

    if (filtered.length === 0) {
      resultsBox.innerHTML = `<div class="search-result-item"><div>Δεν βρέθηκαν αποτελέσματα.</div></div>`;
    } else {
      filtered.slice(0, 10).forEach(book => {
        const div = document.createElement("div");
        div.classList.add("search-result-item");
        div.innerHTML = `
          <img src="${book.img}" alt="${book.title}">
          <div class="info">
            <div class="title">${book.title}</div>
            <div class="author">${book.author || "Άγνωστος συγγραφέας"}</div>
          </div>
        `;

        div.addEventListener("click", () => {
          localStorage.setItem("selectedBook", JSON.stringify(book));
          localStorage.setItem("bookAction", "view");
          window.location.href = "book.php";
        });

        resultsBox.appendChild(div);
      });
    }

    resultsBox.style.display = "block";
  });

  // 🔹 Απόκρυψη αποτελεσμάτων όταν κάνεις κλικ εκτός
  document.addEventListener("click", (e) => {
    if (!resultsBox.contains(e.target) && e.target !== searchInput) {
      resultsBox.style.display = "none";
    }
  });
});
