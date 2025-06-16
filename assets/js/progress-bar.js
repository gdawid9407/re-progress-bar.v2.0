// File: assets/js/progress-bar.js
document.addEventListener('DOMContentLoaded', () => {
    const bar = document.createElement('div');
    bar.id = 're-progress-bar';
    document.body.prepend(bar);

    const content = document.querySelector('.entry-content, .post-content');
    if (!content) return;

    // insert a sentinel at the very bottom of the content
    const sentinel = document.createElement('div');
    sentinel.id = 're-progress-sentinel';
    sentinel.style.position = 'absolute';
    sentinel.style.top = '100%';
    content.appendChild(sentinel);

    // thresholds 0%–100% in 1% increments
    const thresholds = Array.from({ length: 101 }, (_, i) => i / 100);

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.target.id === 're-progress-sentinel') {
                const percent = Math.round(entry.intersectionRatio * 100);
                bar.style.width = percent + '%';
            }
        });
    }, {
        root: null,
        rootMargin: '0px',
        threshold: thresholds
    });

    observer.observe(sentinel);
});
