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
        // allow cross-origin if images served with proper headers
        imgOriginal.crossOrigin = 'anonymous';
        imgAI.crossOrigin = 'anonymous';
        imgOriginal.src = originalSrc;
        imgAI.src = aiSrc;

        let imagesLoaded = 0;
        function resizeCanvasToDisplaySize() {
            const ratio = window.devicePixelRatio || 1;
            const displayWidth = canvas.clientWidth;
            const displayHeight = canvas.clientHeight;
            const w = Math.max(1, Math.floor(displayWidth * ratio));
            const h = Math.max(1, Math.floor(displayHeight * ratio));
            if (canvas.width !== w || canvas.height !== h) {
                canvas.width = w;
                canvas.height = h;
            }
        }

        function onImageLoad() {
            imagesLoaded++;
            if (imagesLoaded === 2) {
                resizeCanvasToDisplaySize();
                drawImage(imgOriginal);
                if (caption) caption.innerHTML = "Original<br>Cliquez si vous aimez Arcane";
            }
        }

        imgOriginal.onload = onImageLoad;
        imgAI.onload = onImageLoad;

        function drawImage(img) {
            // maintain aspect ratio and cover
            const scale = Math.max(canvas.width / img.width, canvas.height / img.height);
            const x = (canvas.width / 2) - (img.width / 2) * scale;
            const y = (canvas.height / 2) - (img.height / 2) * scale;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, x, y, img.width * scale, img.height * scale);
        }

        function pixelDissolveTransition(fromImg, toImg, duration = 800) {
            if (isTransitioning) return;
            isTransitioning = true;
            resizeCanvasToDisplaySize();

            const pixelSize = Math.max(2, Math.floor((canvas.width / 100)));
            const cols = Math.ceil(canvas.width / pixelSize);
            const rows = Math.ceil(canvas.height / pixelSize);
            const totalPixels = cols * rows;

            const pixels = new Array(totalPixels);
            for (let i = 0; i < totalPixels; i++) pixels[i] = i;
            for (let i = pixels.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [pixels[i], pixels[j]] = [pixels[j], pixels[i]];
            }

            drawImage(fromImg);
            const fromImageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

            drawImage(toImg);
            const toImageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

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
                        Math.min(pixelSize, canvas.width - x),
                        Math.min(pixelSize, canvas.height - y)
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
                if (caption) caption.innerHTML = "Original<br>Cliquez si vous aimez Arcane";
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

        // handle window resize to keep canvas crisp
        let resizeTimeout = null;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (imagesLoaded === 2) {
                    resizeCanvasToDisplaySize();
                    // redraw current image
                    if (currentPhoto === 'original') drawImage(imgOriginal);
                    else drawImage(imgAI);
                }
            }, 150);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPhotoToggle);
    } else {
        initPhotoToggle();
    }

})();
