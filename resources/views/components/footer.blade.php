<footer class="custom-footer">
    <div class="footer-content">
        <a href="javascript:void(0);" id="contactLink">Kontakt</a> |
        <a href="{{ asset('pliki_pdf/Regulamin.pdf') }}" target="_blank">Regulamin</a>
        <div class="footer-subtext">Kraków 2025</div>
    </div>

    <!-- Modal z danymi kontaktowymi -->
    <div id="contactModal" class="contact-modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Administrator: {{ $contactInfo['name'] }}</p>
            <p>Email: {{ $contactInfo['email'] }}</p>
        </div>
    </div>
</footer>

<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Minimalna wysokość strony */
        margin: 0;
    }

    .custom-footer {
        margin-top: 2px; /* Ustawienie stopki na dole */
        text-align: center;
        padding: 5px;
        border-top: 1px solid #ccc;
        background-color: white;
        z-index: 1000;
        position: relative; /* Nie "fixed", aby nie zakrywało treści */
    }

    .footer-content {
        display: inline-block;
        text-align: center;
    }

    .footer-content a {
        text-decoration: none;
        margin: 0 5px;
        color: black;
        transition: color 0.3s;
    }

    .footer-content a:hover {
        color: gray;
    }

    .footer-subtext {
        margin-top: 10px;
        font-size: 14px;
        color: #555;
    }

    .contact-modal {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 5px;
        width: 300px;
        position: relative;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .close {
        cursor: pointer;
        position: absolute;
        right: 10px;
        top: 5px;
        font-size: 24px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('contactModal');
        const btn = document.getElementById('contactLink');
        const span = document.getElementsByClassName('close')[0];

        // Wyświetl modal po kliknięciu na "Kontakt"
        btn.onclick = function () {
            modal.style.display = "flex"; // Wyświetlenie modala
        };

        // Zamknij modal po kliknięciu na "X"
        span.onclick = function () {
            modal.style.display = "none";
        };

        // Zamknij modal po kliknięciu poza modalem
        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };
    });
</script>
