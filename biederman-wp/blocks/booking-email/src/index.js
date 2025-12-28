/**
 * Booking Email Block - Editor Component
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

registerBlockType('biederman/booking-email', {
  edit: ({ attributes, setAttributes }) => {
    const blockProps = useBlockProps({
      className: 'wp-block-biederman-booking-email-editor-wrapper',
    });

    return (
      <div {...blockProps}>
        <div className="wp-block-biederman-booking-email-editor">
          <div className="wp-block-biederman-booking-email-editor__header">
            <div className="wp-block-biederman-booking-email-editor__icon">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 4h14v12H3V4zm2 2v8h10V6H5zm2 2h6v1.5H7V8zm0 2.5h6V12H7v-1.5z" fill="currentColor"/>
              </svg>
            </div>
            <div className="wp-block-biederman-booking-email-editor__title">
              <strong>{__('Booking Email', 'biederman')}</strong>
              <span className="wp-block-biederman-booking-email-editor__subtitle">
                {__('Booking email with copy button', 'biederman')}
              </span>
            </div>
          </div>
          <div className="wp-block-biederman-booking-email-editor__content">
            <ServerSideRender
              block="biederman/booking-email"
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

