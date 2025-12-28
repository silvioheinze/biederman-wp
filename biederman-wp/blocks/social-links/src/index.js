/**
 * Social Links Block - Editor Component
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

registerBlockType('biederman/social-links', {
  edit: ({ attributes, setAttributes }) => {
    const blockProps = useBlockProps({
      className: 'wp-block-biederman-social-links-editor-wrapper',
    });

    return (
      <div {...blockProps}>
        <div className="wp-block-biederman-social-links-editor">
          <div className="wp-block-biederman-social-links-editor__header">
            <div className="wp-block-biederman-social-links-editor__icon">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2C5.58 2 2 5.58 2 10s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z" fill="currentColor"/>
                <path d="M8 10h4M10 8v4" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round"/>
              </svg>
            </div>
            <div className="wp-block-biederman-social-links-editor__title">
              <strong>{__('Social Links', 'biederman')}</strong>
              <span className="wp-block-biederman-social-links-editor__subtitle">
                {__('Social media links from customizer', 'biederman')}
              </span>
            </div>
          </div>
          <div className="wp-block-biederman-social-links-editor__content">
            <ServerSideRender
              block="biederman/social-links"
              attributes={attributes}
            />
          </div>
        </div>
      </div>
    );
  },

  save: () => {
    return null; // Server-side rendered
  },
});

