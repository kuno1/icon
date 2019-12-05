/*!
 * Icon Searcher.
 */

const React = wp.element;
const { Button, TextControl } = wp.components;
const { __ } = wp.i18n;
const { icons } = wp.kicon;

class IconSelector extends React.Component {
	constructor( props ) {
		super( props );
		this.state = {
			text: '',
			show: false,
		};
	}

	render() {
		const { text, show } = this.state;
		const { handler } = this.props;
		const showIcons = text.length || show;
		const tags = [];
		for ( const prop in icons ) {
			if ( ! icons.hasOwnProperty( prop ) ) {
				continue;
			}
			tags.push( <h4>{ prop }</h4> );
			const iconSet = icons[ prop ];
			tags.push( (
				<p className="kicon-selector">
					{ iconSet.icons.filter( ( icon ) => {
						return ! text.length || ( -1 < icon.name.indexOf( text ) );
					} ).map( ( icon ) => {
						return (
							<Button className="kicon-selector-item" key={ icon.name } isDefault={ true } title={ icon.name } style={ { 'font-family': iconSet.font_family } }
								onClick={ () => handler( icon.classes ) } >
								<span className={ icon.classes + ' kicon kicon-selector-icon' }></span>
							</Button>
						);
					} ) }
				</p>
			) );
		}
		return (
			<>
				<TextControl
					label={ __( 'Search Icon', 'clinics' ) }
					value={ text } placeholder={ __( 'Type to filter...', 'kicon' ) }
					onChange={ ( newText ) => this.setState( { text: newText } ) }
				/>
				<p>
					<Button isPrimary={ ! show } isDefault={ show } onClick={ () => this.setState( { show: !show } ) }>
						{ show ? __( 'Hide', 'kicon' ) : __( 'Show All', 'kicon' ) }
					</Button>
				</p>
				{ showIcons ? tags : null }
			</>
		);
	}
}

window.wp.kicon.IconSelector = IconSelector;
