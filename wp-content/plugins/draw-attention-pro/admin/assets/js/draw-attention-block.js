(function(blocks, element, serverSideRender, blockEditor, components)
	{
	var el = element.createElement,
		registerBlockType = blocks.registerBlockType,
		ServerSideRender = serverSideRender,
		SelectControl = components.SelectControl,
		InspectorControls = blockEditor.InspectorControls,
		useBlockProps = blockEditor.useBlockProps;

	const { __ } = wp.i18n;

	registerBlockType( 'draw-attention/image', {
		apiVersion: 2,
		title: 'Draw Attention',
		icon: 'images-alt2',
		category: 'widgets',

		edit: function( props ) {
			var blockProps = useBlockProps();

			var options = [];
			Object.keys(drawAttentionImages).forEach(function(key) {
				options.push({
					value: key,
					label: drawAttentionImages[key]
				})
			})

			var renderedImageBlock = el(
				'div',
				blockProps,
				el(ServerSideRender, {
					block: 'draw-attention/image',
					attributes: props.attributes,
				})
			)

			var renderedBlockSettings = el( InspectorControls, {},
				el( wp.components.PanelBody, {},
					el( SelectControl, {
						label: __('Select an Image', 'draw-attention'),
						value: props.attributes.id,
						options: options,
						onChange: ( value ) => { props.setAttributes( {id: value } ); },
					} )
				)
			)

			return [
				renderedImageBlock,
				renderedBlockSettings
			]
		},
	} );
})(
	window.wp.blocks,
	window.wp.element,
	window.wp.serverSideRender,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.i18n
);