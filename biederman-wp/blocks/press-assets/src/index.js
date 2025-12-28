/**
 * Press Assets Block - Editor Component
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, BlockControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, RangeControl, ToolbarGroup, ToolbarButton, SelectControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';
import { media } from '@wordpress/icons';
import ServerSideRender from '@wordpress/server-side-render';
import { useState, useEffect } from '@wordpress/element';

registerBlockType('biederman/press-assets', {
  edit: ({ attributes, setAttributes, isSelected }) => {
    const { type, limit } = attributes;
    const blockProps = useBlockProps({
      className: 'wp-block-biederman-press-assets-editor-wrapper',
    });
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
      // Simulate loading state
      const timer = setTimeout(() => setIsLoading(false), 300);
      return () => clearTimeout(timer);
    }, [attributes]);

    const pressTypeOptions = [
      { label: __('All Types', 'biederman'), value: '' },
      { label: __('Photos', 'biederman'), value: 'photo' },
      { label: __('Rider', 'biederman'), value: 'rider' },
      { label: __('Logo', 'biederman'), value: 'logo' },
      { label: __('Press Kit', 'biederman'), value: 'press' },
    ];

    return (
      <div {...blockProps}>
        <BlockControls>
          <ToolbarGroup>
            <ToolbarButton
              icon={media}
              label={__('Press Assets Block', 'biederman')}
              isPressed={false}
            />
          </ToolbarGroup>
        </BlockControls>
        
        <InspectorControls>
          <PanelBody title={__('Press Assets Settings', 'biederman')} initialOpen={true}>
            <div className="biederman-block-settings">
              <SelectControl
                label={__('Filter by type', 'biederman')}
                help={__('Select a specific type or show all assets', 'biederman')}
                value={type}
                options={pressTypeOptions}
                onChange={(value) => setAttributes({ type: value })}
              />
              <RangeControl
                label={__('Number of assets', 'biederman')}
                help={limit === -1 ? __('Showing all assets', 'biederman') : __('Limit the number of assets displayed', 'biederman')}
                value={limit}
                onChange={(value) => setAttributes({ limit: value })}
                min={-1}
                max={20}
              />
              {limit === -1 && (
                <p className="biederman-block-settings__note">
                  {__('💡 Tip: Set a limit to improve page performance', 'biederman')}
                </p>
              )}
            </div>
          </PanelBody>
        </InspectorControls>

        <div className="wp-block-biederman-press-assets-editor">
          <div className="wp-block-biederman-press-assets-editor__header">
            <div className="wp-block-biederman-press-assets-editor__icon">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 3h14v14H3V3zm2 2v10h10V5H5zm2 2h6v2H7V7zm0 4h6v2H7v-2z" fill="currentColor"/>
              </svg>
            </div>
            <div className="wp-block-biederman-press-assets-editor__title">
              <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                <strong>{__('Press Assets', 'biederman')}</strong>
                {type && (
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
                    {type.charAt(0).toUpperCase() + type.slice(1)}
                  </span>
                )}
              </div>
              <span className="wp-block-biederman-press-assets-editor__subtitle">
                {type 
                  ? sprintf(__('%s assets', 'biederman'), type.charAt(0).toUpperCase() + type.slice(1))
                  : limit === -1 
                    ? __('All assets', 'biederman')
                    : sprintf(__('%d assets', 'biederman'), limit)
                }
              </span>
            </div>
          </div>
          
          {isLoading ? (
            <div className="wp-block-biederman-press-assets-editor__loading">
              <div className="wp-block-biederman-press-assets-editor__spinner"></div>
              <p>{__('Loading press assets...', 'biederman')}</p>
            </div>
          ) : (
            <div className="wp-block-biederman-press-assets-editor__content">
              <ServerSideRender
                block="biederman/press-assets"
                attributes={attributes}
                EmptyResponsePlaceholder={() => (
                  <div className="wp-block-biederman-press-assets-editor__empty">
                    <div className="wp-block-biederman-press-assets-editor__empty-icon">📁</div>
                    <h3>{__('No press assets found', 'biederman')}</h3>
                    <p>
                      {type 
                        ? sprintf(__('No assets of type "%s" found. Try a different type or create new assets.', 'biederman'), type)
                        : __('Create press assets in the Press Assets section to display them here.', 'biederman')
                      }
                    </p>
                    <a 
                      href={wp.url ? wp.url.addQueryArgs('edit.php', { post_type: 'press_asset' }) : 'edit.php?post_type=press_asset'}
                      className="wp-block-biederman-press-assets-editor__empty-link"
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      {__('Go to Press Assets →', 'biederman')}
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

