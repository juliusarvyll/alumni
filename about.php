
    <style>
        .page-section {
            padding: 4rem 0;
        }
        .page-section .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .page-section p {
            color: #555;
            line-height: 1.6;
        }
    </style>
<body>
<br>
<br>
<br>
<br>
        <div class="container">
            <h1 class="text-uppercase text-center">About Us</h1>
        </div>

    <!-- Page Section -->
    <section class="page-section">
        <div class="container">
            <?php echo html_entity_decode($_SESSION['system']['about_content']) ?>        
        </div>
    </section>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>
</html>
