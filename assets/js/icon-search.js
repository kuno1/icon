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
		this.updateTimer = this.updateTimer.bind( this );
	}

	updateTimer( text ) {
		this.setState( { text } );
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
			const iconSet = icons[ prop ];
			const filtered = iconSet.icons.filter( ( icon ) => {
				return ! text.length || ( -1 < icon.name.indexOf( text ) );
			} );
			if ( ! filtered.length ) {
				continue;
			}
			tags.push( <h4>{ prop }</h4> );
			tags.push( (
				<p className="kicon-selector">
					{ filtered.map( ( icon ) => {
						return (
							<Button className="kicon-selector-item" key={ icon.name } isSecondary={ true } title={ icon.name } style={ { 'font-family': iconSet.font_family } }
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
					label={ __( 'Search Icon', 'kicon' ) }
					value={ text } placeholder={ __( 'Type to filter...', 'kicon' ) }
					onChange={ this.updateTimer } />
				<p>
					<Button isPrimary={ ! show } isSecondary={ show } onClick={ () => this.setState( { show: !show } ) }>
						{ show ? __( 'Hide', 'kicon' ) : __( 'Show All', 'kicon' ) }
					</Button>
				</p>
				{ showIcons ? tags : null }
			</>
		);
	}
}

window.wp.kicon.IconSelector = IconSelector;
