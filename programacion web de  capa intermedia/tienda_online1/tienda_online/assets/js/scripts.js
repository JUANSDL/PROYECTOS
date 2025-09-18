document.addEventListener("DOMContentLoaded", function () {
    // Slider de imágenes automático
    document.querySelectorAll(".image-slider").forEach(slider => {
        let images = slider.querySelectorAll(".slider-image");
        let index = 0;

        if (images.length > 1) {
            setInterval(() => {
                images.forEach(img => img.classList.remove("active"));
                images[index].classList.add("active");
                index = (index + 1) % images.length;
            }, 3000);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Seleccionar todos los botones de añadir al carrito
        const addToCartButtons = document.querySelectorAll('.btn-add-cart');
        
        // Añadir event listener a cada botón
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                addToCart(productId);
            });
        });
        
        // Seleccionar todos los botones de añadir a la lista
        const addToListButtons = document.querySelectorAll('.btn-add-list');
        
        // Añadir event listener a cada botón
        addToListButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                addToList(productId);
            });
        });
        
        // Función para añadir al carrito
        function addToCart(productId) {
            // Verificar si ya existe un carrito en localStorage
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            
            // Verificar si el producto ya está en el carrito
            const existingProduct = cart.find(item => item.id === productId);
            
            if (existingProduct) {
                // Si ya existe, incrementar la cantidad
                existingProduct.quantity += 1;
            } else {
                // Si no existe, añadir el producto con cantidad 1
                cart.push({
                    id: productId,
                    quantity: 1
                });
            }
            
            // Guardar el carrito actualizado en localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Mostrar mensaje de confirmación
            alert('Producto añadido al carrito');
            
            // Opcional: actualizar contador del carrito si existe
            updateCartCounter();
        }
        
        // Función para añadir a la lista de deseos
        function addToList(productId) {
            // Verificar si ya existe una lista en localStorage
            let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
            
            // Verificar si el producto ya está en la lista
            if (!wishlist.includes(productId)) {
                // Añadir el producto a la lista
                wishlist.push(productId);
                
                // Guardar la lista actualizada en localStorage
                localStorage.setItem('wishlist', JSON.stringify(wishlist));
                
                // Mostrar mensaje de confirmación
                alert('Producto añadido a la lista de deseos');
            } else {
                alert('Este producto ya está en tu lista de deseos');
            }
        }
        
        // Función para actualizar el contador del carrito (si existe)
        function updateCartCounter() {
            const cartCounter = document.querySelector('.cart-counter');
            if (cartCounter) {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
                cartCounter.textContent = totalItems;
            }
        }
        
        // Inicializar el contador del carrito al cargar la página
        updateCartCounter();
    });
    // ------------------ BOTÓN DE AGREGAR A LISTA ------------------
    document.querySelectorAll(".btn-add-list").forEach(button => {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");
            fetch("../includes/agregar_a_lista.php", {
                method: "POST",
                body: new URLSearchParams({ id_producto: productId }),
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
            })
            .then(response => response.json())
            .then(data => alert(data.message))
            .catch(error => console.error("Error:", error));
        });
    });

    // ------------------ VALIDACIÓN DEL FORMULARIO DE REGISTRO ------------------
    document.getElementById('registroForm')?.addEventListener('submit', function (event) {
        let valid = true;
        const email = document.getElementById('email').value;
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const fechaNacimiento = document.getElementById('fecha_nacimiento').value;

        // Validar email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('El email no es válido.');
            valid = false;
        }

        // Validar nombre de usuario
        if (username.length < 3) {
            alert('El nombre de usuario debe tener al menos 3 caracteres.');
            valid = false;
        }

        // Validar contraseña
        if (!/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(password)) {
            alert('La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.');
            valid = false;
        }

        // Validar fecha de nacimiento (no puede ser en el futuro)
        const fechaActual = new Date();
        const fechaNacimientoDate = new Date(fechaNacimiento);
        if (fechaNacimientoDate > fechaActual) {
            alert('La fecha de nacimiento no puede ser en el futuro.');
            valid = false;
        }

        if (!valid) {
            event.preventDefault(); // Evitar envío del formulario
        }
    });
});