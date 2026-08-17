(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var useInnerBlocksProps = blockEditor.useInnerBlocksProps;

    var TEMPLATE = [
        ['cartimar/timeline-item', { year: '1946', body: 'Cartimar opens its doors and quickly becomes one of Manila’s pioneering shopping centers.' }],
        ['cartimar/timeline-item', { year: '1970s', body: 'Cartimar becomes known for its thriving bicycle, pet, aquarium, and specialty shops.' }],
        ['cartimar/timeline-item', { year: '1990s', body: 'Generations of Filipinos continue to visit Cartimar for its unique mix of merchants.' }],
        ['cartimar/timeline-item', { year: 'Today', body: 'Cartimar remains committed to supporting local businesses and serving loyal customers.' }],
    ];

    blocks.registerBlockType('cartimar/timeline', {
        edit: function () {
            var blockProps = useBlockProps({ className: 'cart-timeline' });
            var innerBlocksProps = useInnerBlocksProps(
                { className: 'cart-timeline__years' },
                { allowedBlocks: ['cartimar/timeline-item'], template: TEMPLATE, templateLock: false }
            );

            return el('div', blockProps, el('div', innerBlocksProps));
        },
        save: function () {
            var blockProps = blockEditor.useBlockProps.save({ className: 'cart-timeline' });
            var innerBlocksProps = blockEditor.useInnerBlocksProps.save({ className: 'cart-timeline__years' });

            return el('div', blockProps, el('div', innerBlocksProps));
        },
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
