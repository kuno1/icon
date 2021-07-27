/*!
 * An Icon block.
 */

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;
const { InspectorControls, AlignmentToolbar, withColors, PanelColorSettings } = wp.blockEditor;
const { PanelBody, RangeControl, RadioControl } = wp.components;
const { IconSelector } = wp.kicon;

registerBlockType( 'kunoichi/icon', {

	title: __( 'Icon', 'kicon' ),

	icon: 'flag',

	category: 'common',

	description: __( 'Insert icon block.', 'kicon' ),

	keywords: [ 'icon' ],

	attributes: {
		color: {
			type: 'string',
			default: 'primary',
		},
		customColor: {
			type: 'string',
		},
		backgroundColor: {
			type: 'string',
		},
		customBackgroundColor: {
			type: 'string',
		},
		size: {
			type: 'number',
			default: 48,
		},
		classNames: {
			type: 'string',
			default: 'dashicons dashicons-flag',
		},
		style: {
			type: 'string',
			default: 'default',
		},
		alignment: {
			type: 'string',
			default: 'center',
		},
	},

	edit: withColors( 'color', 'backgroundColor' )( ( { attributes, setAttributes, className, setColor, color, setBackgroundColor, backgroundColor } ) => {
		const wrapperClass = className.split( ' ' ).filter( ( c ) => !! c );
		wrapperClass.push( 'kicon-wrapper' );
		wrapperClass.push( `is-style-${ attributes.style }` );
		const classNames = [ 'kicon', attributes.classNames ];
		const iconStyle = {
			fontSize: Math.max( 10, attributes.size ) + 'px',
		};
		if ( attributes.color ) {
			classNames.push( `has-${ attributes.color }-color` );
		} else if ( attributes.customColor ) {
			iconStyle.color = attributes.customColor;
		}
		const wrapperStyle = {
			textAlign: attributes.alignment,
		};
		const colorSettings = [
			{
				value: color.color || '',
				label: __( 'Icon Color', 'kicon' ),
				onChange: ( newColor ) => {
					setColor( newColor );
					setAttributes( {
						customColor: color.class ? '' : newColor,
					} );
				},
			},
			{
				value: backgroundColor.color || '',
				label: __( 'Background Color' ),
				onChange: ( newColor ) => {
					setBackgroundColor( newColor );
					setAttributes( {
						customBackgroundColor: backgroundColor.class ? '' : newColor,
					} );
				},
			},
		];
		// Circle and outlined.
		if ( backgroundColor.class ) {
			classNames.push( backgroundColor.class );
		} else if ( backgroundColor.color ) {
			iconStyle.backgroundColor = backgroundColor.color;
		}
		return (
			<>
				<InspectorControls>
					<PanelBody title={ __( 'Icon Type', 'kicon' ) }>
						<p>{ __( 'Current Class', 'kicon' ) }</p>
						<code>{ attributes.classNames }</code>
						<hr />
						<IconSelector handler={ ( newClassNames ) => setAttributes( { classNames: newClassNames } ) } />
					</PanelBody>
					<PanelColorSettings title={ __( 'Appearance', 'kicon' ) } colorSettings={ colorSettings } disableCustomColors={ false } defaultOpen={ false }>
						<hr />
						<RangeControl label={ __( 'Size', 'kicon' ) } value={ attributes.size } icon="text-color"
							min={ 10 } max={ 500 } onChange={ ( size ) => setAttributes( { size } ) } />
						<hr />
						<RadioControl label={ __( 'Style', 'kicon' ) }
							selected={ attributes.style }
							options={ [
								{ label: __( 'Default' ), value: 'default' },
								{ label: __( 'Outline' ), value: 'outline' },
							] }
							onChange={ ( style ) => setAttributes( { style } ) }
						/>
						<hr />
						<p>{ __( 'Align', 'kicon' ) }</p>
						<AlignmentToolbar
							value={ attributes.alignment }
							onChange={ ( alignment ) => setAttributes( { alignment } ) } />
					</PanelColorSettings>
				</InspectorControls>
				<div className={ wrapperClass.join( ' ' ) } style={ wrapperStyle }>
					<span className={ classNames.join( ' ' ) } style={ iconStyle } />
				</div>
			</>
		);
	} ),

	save( { attributes } ) {
		const classNames = [ 'kicon', attributes.classNames ];
		const iconStyle = {
			fontSize: Math.max( 10, attributes.size ) + 'px',
		};
		if ( attributes.color ) {
			classNames.push( `has-${ attributes.color }-color` );
		} else if ( attributes.customColor ) {
			iconStyle.color = attributes.customColor;
		}
		// Background
		if ( attributes.backgroundColor ) {
			classNames.push( `has-${ attributes.backgroundColor }-background-color` );
		} else if ( attributes.customBackgroundColor ) {
			iconStyle.backgroundColor = attributes.customBackgroundColor;
		}
		const wrapperClass = [ 'kicon-wrapper', `is-style-${ attributes.style }` ];
		const wrapperStyle = {
			textAlign: attributes.alignment,
		};
		return (
			<div className={ wrapperClass.join( ' ' ) } style={ wrapperStyle }>
				<span className={ classNames.join( ' ' ) } style={ iconStyle } />
			</div>
		);
	},

} );
