/**
 * Hero Block - Editor Component
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Notice } from '@wordpress/components';
import { PanelBody, TextControl, TextareaControl, Button, ButtonGroup } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { coverImage } from '@wordpress/icons';
import ServerSideRender from '@wordpress/server-side-render';
import { useState, useEffect } from '@wordpress/element';

registerBlockType('biederman/hero', {
  edit: ({ attributes, setAttributes, isSelected }) => {
    const {
      tagline = '',
      title = '',
      lead = '',
      primaryButtonText = '',
      primaryButtonLink = '',
      secondaryButtonText = '',
      secondaryButtonLink = '',
      chips = [],
      imageId = 0,
      imageUrl = '',
      imageAlt = '',
      imageCaption = '',
    } = attributes;

    const blockProps = useBlockProps({
      className: 'wp-block-biederman-hero-editor-wrapper',
    });

    const [chipInput, setChipInput] = useState('');
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
      const timer = setTimeout(() => setIsLoading(false), 300);
      return () => clearTimeout(timer);
    }, [attributes]);

    const addChip = () => {
      if (chipInput.trim()) {
        setAttributes({
          chips: [...(chips || []), chipInput.trim()],
        });
        setChipInput('');
      }
    };

    const removeChip = (index) => {
      const newChips = [...(chips || [])];
      newChips.splice(index, 1);
      setAttributes({ chips: newChips });
    };

    const onSelectImage = (media) => {
      setAttributes({
        imageId: media.id,
        imageUrl: media.url,
        imageAlt: media.alt || '',
        imageCaption: media.caption || '',
      });
    };

    const onRemoveImage = () => {
      setAttributes({
        imageId: 0,
        imageUrl: '',
        imageAlt: '',
        imageCaption: '',
      });
    };

    return (
      <div {...blockProps}>
        <InspectorControls>
          <PanelBody title={__('Hero Image', 'biederman')} initialOpen={true}>
            <MediaUploadCheck>
              <MediaUpload
                onSelect={onSelectImage}
                allowedTypes={['image']}
                value={imageId || 0}
                render={({ open }) => {
                  // Always show the button, regardless of image state
                  return (
                    <div>
                      {imageUrl ? (
                        <>
                          <img 
                            src={imageUrl} 
                            alt={imageAlt || ''} 
                            style={{ 
                              maxWidth: '100%', 
                              height: 'auto', 
                              marginBottom: '10px', 
                              borderRadius: '4px',
                              display: 'block'
                            }} 
                          />
                          <div style={{ display: 'flex', gap: '8px', marginBottom: '10px' }}>
                            <Button 
                              onClick={open} 
                              variant="secondary"
                            >
                              {__('Change Image', 'biederman')}
                            </Button>
                            <Button 
                              onClick={onRemoveImage} 
                              isDestructive 
                              variant="secondary"
                            >
                              {__('Remove Image', 'biederman')}
                            </Button>
                          </div>
                        </>
                      ) : null}
                      {!imageUrl && (
                        <div>
                          <Button 
                            onClick={open} 
                            variant="primary" 
                            isLarge 
                            style={{ 
                              width: '100%', 
                              marginBottom: '10px'
                            }}
                          >
                            {__('Select Image', 'biederman')}
                          </Button>
                          <p style={{ fontSize: '12px', color: '#757575', margin: 0 }}>
                            {__('Choose an image from your media library', 'biederman')}
                          </p>
                        </div>
                      )}
                    </div>
                  );
                }}
              />
            </MediaUploadCheck>
            {imageUrl && (
              <>
                <div style={{ marginTop: '16px', borderTop: '1px solid #ddd', paddingTop: '16px' }}>
                  <TextControl
                    label={__('Image Alt Text', 'biederman')}
                    value={imageAlt || ''}
                    onChange={(value) => setAttributes({ imageAlt: value })}
                    help={__('Alternative text for accessibility', 'biederman')}
                  />
                </div>
                <div style={{ marginTop: '16px' }}>
                  <TextControl
                    label={__('Image Caption', 'biederman')}
                    value={imageCaption || ''}
                    onChange={(value) => setAttributes({ imageCaption: value })}
                    help={__('Caption displayed below the image', 'biederman')}
                  />
                </div>
              </>
            )}
          </PanelBody>

          <PanelBody title={__('Hero Content', 'biederman')} initialOpen={true}>
            <TextControl
              label={__('Tagline', 'biederman')}
              value={tagline || ''}
              onChange={(value) => setAttributes({ tagline: value })}
              help={__('Short tagline displayed above the title', 'biederman')}
            />
            <TextControl
              label={__('Title', 'biederman')}
              value={title || ''}
              onChange={(value) => setAttributes({ title: value })}
              help={__('Main heading (defaults to site name if empty)', 'biederman')}
            />
            <TextareaControl
              label={__('Lead Text', 'biederman')}
              value={lead || ''}
              onChange={(value) => setAttributes({ lead: value })}
              help={__('Introductory text below the title', 'biederman')}
            />
          </PanelBody>

          <PanelBody title={__('Buttons', 'biederman')} initialOpen={false}>
            <TextControl
              label={__('Primary Button Text', 'biederman')}
              value={primaryButtonText || ''}
              onChange={(value) => setAttributes({ primaryButtonText: value })}
            />
            <TextControl
              label={__('Primary Button Link', 'biederman')}
              value={primaryButtonLink || ''}
              onChange={(value) => setAttributes({ primaryButtonLink: value })}
            />
            <TextControl
              label={__('Secondary Button Text', 'biederman')}
              value={secondaryButtonText || ''}
              onChange={(value) => setAttributes({ secondaryButtonText: value })}
            />
            <TextControl
              label={__('Secondary Button Link', 'biederman')}
              value={secondaryButtonLink || ''}
              onChange={(value) => setAttributes({ secondaryButtonLink: value })}
            />
          </PanelBody>

          <PanelBody title={__('Chips', 'biederman')} initialOpen={false}>
            <div style={{ marginBottom: '10px' }}>
              <TextControl
                label={__('Add Chip', 'biederman')}
                value={chipInput}
                onChange={(value) => setChipInput(value)}
                onKeyDown={(e) => {
                  if (e.key === 'Enter') {
                    e.preventDefault();
                    addChip();
                  }
                }}
              />
              <Button onClick={addChip} variant="secondary" style={{ marginTop: '8px' }}>
                {__('Add', 'biederman')}
              </Button>
            </div>
            {(chips || []).length > 0 && (
              <ul style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                {(chips || []).map((chip, index) => (
                  <li key={index} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '8px', padding: '8px', background: '#f0f0f0', borderRadius: '4px' }}>
                    <span>{chip}</span>
                    <Button onClick={() => removeChip(index)} isDestructive isSmall>
                      {__('Remove', 'biederman')}
                    </Button>
                  </li>
                ))}
              </ul>
            )}
          </PanelBody>
        </InspectorControls>

        <div className="wp-block-biederman-hero-editor">
          <div className="wp-block-biederman-hero-editor__header">
            <div className="wp-block-biederman-hero-editor__icon">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 4h16v12H2V4zm0-2a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V4a2 2 0 00-2-2H2z" fill="currentColor"/>
                <path d="M6 8l3 3 5-5" stroke="currentColor" strokeWidth="2" fill="none"/>
              </svg>
            </div>
            <div className="wp-block-biederman-hero-editor__title">
              <strong>{__('Hero', 'biederman')}</strong>
              <span className="wp-block-biederman-hero-editor__subtitle">
                {__('Hero section with image and content', 'biederman')}
              </span>
            </div>
          </div>
          
          {isLoading ? (
            <div className="wp-block-biederman-hero-editor__loading">
              <div className="wp-block-biederman-hero-editor__spinner"></div>
              <p>{__('Loading hero...', 'biederman')}</p>
            </div>
          ) : (
            <div className="wp-block-biederman-hero-editor__content">
              <ServerSideRender
                block="biederman/hero"
                attributes={attributes}
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

