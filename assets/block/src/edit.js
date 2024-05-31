import { useState, React } from 'react';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import {
    useBlockProps,
    BlockControls,
	InspectorControls,
} from '@wordpress/block-editor';

import {
	PanelBody,
	TextControl,
	Toolbar,
	ToolbarButton,
	SelectControl
} from '@wordpress/components';

import { tablet, wordpress } from '@wordpress/icons';

import './editor.scss';

/**
 * Block Editor side
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( props ) {

	const blockProps = useBlockProps();
	const [identificator, setIdentificator] = useState('Slug');

	const onChangePlugin = value => {
		props.setAttributes( { slug: value } );
	};
	const onChangeUsername = value => {
		props.setAttributes( { preferred_username: value } );
	};
	const onChangeType = value => {
		props.setAttributes( { contribution_type: value } );
		if ( ['codex', 'core'].includes( value ) ) {
			setIdentificator('User');
		} else if ( identificator !== 'Slug' ) {
			setIdentificator('Slug');
		}
	};
	const toggletheme = () => {
		props.setAttributes( { theme: !props.attributes.theme } );
	};

	return (
		<div { ...blockProps }>
			{
				<BlockControls key="custom-controls">
					<Toolbar key="options-settings-toolbar" label={ __( 'Options', 'wp-contributions' ) }>
						<ToolbarButton
							key="preview-toggle-btn"
							icon={ tablet }
							label={ __( 'Preview', ' wp-contributions' ) }
							onClick={ toggletheme }
						/>
					</Toolbar>
				</BlockControls>
			}
			<InspectorControls key="inspector">
				<PanelBody key="block-settings" title={ __( 'WP Contributions Settings', 'wp-contributions' ) } >
					<TextControl
						key="author-user-input"
						label={ __( 'Author User', 'wp-contributions' ) }
						value={ props.attributes.preferred_username }
						onChange={ onChangeUsername }
					/>
				</PanelBody>
			</InspectorControls>
			{ ! props.attributes.theme ?
				<div>
					<div className='contrib-title components-placeholder__label'><span className="block-editor-block-icon has-colors">{ wordpress }</span> { __( 'My WP Contributions', 'wp-contributions' ) }</div>
					<SelectControl
						key="select-contribution-type"
						className="contrib-item"
						label={ __( 'Type', 'wp-contributions' ) }
						value={ props.attributes.contribution_type }
						options={ [
							{ label: __( 'Plugin', 'wp-contributions' ), value: 'plugin' },
							{ label: __( 'Theme', 'wp-contributions' ), value: 'theme' },
							{ label: __( 'Core', 'wp-contributions' ), value: 'core' },
							{ label: __( 'Codex', 'wp-contributions' ), value: 'codex' },
						] }
						onChange={ onChangeType }
					/>
					<TextControl
						key="slug-text-control"
						label={ identificator }
						format="string"
						placeholder={ __( `Enter your contribution slug or username.`, 'wp-contributions' ) }
						onChange={ onChangePlugin }
						value={ props.attributes.slug }
						className="contrib-item"
					/>
				</div>
			:
				<MyServerRender myattr={ props.attributes } />
			}
		</div>
	);
}

const MyServerRender = (attr) => {
		try {
			return (
				<ServerSideRender
					block="wp-contributions/block"
					attributes={ {
						slug: attr.myattr.slug,
						contribution_type: attr.myattr.contribution_type
					} }
				/>
			);
		} catch ( error ){
			console.log(error);
			return ( <p> { __( "We've gotten a problem here!", 'wp-contributions' ) } </p> );
		}
}
