// This file contains small frontend-only interactions shared by every page.

document.addEventListener("DOMContentLoaded", function () {
  setTheme();
  createSidebar();
  setupThemeButton();
  setupLanguageSelector();
  setupOrderCalculator();
  setupWhatsAppButton();
  setupDemoForms();
  setupCustomerSearch();
});

function setTheme() {
  var savedTheme = localStorage.getItem("bakerTheme");
  if (savedTheme === "dark") {
    document.body.classList.add("dark-theme");
  }
}

function setupThemeButton() {
  var themeButtons = document.querySelectorAll(".theme-button");

  themeButtons.forEach(function (button) {
    updateThemeIcon(button);
    button.addEventListener("click", function () {
      document.body.classList.toggle("dark-theme");
      var selectedTheme = document.body.classList.contains("dark-theme")
        ? "dark"
        : "light";
      localStorage.setItem("bakerTheme", selectedTheme);
      themeButtons.forEach(updateThemeIcon);
    });
  });
}

function updateThemeIcon(button) {
  var isDark = document.body.classList.contains("dark-theme");
  button.innerHTML = isDark
    ? '<i class="bi bi-sun"></i>'
    : '<i class="bi bi-moon-stars"></i>';
  button.title = isDark ? "Switch to light mode" : "Switch to dark mode";
}

function createSidebar() {
  var sidebar = document.getElementById("sidebar");
  if (!sidebar) return;

  var pageName = window.location.pathname.split("/").pop() || "dashboard.html";
  var navigation = [
    ["/dashboard", "bi-grid-1x2", "Dashboard"],
    ["/customers", "bi-people", "Customers"],
    ["/products", "bi-box-seam", "Products"],
    ["/orders", "bi-receipt", "Orders"],
    ["/payments", "bi-wallet2", "Payments"],
    ["/calendar", "bi-calendar3", "Calendar"],
    
    ["/settings", "bi-gear", "Settings"],
   
  ];

  var links = "";
  navigation.forEach(function (item) {
    var activeClass = pageName === item[0] ? "active" : "";
    links +=
      '<a class="nav-link ' +
      activeClass +
      '" href="' +
      item[0] +
      '"><i class="bi ' +
      item[1] +
      '"></i>' +
      item[2] +
      "</a>";
  });

 sidebar.innerHTML =
    '<a href="dashboard.html" class="brand"><span class="brand-icon"><i class="bi bi-cake2"></i></span>Ghar Ka Baker</a>' +
    '<nav class="nav flex-column">' +
    links +
    "</nav>" +
    '<div class="sidebar-footer">Developed by Sharwan Kolhi</div>';
}

function toggleSidebar() {
  var sidebar = document.getElementById("sidebar");
  if (sidebar) sidebar.classList.toggle("show");
}

function setupLanguageSelector() {
  var selectors = document.querySelectorAll(".language-select");
  selectors.forEach(function (selector) {
    var savedLanguage = localStorage.getItem("bakerLanguage") || "English";
    selector.value = savedLanguage;
    selector.addEventListener("change", function () {
      localStorage.setItem("bakerLanguage", selector.value);
      selectors.forEach(function (otherSelector) {
        otherSelector.value = selector.value;
      });
      showToast("Language selected: " + selector.value + " (demo UI)");
    });
  });
}

function setupOrderCalculator() {
  var totalInput = document.getElementById("totalAmount");
  var advanceInput = document.getElementById("advancePayment");
  var remainingInput = document.getElementById("remainingAmount");

  if (!totalInput || !advanceInput || !remainingInput) return;

  function calculateRemaining() {
    var total = Number(totalInput.value) || 0;
    var advance = Number(advanceInput.value) || 0;
    var remaining = total - advance;
    remainingInput.value = remaining < 0 ? 0 : remaining;
  }

  totalInput.addEventListener("input", calculateRemaining);
  advanceInput.addEventListener("input", calculateRemaining);
  calculateRemaining();
}

/*===========================For Whatsapp==================================================*/

function setupWhatsAppButton() {
  var whatsappButton = document.getElementById("whatsappButton");
  if (!whatsappButton) return;

  whatsappButton.addEventListener("click", function () {
    var customerSelect = document.getElementById("customerSelect");
    var selectedOption = customerSelect ? customerSelect.options[customerSelect.selectedIndex] : null;

    var customerName = selectedOption && selectedOption.dataset.name
      ? selectedOption.dataset.name
      : "Customer";
    var customerWhatsapp = selectedOption && selectedOption.dataset.whatsapp
      ? selectedOption.dataset.whatsapp
      : "";

    if (!customerWhatsapp) {
      showToast("Pehle customer select karein jiska WhatsApp number ho.");
      return;
    }

    var total = document.getElementById("totalAmount")
  ? document.getElementById("totalAmount").value
  : "0";
var advance = document.getElementById("advancePayment")
  ? document.getElementById("advancePayment").value
  : "0";
var remaining = document.getElementById("remainingAmount")
  ? document.getElementById("remainingAmount").value
  : "0";
var deliveryDate = document.getElementById("deliveryDate")
  ? document.getElementById("deliveryDate").value
  : "";

var message =
  "Assalam-o-Alaikum " +
  customerName +
  "!%0A%0AYour order is confirmed by Ghar Ka Baker.%0AOrder Total: PKR " +
  total +
  "%0AAdvance Paid: PKR " +
  advance +
  "%0ARemaining: PKR " +
  remaining +
  "%0ADelivery: " +
  deliveryDate +
  "%0A%0AThank you!";

    // Remove any spaces/dashes from the number, and prefix 92 if it starts with 0
    var cleanNumber = customerWhatsapp.replace(/[^0-9]/g, "");
    if (cleanNumber.startsWith("0")) {
      cleanNumber = "92" + cleanNumber.substring(1);
    }

    window.open("https://wa.me/" + cleanNumber + "?text=" + message, "_blank");
  });
}
/*===========================For Whatsapp==================================================*/


function setupDemoForms() {
  var forms = document.querySelectorAll(".demo-form");
  forms.forEach(function (form) {
    form.addEventListener("submit", function (event) {
      if (
        form.action.includes("/register") ||
        form.action.includes("/login") ||
        form.action.includes("/products")||
        form.action.includes("/customers")||
        form.action.includes("/orders")||
        form.action.includes("/payments")||
        form.action.includes("/settings")
      ) {
        return;
      }
      event.preventDefault();
      showToast("Saved successfully! This is a frontend demo.");
      var modalElement = form.closest(".modal");
      if (modalElement && window.bootstrap) {
        bootstrap.Modal.getInstance(modalElement).hide();
      }
    });
  });
}

function setupCustomerSearch() {
  var input = document.getElementById("customerSearch");
  var rows = document.querySelectorAll("#customerTable tbody tr");
  if (!input || !rows.length) return;

  input.addEventListener("input", function () {
    var searchText = input.value.toLowerCase();
    rows.forEach(function (row) {
      row.style.display = row.innerText.toLowerCase().includes(searchText)
        ? ""
        : "none";
    });
  });
}



function showToast(message) {
  var toast = document.getElementById("appToast");
  var toastText = document.getElementById("toastText");
  if (!toast || !toastText || !window.bootstrap) return;
  toastText.textContent = message;
  new bootstrap.Toast(toast).show();
}

function openAddModal() {
  document.getElementById("productModalTitle").textContent = "Add Product";
  document.getElementById("productForm").action = "/products";
  document.getElementById("formMethod").value = "POST";
  document.getElementById("productForm").reset();
}

function openEditModal(btn) {
  document.getElementById("productModalTitle").textContent = "Edit Product";
  document.getElementById("productForm").action = "/products/" + btn.dataset.id;
  document.getElementById("formMethod").value = "PUT";

  document.querySelector('#productForm [name="name"]').value = btn.dataset.name;
  document.querySelector('#productForm [name="category"]').value = btn.dataset.category;
  document.querySelector('#productForm [name="price"]').value = btn.dataset.price;
  document.querySelector('#productForm [name="description"]').value = btn.dataset.description;
  document.querySelector('#productForm [name="is_available"]').checked = btn.dataset.available === "1";
}



/*===========================For filter===========================================*/
function filterProducts(clickedButton) {
  var selectedCategory = clickedButton.dataset.category;

  // Update active button Style
  var allButtons = document.querySelectorAll("#categoryFilters button");
  allButtons.forEach(function (btn) {
    btn.classList.remove("btn-gradient");
    btn.classList.add("btn-soft");
  });
  clickedButton.classList.remove("btn-soft");
  clickedButton.classList.add("btn-gradient");

  // show/hide products
  var products = document.querySelectorAll(".product-item");
  products.forEach(function (item) {
    if (selectedCategory === "All" || item.dataset.category === selectedCategory) {
      item.style.display = "";
    } else {
      item.style.display = "none";
    }
  });
}
/*===========================For filter===========================================*/


/*===========================For Customer ===========================================*/

function openAddCustomerModal() {
  document.getElementById("customerModalTitle").textContent = "Add New Customer";
  document.getElementById("customerForm").action = "/customers";
  document.getElementById("customerFormMethod").value = "POST";
  document.getElementById("customerForm").reset();
}

function openEditCustomerModal(btn) {
  document.getElementById("customerModalTitle").textContent = "Edit Customer";
  document.getElementById("customerForm").action = "/customers/" + btn.dataset.id;
  document.getElementById("customerFormMethod").value = "PUT";

  document.querySelector('#customerForm [name="name"]').value = btn.dataset.name;
  document.querySelector('#customerForm [name="phone"]').value = btn.dataset.phone;
  document.querySelector('#customerForm [name="whatsapp_number"]').value = btn.dataset.whatsapp;
  document.querySelector('#customerForm [name="city"]').value = btn.dataset.city;
  document.querySelector('#customerForm [name="address"]').value = btn.dataset.address;
}
/*===========================For Customer ===========================================*/


/*===========================For Orders ===========================================*/

var itemIndex = 0;

function addItemRow() {
  var container = document.getElementById("itemsContainer");
  if (!container) return;

  var index = itemIndex;
  itemIndex++;

  var row = document.createElement("div");
  row.className = "row g-3 item-row align-items-end mb-3 border-bottom pb-3";
  row.dataset.index = index;

  var productOptions = '<option value="">-- Custom Item --</option>';
  window.availableProducts.forEach(function (p) {
    productOptions += '<option value="' + p.id + '" data-price="' + p.price + '" data-name="' + p.name + '">' + p.name + '</option>';
  });

  row.innerHTML =
    '<div class="col-md-4">' +
      '<label class="form-label">Product</label>' +
      '<select class="form-select item-product" name="items[' + index + '][product_id]" onchange="handleProductChange(this)">' +
        productOptions +
      '</select>' +
    '</div>' +
    '<div class="col-md-3">' +
      '<label class="form-label">Item Name</label>' +
      '<input type="text" class="form-control item-name" name="items[' + index + '][item_name]" placeholder="e.g. Chocolate Cake" required>' +
    '</div>' +
    '<div class="col-md-2">' +
      '<label class="form-label">Quantity</label>' +
      '<input type="number" class="form-control item-qty" name="items[' + index + '][quantity]" min="1" value="1" onchange="calculateTotal()" oninput="calculateTotal()">' +
    '</div>' +
    '<div class="col-md-2">' +
      '<label class="form-label">Unit Price</label>' +
      '<input type="number" class="form-control item-price" name="items[' + index + '][unit_price]" min="0" value="0" onchange="calculateTotal()" oninput="calculateTotal()">' +
    '</div>' +
    '<div class="col-md-1 d-flex align-items-end">' +
      '<button type="button" class="btn btn-sm btn-soft text-danger" onclick="removeItemRow(this)"><i class="bi bi-trash"></i></button>' +
    '</div>' +
    '<div class="col-12">' +
      '<input type="text" class="form-control item-description" name="items[' + index + '][description]" placeholder="Description (optional)">' +
    '</div>';

  container.appendChild(row);
}

function removeItemRow(btn) {
  var row = btn.closest(".item-row");
  if (row) row.remove();
  calculateTotal();
}

function handleProductChange(select) {
  var row = select.closest(".item-row");
  var selectedOption = select.options[select.selectedIndex];
  var price = selectedOption.dataset.price || 0;
  var name = selectedOption.dataset.name || "";

  row.querySelector(".item-price").value = price;
  if (name) {
    row.querySelector(".item-name").value = name;
  }

  calculateTotal();
}

function calculateTotal() {
  var rows = document.querySelectorAll(".item-row");
  var total = 0;

  rows.forEach(function (row) {
    var qty = Number(row.querySelector(".item-qty").value) || 0;
    var price = Number(row.querySelector(".item-price").value) || 0;
    total += qty * price;
  });

  var totalInput = document.getElementById("totalAmount");
  if (totalInput) totalInput.value = total;

  updateRemaining();
}

function updateRemaining() {
  var total = Number(document.getElementById("totalAmount").value) || 0;
  var advance = Number(document.getElementById("advancePayment").value) || 0;
  var remaining = total - advance;
  document.getElementById("remainingAmount").value = remaining < 0 ? 0 : remaining;
}

// Automatically add an empty item row as soon as the order page loads
document.addEventListener("DOMContentLoaded", function () {
  if (document.getElementById("itemsContainer")) {
    addItemRow();
    var advanceInput = document.getElementById("advancePayment");
    if (advanceInput) {
      advanceInput.addEventListener("input", updateRemaining);
    }
  }
});

/*===========================For Orders ===========================================*/
