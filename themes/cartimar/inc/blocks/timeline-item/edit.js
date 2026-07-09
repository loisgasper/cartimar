(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var MediaUpload = blockEditor.MediaUpload;
    var MediaPlaceholder = blockEditor.MediaPlaceholder;
    var useBlockProps = blockEditor.useBlockProps;

    function imageEl(image, onSelect) {
        var hasImage = image && image.url;
        return el(
            'div',
            { className: 'cart-timeline__item-img' + (hasImage ? '' : ' img-placeholder') },
            hasImage
                ? el(MediaUpload, {
                      onSelect: onSelect,
                      allowedTypes: ['image'],
                      value: image.id,
                      render: function (obj) {
                          return el('img', {
                              src: image.url,
                              alt: image.alt || '',
                              style: { cursor: 'pointer' },
                              title: 'Click to change this year’s photo',
                              onClick: obj.open,
                          });
                      },
                  })
                : el(MediaPlaceholder, {
                      accept: 'image/*',
                      allowedTypes: ['image'],
                      multiple: false,
                      labels: { title: 'Photo for this year' },
                      onSelect: onSelect,
                  })
        );
    }

    blocks.registerBlockType('cartimar/timeline-item', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps({ className: 'cart-timeline__item' });

            function onSelectImage(media) {
                setAttributes({ image: { id: media.id, url: media.url, alt: media.alt || '' } });
            }

            return el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'cart-timeline__item-body' },
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
                ),
                imageEl(attributes.image, onSelectImage)
            );
        },
        save: function (props) {
            var attributes = props.attributes;
            var image = attributes.image;
            var hasImage = image && image.url;
            var blockProps = blockEditor.useBlockProps.save({ className: 'cart-timeline__item' });

            return el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'cart-timeline__item-body' },
                    el(RichText.Content, { tagName: 'h4', className: 'cart-timeline__year', value: attributes.year }),
                    el(RichText.Content, { tagName: 'p', value: attributes.body })
                ),
                el(
                    'div',
                    { className: 'cart-timeline__item-img' + (hasImage ? '' : ' img-placeholder') },
                    hasImage ? el('img', { src: image.url, alt: image.alt || '' }) : null
                )
            );
        },
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
