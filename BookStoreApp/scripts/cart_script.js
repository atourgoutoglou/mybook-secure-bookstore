document.addEventListener("DOMContentLoaded", () => {
  const cartContainer = document.querySelector(".cart-items");
  const emptyMessage = document.querySelector(".empty");
  const totalPriceEl = document.getElementById("total-price");
  const checkoutBtn = document.getElementById("checkout-btn");
  const openLoginModal = document.getElementById("open-login-modal");

  /*if (!cartContainer || !emptyMessage || !totalPriceEl) {
    console.error("Missing cart DOM elements:", { cartContainer, emptyMessage, totalPriceEl });
    return;
  }*/

  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  cart = cart.map(item => ({ ...item, quantity: item.quantity || 1 }));

  // Save Cart Function
  function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
  }

  // Render Cart Function
  function renderCart() {
    cartContainer.innerHTML = "";

    if (cart.length === 0) {
      emptyMessage.style.display = "block";
      totalPriceEl.textContent = "€0.00";
      return;
    }

    emptyMessage.style.display = "none";

    let total = 0;
    cart.forEach((item, index) => {
      total += item.price * item.quantity;

      const div = document.createElement("div");
      div.classList.add("cart-item");

      div.innerHTML = `
        <img src="${item.img}" alt="${item.title}">
        <div class="item-info">
          <div class="item-title">${item.title}</div>
            <div class="quantity-control">
              <button class="decrease" data-index="${index}">-</button>
              <span>${item.quantity}</span>
              <button class="increase" data-index="${index}">+</button>
            </div>
          </div>
          <div class="item-price">€${(item.price * item.quantity).toFixed(2)}</div>
          <button class="remove-btn" data-index="${index}">Αφαίρεση</button>
        `;

      cartContainer.appendChild(div);
    });

    totalPriceEl.textContent = "€" + total.toFixed(2);
    saveCart();
  }


  // Κουμπί Αφαίρεσης, Προσθήκης Προίόντος
  cartContainer.addEventListener("click", (e) => {
    const index = e.target.dataset.index;
    if (e.target.classList.contains("remove-btn")) {
      cart.splice(index, 1);
      localStorage.setItem("cart", JSON.stringify(cart));
          
    } else if (e.target.classList.contains("increase")) {
      cart[index].quantity++;
    } else if (e.target.classList.contains("decrease")) {
      cart[index].quantity--;

      if (cart[index].quantity <= 0) {         
        cart.splice(index, 1);
        localStorage.setItem("cart", JSON.stringify(cart));           
      }
    }    
    renderCart();
        
  });

  let role = typeof userRole !== "undefined" ? userRole : document.body?.dataset?.role || "guest";
  if (checkoutBtn) {
    checkoutBtn.addEventListener("click", (ev) => {
      ev.preventDefault();
      if (cart.length === 0) {
        alert("Το καλάθι σας είναι άδειο!");
        return;
      }

      if (role === "guest") {
      // αν υπάρχει το εικονίδιο/dummy που ανοίγει modal
        if (openLoginModal) {
          openLoginModal.click();
        } else {
          alert("Παρακαλώ συνδεθείτε για να συνεχίσετε.");
        }
        return;
      }

      // αν είναι συνδεδεμένος
      alert("Ευχαριστούμε για την αγορά σας! 📚");
      localStorage.removeItem("cart");
      cart = [];
      renderCart();
    });
  }
  renderCart();
});
      