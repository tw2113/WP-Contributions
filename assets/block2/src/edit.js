/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
//import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { __ } from '@wordpress/i18n';
import { ServerSideRender } from '@wordpress/server-side-render';
// import { useState } from '@wordpress/element';
import {
    useBlockProps,
    RichText,
    BlockControls,
	InspectorControls,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	Dashicon,
	Toolbar,
	Button,
	Tooltip,
	SelectControl
} from '@wordpress/element';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit() {
	const props = useBlockProps();
	const onChangePlugin = value => {
		props.setAttributes( { slug: value } );
	};
	const onChangeUsername = value => {
		props.setAttributes( { preferred_username: value } );
	};
	const onChangeType = value => {
		props.setAttributes( { contribution_type: value } );
	};
	const toggletheme = value => {
		props.setAttributes( { theme: !props.attributes.theme } );
	};
	return (
			 !! props.isSelected && (
				<BlockControls key="custom-controls">
					<Toolbar
						className='components-toolbar'
					>
						<Tooltip text={ __( 'Preview' )	}>
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
			props.attributes.theme ?
				<div className={ props.className }>
					<div className="click-to-send">
						<div className="ctt-text">
							<SelectControl
								className="ctt-type"
								label="Contribution Type"
								value={ props.attributes.contribution_type }
								options={ [
									{ label: 'Plugin', value: 'plugin' },
									{ label: 'Theme', value: 'theme' },
									{ label: 'Core', value: 'core' },
									{ label: 'Codex', value: 'codex' },
								] }
								onChange={ onChangeType }
							/>
							<TextControl
								label="Slug"
								placeholder={ __( 'Enter your contribution slug here.' ) }
								onChange={ onChangePlugin }
								value={ props.attributes.slug }
								className="ctt-textbox"
							/>
						</div>
						<p>
							<a className="ctt-btn">
								{ props.attributes.preferred_username }
							</a>
						</p>
					</div>
				</div>
			:
			<ServerSideRender
				block="wp-contributions/my-plugin"
				attributes={ {
					slug: props.attributes.slug,
					contribution_type: props.attributes.contribution_type
				} }
			/>
	);
}
