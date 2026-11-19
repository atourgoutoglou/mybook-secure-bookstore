document.addEventListener("DOMContentLoaded", () => {
  const selectedBookJSON = localStorage.getItem("selectedBook");
  const action = localStorage.getItem("bookAction");
  const inputs = document.querySelectorAll(".book-info-section input, .book-info-section textarea");
  const bookImage = document.getElementById("book-image");
  const removeBtn = document.getElementById("remove-btn");
  const fileInput = document.getElementById("file-input");
  const uploadBtn = document.getElementById("upload-btn");
  const addButton = document.getElementById("add-to-cart-btn");
  const headerH2 = document.getElementById("h2");
  

  if (action !== "modify") {
  inputs.forEach(input => input.value = "");
    if (bookImage) {
      bookImage.src = "";
      bookImage.style.display = "none";
    }
    if (removeBtn) removeBtn.style.display = "none";
    if (fileInput) fileInput.value = "";
    localStorage.removeItem("selectedBook");
    localStorage.removeItem("bookAction");
  } else if (selectedBookJSON) {

  
    const selectedBook = JSON.parse(selectedBookJSON);
    
    
    if (inputs.length >= 5) {
      inputs[0].value = selectedBook.title || "";
      inputs[1].value = selectedBook.author || "";
      inputs[2].value = selectedBook.publisher || "";
      inputs[3].value = selectedBook.pages || "";
      inputs[4].value = selectedBook.price || "";
      inputs[5].value = selectedBook.stars || "";
      inputs[6].value = selectedBook.description || "";
    }
    
    if (selectedBook.img) {
      bookImage.src = selectedBook.img;
      bookImage.style.display = "block";
      removeBtn.style.display = "inline-block";
    }
    addButton.textContent = "Αποθήκευση Αλλαγών";
    headerH2.textContent = "Τροποποίηση Βιβλίου";
  }
  
  // Επιλογή νέας εικόνας
  uploadBtn.addEventListener("click", () => fileInput.click());
  fileInput.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => {
      bookImage.src = reader.result;
      bookImage.style.display = "block";
      removeBtn.style.display = "inline-block";

      const imagePath = "Images/" + file.name;  
      console.log("Image path:", imagePath); // debug
      document.getElementById("image-path-input").value = imagePath;
    };
    reader.readAsDataURL(file);
  });
  // Αφαίρεση εικόνας
  removeBtn.addEventListener("click", () => {
    bookImage.src = "";
    bookImage.style.display = "none";
    removeBtn.style.display = "none";
    fileInput.value = "";
  });
  // Προαιρετικά: καθαρισμός του selectedBook μετά την αποθήκευση
  addButton.addEventListener("click", () => {
    if (selectedBookJSON) localStorage.removeItem("selectedBook");
  });

  window.addEventListener("beforeunload", () => {
    // Καθαρισμός όλων των input & textarea
    inputs.forEach(input => input.value = "");

    // Καθαρισμός εικόνας
    bookImage.src = "";
    bookImage.style.display = "none";
    removeBtn.style.display = "none";
    fileInput.value = "";

    // Αφαίρεση αποθηκευμένων δεδομένων από localStorage
    localStorage.removeItem("selectedBook");
  });
});

