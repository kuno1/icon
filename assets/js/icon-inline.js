/*!
 * Inline icon inserter.
 */

const React = wp.element;
const { registerFormatType, insertObject } = wp.richText;
const { RichTextToolbarButton, BlockFormatControls } = wp.blockEditor;
const { Modal, Button, Toolbar, ToolbarButton } = wp.components;
const { __ } = wp.i18n;
const { IconSelector } = wp.kicon;

const title = __( 'Inline Icon', 'kicon' );
const name = 'kunoichi/inline-icon';

class KunoichiInlineIcon extends React.Component {
	constructor( props ) {
		super( props );
		this.state = {
			text: false,
			modal: false,
		};
		this.modalOpen = this.modalOpen.bind( this );
		this.modalClose = this.modalClose.bind( this );
		this.classDefined = this.classDefined.bind( this );
	}

	modalOpen() {
		this.setState( { modal: true } );
	}

	modalClose() {
		this.setState( { modal: false } );
	}

	classDefined( className ) {
		this.modalClose();
		className = 'kicon-inline ' + className;
		const { value, onChange } = this.props;
		onChange( insertObject( value, {
			type: name,
			attributes: {
				className: `kicon kicon-inline ${className}`,
			},
		}, value.start, value.end ) );
	}

	render() {
		return (
			<>
				<BlockFormatControls>
					<div className="editor-format-toolbar block-editor-format-toolbar">
						<Toolbar label="Icon">
							<ToolbarButton
								icon={ 'flag' }
								title={ title }
								onClick={ this.modalOpen } />
						</Toolbar>
					</div>
				</BlockFormatControls>
				<RichTextToolbarButton
					icon="flag"
					title={ title }
					isActive={ this.props.isActive }
					onClick={ this.modalOpen } />
				{ this.state.modal && (
					<Modal title={ title } onRequestClose={ this.modalClose }>
						<IconSelector handler={ this.classDefined } />
						<hr />
						<p style={ { textAlign: 'right' } } >
							<Button isSecondary onClick={ this.modalClose }>
								{ __( 'Cancel' ) }
							</Button>
						</p>
					</Modal>
				) }
			</>
		);
	}
}

registerFormatType( name, {
	name,
	title,
	tagName: 'span',
	object: false,
	className: 'kicon-inline',
	attributes: {
		className: 'class',
	},
	keywords: [ __( 'Icon', 'kicon' ) ],
	edit: KunoichiInlineIcon,
} );
