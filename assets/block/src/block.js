/**
 * Block dependencies
 */

import classnames from 'classnames';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const {
	RichText,
	InspectorControls,
	BlockControls,
} = wp.editor;

const {
	PanelBody,
	TextControl,
	Dashicon,
	Toolbar,
	Button,
	Tooltip,
} = wp.components;

/**
 * Register block wp-contributions/my-plugin
 */
export default registerBlockType( 'wp-contributions/my-plugin', {
	title: __( 'WP Contributions to plugins' ),
	description: __( 'Showcase your contributions to Wordpress.' ),
	category: 'common',
	icon: 'embed-photo',
	keywords: [
		__( 'Wordpress' ),
		__( 'Plugins' ),
		__( 'WP Contributions' ),
	],
	attributes: {
		plugin_slug: {
			type: 'string',
		},
		preferred_username: {
			type: 'string',
			default: __( 'Preferred @user_handle (change from sidebar)' ),
		},
		theme: {
			type: 'boolean',
			default: false,
		},
	},
	edit: props => {
		const onChangePlugin = value => {
			props.setAttributes( { plugin_slug: value } );
		};
		const onChangeUsername = value => {
			props.setAttributes( { preferred_username: value } );
		};
		const toggletheme = value => {
			props.setAttributes( { theme: !props.attributes.theme } );
		};
		return [
			!! props.isSelected && (
				<BlockControls key="custom-controls">
					<Toolbar
						className='components-toolbar'
					>
						<Tooltip text={ __( 'Alternative Color' )	}>
							<Button
								className={ classnames(
									'components-icon-button',
									'components-toolbar__control',
									{ 'is-active': props.attributes.theme },
								) }
								onClick={ toggletheme }
							>
								<Dashicon icon="tablet" />
							</Button>
						</Tooltip>
					</Toolbar>
				</BlockControls>
			),
			!! props.isSelected && (
				<InspectorControls key="inspector">
					<PanelBody title={ __( 'WP Contributions Settings' ) } >
						<TextControl
							label={ __( 'Author User' ) }
							value={ props.attributes.preferred_username }
							onChange={ onChangeUsername }
						/>
					</PanelBody>
				</InspectorControls>
			),
			<div className={ props.className }>
				<div className={ ( props.attributes.theme ? 'click-to-send-alt' : 'click-to-send' ) }>
					<div className="ctt-text">
						<RichText
							format="string"
							formattingControls={ [] }
							placeholder={ __( 'Enter your plugin slug here.' ) }
							onChange={ onChangePlugin }
							value={ props.attributes.plugin_slug }
						/>
					</div>
					<p>
						<a className="ctt-btn">
							{ props.attributes.preferred_username }
						</a>
					</p>
				</div>
			</div>
		];
	},
	save() {
		return null;
	},
});
