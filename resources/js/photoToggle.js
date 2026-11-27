// Photo toggle: click (or Enter/Space) on the profile photo switches between the
// original photo and its AI-generated version, with a crossfade.
//
// Inputs (data attributes on #profilePhotoContainer, set in home.blade.php):
//   data-original / data-ai                  image URLs
//   data-caption-original / data-caption-ai  translated captions shown under the photo
//
// Both images are drawn into a <canvas> (#photoCanvas). The hexagonal shape comes from
// a CSS clip-path on the canvas, the outline from the SVG wireframe next to it.

(function () {
    function initPhotoToggle() {
        const container = document.getElementById('profilePhotoContainer');
        if (!container) return;

        const canvas = container.querySelector('#photoCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const caption = document.getElementById('photoCaption');

        const captionOriginal = container.dataset.captionOriginal || '';
        const captionAi = container.dataset.captionAi || '';
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
                drawImage(imgOriginal);
                if (caption) caption.textContent = captionOriginal;
            }
        }

        imgOriginal.onload = onImageLoad;
        imgAI.onload = onImageLoad;

        /**
         * "object-fit: cover" for a canvas: scale the image so it fills the canvas,
         * centre it, then nudge it down by 12px so the face sits in the hexagon's centre.
         * The AI picture is framed slightly wider than the photo, hence its 3% zoom.
         * (Both values were tuned by eye on the current pictures.)
         */
        function getImageLayout(img, width, height) {
            const isAiPhoto = img === imgAI;
            const scale = Math.max(width / img.width, height / img.height) * (isAiPhoto ? 1.03 : 1);
            const renderedWidth = Math.round(img.width * scale);
            const renderedHeight = Math.round(img.height * scale);
            const verticalOffset = 12;

            return {
                width: renderedWidth,
                height: renderedHeight,
                x: Math.round((width - renderedWidth) / 2),
                y: Math.round((height - renderedHeight) / 2) + verticalOffset,
            };
        }

        function drawShiftedImage(context, img, width, height) {
            const layout = getImageLayout(img, width, height);

            context.drawImage(img, layout.x, layout.y, layout.width, layout.height);
        }

        function drawImage(img) {
            const cw = canvas.width;
            const ch = canvas.height;
            ctx.clearRect(0, 0, cw, ch);
            drawShiftedImage(ctx, img, cw, ch);
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
                octx.clearRect(0, 0, cw, ch);
                drawShiftedImage(octx, img, cw, ch);
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
                if (caption) caption.textContent = captionAi;
                container.setAttribute('aria-pressed', 'true');
                currentPhoto = 'ai';
            } else {
                cssCanvasCrossfadeTransition(imgAI, imgOriginal);
                if (caption) caption.textContent = captionOriginal;
                container.setAttribute('aria-pressed', 'false');
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
