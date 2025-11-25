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

        // Lightweight, GPU-friendly crossfade implemented with an overlay canvas.
        // This avoids per-pixel operations (getImageData/putImageData) and keeps
        // layout stable because we do not insert <img> elements that can shift.
        function cssCanvasCrossfadeTransition(fromImg, toImg, duration = 420) {
            if (isTransitioning) return;
            isTransitioning = true;

            // Ensure container has relative positioning
            container.classList.add('photo-transition-wrap');

            // Create overlay canvas that matches main canvas logical size
            const overlay = document.createElement('canvas');
            overlay.className = 'photo-transition-canvas';
            overlay.width = canvas.width;
            overlay.height = canvas.height;

            // Position overlay to fully cover the actual canvas element.
            // Append the overlay to the same parent as the canvas so inset:0
            // matches the canvas layout, and set explicit CSS width/height
            // in pixels to match any CSS-scaling applied to the canvas while
            // keeping the canvas drawing resolution in its attributes.
            overlay.style.position = 'absolute';
            overlay.style.left = '0';
            overlay.style.top = '0';
            overlay.style.pointerEvents = 'none';

            const octx = overlay.getContext('2d');

            // Draw target image into overlay canvas (cover behaviour)
            (function drawToOverlay(img) {
                const cw = overlay.width;
                const ch = overlay.height;
                const scale = Math.max(cw / img.width, ch / img.height);
                const w = Math.round(img.width * scale);
                const h = Math.round(img.height * scale);
                const x = Math.round((cw - w) / 2);
                const y = Math.round((ch - h) / 2);
                octx.clearRect(0, 0, cw, ch);
                octx.drawImage(img, x, y, w, h);
            })(toImg);

            // Append overlay to the same parent as the main canvas (prefer
            // the immediate wrapper) so it covers only the canvas area.
            const overlayParent = canvas.parentNode || container;
            overlayParent.appendChild(overlay);

            // Match the overlay's CSS size to the rendered CSS size of the
            // main canvas to avoid any temporary visual scaling. We keep the
            // overlay's drawing buffer equal to the main canvas's buffer
            // so drawn pixels align exactly.
            try {
                const cssWidth = canvas.offsetWidth;
                const cssHeight = canvas.offsetHeight;
                overlay.style.width = cssWidth + 'px';
                overlay.style.height = cssHeight + 'px';
                // Ensure the overlay buffer resolution matches the main canvas
                overlay.width = canvas.width;
                overlay.height = canvas.height;
            } catch (err) {
                // Fallback to 100% sizing if anything goes wrong
                overlay.style.width = '100%';
                overlay.style.height = '100%';
            }

            // Force a frame then add class to trigger CSS transition
            requestAnimationFrame(() => overlay.classList.add('show'));

            // When transition finishes copy final image to main canvas and cleanup
            const onEnd = (e) => {
                // Wait for either the opacity or filter transition to finish.
                // We purposely do not rely on 'transform' since we removed any
                // scaling/transform effects to keep the image size stable.
                if (e.propertyName !== 'opacity' && e.propertyName !== 'filter') return;
                try { drawImage(toImg); } catch (err) { /* ignore */ }
                overlay.removeEventListener('transitionend', onEnd);
                if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
                isTransitioning = false;
            };

            overlay.addEventListener('transitionend', onEnd);
            // safety fallback
            setTimeout(() => {
                if (isTransitioning) {
                    try { drawImage(toImg); } catch (e) { }
                    if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
                    isTransitioning = false;
                }
            }, duration + 300);
        }

        function togglePhoto() {
            if (isTransitioning || imagesLoaded < 2) return;
            if (currentPhoto === 'original') {
                cssCanvasCrossfadeTransition(imgOriginal, imgAI);
                if (caption) caption.innerHTML = "Version Arcane, générée par l'IA Bing GPT-4o<br>Cliquez pour voir l'original";
                currentPhoto = 'ai';
            } else {
                cssCanvasCrossfadeTransition(imgAI, imgOriginal);
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
