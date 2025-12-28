/**
 * Show Featured Block - Editor Component
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, BlockControls } from '@wordpress/block-editor';
import { PanelBody, ToolbarGroup, ToolbarButton } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { starFilled } from '@wordpress/icons';
import ServerSideRender from '@wordpress/server-side-render';
import { useState, useEffect } from '@wordpress/element';

registerBlockType('biederman/show-featured', {
  edit: ({ attributes, setAttributes, isSelected }) => {
    const blockProps = useBlockProps({
      className: 'wp-block-biederman-show-featured-editor-wrapper',
    });
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
      // Simulate loading state
      const timer = setTimeout(() => setIsLoading(false), 300);
      return () => clearTimeout(timer);
    }, [attributes]);

    return (
      <div {...blockProps}>
        <BlockControls>
          <ToolbarGroup>
            <ToolbarButton
              icon={starFilled}
              label={__('Featured Show Block', 'biederman')}
              isPressed={false}
            />
          </ToolbarGroup>
        </BlockControls>
        
        <InspectorControls>
          <PanelBody title={__('Featured Show Settings', 'biederman')} initialOpen={true}>
            <div className="biederman-block-settings">
              <p className="biederman-block-settings__note">
                {__('This block displays the featured show. If no show is marked as featured, it will display the next upcoming show by date.', 'biederman')}
              </p>
            </div>
          </PanelBody>
        </InspectorControls>

        <div className="wp-block-biederman-show-featured-editor">
          <div className="wp-block-biederman-show-featured-editor__header">
            <div className="wp-block-biederman-show-featured-editor__icon">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2l2.5 5.5L18 8.5l-4.5 4 1 6-4.5-2.5L6 18.5l1-6L2.5 8.5l5.5-1L10 2z" fill="currentColor"/>
              </svg>
            </div>
            <div className="wp-block-biederman-show-featured-editor__title">
              <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                <strong>{__('Featured Show', 'biederman')}</strong>
                <span style={{
                  display: 'inline-block',
                  padding: '2px 8px',
                  background: '#2271b1',
                  color: '#ffffff',
                  borderRadius: '3px',
                  fontSize: '11px',
                  fontWeight: 600,
                  textTransform: 'uppercase',
                  letterSpacing: '0.5px'
                }}>
                  {__('Featured', 'biederman')}
                </span>
              </div>
              <span className="wp-block-biederman-show-featured-editor__subtitle">
                {__('Displays the featured or next upcoming show', 'biederman')}
              </span>
            </div>
          </div>
          
          {isLoading ? (
            <div className="wp-block-biederman-show-featured-editor__loading">
              <div className="wp-block-biederman-show-featured-editor__spinner"></div>
              <p>{__('Loading featured show...', 'biederman')}</p>
            </div>
          ) : (
            <div className="wp-block-biederman-show-featured-editor__content">
              <ServerSideRender
                block="biederman/show-featured"
                attributes={attributes}
                EmptyResponsePlaceholder={() => (
                  <div className="wp-block-biederman-show-featured-editor__empty">
                    <div className="wp-block-biederman-show-featured-editor__empty-icon">⭐</div>
                    <h3>{__('No featured show found', 'biederman')}</h3>
                    <p>{__('Create shows and mark one as featured, or create shows with dates to display the next upcoming show.', 'biederman')}</p>
                    <a 
                      href={wp.url ? wp.url.addQueryArgs('edit.php', { post_type: 'show' }) : 'edit.php?post_type=show'}
                      className="wp-block-biederman-show-featured-editor__empty-link"
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      {__('Go to Shows →', 'biederman')}
                    </a>
                  </div>
                )}
              />
            </div>
          )}
        </div>
      </div>
    );
  },

  save: () => {
    return null; // Server-side rendered
  },
});

