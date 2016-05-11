#------------#
# CONSTANTES #
#------------#

EVENT_ATTRIBUTES = ['onabort', 'onafterprint', 'onbeforeprint', 'onbeforeunload', 'onblur', 'oncanplay', 'oncanplaythrough', 'onchange', 'onclick', 'oncontextmenu', 'oncopy', 'oncuechange', 'oncut', 'ondblclick', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'ondurationchange', 'onemptied', 'onended', 'onerror', 'onerror', 'onfocus', 'onhashchange', 'oninput', 'oninvalid', 'onkeydown', 'onkeypress', 'onkeyup', 'onload', 'onloadeddata', 'onloadedmetadata', 'onloadstart', 'onmessage', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onoffline', 'ononline', 'onpagehide', 'onpageshow', 'onpaste', 'onpause', 'onplay', 'onplaying', 'onpopstate', 'onprogress', 'onratechange', 'onreset', 'onresize', 'onscroll', 'onsearch', 'onseeked', 'onseeking', 'onselect', 'onshow', 'onstalled', 'onstorage', 'onsubmit', 'onsuspend', 'ontimeupdate', 'ontoggle', 'onunload', 'onvolumechange', 'onwaiting', 'onwheel']

GLOBAL_ATTRIBUTES = ['accesskey', 'class', 'contenteditable', 'contextmenu', 'data-*', 'dir', 'draggable', 'dropzone', 'hidden', 'id', 'lang', 'spellcheck', 'style', 'title', 'translate', 'xml:lang']

#-----------------#
# FUNÇÕES GLOBAIS #
#-----------------#

def event_attributes(**attributes):
	_event_attributes = ''
	for attribute in attributes:
		clean_attribute = attribute[1:] if attribute.startswith('_') else attribute
		if clean_attribute.lower() in EVENT_ATTRIBUTES:
			_event_attributes += (' ' + clean_attribute.lower() + '="' + attributes[attribute] + '"' if not isinstance(attributes[attribute], bool) else ' ' + attribute)
	return _event_attributes

def global_attributes(**attributes):
	_global_attributes = ''
	for attribute in attributes:
		clean_attribute = attribute[1:] if attribute.startswith('_') else attribute
		if clean_attribute.lower() in GLOBAL_ATTRIBUTES:
			_global_attributes += (' ' + clean_attribute.lower() + '="' + attributes[attribute] + '"' if not isinstance(attributes[attribute], bool) else ' ' + attribute)
	return _global_attributes

#-----------#
# TAGS HTML #
#-----------#

# Define um link
def a(download=None, href=None, hreflang=None, media=None, ping=None, referrerpolicy=None, rel=None, target=None, text='', _type=None, **attributes):
	_a = '<a'
	_a += (' download="' + download + '"' if download else '')
	_a += (' href="' + href + '"' if href else '')
	_a += (' hreflang="' + hreflang + '"' if hreflang else '')
	_a += (' media="' + media + '"' if media else '')
	_a += (' ping="' + ping + '"' if ping else '')
	_a += (' referrerpolicy="' + referrerpolicy + '"' if referrerpolicy else '')
	_a += (' rel="' + rel + '"' if rel else '')
	_a += (' target="' + target + '"' if target else '')
	_a += (' type="' + _type + '"' if _type else '')
	_a += global_attributes(**attributes)
	_a += event_attributes(**attributes)
	return _a + '>' + ''.join(text) + '</a>'

# Define uma abreviação
def abbr(text='', **attributes):
	_abbr = '<abbr'
	_abbr += global_attributes(**attributes)
	_abbr += event_attributes(**attributes)
	return _abbr + '>' + text + '</abbr>'

# Define um endereço
def address(text='', **attributes):
	_address = '<address'
	_address += global_attributes(**attributes)
	_address += event_attributes(**attributes)
	return _address + '>' + ''.join(text) + '</address>'

# Define uma área dentro de um mapa de imagem
def area(alt='', coords=None, download=None, href=None, hreflang=None, media=None, rel=None, shape=None, target=None, _type=None, **attributes):
	_area = '<area'
	_area += ' alt="' + alt + '"'
	_area += (' coords="' + coords + '"' if coords else '')
	_area += (' download="' + download + '"' if download else '')
	_area += (' href="' + href + '"' if href else '')
	_area += (' hreflang="' + hreflang + '"' if hreflang else '')
	_area += (' media="' + media + '"' if media else '')
	_area += (' rel="' + rel + '"' if rel else '')
	_area += (' shape="' + shape + '"' if shape else '')
	_area += (' target="' + target + '"' if target else '')
	_area += (' type="' + _type + '"' if _type else '')
	_area += global_attributes(**attributes)
	_area += event_attributes(**attributes)
	return _area + '/>'

# Define um artigo
def article(text='', **attributes):
	_article = '<article'
	_article += global_attributes(**attributes)
	_article += event_attributes(**attributes)
	return _article + '>' + ''.join(text) + '</article>'

# Define o conteúdo além do conteúdo da página
def aside(text='', **attributes):
	_aside = '<aside'
	_aside += global_attributes(**attributes)
	_aside += event_attributes(**attributes)
	return _aside + '>' + ''.join(text) + '</aside>'

# Define o conteúdo de som
def audio(autoplay=False, controls=False, loop=False, muted=False, preload=None, src=None, text='', **attributes):
	_audio = '<audio'
	_audio += (' autoplay' if autoplay else '')
	_audio += (' controls' if controls else '')
	_audio += (' loop' if loop else '')
	_audio += (' muted' if muted else '')
	_audio += (' preload="' + preload + '"' if preload else '')
	_audio += (' src="' + src + '"' if src else '')
	_audio += global_attributes(**attributes)
	_audio += event_attributes(**attributes)
	return _audio + '>' + ''.join(text) + '</audio>'

# Define um texto em negrito
def b(text='', **attributes):
	_b = '<b'
	_b += global_attributes(**attributes)
	_b += event_attributes(**attributes)
	return _b + '>' + text + '</b>'

# Define uma URL base para todos os links da página
def base(href='', target='', **attributes):
	_base = '<base'
	_base += ' href="' + href + '"'
	_base += ' target="' + target + '"'
	_base += global_attributes(**attributes)
	_base += event_attributes(**attributes)
	return _base + '/>'

# Define um isolamento bidirecional
def bdi(text='', **attributes):
	_bdi = '<bdi'
	_bdi += global_attributes(**attributes)
	_bdi += event_attributes(**attributes)
	return _bdi + '>' + text + '</bdi>'

# Define a direção do texto apresentado
def bdo(_dir=None, text='', **attributes):
	_bdo = '<bdo'
	_bdo += (' dir="' + _dir + '"' if _dir else '')
	_bdo += global_attributes(**attributes)
	_bdo += event_attributes(**attributes)
	return _bdo + '>' + ''.join(text) + '</bdo>'

# Define uma citação longa
def blockquote(cite=None, text='', **attributes):
	_blockquote = '<blockquote'
	_blockquote += (' cite="' + cite + '"' if cite else '')
	_blockquote += global_attributes(**attributes)
	_blockquote += event_attributes(**attributes)
	return _blockquote + '>' + ''.join(text) + '</blockquote>'

# Define o corpo da página
def body(text='', **attributes):
	_body = '<body'
	_body += global_attributes(**attributes)
	_body += event_attributes(**attributes)
	return _body + '>' + ''.join(text) + '</body>'

# Insere uma única quebra de linha
def br(**attributes):
	_br = '<br'
	_br += global_attributes(**attributes)
	_br += event_attributes(**attributes)
	return _br + '/>'

# Define um botão de comando
def button(autofocus=False, disabled=False, form=None, formaction=None, formenctype=None, formnovalidate=False, formtarget=None, name=None, text='', _type=None, value=None, **attributes):
	_button = '<button'
	_button += (' autofocus' if autofocus else '')
	_button += (' disabled' if disabled else '')
	_button += (' form="' + form + '"' if form else '')
	_button += (' formaction="' + formaction + '"' if formaction else '')
	_button += (' formenctype="' + formenctype + '"' if formenctype else '')
	_button += (' formnovalidate' if formnovalidate else '')
	_button += (' formtarget="' + formtarget + '"' if formtarget else '')
	_button += (' name="' + name + '"' if name else '')
	_button += (' type="' + _type + '"' if _type else '')
	_button += (' value="' + value + '"' if value else '')
	_button += global_attributes(**attributes)
	_button += event_attributes(**attributes)
	return _button + '>' + ''.join(text) + '</button>'

# Define gráficos
def canvas(height=None, text='', width=None, **attributes):
	_canvas = '<canvas'
	_canvas += (' height="' + height + '"' if height else '')
	_canvas += (' width="' + width + '"' if width else '')
	_canvas += global_attributes(**attributes)
	_canvas += event_attributes(**attributes)
	return _canvas + '>' + ''.join(text) + '</canvas>'

# Define o título/descrição de uma tabela
def caption(text='', **attributes):
	_caption = '<caption'
	_caption += global_attributes(**attributes)
	_caption += event_attributes(**attributes)
	return _caption + '>' + text + '</caption>'

# Define uma citação
def cite(text='', **attributes):
	_cite = '<cite'
	_cite += global_attributes(**attributes)
	_cite += event_attributes(**attributes)
	return _cite + '>' + text + '</cite>'

# Define o código texto do computador
def code(text='', **attributes):
	_code = '<code'
	_code += global_attributes(**attributes)
	_code += event_attributes(**attributes)
	return _code + '>' + ''.join(text) + '</code>'

# Define os atributos da coluna da tabela
def col(span=None, **attributes):
	_col = '<col'
	_col += (' span="' + span + '"' if span else '')
	_col += global_attributes(**attributes)
	_col += event_attributes(**attributes)
	return _col + '/>'

# Define um grupo de colunas da tabela
def colgroup(span=None, text='', **attributes):
	_colgroup = '<colgroup'
	_colgroup += (' span="' + span + '"' if span else '')
	_colgroup += global_attributes(**attributes)
	_colgroup += event_attributes(**attributes)
	return _colgroup + '>' + ''.join(text) + '</colgroup>'

# Define um comentário
def comment(text=''):
	return '<!--' + ''.join(text) + '-->'

# Define uma lista suspensa
def datalist(text='', **attributes):
	_datalist = '<datalist'
	_datalist += global_attributes(**attributes)
	_datalist += event_attributes(**attributes)
	return _datalist + '>' + ''.join(text) + '</datalist>'

# Define uma descrição de definição
def dd(text='', **attributes):
	_dd = '<dd'
	_dd += global_attributes(**attributes)
	_dd += event_attributes(**attributes)
	return _dd + '>' + text + '</dd>'

# Define um texto deletado
def _del(cite=None, datetime=None, text='', **attributes):
	__del = '<del'
	__del += (' cite="' + cite + '"' if cite else '')
	__del += (' datetime="' + datetime + '"' if datetime else '')
	__del += global_attributes(**attributes)
	__del += event_attributes(**attributes)
	return __del + '>' + ''.join(text) + '</del>'

# Define detalhes de um elemento
def details(_open=False, text='', **attributes):
	_details = '<details'
	_details += (' open' if _open else '')
	_details += global_attributes(**attributes)
	_details += event_attributes(**attributes)
	return _details + '>' + ''.join(text) + '</details>'

# Define um termo de definição
def dfn(text='', **attributes):
	_dfn = '<dfn'
	_dfn += global_attributes(**attributes)
	_dfn += event_attributes(**attributes)
	return _dfn + '>' + text + '</dfn>'

# Define uma conversa ou pessoas falando
def dialog(_open=False, text='', **attributes):
	_dialog = '<dialog'
	_dialog += (' open' if _open else '')
	_dialog += global_attributes(**attributes)
	_dialog += event_attributes(**attributes)
	return _dialog + '>' + ''.join(text) + '</dialog>'

# Define uma seção no documento
def div(text='', **attributes):
	_div = '<div'
	_div += global_attributes(**attributes)
	_div += event_attributes(**attributes)
	return _div + '>' + ''.join(text) + '</div>'

# Define o tipo de documento
def doctype():
	return '<!DOCTYPE html>'

# Define um documento
def document(text=''):
	return ''.join(text)

# Define uma lista de definição
def dl(text='', **attributes):
	_dl = '<dl'
	_dl += global_attributes(**attributes)
	_dl += event_attributes(**attributes)
	return _dl + '>' + ''.join(text) + '</dl>'

# Define um termo de definição
def dt(text='', **attributes):
	_dt = '<dt'
	_dt += global_attributes(**attributes)
	_dt += event_attributes(**attributes)
	return _dt + '>' + text + '</dt>'

# Define um texto em ênfase
def em(text='', **attributes):
	_em = '<em'
	_em += global_attributes(**attributes)
	return _em + '>' + text + '</em>'

# Define o conteúdo interativo ou plugin externo
def embed(height=None, src=None, _type=None, width=None, **attributes):
	_embed = '<embed'
	_embed += (' height="' + height + '"' if height else '')
	_embed += (' src="' + src + '"' if src else '')
	_embed += (' type="' + _type + '"' if _type else '')
	_embed += (' width="' + width + '"' if width else '')
	_embed += global_attributes(**attributes)
	_embed += event_attributes(**attributes)
	return _embed + '/>'

# Define um conjunto de campos
def fieldset(disabled=False, form=None, name=None, text='', **attributes):
	_fieldset = '<fieldset'
	_fieldset += (' disabled' if disabled else '')
	_fieldset += (' form="' + form + '"' if form else '')
	_fieldset += (' name="' + name + '"' if name else '')
	_fieldset += global_attributes(**attributes)
	_fieldset += event_attributes(**attributes)
	return _fieldset + '>' + ''.join(text) + '</fieldset>'

# Define o título/legenda de uma imagem
def figcaption(text='', **attributes):
	_figcaption = '<figcaption'
	_figcaption += global_attributes(**attributes)
	_figcaption += event_attributes(**attributes)
	return _figcaption + '>' + text + '</figcaption>'

# Define um grupo de mídia
def figure(text='', **attributes):
	_figure = '<figure'
	_figure += global_attributes(**attributes)
	_figure += event_attributes(**attributes)
	return _figure + '>' + ''.join(text) + '</figure>'

# Define o rodapé de uma página
def footer(text='', **attributes):
	_footer = '<footer'
	_footer += global_attributes(**attributes)
	_footer += event_attributes(**attributes)
	return _footer + '>' + ''.join(text) + '</footer>'

# Define um formulário
def form(accept_charset=None, action=None, autocomplete=None, enctype=None, method=None, name=None, novalidate=False, rel=None, target=None, text='', **attributes):
	_form = '<form'
	_form += (' accept-charset="' + accept_charset + '"' if accept_charset else '')
	_form += (' action="' + action + '"' if action else '')
	_form += (' autocomplete="' + autocomplete + '"' if autocomplete else '')
	_form += (' enctype="' + enctype + '"' if enctype else '')
	_form += (' method="' + method + '"' if method else '')
	_form += (' name="' + name + '"' if name else '')
	_form += (' novalidate' if novalidate else '')
	_form += (' rel="' + rel + '"' if rel else '')
	_form += (' target="' + target + '"' if target else '')
	_form += global_attributes(**attributes)
	_form += event_attributes(**attributes)
	return _form + '>' + ''.join(text) + '</form>'

# Define o cabeçalho 1
def h1(text='', **attributes):
	_h1 = '<h1'
	_h1 += global_attributes(**attributes)
	_h1 += event_attributes(**attributes)
	return _h1 + '>' + text + '</h1>'

# Define o cabeçalho 2
def h2(text='', **attributes):
	_h2 = '<h2'
	_h2 += global_attributes(**attributes)
	_h2 += event_attributes(**attributes)
	return _h2 + '>' + text + '</h2>'

# Define o cabeçalho 3
def h3(text='', **attributes):
	_h3 = '<h3'
	_h3 += global_attributes(**attributes)
	_h3 += event_attributes(**attributes)
	return _h3 + '>' + text + '</h3>'

# Define o cabeçalho 4
def h4(text='', **attributes):
	_h4 = '<h4'
	_h4 += global_attributes(**attributes)
	_h4 += event_attributes(**attributes)
	return _h4 + '>' + text + '</h4>'

# Define o cabeçalho 5
def h5(text='', **attributes):
	_h5 = '<h5'
	_h5 += global_attributes(**attributes)
	_h5 += event_attributes(**attributes)
	return _h5 + '>' + text + '</h5>'

# Define o cabeçalho 6
def h6(text='', **attributes):
	_h6 = '<h6'
	_h6 += global_attributes(**attributes)
	_h6 += event_attributes(**attributes)
	return _h6 + '>' + text + '</h6>'

# Define uma informação sobre o documento
def head(text='', **attributes):
	_head = '<head'
	_head += global_attributes(**attributes)
	return _head + '>' + ''.join(text) + '</head>'

# Define o cabeçalho de uma página
def header(text='', **attributes):
	_header = '<header'
	_header += global_attributes(**attributes)
	_header += event_attributes(**attributes)
	return _header + '>' + ''.join(text) + '</header>'

# Define uma regra horizontal
def hr(**attributes):
	_hr = '<hr'
	_hr += global_attributes(**attributes)
	_hr += event_attributes(**attributes)
	return _hr + '/>'

# Define um documento HTML
def html(text='', xmlns=None, **attributes):
	_html = '<html'
	_html += (' xmlns="' + xmlns + '"' if xmlns else '')
	_html += global_attributes(**attributes)
	return _html + '>' + ''.join(text) + '</html>'

# Define um texto em itálico
def i(text='', **attributes):
	_i = '<i'
	_i += global_attributes(**attributes)
	_i += event_attributes(**attributes)
	return _i + '>' + text + '</i>'

# Define uma linha sobre a janela
def iframe(allowfullscreen=False, height=None, name=None, sandbox=None, src=None, srcdoc=None, text='', width=None, **attributes):
	_iframe = '<iframe'
	_iframe += (' allowfullscreen' if allowfullscreen else '')
	_iframe += (' height="' + height + '"' if height else '')
	_iframe += (' name="' + name + '"' if name else '')
	_iframe += (' sandbox="' + sandbox + '"' if sandbox else '')
	_iframe += (' src="' + src + '"' if src else '')
	_iframe += (' srcdoc="' + srcdoc + '"' if srcdoc else '')
	_iframe += (' width="' + width + '"' if width else '')
	_iframe += global_attributes(**attributes)
	_iframe += event_attributes(**attributes)
	return _iframe + '>' + text + '</iframe>'

# Define uma imagem
def img(alt='', crossorigin=None, height=None, ismap=False, longdesc=None, referrerpolicy=None, sizes=None, src='', srcset=None, usemap=None, width=None, **attributes):
	_img = '<img'
	_img += ' alt="' + alt + '"'
	_img += (' crossorigin="' + crossorigin + '"' if crossorigin else '')
	_img += (' height="' + height + '"' if height else '')
	_img += (' ismap' if ismap else '')
	_img += (' longdesc="' + longdesc + '"' if longdesc else '')
	_img += (' referrerpolicy="' + referrerpolicy + '"' if referrerpolicy else '')
	_img += (' sizes="' + sizes + '"' if sizes else '')
	_img += ' src="' + src + '"'
	_img += (' srcset="' + srcset + '"' if srcset else '')
	_img += (' usemap="' + usemap + '"' if usemap else '')
	_img += (' width="' + width + '"' if width else '')
	_img += global_attributes(**attributes)
	_img += event_attributes(**attributes)
	return _img + '/>'

# Define um campo de inserção
def _input(accept=None, alt=None, autocomplete=None, autofocus=False, checked=False, dirname=None, disabled=False, form=None, formaction=None, formenctype=None, formmethod=None, formnovalidate=None, formtarget=None, height=None, _list=None, _max=None, maxlength=None, _min=None, minlength=None, multiple=False, name=None, pattern=None, placeholder=None, readonly=False, required=False, size=None, src=None, step=None, _type=None, value=None, width=None, **attributes):
	__input = '<input'
	__input += (' accept="' + accept + '"' if accept else '')
	__input += (' alt="' + alt + '"' if alt else '')
	__input += (' autocomplete="' + autocomplete + '"' if autocomplete else '')
	__input += (' autofocus' if autofocus else '')
	__input += (' checked' if checked else '')
	__input += (' dirname="' + dirname + '"' if dirname else '')
	__input += (' disabled' if disabled else '')
	__input += (' form="' + form + '"' if form else '')
	__input += (' formaction="' + formaction + '"' if formaction else '')
	__input += (' formenctype="' + formenctype + '"' if formenctype else '')
	__input += (' formmethod="' + formmethod + '"' if formmethod else '')
	__input += (' formnovalidate="' + formnovalidate + '"' if formnovalidate else '')
	__input += (' formtarget="' + formtarget + '"' if formtarget else '')
	__input += (' height="' + height + '"' if height else '')
	__input += (' list="' + _list + '"' if _list else '')
	__input += (' max="' + _max + '"' if _max else '')
	__input += (' maxlength="' + maxlength + '"' if maxlength else '')
	__input += (' min="' + _min + '"' if _min else '')
	__input += (' minlength="' + minlength + '"' if minlength else '')
	__input += (' multiple' if multiple else '')
	__input += (' name="' + name + '"' if name else '')
	__input += (' pattern="' + pattern + '"' if pattern else '')
	__input += (' placeholder="' + placeholder + '"' if placeholder else '')
	__input += (' readonly' if readonly else '')
	__input += (' required' if required else '')
	__input += (' size="' + size + '"' if size else '')
	__input += (' src="' + src + '"' if src else '')
	__input += (' step="' + step + '"' if step else '')
	__input += (' type="' + _type + '"' if _type else '')
	__input += (' value="' + value + '"' if value else '')
	__input += (' width="' + width + '"' if width else '')
	__input += global_attributes(**attributes)
	__input += event_attributes(**attributes)
	return __input + '/>'

# Define um texto inserido
def ins(cite=None, datetime=None, text='', **attributes):
	_ins = '<ins'
	_ins += (' cite="' + cite + '"' if cite else '')
	_ins += (' datetime="' + datetime + '"' if datetime else '')
	_ins += global_attributes(**attributes)
	_ins += event_attributes(**attributes)
	return _ins + '>' + text + '</ins>'

# Define um texto do teclado
def kbd(text='', **attributes):
	_kbd = '<kbd'
	_kbd += global_attributes(**attributes)
	_kbd += event_attributes(**attributes)
	return _kbd + '>' + text + '</kbd>'

# Define uma legenda/rótulo para um elemento do formulário
def label(_for=None, form=None, text='', **attributes):
	_label = '<label'
	_label += (' for="' + _for + '"' if _for else '')
	_label += (' form="' + form + '"' if form else '')
	_label += global_attributes(**attributes)
	_label += event_attributes(**attributes)
	return _label + '>' + text + '</label>'

# Define um título para os campos (fields)
def legend(text='', **attributes):
	_legend = '<legend'
	_legend += global_attributes(**attributes)
	_legend += event_attributes(**attributes)
	return _legend + '>' + text + '</legend>'

# Define os itens da lista
def li(text='', value=None, **attributes):
	_li = '<li'
	_li += (' value="' + value + '"' if value else '')
	_li += global_attributes(**attributes)
	_li += event_attributes(**attributes)
	return _li + '>' + ''.join(text) + '</li>'

# Define uma referência
def link(crossorigin=None, href=None, hreflang=None, media=None, referrerpolicy=None, rel=None, sizes=None, _type=None, **attributes):
	_link = '<link'
	_link += (' crossorigin="' + crossorigin + '"' if crossorigin else '')
	_link += (' href="' + href + '"' if href else '')
	_link += (' hreflang="' + hreflang + '"' if hreflang else '')
	_link += (' media="' + media + '"' if media else '')
	_link += (' referrerpolicy="' + referrerpolicy + '"' if referrerpolicy else '')
	_link += (' rel="' + rel + '"' if rel else '')
	_link += (' sizes="' + sizes + '"' if sizes else '')
	_link += (' type="' + _type + '"' if _type else '')
	_link += global_attributes(**attributes)
	_link += event_attributes(**attributes)
	return _link + '/>'

# Especifica o conteúdo principal de um documento
def main(text='', **attributes):
	_main = '<main'
	_main += global_attributes(**attributes)
	_main += event_attributes(**attributes)
	return _main + '>' + ''.join(text) + '</main>'

# Define uma imagem de mapa
def _map(name=None, text='', **attributes):
	__map = '<map'
	__map += (' name="' + name + '"' if name else '')
	__map += global_attributes(**attributes)
	__map += event_attributes(**attributes)
	return __map + '>' + ''.join(text) + '</map>'

# Define a marcação de um texto
def mark(text='', **attributes):
	_mark = '<mark'
	_mark += global_attributes(**attributes)
	_mark += event_attributes(**attributes)
	return _mark + '>' + text + '</mark>'

# Define informações meta
def meta(charset=None, content=None, http_equiv=None, itemprop=None, name=None, _property=None, **attributes):
	_meta = '<meta'
	_meta += (' charset="' + charset + '"' if charset else '')
	_meta += (' content="' + content + '"' if content else '')
	_meta += (' http-equiv="' + http_equiv + '"' if http_equiv else '')
	_meta += (' itemprop="' + itemprop + '"' if itemprop else '')
	_meta += (' name="' + name + '"' if name else '')
	_meta += (' property="' + _property + '"' if _property else '')
	_meta += global_attributes(**attributes)
	return _meta + '/>'

# Define a medição dentro de um intervalo pré-definido
def meter(form=None, high=None, low=None, _max=None, _min=None, optimum=None, text='', value='', **attributes):
	_meter = '<meter'
	_meter += (' form="' + form + '"' if form else '')
	_meter += (' high="' + high + '"' if high else '')
	_meter += (' low="' + low + '"' if low else '')
	_meter += (' max="' + _max + '"' if _max else '')
	_meter += (' min="' + _min + '"' if _min else '')
	_meter += (' optimum="' + optimum + '"' if optimum else '')
	_meter += ' value="' + value + '"'
	_meter += global_attributes(**attributes)
	_meter += event_attributes(**attributes)
	return _meter + '>' + text + '</meter>'

# Define os links de navegação
def nav(text='', **attributes):
	_nav = '<nav'
	_nav += global_attributes(**attributes)
	_nav += event_attributes(**attributes)
	return _nav + '>' + ''.join(text) + '</nav>'

# Define uma seção alternativa para exibição quando o JavaScript estiver desativado
def noscript(text='', **attributes):
	_noscript = '<noscript'
	_noscript += global_attributes(**attributes)
	return _noscript + '>' + text + '</noscript>'

# Define um objeto incorporado
def _object(data='', height=None, name=None, text='', _type='', usemap=None, width=None, **attributes):
	__object = '<object'
	__object += ' data="' + data + '"'
	__object += (' height="' + height + '"' if height else '')
	__object += (' name="' + name + '"' if name else '')
	__object += ' type="' + _type + '"'
	__object += (' usemap="' + usemap + '"' if usemap else '')
	__object += (' width="' + width + '"' if width else '')
	__object += global_attributes(**attributes)
	__object += event_attributes(**attributes)
	return __object + '>' + text + '</object>'

# Define uma lista ordenada
def ol(_reversed=False, start='', text='', _type='', **attributes):
	_ol = '<ol'
	_ol += (' reversed' if _reversed else '')
	_ol += ' start="' + start + '"'
	_ol += ' type="' + _type + '"'
	_ol += global_attributes(**attributes)
	_ol += event_attributes(**attributes)
	return _ol + '>' + text + '</ol>'

# Define um grupo de opção
def optgroup(disabled=False, label='', text='', **attributes):
	_optgroup = '<optgroup'
	_optgroup += (' disabled' if disabled else '')
	_optgroup += ' label="' + label + '"'
	_optgroup += global_attributes(**attributes)
	_optgroup += event_attributes(**attributes)
	return _optgroup + '>' + text + '</optgroup>'

# Define uma opção em uma lista suspensa
def option(disabled=False, label=None, selected=False, text='', value=None, **attributes):
	_option = '<option'
	_option += (' disabled' if disabled else '')
	_option += (' label="' + label + '"' if label else '')
	_option += (' selected' if selected else '')
	_option += (' value="' + value + '"' if value else '')
	_option += global_attributes(**attributes)
	_option += event_attributes(**attributes)
	return _option + '>' + text + '</option>'

# Define os tipos de saída (outputs)
def output(_for=None, form=None, name=None, text='', **attributes):
	_output = '<output'
	_output += (' for="' + _for + '"' if _for else '')
	_output += (' form="' + form + '"' if form else '')
	_output += (' name="' + name + '"' if name else '')
	_output += global_attributes(**attributes)
	_output += event_attributes(**attributes)
	return _output + '>' + text + '</output>'

# Define um parágrafo
def p(text='', **attributes):
	_p = '<p'
	_p += global_attributes(**attributes)
	_p += event_attributes(**attributes)
	return _p + '>' + ''.join(text) + '</p>'

# Define um parâmetro para determinado objeto
def param(name='', value=None, **attributes):
	_param = '<param'
	_param += ' name="' + name + '"'
	_param += (' value="' + value + '"' if value else '')
	_param += global_attributes(**attributes)
	_param += event_attributes(**attributes)
	return _param + '/>'

# Fornece mais flexibilidade na especificação de recursos de imagem
def picture(text='', **attributes):
	_picture = '<picture'
	_picture += global_attributes(**attributes)
	_picture += event_attributes(**attributes)
	return _picture + '>' + ''.join(text) + '</picture>'

# Define um texto pré-formatado
def pre(text='', **attributes):
	_pre = '<pre'
	_pre += global_attributes(**attributes)
	_pre += event_attributes(**attributes)
	return _pre + '>' + text + '</pre>'

# Define o progresso de uma tarefa qualquer
def progress(_max=None, text='', value=None, **attributes):
	_progress = '<progress'
	_progress += (' max="' + _max + '"' if _max else '')
	_progress += (' value="' + value + '"' if value else '')
	_progress += global_attributes(**attributes)
	_progress += event_attributes(**attributes)
	return _progress + '>' + text + '</progress>'

# Define uma citação curta
def q(cite=None, text='', **attributes):
	_q = '<q'
	_q += (' cite="' + cite + '"' if cite else '')
	_q += global_attributes(**attributes)
	_q += event_attributes(**attributes)
	return _q + '>' + text + '</q>'

# Define o browser substituto para elementos não suportados pelo Ruby
def rp(text='', **attributes):
	_rp = '<rp'
	_rp += global_attributes(**attributes)
	_rp += event_attributes(**attributes)
	return _rp + '>' + text + '</rp>'

# Define explicações para as anotações de Ruby
def rt(text='', **attributes):
	_rt = '<rt'
	_rt += global_attributes(**attributes)
	_rt += event_attributes(**attributes)
	return _rt + '>' + text + '</rt>'

# Define as anotações de Ruby
def ruby(text='', **attributes):
	_ruby = '<ruby'
	_ruby += global_attributes(**attributes)
	_ruby += event_attributes(**attributes)
	return _ruby + '>' + ''.join(text) + '</ruby>'

# Define um texto que não é mais correto
def s(text='', **attributes):
	_s = '<s'
	_s += global_attributes(**attributes)
	_s += event_attributes(**attributes)
	return _s + '>' + text + '</s>'

# Define um código de amostra
def samp(text='', **attributes):
	_samp = '<samp'
	_samp += global_attributes(**attributes)
	_samp += event_attributes(**attributes)
	return _samp + '>' + text + '</samp>'

# Define um script
def script(_async=False, charset=None, defer=False, src=None, text='', _type=None, **attributes):
	_script = '<script'
	_script += (' async' if _async else '')
	_script += (' charset="' + charset + '"' if charset else '')
	_script += (' defer' if defer else '')
	_script += (' src="' + src + '"' if src else '')
	_script += (' type="' + _type + '"' if _type else '')
	_script += global_attributes(**attributes)
	return _script + '>' + ''.join(text) + '</script>'

# Define uma área ou seção
def section(text='', **attributes):
	_section = '<section'
	_section += global_attributes(**attributes)
	_section += event_attributes(**attributes)
	return _section + '>' + ''.join(text) + '</section>'

# Define uma lista selecionável
def select(autofocus=False, disabled=False, form=None, multiple=False, name=None, required=False, size=None, text='', **attributes):
	_select = '<select'
	_select += (' autofocus' if autofocus else '')
	_select += (' disabled' if disabled else '')
	_select += (' form="' + form + '"' if form else '')
	_select += (' multiple' if multiple else '')
	_select += (' name="' + name + '"' if name else '')
	_select += (' required' if required else '')
	_select += (' size="' + size + '"' if size else '')
	_select += global_attributes(**attributes)
	_select += event_attributes(**attributes)
	return _select + '>' + ''.join(text) + '</select>'

# Define um pequeno texto
def small(text='', **attributes):
	_small = '<small'
	_small += global_attributes(**attributes)
	_small += event_attributes(**attributes)
	return _small + '>' + text + '</small>'

# Define recursos de mídia
def source(media=None, src='', srcset=None, _type=None, **attributes):
	_source = '<source'
	_source += (' media="' + media + '"' if media else '')
	_source += ' src="' + src + '"'
	_source += (' srcset="' + srcset + '"' if srcset else '')
	_source += (' type="' + _type + '"' if _type else '')
	_source += global_attributes(**attributes)
	_source += event_attributes(**attributes)
	return _source + '/>'

# Define uma seção no documento
def span(text='', **attributes):
	_span = '<span'
	_span += global_attributes(**attributes)
	_span += event_attributes(**attributes)
	return _span + '>' + text + '</span>'

# Define um texto forte (similar ao negrito)
def strong(text='', **attributes):
	_strong = '<strong'
	_strong += global_attributes(**attributes)
	_strong += event_attributes(**attributes)
	return _strong + '>' + text + '</strong>'

# Define um estilo
def style(media=None, text='', _type=None, **attributes):
	_style = '<style'
	_style += (' media="' + media + '"' if media else '')
	_style += (' type="' + _type + '"' if _type else '')
	_style += global_attributes(**attributes)
	_style += event_attributes(**attributes)
	return _style + '>' + ''.join(text) + '</style>'

# Define um texto subscrito
def sub(text='', **attributes):
	_sub = '<sub'
	_sub += global_attributes(**attributes)
	_sub += event_attributes(**attributes)
	return _sub + '>' + text + '</sub>'

# Define o cabeçalho de dados “detalhe”
def summary(text='', **attributes):
	_summary = '<summary'
	_summary += global_attributes(**attributes)
	_summary += event_attributes(**attributes)
	return _summary + '>' + text + '</summary>'

# Define um texto sobrescrito
def sup(text='', **attributes):
	_sup = '<sup'
	_sup += global_attributes(**attributes)
	_sup += event_attributes(**attributes)
	return _sup + '>' + text + '</sup>'

# Define um contêiner para gráficos SVG
def svg(height=None, text='', width=None):
	_svg = '<svg'
	_svg += (' height="' + height + '"' if height else '')
	_svg += (' width="' + width + '"' if width else '')
	return _svg + '>' + ''.join(text) + '</svg>'

# Define uma tabela
def table(text='', **attributes):
	_table = '<table'
	_table += global_attributes(**attributes)
	_table += event_attributes(**attributes)
	return _table + '>' + ''.join(text) + '</table>'

# Define o corpo da tabela
def tbody(text='', **attributes):
	_tbody = '<tbody'
	_tbody += global_attributes(**attributes)
	_tbody += event_attributes(**attributes)
	return _tbody + '>' + ''.join(text) + '</tbody>'

# Define uma célula da tabela
def td(colspan=None, headers=None, rowspan=None, text='', **attributes):
	_td = '<td'
	_td += (' colspan="' + colspan + '"' if colspan else '')
	_td += (' headers="' + headers + '"' if headers else '')
	_td += (' rowspan="' + rowspan + '"' if rowspan else '')
	_td += global_attributes(**attributes)
	_td += event_attributes(**attributes)
	return _td + '>' + text + '</td>'

# Define um conteúdo oculto
def template(text='', **attributes):
	_template = '<template'
	_template += global_attributes(**attributes)
	return _template + '>' + ''.join(text) + '</template>'

# Define uma área de texto
def textarea(autofocus=False, cols=None, dirname=None, disabled=False, form=None, maxlength=None, minlength=None, name=None, placeholder=None, readonly=False, required=False, rows=None, text='', wrap=None, **attributes):
	_textarea = '<textarea'
	_textarea += (' autofocus' if autofocus else '')
	_textarea += (' cols="' + cols + '"' if cols else '')
	_textarea += (' dirname="' + dirname + '"' if dirname else '')
	_textarea += (' disabled' if disabled else '')
	_textarea += (' form="' + form + '"' if form else '')
	_textarea += (' maxlength="' + maxlength + '"' if maxlength else '')
	_textarea += (' minlength="' + minlength + '"' if minlength else '')
	_textarea += (' name="' + name + '"' if name else '')
	_textarea += (' placeholder="' + placeholder + '"' if placeholder else '')
	_textarea += (' readonly' if readonly else '')
	_textarea += (' required' if required else '')
	_textarea += (' rows="' + rows + '"' if rows else '')
	_textarea += (' wrap="' + wrap + '"' if wrap else '')
	_textarea += global_attributes(**attributes)
	_textarea += event_attributes(**attributes)
	return _textarea + '>' + text + '</textarea>'

# Define o rodapé da tabela
def tfoot(text='', **attributes):
	_tfoot = '<tfoot'
	_tfoot += global_attributes(**attributes)
	_tfoot += event_attributes(**attributes)
	return _tfoot + '>' + ''.join(text) + '</tfoot>'

# Define o cabeçalho da tabela
def th(abbr=None, colspan=None, headers=None, rowspan=None, scope=None, text='', **attributes):
	_th = '<th'
	_th += (' abbr="' + abbr + '"' if abbr else '')
	_th += (' colspan="' + colspan + '"' if colspan else '')
	_th += (' headers="' + headers + '"' if headers else '')
	_th += (' rowspan="' + rowspan + '"' if rowspan else '')
	_th += (' scope="' + scope + '"' if scope else '')
	_th += global_attributes(**attributes)
	_th += event_attributes(**attributes)
	return _th + '>' + ''.join(text) + '</th>'

# Define o cabeçalho da tabela
def thead(text='', **attributes):
	_thead = '<thead'
	_thead += global_attributes(**attributes)
	_thead += event_attributes(**attributes)
	return _thead + '>' + ''.join(text) + '</thead>'

# Define uma data ou hora
def time(datetime=None, text='', **attributes):
	_time = '<time'
	_time += (' datetime="' + datetime + '"' if datetime else '')
	_time += global_attributes(**attributes)
	_time += event_attributes(**attributes)
	return _time + '>' + text + '</time>'

# Define o título do documento
def title(text='', **attributes):
	_title = '<title'
	_title += global_attributes(**attributes)
	return _title + '>' + text + '</title>'

# Define uma linha da tabela
def tr(text='', **attributes):
	_tr = '<tr'
	_tr += global_attributes(**attributes)
	_tr += event_attributes(**attributes)
	return _tr + '>' + ''.join(text) + '</tr>'

# Define faixas de texto para elementos de mídia
def track(default=False, kind=None, label=None, src='', srclang=None, **attributes):
	_track = '<track'
	_track += (' default' if default else '')
	_track += (' kind="' + kind + '"' if kind else '')
	_track += (' label="' + label + '"' if label else '')
	_track += ' src="' + src + '"'
	_track += (' srclang="' + srclang + '"' if srclang else '')
	_track += global_attributes(**attributes)
	_track += event_attributes(**attributes)
	return _track + '/>'

# Define sublinhado
def u(text='', **attributes):
	_u = '<u'
	_u += global_attributes(**attributes)
	_u += event_attributes(**attributes)
	return _u + '>' + text + '</u>'

# Define uma lista desordenada
def ul(text='', **attributes):
	_ul = '<ul'
	_ul += global_attributes(**attributes)
	_ul += event_attributes(**attributes)
	return _ul + '>' + ''.join(text) + '</ul>'

# Define uma variável
def var(text='', **attributes):
	_var = '<var'
	_var += global_attributes(**attributes)
	_var += event_attributes(**attributes)
	return _var + '>' + text + '</var>'

# Define um vídeo
def video(autoplay=False, controls=False, height=None, loop=False, muted=False, poster=None, preload=None, src=None, text='', width=None, **attributes):
	_video = '<video'
	_video += (' autoplay' if autoplay else '')
	_video += (' controls' if controls else '')
	_video += (' height="' + height + '"' if height else '')
	_video += (' loop' if loop else '')
	_video += (' muted' if muted else '')
	_video += (' poster="' + poster + '"' if poster else '')
	_video += (' preload="' + preload + '"' if preload else '')
	_video += (' src="' + src + '"' if src else '')
	_video += (' width="' + width + '"' if width else '')
	_video += global_attributes(**attributes)
	_video += event_attributes(**attributes)
	return _video + '>' + ''.join(text) + '</video>'

# Define uma possível quebra de linha
def wbr(**attributes):
	_wbr = '<wbr'
	_wbr += global_attributes(**attributes)
	_wbr += event_attributes(**attributes)
	return _wbr + '/>'
