/**
 * Facts Block - Editor Component
 */

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

registerBlockType('biederman/facts', {
  edit: ({ attributes, setAttributes }) => {
    const blockProps = useBlockProps({
      className: 'wp-block-biederman-facts-editor-wrapper',
    });

    const { facts = [] } = attributes;

    const updateFact = (index, field, value) => {
      const newFacts = [...facts];
      if (!newFacts[index]) {
        newFacts[index] = { key: '', value: '', link: '' };
      }
      newFacts[index][field] = value;
      setAttributes({ facts: newFacts });
    };

    const addFact = () => {
      setAttributes({
        facts: [...facts, { key: '', value: '', link: '' }],
      });
    };

    const removeFact = (index) => {
      const newFacts = facts.filter((_, i) => i !== index);
      setAttributes({ facts: newFacts });
    };

    return (
      <div {...blockProps}>
        <InspectorControls>
          <PanelBody title={__('Facts', 'biederman')} initialOpen={true}>
            {facts.map((fact, index) => (
              <div key={index} style={{ marginBottom: '1rem', padding: '0.75rem', border: '1px solid #ddd', borderRadius: '4px' }}>
                <TextControl
                  label={__('Key', 'biederman')}
                  value={fact.key || ''}
                  onChange={(value) => updateFact(index, 'key', value)}
                  placeholder={__('e.g., Stil', 'biederman')}
                />
                <TextControl
                  label={__('Value', 'biederman')}
                  value={fact.value || ''}
                  onChange={(value) => updateFact(index, 'value', value)}
                  placeholder={__('e.g., Comedy · Live · Pop', 'biederman')}
                />
                <TextControl
                  label={__('Link (optional)', 'biederman')}
                  value={fact.link || ''}
                  onChange={(value) => updateFact(index, 'link', value)}
                  placeholder={__('e.g., #contact', 'biederman')}
                />
                <Button
                  isDestructive
                  onClick={() => removeFact(index)}
                  style={{ marginTop: '0.5rem' }}
                >
                  {__('Remove', 'biederman')}
                </Button>
              </div>
            ))}
            <Button isPrimary onClick={addFact} style={{ width: '100%', marginTop: '0.5rem' }}>
              {__('Add Fact', 'biederman')}
            </Button>
          </PanelBody>
        </InspectorControls>

        <div className="wp-block-biederman-facts-editor">
          <div className="wp-block-biederman-facts-editor__header">
            <div className="wp-block-biederman-facts-editor__icon">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2C5.58 2 2 5.58 2 10s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6zm1-9H9v6h2V7zm0-2H9v2h2V5z" fill="currentColor"/>
              </svg>
            </div>
            <div className="wp-block-biederman-facts-editor__title">
              <strong>{__('Facts', 'biederman')}</strong>
              <span className="wp-block-biederman-facts-editor__subtitle">
                {facts.length > 0 ? `${facts.length} ${facts.length === 1 ? __('fact', 'biederman') : __('facts', 'biederman')}` : __('No facts added', 'biederman')}
              </span>
            </div>
          </div>
          <div className="wp-block-biederman-facts-editor__content">
            {facts.length > 0 ? (
              <div className="facts" style={{ marginTop: '1rem' }}>
                {facts.map((fact, index) => (
                  <div key={index} className="fact" style={{ marginBottom: '0.5rem', padding: '0.5rem', background: '#f0f0f0', borderRadius: '4px' }}>
                    <div className="fact__k" style={{ fontWeight: 'bold', marginBottom: '0.25rem' }}>{fact.key || __('Key', 'biederman')}</div>
                    <div className="fact__v">
                      {fact.link ? (
                        <a className="textlink" href={fact.link}>{fact.value || __('Value', 'biederman')}</a>
                      ) : (
                        <span>{fact.value || __('Value', 'biederman')}</span>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <div style={{ padding: '20px', background: '#f0f0f0', borderRadius: '4px', marginTop: '10px' }}>
                <p style={{ margin: 0, color: '#666' }}>
                  {__('Add facts using the sidebar panel.', 'biederman')}
                </p>
              </div>
            )}
          </div>
        </div>
      </div>
    );
  },

  save: () => {
    return null; // Server-side rendered
  },
});

