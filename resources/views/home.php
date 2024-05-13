<!doctype html>
<html lang="en">

<head>
    <?php echo view('partials.header')->render(); ?>
    <title>Document</title>
    <style>
        .container {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        .pdf-viewer {
            flex: 1;
            height: 100%;
        }
        .menu {
            flex: 0.3;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <iframe id="pdfFrame" class="pdf-viewer"></iframe>
        <div class="menu">
            <form id="pdfForm" action="/upload-pdf" method="post" enctype="multipart/form-data">
                <input type="file" name="pdfFile" id="pdfInput" accept="application/pdf">
                <button type="submit">Upload PDF</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('pdfForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('/upload-pdf', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(function(data) {
                console.log(data);
            });
        });
    </script>

    <?php echo view('partials.footer')->render(); ?>
</body>

</html>
</html>