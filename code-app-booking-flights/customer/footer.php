<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="footer-nav">
                    <ul class="list-unstyled">
                        <li><a href="https://www.yourwebsite.com/about/" class="footer-link">About Us</a></li>
                        <li><a href="https://www.yourwebsite.com/contact/" class="footer-link">Contact Us</a></li>
                        <li><a href="https://www.yourwebsite.com/privacy-policy/" class="footer-link">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>&copy; <?php echo date("Y"); ?> Booking Flight. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    footer {
        background-color: rgba(255, 255, 255, 0.7);
        padding: 30px 0;
        width: 100%;
    }

    .footer-nav ul {
        padding-left: 0;
        text-align: center;
    }

    .footer-nav li {
        display: inline-block;
        margin-right: 20px;
    }

    .footer-nav li:last-child {
        margin-right: 0;
    }

    .footer-link {
        color: #343a40;
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer-link:hover {
        color: #007bff;
    }

    .footer-bottom p {
        margin: 0;
        font-size: 14px;
        color: #6c757d;
    }
</style>
