(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var InnerBlocks = blockEditor.InnerBlocks;
    var useBlockProps = blockEditor.useBlockProps;
    var useInnerBlocksProps = blockEditor.useInnerBlocksProps;

    var ARROW_PREV = el(
        'svg',
        { width: 20, height: 20, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
        el('path', { d: 'M15 18l-6-6 6-6' })
    );
    var ARROW_NEXT = el(
        'svg',
        { width: 20, height: 20, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2' },
        el('path', { d: 'M9 18l6-6-6-6' })
    );

    blocks.registerBlockType('cartimar/carousel', {
        edit: function () {
            var blockProps = useBlockProps({ className: 'cart-serve__carousel' });
            var innerBlocksProps = useInnerBlocksProps(
                { className: 'cart-serve__track' },
                {
                    allowedBlocks: ['core/image'],
                    orientation: 'horizontal',
                    template: [
                        ['core/image', {}],
                        ['core/image', {}],
                        ['core/image', {}],
                        ['core/image', {}],
                    ],
                    templateLock: false,
                }
            );

            return el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'cart-serve__nav' },
                    el('button', { type: 'button', className: 'cart-serve__arrow cart-serve__arrow--prev' }, ARROW_PREV),
                    el('button', { type: 'button', className: 'cart-serve__arrow cart-serve__arrow--next' }, ARROW_NEXT)
                ),
                el('div', { className: 'cart-serve__viewport' }, el('div', innerBlocksProps))
            );
        },
        save: function () {
            var blockProps = blockEditor.useBlockProps.save({ className: 'cart-serve__carousel' });
            var innerBlocksProps = blockEditor.useInnerBlocksProps.save({ className: 'cart-serve__track' });

            return el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'cart-serve__nav' },
                    el('button', { type: 'button', className: 'cart-serve__arrow cart-serve__arrow--prev', 'aria-label': 'Previous' }, ARROW_PREV),
                    el('button', { type: 'button', className: 'cart-serve__arrow cart-serve__arrow--next', 'aria-label': 'Next' }, ARROW_NEXT)
                ),
                el('div', { className: 'cart-serve__viewport' }, el('div', innerBlocksProps))
            );
        },
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
