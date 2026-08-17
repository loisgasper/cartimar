(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var useInnerBlocksProps = blockEditor.useInnerBlocksProps;

    var TEMPLATE = [
        ['cartimar/hero-carousel-slides', {}],
        ['core/group', { className: 'cart-hero__content' }],
    ];

    blocks.registerBlockType('cartimar/hero-carousel', {
        edit: function (props) {
            var blockProps = useBlockProps({ className: 'cart-hero cart-hero--carousel' });
            var innerBlocksProps = useInnerBlocksProps(blockProps, {
                template: TEMPLATE,
                templateLock: false,
            });

            return el('div', innerBlocksProps);
        },
        save: function () {
            var blockProps = blockEditor.useBlockProps.save({ className: 'cart-hero cart-hero--carousel' });
            var innerBlocksProps = blockEditor.useInnerBlocksProps.save(blockProps);
            return el('div', innerBlocksProps);
        },
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
