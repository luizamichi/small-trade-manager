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
	animation_properties = ''
	for attribute in attributes:
		if attribute in ANIMATION_PROPERTIES:
			animation_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return animation_properties

def background_properties(attributes):
	background_properties = ''
	for attribute in attributes:
		if attribute in BACKGROUND_PROPERTIES:
			background_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return background_properties

def border_properties(attributes):
	border_properties = ''
	for attribute in attributes:
		if attribute in BORDER_PROPERTIES:
			border_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return border_properties

def color_properties(attributes):
	color_properties = ''
	for attribute in attributes:
		if attribute in COLOR_PROPERTIES:
			color_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return color_properties

def dimension_properties(attributes):
	dimension_properties = ''
	for attribute in attributes:
		if attribute in DIMENSION_PROPERTIES:
			dimension_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return dimension_properties

def generated_content_properties(attributes):
	generated_content_properties = ''
	for attribute in attributes:
		if attribute in GENERATED_CONTENT_PROPERTIES:
			generated_content_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return generated_content_properties

def flexible_box_layout(attributes):
	flexible_box_layout = ''
	for attribute in attributes:
		if attribute in FLEXIBLE_BOX_LAYOUT:
			flexible_box_layout += attribute.lower() + ':' + attributes[attribute] + ';'
	return flexible_box_layout

def font_properties(attributes):
	font_properties = ''
	for attribute in attributes:
		if attribute in FONT_PROPERTIES:
			font_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return font_properties

def list_properties(attributes):
	list_properties = ''
	for attribute in attributes:
		if attribute in LIST_PROPERTIES:
			list_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return list_properties

def margin_properties(attributes):
	margin_properties = ''
	for attribute in attributes:
		if attribute in MARGIN_PROPERTIES:
			margin_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return margin_properties

def media_features(attributes):
	media_features = ''
	for attribute in attributes:
		if attribute in MEDIA_FEATURES:
			media_features += ' (' + attribute.lower() + ':' + attributes[attribute] + ')'
	return media_features

def media_types(attributes):
	media_types = ''
	for attribute in attributes:
		if attribute in MEDIA_TYPES:
			media_types += ' ' + attribute.lower() + ' and'
	return media_types

def multi_column_layout_properties(attributes):
	multi_column_layout_properties = ''
	for attribute in attributes:
		if attribute in MULTI_COLUMN_LAYOUT_PROPERTIES:
			multi_column_layout_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return multi_column_layout_properties

def outline_properties(attributes):
	outline_properties = ''
	for attribute in attributes:
		if attribute in OUTLINE_PROPERTIES:
			outline_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return outline_properties

def padding_properties(attributes):
	padding_properties = ''
	for attribute in attributes:
		if attribute in PADDING_PROPERTIES:
			padding_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return padding_properties

def print_properties(attributes):
	print_properties = ''
	for attribute in attributes:
		if attribute in PRINT_PROPERTIES:
			print_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return print_properties

def table_properties(attributes):
	table_properties = ''
	for attribute in attributes:
		if attribute in TABLE_PROPERTIES:
			table_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return table_properties

def text_properties(attributes):
	text_properties = ''
	for attribute in attributes:
		if attribute in TEXT_PROPERTIES:
			text_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return text_properties

def transform_properties(attributes):
	transform_properties = ''
	for attribute in attributes:
		if attribute in TRANSFORM_PROPERTIES:
			transform_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return transform_properties

def transitions_properties(attributes):
	transitions_properties = ''
	for attribute in attributes:
		if attribute in TRANSITIONS_PROPERTIES:
			transitions_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return transitions_properties

def visual_formatting_properties(attributes):
	visual_formatting_properties = ''
	for attribute in attributes:
		if attribute in VISUAL_FORMATTING_PROPERTIES:
			visual_formatting_properties += attribute.lower() + ':' + attributes[attribute] + ';'
	return visual_formatting_properties

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
	element = seletor + '{'
	element += animation_properties(attributes)
	element += background_properties(attributes)
	element += border_properties(attributes)
	element += color_properties(attributes)
	element += dimension_properties(attributes)
	element += generated_content_properties(attributes)
	element += flexible_box_layout(attributes)
	element += font_properties(attributes)
	element += list_properties(attributes)
	element += margin_properties(attributes)
	element += multi_column_layout_properties(attributes)
	element += outline_properties(attributes)
	element += padding_properties(attributes)
	element += print_properties(attributes)
	element += table_properties(attributes)
	element += text_properties(attributes)
	element += transform_properties(attributes)
	element += transitions_properties(attributes)
	element += visual_formatting_properties(attributes)
	return element + '}'

# Define consultas de mídia para aplicar diferentes estilos em diferentes dispositivos
def media(attributes={}, text=''):
	media = '@media'
	media += media_types(attributes)
	media += media_features(attributes)
	return media + '{' + ''.join(text) + '}'
