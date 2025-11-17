// Photo toggle with pixel-dissolve transition
// Reads image URLs from data attributes on #profilePhotoContainer

(function () {
    function initPhotoToggle() {
        const container = document.getElementById('profilePhotoContainer');
        if (!container) return;

        const canvas = container.querySelector('#photoCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const caption = document.getElementById('photoCaption');

        const originalSrc = container.dataset.original;
        const aiSrc = container.dataset.ai;
        if (!originalSrc || !aiSrc) return;

        let currentPhoto = 'original';
        let isTransitioning = false;

        const imgOriginal = new Image();
        const imgAI = new Image();
        imgOriginal.crossOrigin = 'anonymous';
        imgAI.crossOrigin = 'anonymous';
        imgOriginal.src = originalSrc;
        imgAI.src = aiSrc;

        let imagesLoaded = 0;

        function onImageLoad() {
            imagesLoaded++;
            if (imagesLoaded === 2) {
                // Canvas size is fixed via HTML attributes (width/height).
                // Just draw the original image to start. Do not show a caption
                // for the original image – only show a caption when the AI
                // version is displayed.
                drawImage(imgOriginal);
                if (caption) caption.innerHTML = '';
            }
        }

        imgOriginal.onload = onImageLoad;
        imgAI.onload = onImageLoad;

        function drawImage(img) {
            // maintain aspect ratio and cover within the fixed canvas dimensions
            const cw = canvas.width;
            const ch = canvas.height;
            const scale = Math.max(cw / img.width, ch / img.height);
            const w = Math.round(img.width * scale);
            const h = Math.round(img.height * scale);
            const x = Math.round((cw - w) / 2);
            const y = Math.round((ch - h) / 2);
            ctx.clearRect(0, 0, cw, ch);
            ctx.drawImage(img, x, y, w, h);
        }

        function pixelDissolveTransition(fromImg, toImg, duration = 800) {
            if (isTransitioning) return;
            isTransitioning = true;

            const cw = canvas.width;
            const ch = canvas.height;
            const pixelSize = Math.max(2, Math.floor((cw / 100)));
            const cols = Math.ceil(cw / pixelSize);
            const rows = Math.ceil(ch / pixelSize);
            const totalPixels = cols * rows;

            const pixels = new Array(totalPixels);
            for (let i = 0; i < totalPixels; i++) pixels[i] = i;
            for (let i = pixels.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [pixels[i], pixels[j]] = [pixels[j], pixels[i]];
            }

            drawImage(fromImg);
            const fromImageData = ctx.getImageData(0, 0, cw, ch);

            drawImage(toImg);
            const toImageData = ctx.getImageData(0, 0, cw, ch);

            ctx.putImageData(fromImageData, 0, 0);

            const startTime = Date.now();

            function animateFrame() {
                const elapsed = Date.now() - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const targetPixelIndex = Math.floor(progress * totalPixels);

                for (let i = 0; i < targetPixelIndex; i++) {
                    const pixelIndex = pixels[i];
                    const col = pixelIndex % cols;
                    const row = Math.floor(pixelIndex / cols);
                    const x = col * pixelSize;
                    const y = row * pixelSize;

                    ctx.putImageData(
                        toImageData,
                        0, 0,
                        x, y,
                        Math.min(pixelSize, cw - x),
                        Math.min(pixelSize, ch - y)
                    );
                }

                if (progress < 1) requestAnimationFrame(animateFrame);
                else {
                    drawImage(toImg);
                    isTransitioning = false;
                }
            }

            requestAnimationFrame(animateFrame);
        }

        function togglePhoto() {
            if (isTransitioning || imagesLoaded < 2) return;
            if (currentPhoto === 'original') {
                pixelDissolveTransition(imgOriginal, imgAI);
                if (caption) caption.innerHTML = "Version Arcane, générée par l'IA Bing GPT-4o<br>Cliquez pour voir l'original";
                currentPhoto = 'ai';
            } else {
                pixelDissolveTransition(imgAI, imgOriginal);
                if (caption) caption.innerHTML = '';
                currentPhoto = 'original';
            }
        }

        // attach click handler + keyboard accessibility
        container.addEventListener('click', (e) => {
            e.preventDefault();
            togglePhoto();
        });
        container.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                togglePhoto();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPhotoToggle);
    } else {
        initPhotoToggle();
    }

})();
