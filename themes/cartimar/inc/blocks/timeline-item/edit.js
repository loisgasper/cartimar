(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('cartimar/timeline-item', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps({ className: 'cart-timeline__item' });

            return el(
                'div',
                blockProps,
                el(RichText, {
                    tagName: 'h4',
                    className: 'cart-timeline__year',
                    value: attributes.year,
                    onChange: function (value) { setAttributes({ year: value }); },
                    placeholder: 'Year',
                }),
                el(RichText, {
                    tagName: 'p',
                    value: attributes.body,
                    onChange: function (value) { setAttributes({ body: value }); },
                    placeholder: 'Description',
                })
            );
        },
        save: function (props) {
            var attributes = props.attributes;
            var blockProps = blockEditor.useBlockProps.save({ className: 'cart-timeline__item' });

            return el(
                'div',
                blockProps,
                el(RichText.Content, { tagName: 'h4', className: 'cart-timeline__year', value: attributes.year }),
                el(RichText.Content, { tagName: 'p', value: attributes.body })
            );
        },
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
