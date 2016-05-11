#------------#
# CONSTANTES #
#------------#

ANIMATION_PROPERTIES = ['animation', 'animation-delay', 'animation-direction', 'animation-duration', 'animation-fill-mode', 'animation-iteration-count', 'animation-name', 'animation-play-state', 'animation-timing-function']

BACKGROUND_PROPERTIES = ['backface-visibility', 'background', 'background-attachment', 'background-clip', 'background-color', 'background-image', 'background-origin', 'background-position', 'background-repeat', 'background-size']

BORDER_PROPERTIES = ['border', 'border-bottom', 'border-bottom-color', 'border-bottom-left-radius', 'border-bottom-right-radius', 'border-bottom-style', 'border-bottom-width', 'border-collapse', 'border-color', 'border-image', 'border-image-outset</', 'border-image-repeat', 'border-image-slice</', 'border-image-source', 'border-image-width</', 'border-left', 'border-left-color', 'border-left-style', 'border-left-width', 'border-radius', 'border-right', 'border-right-color', 'border-right-style', 'border-right-width', 'border-spacing', 'border-style', 'border-top', 'border-top-color', 'border-top-left-radius', 'border-top-right-radius', 'border-top-style', 'border-top-width', 'border-width']

COLOR_PROPERTIES = ['color', 'opacity']

DIMENSION_PROPERTIES = ['height', 'max-height', 'max-width', 'min-height', 'min-width', 'width']

FLEXIBLE_BOX_LAYOUT = ['align-content', 'align-items', 'align-self', 'flex', 'flex-basis', 'flex-direction', 'flex-direction', 'flex-wrap', 'flex-grow', 'flex-shrink', 'flex-wrap', 'justify-content', 'order']

FONT_PROPERTIES = ['font', 'font-family', 'font-size', 'font-size-adjust', 'font-stretch', 'font-style', 'font-variant', 'font-weight']

GENERATED_CONTENT_PROPERTIES = ['content', 'counter-increment', 'counter-reset', 'quotes']

LIST_PROPERTIES = ['list-style', 'list-style-image', 'list-style-position', 'list-style-type']

MARGIN_PROPERTIES = ['margin', 'margin-bottom', 'margin-left', 'margin-right', 'margin-top']

MEDIA_FEATURES = ['any-hover', 'any-pointer', 'aspect-ratio', 'color', 'color-gamut', 'color-index', 'grid', 'height', 'hover', 'inverted-colors', 'light-level', 'max-aspect-ratio', 'max-color', 'max-color-index', 'max-height', 'max-monochrome', 'max-resolution', 'max-width', 'min-aspect-ratio', 'min-color', 'min-color-index', 'min-height', 'min-monochrome', 'min-resolution', 'min-width', 'monochrome', 'orientation', 'overflow-block', 'overflow-inline', 'pointer', 'resolution', 'scan', 'scripting', 'update', 'width']

MEDIA_TYPES = ['all', 'print', 'screen', 'speech']

MULTI_COLUMN_LAYOUT_PROPERTIES = ['column-count', 'column-fill', 'column-gap', 'column-rule', 'column-rule-color', 'column-rule-style', 'column-rule-width', 'column-span', 'column-width', 'columns']

OUTLINE_PROPERTIES = ['outline', 'outline-color', 'outline-offset', 'outline-style', 'outline-width']

PADDING_PROPERTIES = ['padding', 'padding-bottom', 'padding-left', 'padding-right', 'padding-top']

PRINT_PROPERTIES = ['page-break-after', 'page-break-before', 'page-break-inside']

TABLE_PROPERTIES = ['border-collapse', 'border-spacing', 'caption-side', 'empty-cells', 'table-layout']

TEXT_PROPERTIES = ['direction', 'tab-size', 'text-align', 'text-align-last', 'text-decoration', 'text-decoration-color', 'text-decoration-line', 'text-decoration-style', 'text-indent', 'text-justify', 'text-overflow', 'text-shadow', 'text-transform', 'line-height', 'vertical-align', 'letter-spacing', 'word-spacing', 'white-space', 'word-break', 'word-wrap']

TRANSFORM_PROPERTIES = ['backface-visibility', 'perspective', 'perspective-origin', 'transform', 'transform-origin', 'transform-style']

TRANSITIONS_PROPERTIES = ['transition', 'transition-delay', 'transition-duration', 'transition-property', 'transition-timing-function']

VISUAL_FORMATTING_PROPERTIES = ['bottom', 'box-shadow', 'box-sizing', 'clear', 'clip', 'cursor', 'display', 'float', 'left', 'overflow', 'overflow-x', 'overflow-y', 'position', 'resize', 'right', 'top', 'visibility', 'z-index']

#-----------------#
# FUNÇÕES GLOBAIS #
#-----------------#

def animation_properties(attributes):
	_animation_properties = ''
	for attribute in attributes:
		if attribute in ANIMATION_PROPERTIES:
			_animation_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _animation_properties

def background_properties(attributes):
	_background_properties = ''
	for attribute in attributes:
		if attribute in BACKGROUND_PROPERTIES:
			_background_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _background_properties

def border_properties(attributes):
	_border_properties = ''
	for attribute in attributes:
		if attribute in BORDER_PROPERTIES:
			_border_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _border_properties

def color_properties(attributes):
	_color_properties = ''
	for attribute in attributes:
		if attribute in COLOR_PROPERTIES:
			_color_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _color_properties

def dimension_properties(attributes):
	_dimension_properties = ''
	for attribute in attributes:
		if attribute in DIMENSION_PROPERTIES:
			_dimension_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _dimension_properties

def generated_content_properties(attributes):
	_generated_content_properties = ''
	for attribute in attributes:
		if attribute in GENERATED_CONTENT_PROPERTIES:
			_generated_content_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _generated_content_properties

def flexible_box_layout(attributes):
	_flexible_box_layout = ''
	for attribute in attributes:
		if attribute in FLEXIBLE_BOX_LAYOUT:
			_flexible_box_layout += attribute.lower() + ':' + attributes[attribute] + ';'
	return _flexible_box_layout

def font_properties(attributes):
	_font_properties = ''
	for attribute in attributes:
		if attribute in FONT_PROPERTIES:
			_font_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _font_properties

def list_properties(attributes):
	_list_properties = ''
	for attribute in attributes:
		if attribute in LIST_PROPERTIES:
			_list_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _list_properties

def margin_properties(attributes):
	_margin_properties = ''
	for attribute in attributes:
		if attribute in MARGIN_PROPERTIES:
			_margin_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _margin_properties

def media_features(attributes):
	_media_features = ''
	for attribute in attributes:
		if attribute in MEDIA_FEATURES:
			_media_features += ' (' + attribute.lower() + ':' + attributes[attribute] + ')'
	return _media_features

def media_types(attributes):
	_media_types = ''
	for attribute in attributes:
		if attribute in MEDIA_TYPES:
			_media_types += ' ' + attribute.lower() + ' and'
	return _media_types

def multi_column_layout_properties(attributes):
	_multi_column_layout_properties = ''
	for attribute in attributes:
		if attribute in MULTI_COLUMN_LAYOUT_PROPERTIES:
			_multi_column_layout_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _multi_column_layout_properties

def outline_properties(attributes):
	_outline_properties = ''
	for attribute in attributes:
		if attribute in OUTLINE_PROPERTIES:
			_outline_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _outline_properties

def padding_properties(attributes):
	_padding_properties = ''
	for attribute in attributes:
		if attribute in PADDING_PROPERTIES:
			_padding_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _padding_properties

def print_properties(attributes):
	_print_properties = ''
	for attribute in attributes:
		if attribute in PRINT_PROPERTIES:
			_print_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _print_properties

def table_properties(attributes):
	_table_properties = ''
	for attribute in attributes:
		if attribute in TABLE_PROPERTIES:
			_table_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _table_properties

def text_properties(attributes):
	_text_properties = ''
	for attribute in attributes:
		if attribute in TEXT_PROPERTIES:
			_text_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _text_properties

def transform_properties(attributes):
	_transform_properties = ''
	for attribute in attributes:
		if attribute in TRANSFORM_PROPERTIES:
			_transform_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _transform_properties

def transitions_properties(attributes):
	_transitions_properties = ''
	for attribute in attributes:
		if attribute in TRANSITIONS_PROPERTIES:
			_transitions_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _transitions_properties

def visual_formatting_properties(attributes):
	_visual_formatting_properties = ''
	for attribute in attributes:
		if attribute in VISUAL_FORMATTING_PROPERTIES:
			_visual_formatting_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return _visual_formatting_properties

#------------#
# BLOCOS CSS #
#------------#

# Define um comentário
def comment(text=''):
	return '/* ' + ''.join(text) + '*/'

# Define um documento
def document(text=''):
	return ''.join(text)

# Define um bloco de declaração
def element(attributes={}, seletor=''):
	_element = seletor + '{'
	_element += animation_properties(attributes)
	_element += background_properties(attributes)
	_element += border_properties(attributes)
	_element += color_properties(attributes)
	_element += dimension_properties(attributes)
	_element += generated_content_properties(attributes)
	_element += flexible_box_layout(attributes)
	_element += font_properties(attributes)
	_element += list_properties(attributes)
	_element += margin_properties(attributes)
	_element += multi_column_layout_properties(attributes)
	_element += outline_properties(attributes)
	_element += padding_properties(attributes)
	_element += print_properties(attributes)
	_element += table_properties(attributes)
	_element += text_properties(attributes)
	_element += transform_properties(attributes)
	_element += transitions_properties(attributes)
	_element += visual_formatting_properties(attributes)
	return _element + '}'

# Define consultas de mídia para aplicar diferentes estilos em diferentes dispositivos
def media(attributes={}, text=''):
	_media = '@media'
	_media += media_types(attributes)
	_media += media_features(attributes)
	return _media + '{' + ''.join(text) + '}'
