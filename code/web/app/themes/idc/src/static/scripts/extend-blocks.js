wp.domReady(() => {

    /**
     * Buttons
     */
    wp.blocks.registerBlockStyle('core/button', {
        name: 'idc-button',
        label: 'IDC',
        isDefault: true,
    });
    wp.blocks.registerBlockStyle('core/button', {
        name: 'idc-button-text-right',
        label: 'IDC Text Right Icon',
    });
    wp.blocks.registerBlockStyle('core/button', {
        name: 'idc-button-text-left',
        label: 'IDC Text Left Icon',
    });

    wp.blocks.unregisterBlockStyle('core/button', 'fill');
    wp.blocks.unregisterBlockStyle('core/button', 'outline');


    /**
     * Lists
     */
    wp.blocks.registerBlockStyle('core/list', {
        name: 'idc-list-custom',
        label: 'IDC List Style',
        isDefault: true,
    });

    wp.blocks.unregisterBlockStyle('core/list', 'default');


    /**
     * Separators
     */
    wp.blocks.registerBlockStyle('core/separator', {
        name: 'idc-dots-full',
        label: 'IDC Dots Full Width',
    });
    wp.blocks.registerBlockStyle('core/separator', {
        name: 'idc-dots-small',
        label: 'IDC Dots Small',
    });

    wp.blocks.unregisterBlockStyle('core/separator', 'wide');
    wp.blocks.unregisterBlockStyle('core/separator', 'dots');
});