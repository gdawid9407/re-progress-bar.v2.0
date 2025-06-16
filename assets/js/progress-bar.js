
document.addEventListener('DOMContentLoaded', () => {
    const bar = document.createElement('div');
    bar.id = 're-progress-bar';
    document.body.prepend(bar);

    let thresholdReached = false;

    // Heights of elements to ignore
    const headerElems = document.querySelectorAll('header, .site-header, .entry-header');
    const footerElems = document.querySelectorAll('footer, .site-footer, .entry-footer');
    const stickyElems = document.querySelectorAll('[class*="sticky"], .is-sticky');

    const headerHeight = Array.from(headerElems).reduce((sum, el) => sum + el.offsetHeight, 0);
    const footerHeight = Array.from(footerElems).reduce((sum, el) => sum + el.offsetHeight, 0);
    const stickyHeight = Array.from(stickyElems).reduce((sum, el) => sum + el.offsetHeight, 0);

    window.addEventListener('scroll', () => {
        const scrollTop      = document.documentElement.scrollTop || document.body.scrollTop;
        const docHeight      = document.documentElement.scrollHeight;
        const viewportHeight = window.innerHeight;

        // Compute scrollable area minus ignored elements
        const scrollableArea = docHeight - viewportHeight - headerHeight - footerHeight - stickyHeight;
        const percent        = Math.min(
            Math.round(scrollTop / scrollableArea * 100),
            100
        );

        bar.style.width = percent + '%';

        if (!thresholdReached && percent >= ReProgressBarSettings.progress_threshold) {
            thresholdReached = true;
            document.dispatchEvent(
                new CustomEvent('reProgressThresholdReached', { detail: { percent } })
            );
        }
    });
});
