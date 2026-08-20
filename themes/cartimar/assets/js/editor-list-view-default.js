/* Opens the block editor's List View (Document Overview) panel by default,
   so a client landing on a page sees its block structure immediately
   instead of having to know the toggle exists. Runs in the parent admin
   document (enqueue_block_editor_assets), not the iframe canvas — this
   toolbar button lives outside the iframe, unlike the hero carousel. */
wp.domReady(function () {
    var editorStore = wp.data.select('core/edit-post')
        ? 'core/edit-post'
        : 'core/edit-site';
    if (wp.data.select(editorStore).isListViewOpened()) return;
    wp.data.dispatch(editorStore).setIsListViewOpened(true);
});
