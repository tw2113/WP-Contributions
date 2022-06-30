
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

import { tablet } from '@wordpress/icons';

import './editor.scss';

/**
 * Block Editor side
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( props ) {

	const blockProps = useBlockProps();
	const [identificator, setIdentificator] = useState('slug');

	const onChangePlugin = value => {
		props.setAttributes( { slug: value } );
	};
	const onChangeUsername = value => {
		props.setAttributes( { preferred_username: value } );
	};
	const onChangeType = value => {
		props.setAttributes( { contribution_type: value } );
		if ( ['codex', 'core'].includes( value ) ) {
			setIdentificator('user');
		} else if ( identificator != 'slug' ) {
			setIdentificator('slug');
		}
	};
	const toggletheme = () => {
		props.setAttributes( { theme: !props.attributes.theme } );
	};

	return (
		<div { ...blockProps }>
			{
				<BlockControls key="custom-controls">
					<Toolbar key="options-settings-toolbar" label={ __( 'Options' ) }>
						<ToolbarButton
							key="preview-toggle-btn"
							icon={ tablet }
							label={ __( 'Preview' )	}
							onClick={ toggletheme }
						/>
					</Toolbar>
				</BlockControls>
			}
			<InspectorControls key="inspector">
				<PanelBody key="block-settings" title={ __( 'WP Contributions Settings' ) } >
					<TextControl
						key="author-user-input"
						label={ __( 'Author User' ) }
						value={ props.attributes.preferred_username }
						onChange={ onChangeUsername }
					/>
				</PanelBody>
			</InspectorControls>
			{ ! props.attributes.theme ?
				<div className='blocki'>
					<h5 className='contrib-title'>WP Contributions - My Contributions</h5>
					<SelectControl
						key="select-contribution-type"
						className="contrib-item"
						label="Type"
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
						key="slug-text-control"
						label="Slug"
						format="string"
						placeholder={ __( `Enter your contribution ${ identificator }.` ) }
						onChange={ onChangePlugin }
						value={ props.attributes.slug }
						className="contrib-item"
					/>
					<p className="block-auth">@By { props.attributes.preferred_username }</p>
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
			return ( <p> We've gotten a problem here! </p> );
		}
}
