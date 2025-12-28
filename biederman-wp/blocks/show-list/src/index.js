/**
 * Show List Block - Editor Component
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, BlockControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToolbarGroup, ToolbarButton } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';
import { calendar } from '@wordpress/icons';
import ServerSideRender from '@wordpress/server-side-render';
import { useState, useEffect } from '@wordpress/element';

registerBlockType('biederman/show-list', {
  edit: ({ attributes, setAttributes, isSelected }) => {
    const { limit } = attributes;
    const blockProps = useBlockProps({
      className: 'wp-block-biederman-show-list-editor-wrapper',
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
              icon={calendar}
              label={__('Show List Block', 'biederman')}
              isPressed={false}
            />
          </ToolbarGroup>
        </BlockControls>
        
        <InspectorControls>
          <PanelBody title={__('Show List Settings', 'biederman')} initialOpen={true}>
            <div className="biederman-block-settings">
              <RangeControl
                label={__('Number of shows', 'biederman')}
                help={sprintf(__('Display up to %d shows', 'biederman'), limit)}
                value={limit}
                onChange={(value) => setAttributes({ limit: value })}
                min={1}
                max={20}
              />
              {limit > 10 && (
                <p className="biederman-block-settings__note">
                  {__('💡 Tip: Consider limiting to 10 or fewer shows for better performance', 'biederman')}
                </p>
              )}
            </div>
          </PanelBody>
        </InspectorControls>

        <div className="wp-block-biederman-show-list-editor">
          <div className="wp-block-biederman-show-list-editor__header">
            <div className="wp-block-biederman-show-list-editor__icon">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 2h2a1 1 0 011 1v14a1 1 0 01-1 1H3a1 1 0 01-1-1V3a1 1 0 011-1h2m0 0V0m0 2h8m0 0V0m-4 4v12m-4-4h8" stroke="currentColor" strokeWidth="1.5" fill="none"/>
              </svg>
            </div>
            <div className="wp-block-biederman-show-list-editor__title">
              <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                <strong>{__('Show List', 'biederman')}</strong>
              </div>
              <span className="wp-block-biederman-show-list-editor__subtitle">
                {sprintf(__('%d shows', 'biederman'), limit)}
              </span>
            </div>
          </div>
          
          {isLoading ? (
            <div className="wp-block-biederman-show-list-editor__loading">
              <div className="wp-block-biederman-show-list-editor__spinner"></div>
              <p>{__('Loading shows...', 'biederman')}</p>
            </div>
          ) : (
            <div className="wp-block-biederman-show-list-editor__content">
              <ServerSideRender
                block="biederman/show-list"
                attributes={attributes}
                EmptyResponsePlaceholder={() => (
                  <div className="wp-block-biederman-show-list-editor__empty">
                    <div className="wp-block-biederman-show-list-editor__empty-icon">📅</div>
                    <h3>{__('No shows found', 'biederman')}</h3>
                    <p>{__('Create shows in the Shows section to display them here.', 'biederman')}</p>
                    <a 
                      href={wp.url ? wp.url.addQueryArgs('edit.php', { post_type: 'show' }) : 'edit.php?post_type=show'}
                      className="wp-block-biederman-show-list-editor__empty-link"
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

