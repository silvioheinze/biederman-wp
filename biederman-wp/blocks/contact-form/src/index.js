/**
 * Contact Form Block - Editor Component
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

registerBlockType('biederman/contact-form', {
  edit: ({ attributes, setAttributes }) => {
    const blockProps = useBlockProps({
      className: 'wp-block-biederman-contact-form-editor-wrapper',
    });

    return (
      <div {...blockProps}>
        <div className="wp-block-biederman-contact-form-editor">
          <div className="wp-block-biederman-contact-form-editor__header">
            <div className="wp-block-biederman-contact-form-editor__icon">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 4h14v12H3V4zm2 2v8h10V6H5zm2 2h6v1.5H7V8zm0 2.5h6V12H7v-1.5z" fill="currentColor"/>
              </svg>
            </div>
            <div className="wp-block-biederman-contact-form-editor__title">
              <strong>{__('Contact Form', 'biederman')}</strong>
              <span className="wp-block-biederman-contact-form-editor__subtitle">
                {__('Contact form with name, email, and message fields', 'biederman')}
              </span>
            </div>
          </div>
          <div className="wp-block-biederman-contact-form-editor__content">
            <div style={{ padding: '20px', background: '#f0f0f0', borderRadius: '4px', marginTop: '10px' }}>
              <p style={{ margin: 0, color: '#666' }}>
                {__('Contact form will be displayed on the frontend.', 'biederman')}
              </p>
            </div>
          </div>
        </div>
      </div>
    );
  },

  save: () => {
    return null; // Server-side rendered
  },
});

