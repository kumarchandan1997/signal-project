<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Open and Track PDF File</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <div class="text-center">
      <h2>Open and Track PDF File</h2>
      <p>Click the button below to open a PDF file.</p>
      <button class="btn btn-primary" onclick="openAndTrackPDF()">Open PDF</button>
    </div>

    <!-- Container for PDF iframe -->
    <div id="pdfContainer" class="mt-4" style="display: none;">
      <iframe id="pdfFrame" width="100%" height="600px"></iframe>
      <button id="closePdfButton" class="btn btn-danger mt-3" onclick="closePDF()">Close PDF</button>
    </div>
  </div>

  <!-- Bootstrap JS and dependencies (optional for some features) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

  <script>
    var openTime; 

    function openAndTrackPDF() {
      var pdfPath = 'path_to_your_pdf_file.pdf';

      openTime = new Date();

      // Show PDF container and hide button
      document.getElementById('pdfContainer').style.display = 'block';

      // Load PDF in iframe
      var pdfFrame = document.getElementById('pdfFrame');
      pdfFrame.src = pdfPath;

      pdfFrame.onload = function() {
      };
    }

    function closePDF() {
      // Record the time when the PDF was closed manually
      var closeTime = new Date();

      // Calculate the total time the PDF was open
      var totalTime = closeTime.getTime() - openTime.getTime();
      var totalTimeInSeconds = totalTime / 1000;

      // You can also log these times or handle them as needed
      console.log('PDF opened at:', openTime);
      console.log('PDF closed at:', closeTime);
      console.log('Total time (seconds):', totalTimeInSeconds);

      document.getElementById('pdfContainer').style.display = 'none';
    }
  </script>
</body>
</html>
