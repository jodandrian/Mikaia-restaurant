/**
 * NOTIONS CLÉS À MAÎTRISER (Alpine.js + Fetch AJAX) :
 * 1. Alpine.data() : Déclare un composant réactif réutilisable.
 * 2. localStorage : Conserve le panier du client même s'il rafraîchit la page.
 * 3. $watch() : Surveille le tableau 'items' et sauvegarde automatiquement dans localStorage à chaque changement.
 * 4. fetch() : Envoie les données sous forme de payload JSON au contrôleur PHP Symfony sans recharger la page.
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('cartComponent', () => ({
        // Chargement initial des articles sauvegardés dans le navigateur
        items: JSON.parse(localStorage.getItem('mikaia_cart') || '[]'),
        customerName: '',
        deliveryMode: 'livraison',
        tableNumber: '',
        isSubmitting: false,
        toastShow: false,
        toastMessage: '',

        updateCartCountDOM() {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = this.totalItemsCount();
            }
        },

        init() {
            // 1. Mise à jour au chargement initial de la page
            this.updateCartCountDOM();

            // 2. Mise à jour automatique à chaque changement du panier
            this.$watch('items', (val) => {
                localStorage.setItem('mikaia_cart', JSON.stringify(val));
                this.updateCartCountDOM(); // <--- Met à jour l'élément #cart-count
            });
        },

        // Ajoute un plat au panier ou augmente sa quantité si déjà présent
        addItem(id, name, price) {
            const existing = this.items.find(i => i.id === id);
            if (existing) {
                existing.quantity++;
            } else {
                this.items.push({ id, name, price, quantity: 1 });
            }
            this.showToast('Plat ajouté au panier !');
        },

        // Ajuste la quantité (+1 ou -1) et supprime l'article si la quantité atteint 0
        updateQty(id, delta) {
            const item = this.items.find(i => i.id === id);
            if (item) {
                item.quantity += delta;
                if (item.quantity <= 0) {
                    this.items = this.items.filter(i => i.id !== id);
                }
            }
        },

        // Calcul du sous-total des articles
        subtotal() {
            return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        // Calcul des frais de livraison (5000 Ar sauf si emporter/sur place)
        deliveryFee() {
            if (this.items.length === 0 || this.deliveryMode !== 'livraison') return 0;
            return 5000;
        },

        // Total général
        total() {
            return this.subtotal() + this.deliveryFee();
        },

        // Compteur total d'articles pour le badge du panier
        totalItemsCount() {
            return this.items.reduce((sum, item) => sum + item.quantity, 0);
        },

        // Formatage monétaire en Ariary
        formatMoney(amount) {
            return new Intl.NumberFormat('fr-FR').format(amount) + ' Ar';
        },

        // Affichage temporaire de la notification Toast
        showToast(msg) {
            this.toastMessage = msg;
            this.toastShow = true;
            setTimeout(() => { this.toastShow = false; }, 3000);
        },

        scrollToSection(targetId) {
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        },

        // ENVOI DE LA COMMANDE PAR AJAX VERS LE CONTRÔLEUR SYMFONY
        async submitOrder() {
            if (this.items.length === 0) return;

            this.isSubmitting = true;

            // Clés JSON exactement alignées avec votre FrontOrderController PHP
            const payload = {
                customerName: this.customerName,
                customerInfo: this.tableNumber, // Contient téléphone / adresse / numéro de table
                deliveryMode: this.deliveryMode,
                items: this.items
            };

            try {
                // Récupération de l'URL depuis l'attribut data-order-url de la div Alpine
                const apiUrl = this.$el.closest('[data-order-url]')?.dataset.orderUrl || '/api/order/create';
                // Utilisation du nom exact de la route Symfony défini dans votre contrôleur (api_order_create)
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                // Vérification de la réponse envoyée par le serveur (201 Created ou 'status' === 'success')
                if (response.ok && (result.status === 'success' || result.success)) {
                    this.showToast('Commande #' + (result.orderId || '') + ' enregistrée avec succès !');

                    // Réinitialisation du panier et du formulaire
                    this.items = [];
                    this.customerName = '';
                    this.tableNumber = '';
                    localStorage.removeItem('mikaia_cart');
                } else {
                    this.showToast(result.message || 'Erreur lors de l\'enregistrement.');
                }
            } catch (error) {
                console.error('Erreur AJAX:', error);
                this.showToast('Erreur réseau. Veuillez réessayer.');
            } finally {
                this.isSubmitting = false;
            }
        }
    }));
});

// Script JS Vanille pour le carrousel automatique d'images du Hero
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.carousel-slide');
    if (slides.length === 0) return;
    const dots = document.querySelectorAll('#carouselDots .dot');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    let currentSlide = 0;
    let slideInterval;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0', 'z-0');
                slide.classList.add('opacity-100', 'z-10');
            } else {
                slide.classList.remove('opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'z-0');
            }
        });

        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.remove('bg-white/40');
                dot.classList.add('bg-[#8CD62B]');
            } else {
                dot.classList.remove('bg-[#8CD62B]');
                dot.classList.add('bg-white/40');
            }
        });

        currentSlide = index;
    }

    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }

    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }

    function startTimer() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    function resetTimer() {
        clearInterval(slideInterval);
        startTimer();
    }

    if (nextBtn && prevBtn) {
        nextBtn.addEventListener('click', () => { nextSlide(); resetTimer(); });
        prevBtn.addEventListener('click', () => { prevSlide(); resetTimer(); });
    }

    dots.forEach((dot) => {
        dot.addEventListener('click', (e) => {
            const index = parseInt(e.target.getAttribute('data-index'));
            showSlide(index);
            resetTimer();
        });
    });

    startTimer();
});

/* ==========================================================================
   FONCTIONS AJOUTÉES : GESTION DU MENU MOBILE ET DU PANIER (DROPDOWN/DRAWER)
   ========================================================================== */

function toggleMobileMenu() {
    const menu = document.getElementById('mobile-nav-menu');
    const content = document.getElementById('mobile-nav-content');

    if (!menu || !content) return;
    const isOpen = !menu.classList.contains('pointer-events-none');

    if (isOpen) {
        menu.classList.add('pointer-events-none', 'opacity-0');
        content.classList.add('-translate-y-full');
    } else {
        menu.classList.remove('pointer-events-none', 'opacity-0');
        content.classList.remove('-translate-y-full');
    }
}

function toggleCart(forceClose = false) {
    const dropdown = document.getElementById('cart-dropdown');
    if (!dropdown) return;

    const shouldClose = forceClose || !dropdown.classList.contains('pointer-events-none');

    if (shouldClose) {
        dropdown.classList.add('pointer-events-none', 'opacity-0', '-translate-y-2');
    } else {
        dropdown.classList.remove('pointer-events-none', 'opacity-0', '-translate-y-2');
    }
}

// Fermeture automatique au clic à l'extérieur
document.addEventListener('click', (event) => {
    const dropdown = document.getElementById('cart-dropdown');
    const cartButton = event.target.closest('button[onclick*="toggleCart"]');

    if (dropdown && !dropdown.contains(event.target) && !cartButton && !dropdown.classList.contains('pointer-events-none')) {
        toggleCart(true);
    }
});