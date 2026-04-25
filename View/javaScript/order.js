let order = document.querySelector(".checkout-btn");
let order_check = Document.querySelector(".is_checked")

order.addEventListener("click", () => {
    if (order_check.length() === 0) {
        alert("Please select at least one item to order");
    }

    window.location.href="order.html"


    
})
