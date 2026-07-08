(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var InnerBlocks = blockEditor.InnerBlocks;
    var MediaPlaceholder = blockEditor.MediaPlaceholder;
    var MediaUpload = blockEditor.MediaUpload;
    var useBlockProps = blockEditor.useBlockProps;
    var useInnerBlocksProps = blockEditor.useInnerBlocksProps;

    var TEMPLATE = [
        ['cartimar/timeline-item', { year: '1946', body: 'Cartimar opens its doors and quickly becomes one of Manila’s pioneering shopping centers.' }],
        ['cartimar/timeline-item', { year: '1970s', body: 'Cartimar becomes known for its thriving bicycle, pet, aquarium, and specialty shops.' }],
        ['cartimar/timeline-item', { year: '1990s', body: 'Generations of Filipinos continue to visit Cartimar for its unique mix of merchants.' }],
        ['cartimar/timeline-item', { year: 'Today', body: 'Cartimar remains committed to supporting local businesses and serving loyal customers.' }],
    ];

    blocks.registerBlockType('cartimar/timeline', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var image = attributes.image;
            var blockProps = useBlockProps({ className: 'cart-timeline' });
            var innerBlocksProps = useInnerBlocksProps(
                { className: 'cart-timeline__years' },
                { allowedBlocks: ['cartimar/timeline-item'], template: TEMPLATE, templateLock: false }
            );

            return el(
                'div',
                blockProps,
                el('div', innerBlocksProps),
                el(
                    'div',
                    { className: 'cart-timeline__img' + (image && image.url ? '' : ' img-placeholder') },
                    image && image.url
                        ? el(MediaUpload, {
                              onSelect: function (media) {
                                  setAttributes({ image: { id: media.id, url: media.url, alt: media.alt || '' } });
                              },
                              allowedTypes: ['image'],
                              value: image.id,
                              render: function (obj) {
                                  return el('img', {
                                      src: image.url,
                                      alt: image.alt || '',
                                      style: { cursor: 'pointer' },
                                      title: 'Click to change the sticky timeline image',
                                      onClick: obj.open,
                                  });
                              },
                          })
                        : el(MediaPlaceholder, {
                              accept: 'image/*',
                              allowedTypes: ['image'],
                              multiple: false,
                              labels: { title: 'Sticky Timeline Image' },
                              onSelect: function (media) {
                                  setAttributes({ image: { id: media.id, url: media.url, alt: media.alt || '' } });
                              },
                          })
                )
            );
        },
        save: function (props) {
            var attributes = props.attributes;
            var image = attributes.image;
            var blockProps = blockEditor.useBlockProps.save({ className: 'cart-timeline' });
            var innerBlocksProps = blockEditor.useInnerBlocksProps.save({ className: 'cart-timeline__years' });
            var hasImage = image && image.url;

            return el(
                'div',
                blockProps,
                el('div', innerBlocksProps),
                el(
                    'div',
                    { className: 'cart-timeline__img' + (hasImage ? '' : ' img-placeholder') },
                    hasImage ? el('img', { src: image.url, alt: image.alt || '' }) : null
                )
            );
        },
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
