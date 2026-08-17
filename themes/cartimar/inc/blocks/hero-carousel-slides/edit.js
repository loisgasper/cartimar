(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var useInnerBlocksProps = blockEditor.useInnerBlocksProps;

    blocks.registerBlockType('cartimar/hero-carousel-slides', {
        edit: function () {
            var blockProps = useBlockProps({ className: 'cart-hero__slides' });
            var innerBlocksProps = useInnerBlocksProps(blockProps, {
                allowedBlocks: ['core/image', 'core/video'],
                orientation: 'horizontal',
                template: [['core/image', {}]],
                templateInsertUpdatesSelection: true,
            });

            return el('div', innerBlocksProps);
        },
        save: function () {
            var blockProps = blockEditor.useBlockProps.save({ className: 'cart-hero__slides' });
            var innerBlocksProps = blockEditor.useInnerBlocksProps.save(blockProps);
            return el('div', innerBlocksProps);
        },
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
