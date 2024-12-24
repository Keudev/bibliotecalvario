<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leitor de Código de Barras</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/webcam-easy@1.0.8/dist/webcam-easy.min.js"></script>
  <style>
    #video {
      width: 100%;
      height: auto;
      display: none; /* Esconder o vídeo até a câmera ser iniciada */
    }
    #canvas {
      display: none;
    }
    #startButton {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <button id="startButton">Iniciar Câmera</button>
  <video id="video" autoplay></video>
  <canvas id="canvas"></canvas>

  <script>
    $(document).ready(function() {
      const video = document.getElementById('video');
      const canvas = document.getElementById('canvas');
      const canvasContext = canvas.getContext('2d');
      const startButton = document.getElementById('startButton');
      const webcam = new Webcam(video, 'environment', canvas);

      // Função para iniciar a câmera
      function startCamera() {
        webcam.start()
          .then(result => {
            console.log("Câmera iniciada com sucesso");
            video.style.display = 'block'; // Mostrar o vídeo
            startButton.style.display = 'none'; // Esconder o botão de iniciar
            requestAnimationFrame(tick);
          })
          .catch(err => {
            console.error("Erro ao acessar a câmera: ", err);
          });
      }

      // Função para capturar frames e decodificar o código de barras
      function tick() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
          canvas.height = video.videoHeight;
          canvas.width = video.videoWidth;
          canvasContext.drawImage(video, 0, 0, canvas.width, canvas.height);

          const imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height);
          const code = jsQR(imageData.data, imageData.width, imageData.height);

          if (code) {
            alert('Código de Barras Decodificado: ' + code.data);
            // Aqui você pode fazer algo com o código decodificado
          }
        }
        requestAnimationFrame(tick);
      }

      // Adiciona o evento de clique ao botão para iniciar a câmera
      startButton.addEventListener('click', startCamera);
    });
  </script>
</body>
</html>
