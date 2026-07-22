import mediumZoom from 'medium-zoom';

function initZoom() {
    mediumZoom('[data-zoomable]', {
        margin: 24,
        background: 'rgba(0, 0, 0, 0.85)',
    });
}

document.addEventListener('livewire:navigated', initZoom);
document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', () => initZoom());
});
initZoom();
