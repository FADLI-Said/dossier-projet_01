<footer class="text-dark">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5>Contact</h5>
                <ul class="list-unstyled">
                    <li><i class="fa-solid fa-location-dot me-2"></i>3 Rue Maximilien Robespierre, 76610 Le Havre
                    </li>
                    <li><i class="fa-solid fa-phone me-2"></i>+33 6 27 40 63 90</li>
                    <li><i class="fa-solid fa-envelope me-2"></i>contact@annbeautyvisage.fr</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5>Nous trouver</h5>
                <div class="ratio ratio-16x9 rounded shadow">
                    <iframe src="https://www.google.com/maps?q=3+Rue+Maximilien+Robespierre,+Havre&output=embed"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="Carte AnnBeautyVisage"></iframe>
                </div>
            </div>
        </div>
        <div class="text-center mt-3 small">
            &copy; 2024 AnnBeautyVisage. Tous droits réservés.
        </div>
    </div>
</footer>

<script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
<script src="https://kit.fontawesome.com/50a1934b21.js" crossorigin="anonymous"></script>
<script>
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('navbar');
        if (window.scrollY > 0) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    // Ajoute une transition CSS pour la navbar
    document.addEventListener('DOMContentLoaded', function() {
        const nav = document.getElementById('navbar');
        nav.style.transition = 'background-color 0.3s, box-shadow 0.3s';
    });
</script>