#------------#
# CONSTANTES #
#------------#

EVENT_ATTRIBUTES = ['onabort', 'onafterprint', 'onbeforeprint', 'onbeforeunload', 'onblur', 'oncanplay', 'oncanplaythrough', 'onchange', 'onclick', 'oncontextmenu', 'oncopy', 'oncuechange', 'oncut', 'ondblclick', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'ondurationchange', 'onemptied', 'onended', 'onerror', 'onerror', 'onfocus', 'onhashchange', 'oninput', 'oninvalid', 'onkeydown', 'onkeypress', 'onkeyup', 'onload', 'onloadeddata', 'onloadedmetadata', 'onloadstart', 'onmessage', 'onmousedown', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onoffline', 'ononline', 'onpagehide', 'onpageshow', 'onpaste', 'onpause', 'onplay', 'onplaying', 'onpopstate', 'onprogress', 'onratechange', 'onreset', 'onresize', 'onscroll', 'onsearch', 'onseeked', 'onseeking', 'onselect', 'onshow', 'onstalled', 'onstorage', 'onsubmit', 'onsuspend', 'ontimeupdate', 'ontoggle', 'onunload', 'onvolumechange', 'onwaiting', 'onwheel']

GLOBAL_ATTRIBUTES = ['accesskey', 'class', 'contenteditable', 'contextmenu', 'data-*', 'dir', 'draggable', 'dropzone', 'hidden', 'id', 'lang', 'spellcheck', 'style', 'title', 'translate', 'xml:lang']

#-----------------#
# FUNÇÕES GLOBAIS #
#-----------------#

def event_attributes(**attributes):
	event_attributes = ''
	for attribute in attributes:
		if attribute.lower() in EVENT_ATTRIBUTES:
			event_attributes += (' ' + attribute.lower() + '="' + attributes[attribute] + '"' if not isinstance(attributes[attribute], bool) else ' ' + attribute)
	return event_attributes

def global_attributes(**attributes):
	global_attributes = ''
	for attribute in attributes:
		if attribute.lower() in GLOBAL_ATTRIBUTES:
			global_attributes += (' ' + attribute.lower() + '="' + attributes[attribute] + '"' if not isinstance(attributes[attribute], bool) else ' ' + attribute)
	return global_attributes

#-----------#
# TAGS HTML #
#-----------#

# Define um hyperlink
def a(download=None, href=None, hreflang=None, media=None, ping=None, referrerpolicy=None, rel=None, target=None, text='', type=None, **attributes):
	a = '<a'
	a += (' download="' + download + '"' if download else '')
	a += (' href="' + href + '"' if href else '')
	a += (' hreflang="' + hreflang + '"' if hreflang else '')
	a += (' media="' + media + '"' if media else '')
	a += (' ping="' + ping + '"' if ping else '')
	a += (' referrerpolicy="' + referrerpolicy + '"' if referrerpolicy else '')
	a += (' rel="' + rel + '"' if rel else '')
	a += (' target="' + target + '"' if target else '')
	a += (' type="' + type + '"' if type else '')
	a += global_attributes(**attributes)
	a += event_attributes(**attributes)
	return a + '>' + ''.join(text) + '</a>'

# Define uma abreviação
def abbr(text='', **attributes):
	abbr = '<abbr'
	abbr += global_attributes(**attributes)
	abbr += event_attributes(**attributes)
	return abbr + '>' + text + '</abbr>'

# Define um endereço
def address(text='', **attributes):
	address = '<address'
	address += global_attributes(**attributes)
	address += event_attributes(**attributes)
	return address + '>' + ''.join(text) + '</address>'

# Define uma área dentro de um mapa de imagem
def area(alt='', coords=None, download=None, href=None, hreflang=None, media=None, rel=None, shape=None, target=None, type=None, **attributes):
	area = '<area'
	area += ' alt="' + alt + '"'
	area += (' coords="' + coords + '"' if coords else '')
	area += (' download="' + download + '"' if download else '')
	area += (' href="' + href + '"' if href else '')
	area += (' hreflang="' + hreflang + '"' if hreflang else '')
	area += (' media="' + media + '"' if media else '')
	area += (' rel="' + rel + '"' if rel else '')
	area += (' shape="' + shape + '"' if shape else '')
	area += (' target="' + target + '"' if target else '')
	area += (' type="' + type + '"' if type else '')
	area += global_attributes(**attributes)
	area += event_attributes(**attributes)
	return area + '/>'

# Define um artigo
def article(text='', **attributes):
	article = '<article'
	article += global_attributes(**attributes)
	article += event_attributes(**attributes)
	return article + '>' + ''.join(text) + '</article>'

# Define o conteúdo além do conteúdo da página
def aside(text='', **attributes):
	aside = '<aside'
	aside += global_attributes(**attributes)
	aside += event_attributes(**attributes)
	return aside + '>' + ''.join(text) + '</aside>'

# Define o conteúdo de som
def audio(autoplay=False, controls=False, loop=False, muted=False, preload=None, src=None, text='', **attributes):
	audio = '<audio'
	audio += (' autoplay' if autoplay else '')
	audio += (' controls' if controls else '')
	audio += (' loop' if loop else '')
	audio += (' muted' if muted else '')
	audio += (' preload="' + preload + '"' if preload else '')
	audio += (' src="' + src + '"' if src else '')
	audio += global_attributes(**attributes)
	audio += event_attributes(**attributes)
	return audio + '>' + ''.join(text) + '</audio>'

# Define um texto em negrito
def b(text='', **attributes):
	b = '<b'
	b += global_attributes(**attributes)
	b += event_attributes(**attributes)
	return b + '>' + text + '</b>'

# Define uma base URL para todos os links da página
def base(href='', target='', **attributes):
	base = '<base'
	base += ' href="' + href + '"'
	base += ' target="' + target + '"'
	base += global_attributes(**attributes)
	base += event_attributes(**attributes)
	return base + '/>'

# Define um isolamento bidirecional
def bdi(text='', **attributes):
	bdi = '<bdi'
	bdi += global_attributes(**attributes)
	bdi += event_attributes(**attributes)
	return bdi + '>' + text + '</bdi>'

# Define a direção do texto apresentado
def bdo(dir=None, text='', **attributes):
	bdo = '<bdo'
	bdo += (' dir="' + dir + '"' if dir else '')
	bdo += global_attributes(**attributes)
	bdo += event_attributes(**attributes)
	return bdo + '>' + ''.join(text) + '</bdo>'

# Define uma citação longa
def blockquote(cite=None, text='', **attributes):
	blockquote = '<blockquote'
	blockquote += (' cite="' + cite + '"' if cite else '')
	blockquote += global_attributes(**attributes)
	blockquote += event_attributes(**attributes)
	return blockquote + '>' + ''.join(text) + '</blockquote>'

# Define o corpo da página
def body(text='', **attributes):
	body = '<body'
	body += global_attributes(**attributes)
	body += event_attributes(**attributes)
	return body + '>' + ''.join(text) + '</body>'

# Insere uma única quebra de linha
def br(**attributes):
	br = '<br'
	br += global_attributes(**attributes)
	br += event_attributes(**attributes)
	return br + '/>'

# Define um botão de comando
def button(autofocus=False, disabled=False, form=None, formaction=None, formenctype=None, formnovalidate=False, formtarget=None, name=None, text='', type=None, value=None, **attributes):
	button = '<button'
	button += (' autofocus' if autofocus else '')
	button += (' disabled' if disabled else '')
	button += (' form="' + form + '"' if form else '')
	button += (' formaction="' + formaction + '"' if formaction else '')
	button += (' formenctype="' + formenctype + '"' if formenctype else '')
	button += (' formnovalidate' if formnovalidate else '')
	button += (' formtarget="' + formtarget + '"' if formtarget else '')
	button += (' name="' + name + '"' if name else '')
	button += (' type="' + type + '"' if type else '')
	button += (' value="' + value + '"' if value else '')
	button += global_attributes(**attributes)
	button += event_attributes(**attributes)
	return button + '>' + ''.join(text) + '</button>'

# Define gráficos
def canvas(height=None, text='', width=None, **attributes):
	canvas = '<canvas'
	canvas += (' height="' + height + '"' if height else '')
	canvas += (' width="' + width + '"' if width else '')
	canvas += global_attributes(**attributes)
	canvas += event_attributes(**attributes)
	return canvas + '>' + ''.join(text) + '</canvas>'

# Define o "caption" de uma tabela
def caption(text='', **attributes):
	caption = '<caption'
	caption += global_attributes(**attributes)
	caption += event_attributes(**attributes)
	return caption + '>' + text + '</caption>'

# Define uma citação
def cite(text='', **attributes):
	cite = '<cite'
	cite += global_attributes(**attributes)
	cite += event_attributes(**attributes)
	return cite + '>' + text + '</cite>'

# Define o código texto do computador
def code(text='', **attributes):
	code = '<code'
	code += global_attributes(**attributes)
	code += event_attributes(**attributes)
	return code + '>' + ''.join(text) + '</code>'

# Define os atributos da coluna da tabela
def col(span=None, **attributes):
	col = '<col'
	col += (' span="' + span + '"' if span else '')
	col += global_attributes(**attributes)
	col += event_attributes(**attributes)
	return col + '/>'

# Define um grupo de colunas da tabela
def colgroup(span=None, text='', **attributes):
	colgroup = '<colgroup'
	colgroup += (' span="' + span + '"' if span else '')
	colgroup += global_attributes(**attributes)
	colgroup += event_attributes(**attributes)
	return colgroup + '>' + ''.join(text) + '</colgroup>'

# Define um comentário
def comment(text=''):
	return '<!--' + ''.join(text) + '-->'

# Define uma lista suspensa (DropDown)
def datalist(text='', **attributes):
	datalist = '<datalist'
	datalist += global_attributes(**attributes)
	datalist += event_attributes(**attributes)
	return datalist + '>' + ''.join(text) + '</datalist>'

# Define uma descrição de definição
def dd(text='', **attributes):
	dd = '<dd'
	dd += global_attributes(**attributes)
	dd += event_attributes(**attributes)
	return dd + '>' + text + '</dd>'

# Define um texto deletado
def Del(cite=None, datetime=None, text='', **attributes):
	Del = '<del'
	Del += (' cite="' + cite + '"' if cite else '')
	Del += (' datetime="' + datetime + '"' if datetime else '')
	Del += global_attributes(**attributes)
	Del += event_attributes(**attributes)
	return Del + '>' + ''.join(text) + '</Del>'

# Define detalhes de um elemento
def details(open=False, text='', **attributes):
	details = '<details'
	details += (' open' if open else '')
	details += global_attributes(**attributes)
	details += event_attributes(**attributes)
	return details + '>' + ''.join(text) + '</details>'

# Define um termo de definição
def dfn(text='', **attributes):
	dfn = '<dfn'
	dfn += global_attributes(**attributes)
	dfn += event_attributes(**attributes)
	return dfn + '>' + text + '</dfn>'

# Define uma conversa ou pessoas falando
def dialog(open=False, text='', **attributes):
	dialog = '<dialog'
	dialog += (' open' if open else '')
	dialog += global_attributes(**attributes)
	dialog += event_attributes(**attributes)
	return dialog + '>' + ''.join(text) + '</dialog>'

# Define uma seção no documento
def div(text='', **attributes):
	div = '<div'
	div += global_attributes(**attributes)
	div += event_attributes(**attributes)
	return div + '>' + ''.join(text) + '</div>'

# Define o tipo de documento
def doctype():
	return '<!DOCTYPE html>'

# Define um documento
def document(text=''):
	return ''.join(text)

# Define uma lista de definição
def dl(text='', **attributes):
	dl = '<dl'
	dl += global_attributes(**attributes)
	dl += event_attributes(**attributes)
	return dl + '>' + ''.join(text) + '</dl>'

# Define um termo de definição
def dt(text='', **attributes):
	dt = '<dt'
	dt += global_attributes(**attributes)
	dt += event_attributes(**attributes)
	return dt + '>' + text + '</dt>'

# Define um texto em ênfase
def em(text='', **attributes):
	em = '<em'
	em += global_attributes(**attributes)
	return em + '>' + text + '</em>'

# Define o conteúdo interativo ou plugin externo
def embed(height=None, src=None, type=None, width=None, **attributes):
	embed = '<embed'
	embed += (' height="' + height + '"' if height else '')
	embed += (' src="' + src + '"' if src else '')
	embed += (' type="' + type + '"' if type else '')
	embed += (' width="' + width + '"' if width else '')
	embed += global_attributes(**attributes)
	embed += event_attributes(**attributes)
	return embed + '/>'

# Define um conjunto de campos (fieldset)
def fieldset(disabled=False, form=None, name=None, text='', **attributes):
	fieldset = '<fieldset'
	fieldset += (' disabled' if disabled else '')
	fieldset += (' form="' + form + '"' if form else '')
	fieldset += (' name="' + name + '"' if name else '')
	fieldset += global_attributes(**attributes)
	fieldset += event_attributes(**attributes)
	return fieldset + '>' + ''.join(text) + '</fieldset>'

# Define o caption de uma imagem
def figcaption(text='', **attributes):
	figcaption = '<figcaption'
	figcaption += global_attributes(**attributes)
	figcaption += event_attributes(**attributes)
	return figcaption + '>' + text + '</figcaption>'

# Define um grupo de média e seus captions
def figure(text='', **attributes):
	figure = '<figure'
	figure += global_attributes(**attributes)
	figure += event_attributes(**attributes)
	return figure + '>' + ''.join(text) + '</figure>'

# Define o rodapé de uma página
def footer(text='', **attributes):
	footer = '<footer'
	footer += global_attributes(**attributes)
	footer += event_attributes(**attributes)
	return footer + '>' + ''.join(text) + '</footer>'

# Define um formulário
def form(accept_charset=None, action=None, autocomplete=None, enctype=None, method=None, name=None, novalidate=False, rel=None, target=None, text='', **attributes):
	form = '<form'
	form += (' accept-charset="' + accept_charset + '"' if accept_charset else '')
	form += (' action="' + action + '"' if action else '')
	form += (' autocomplete="' + autocomplete + '"' if autocomplete else '')
	form += (' enctype="' + enctype + '"' if enctype else '')
	form += (' method="' + method + '"' if method else '')
	form += (' name="' + name + '"' if name else '')
	form += (' novalidate' if novalidate else '')
	form += (' rel="' + rel + '"' if rel else '')
	form += (' target="' + target + '"' if target else '')
	form += global_attributes(**attributes)
	form += event_attributes(**attributes)
	return form + '>' + ''.join(text) + '</form>'

# Define o cabeçalho 1
def h1(text='', **attributes):
	h1 = '<h1'
	h1 += global_attributes(**attributes)
	h1 += event_attributes(**attributes)
	return h1 + '>' + text + '</h1>'

# Define o cabeçalho 2
def h2(text='', **attributes):
	h2 = '<h2'
	h2 += global_attributes(**attributes)
	h2 += event_attributes(**attributes)
	return h2 + '>' + text + '</h2>'

# Define o cabeçalho 3
def h3(text='', **attributes):
	h3 = '<h3'
	h3 += global_attributes(**attributes)
	h3 += event_attributes(**attributes)
	return h3 + '>' + text + '</h3>'

# Define o cabeçalho 4
def h4(text='', **attributes):
	h4 = '<h4'
	h4 += global_attributes(**attributes)
	h4 += event_attributes(**attributes)
	return h4 + '>' + text + '</h4>'

# Define o cabeçalho 5
def h5(text='', **attributes):
	h5 = '<h5'
	h5 += global_attributes(**attributes)
	h5 += event_attributes(**attributes)
	return h5 + '>' + text + '</h5>'

# Define o cabeçalho 6
def h6(text='', **attributes):
	h6 = '<h6'
	h6 += global_attributes(**attributes)
	h6 += event_attributes(**attributes)
	return h6 + '>' + text + '</h6>'

# Define uma informação sobre o documento
def head(text='', **attributes):
	head = '<head'
	head += global_attributes(**attributes)
	return head + '>' + ''.join(text) + '</head>'

# Define o cabeçalho de uma página
def header(text='', **attributes):
	header = '<header'
	header += global_attributes(**attributes)
	header += event_attributes(**attributes)
	return header + '>' + ''.join(text) + '</header>'

# Define uma regra horizontal
def hr(**attributes):
	hr = '<hr'
	hr += global_attributes(**attributes)
	hr += event_attributes(**attributes)
	return hr + '/>'

# Define um documento HTML
def html(text='', xmlns=None, **attributes):
	html = '<html'
	html += (' xmlns="' + xmlns + '"' if xmlns else '')
	html += global_attributes(**attributes)
	return html + '>' + ''.join(text) + '</html>'

# Define um texto em itálico
def i(text='', **attributes):
	i = '<i'
	i += global_attributes(**attributes)
	i += event_attributes(**attributes)
	return i + '>' + text + '</i>'

# Define uma linhas sobre a janela (frame)
def iframe(allowfullscreen=False, height=None, name=None, sandbox=None, src=None, srcdoc=None, text='', width=None, **attributes):
	iframe = '<iframe'
	iframe += (' allowfullscreen' if allowfullscreen else '')
	iframe += (' height="' + height + '"' if height else '')
	iframe += (' name="' + name + '"' if name else '')
	iframe += (' sandbox="' + sandbox + '"' if sandbox else '')
	iframe += (' src="' + src + '"' if src else '')
	iframe += (' srcdoc="' + srcdoc + '"' if srcdoc else '')
	iframe += (' width="' + width + '"' if width else '')
	iframe += global_attributes(**attributes)
	iframe += event_attributes(**attributes)
	return iframe + '>' + text + '</iframe>'

# Define uma imagem
def img(alt='', crossorigin=None, height=None, ismap=False, longdesc=None, referrerpolicy=None, sizes=None, src='', srcset=None, usemap=None, width=None, **attributes):
	img = '<img'
	img += ' alt="' + alt + '"'
	img += (' crossorigin="' + crossorigin + '"' if crossorigin else '')
	img += (' height="' + height + '"' if height else '')
	img += (' ismap' if ismap else '')
	img += (' longdesc="' + longdesc + '"' if longdesc else '')
	img += (' referrerpolicy="' + referrerpolicy + '"' if referrerpolicy else '')
	img += (' sizes="' + sizes + '"' if sizes else '')
	img += ' src="' + src + '"'
	img += (' srcset="' + srcset + '"' if srcset else '')
	img += (' usemap="' + usemap + '"' if usemap else '')
	img += (' width="' + width + '"' if width else '')
	img += global_attributes(**attributes)
	img += event_attributes(**attributes)
	return img + '/>'

# Define um campo de inserção
def input(accept=None, alt=None, autocomplete=None, autofocus=False, checked=False, dirname=None, disabled=False, form=None, formaction=None, formenctype=None, formmethod=None, formnovalidate=None, formtarget=None, height=None, list=None, max=None, maxlength=None, min=None, minlength=None, multiple=False, name=None, pattern=None, placeholder=None, readonly=False, required=False, size=None, src=None, step=None, type=None, value=None, width=None, **attributes):
	input = '<input'
	input += (' accept="' + accept + '"' if accept else '')
	input += (' alt="' + alt + '"' if alt else '')
	input += (' autocomplete="' + autocomplete + '"' if autocomplete else '')
	input += (' autofocus' if autofocus else '')
	input += (' checked' if checked else '')
	input += (' dirname="' + dirname + '"' if dirname else '')
	input += (' disabled' if disabled else '')
	input += (' form="' + form + '"' if form else '')
	input += (' formaction="' + formaction + '"' if formaction else '')
	input += (' height="' + height + '"' if height else '')
	input += (' list="' + list + '"' if list else '')
	input += (' max="' + max + '"' if max else '')
	input += (' maxlength="' + maxlength + '"' if maxlength else '')
	input += (' min="' + min + '"' if min else '')
	input += (' minlength="' + minlength + '"' if minlength else '')
	input += (' multiple' if multiple else '')
	input += (' name="' + name + '"' if name else '')
	input += (' pattern="' + pattern + '"' if pattern else '')
	input += (' placeholder="' + placeholder + '"' if placeholder else '')
	input += (' readonly' if readonly else '')
	input += (' required' if required else '')
	input += (' size="' + size + '"' if size else '')
	input += (' src="' + src + '"' if src else '')
	input += (' step="' + step + '"' if step else '')
	input += (' type="' + type + '"' if type else '')
	input += (' value="' + value + '"' if value else '')
	input += (' width="' + width + '"' if width else '')
	input += global_attributes(**attributes)
	input += event_attributes(**attributes)
	return input + '/>'

# Define um texto inserido
def ins(cite=None, datetime=None, text='', **attributes):
	ins = '<ins'
	ins += (' cite="' + cite + '"' if cite else '')
	ins += (' datetime="' + datetime + '"' if datetime else '')
	ins += global_attributes(**attributes)
	ins += event_attributes(**attributes)
	return ins + '>' + text + '</ins>'

# Define um texto do teclado
def kbd(text='', **attributes):
	kbd = '<kbd'
	kbd += global_attributes(**attributes)
	kbd += event_attributes(**attributes)
	return kbd + '>' + text + '</kbd>'

# Define uma "label" para o formulário
def label(For=None, form=None, text='', **attributes):
	label = '<label'
	label += (' for="' + For + '"' if For else '')
	label += (' form="' + form + '"' if form else '')
	label += global_attributes(**attributes)
	label += event_attributes(**attributes)
	return label + '>' + text + '</label>'

# Define um título para os campos (fields)
def legend(text='', **attributes):
	legend = '<legend'
	legend += global_attributes(**attributes)
	legend += event_attributes(**attributes)
	return legend + '>' + text + '</legend>'

# Define os itens da lista
def li(text='', value=None, **attributes):
	li = '<li'
	li += (' value="' + value + '"' if value else '')
	li += global_attributes(**attributes)
	li += event_attributes(**attributes)
	return li + '>' + ''.join(text) + '</li>'

# Define uma referência
def link(crossorigin=None, href=None, hreflang=None, media=None, referrerpolicy=None, rel=None, sizes=None, type=None, **attributes):
	link = '<link'
	link += (' crossorigin="' + crossorigin + '"' if crossorigin else '')
	link += (' href="' + href + '"' if href else '')
	link += (' hreflang="' + hreflang + '"' if hreflang else '')
	link += (' media="' + media + '"' if media else '')
	link += (' referrerpolicy="' + referrerpolicy + '"' if referrerpolicy else '')
	link += (' rel="' + rel + '"' if rel else '')
	link += (' sizes="' + sizes + '"' if sizes else '')
	link += (' type="' + type + '"' if type else '')
	link += global_attributes(**attributes)
	link += event_attributes(**attributes)
	return link + '/>'

# Especifica o conteúdo principal de um documento
def main(text='', **attributes):
	main = '<main'
	main += global_attributes(**attributes)
	main += event_attributes(**attributes)
	return main + '>' + ''.join(text) + '</main>'

# Define uma imagem de mapa
def map(name=None, text='', **attributes):
	map = '<map'
	map += (' name="' + name + '"' if name else '')
	map += global_attributes(**attributes)
	map += event_attributes(**attributes)
	return map + '>' + ''.join(text) + '</map>'

# Define a marcação de um texto
def mark(text='', **attributes):
	mark = '<mark'
	mark += global_attributes(**attributes)
	mark += event_attributes(**attributes)
	return mark + '>' + text + '</mark>'

# Define informações meta
def meta(charset=None, content=None, http_equiv=None, itemprop=None, name=None, property=None, **attributes):
	meta = '<meta'
	meta += (' charset="' + charset + '"' if charset else '')
	meta += (' content="' + content + '"' if content else '')
	meta += (' http-equiv="' + http_equiv + '"' if http_equiv else '')
	meta += (' itemprop="' + itemprop + '"' if itemprop else '')
	meta += (' name="' + name + '"' if name else '')
	meta += (' property="' + property + '"' if property else '')
	meta += global_attributes(**attributes)
	return meta + '/>'

# Define a medição dentro de um intervalo pré-definido
def meter(form=None, high=None, low=None, max=None, min=None, optimum=None, text='', value='', **attributes):
	meter = '<meter'
	meter += (' form="' + form + '"' if form else '')
	meter += (' high="' + high + '"' if high else '')
	meter += (' low="' + low + '"' if low else '')
	meter += (' max="' + max + '"' if max else '')
	meter += (' min="' + min + '"' if min else '')
	meter += (' optimum="' + optimum + '"' if optimum else '')
	meter += ' value="' + value + '"'
	meter += global_attributes(**attributes)
	meter += event_attributes(**attributes)
	return meter + '>' + text + '</meter>'

# Define os links de navegação
def nav(text='', **attributes):
	nav = '<nav'
	nav += global_attributes(**attributes)
	nav += event_attributes(**attributes)
	return nav + '>' + ''.join(text) + '</nav>'

# Define uma seção noscript
def noscript(text='', **attributes):
	noscript = '<noscript'
	noscript += global_attributes(**attributes)
	return noscript + '>' + text + '</noscript>'

# Define um objeto incorporado
def object(data='', height=None, name=None, text='', type='', usemap=None, width=None, **attributes):
	object = '<object'
	object += ' data="' + data + '"'
	object += (' height="' + height + '"' if height else '')
	object += (' name="' + name + '"' if name else '')
	object += ' type="' + type + '"'
	object += (' usemap="' + usemap + '"' if usemap else '')
	object += (' width="' + width + '"' if width else '')
	object += global_attributes(**attributes)
	object += event_attributes(**attributes)
	return object + '>' + text + '</object>'

# Define uma lista ordenada
def ol(reversed=False, start='', text='', type='', **attributes):
	ol = '<ol'
	ol += (' reversed' if reversed else '')
	ol += ' start="' + start + '"'
	ol += ' type="' + type + '"'
	ol += global_attributes(**attributes)
	ol += event_attributes(**attributes)
	return ol + '>' + text + '</ol>'

# Define um grupo de opção
def optgroup(disabled=False, label='', text='', **attributes):
	optgroup = '<optgroup'
	optgroup += (' disabled' if disabled else '')
	optgroup += ' label="' + label + '"'
	optgroup += global_attributes(**attributes)
	optgroup += event_attributes(**attributes)
	return optgroup + '>' + text + '</optgroup>'

# Define uma opção em uma lista suspensa (drop-down list)
def option(disabled=False, label=None, selected=False, text='', value=None, **attributes):
	option = '<option'
	option += (' disabled' if disabled else '')
	option += (' label="' + label + '"' if label else '')
	option += (' selected' if selected else '')
	option += (' value="' + value + '"' if value else '')
	option += global_attributes(**attributes)
	option += event_attributes(**attributes)
	return option + '>' + text + '</option>'

# Define os tipos de saída (outputs)
def output(For=None, form=None, name=None, text='', **attributes):
	output = '<output'
	output += (' for="' + For + '"' if For else '')
	output += (' form="' + form + '"' if form else '')
	output += (' name="' + name + '"' if name else '')
	output += global_attributes(**attributes)
	output += event_attributes(**attributes)
	return output + '>' + text + '</output>'

# Define um parágrafo
def p(text='', **attributes):
	p = '<p'
	p += global_attributes(**attributes)
	p += event_attributes(**attributes)
	return p + '>' + ''.join(text) + '</p>'

# Define um parâmetro para determinado objeto
def param(name='', value=None, **attributes):
	param = '<param'
	param += ' name="' + name + '"'
	param += (' value="' + value + '"' if value else '')
	param += global_attributes(**attributes)
	param += event_attributes(**attributes)
	return param + '/>'

# Fornece mais flexibilidade na especificação de recursos de imagem
def picture(text='', **attributes):
	picture = '<picture'
	picture += global_attributes(**attributes)
	picture += event_attributes(**attributes)
	return picture + '>' + ''.join(text) + '</picture>'

# Define um texto pré-formatado
def pre(text='', **attributes):
	pre = '<pre'
	pre += global_attributes(**attributes)
	pre += event_attributes(**attributes)
	return pre + '>' + text + '</pre>'

# Define o progresso de uma tarefa qualquer
def progress(max=None, text='', value=None, **attributes):
	progress = '<progress'
	progress += (' max="' + max + '"' if max else '')
	progress += (' value="' + value + '"' if value else '')
	progress += global_attributes(**attributes)
	progress += event_attributes(**attributes)
	return progress + '>' + text + '</progress>'

# Define uma citação curta
def q(cite=None, text='', **attributes):
	q = '<q'
	q += (' cite="' + cite + '"' if cite else '')
	q += global_attributes(**attributes)
	q += event_attributes(**attributes)
	return q + '>' + text + '</q>'

# Define o browser substituto para elementos não suportados pelo Ruby
def rp(text='', **attributes):
	rp = '<rp'
	rp += global_attributes(**attributes)
	rp += event_attributes(**attributes)
	return rp + '>' + text + '</rp>'

# Define explicações para as anotações de Ruby
def rt(text='', **attributes):
	rt = '<rt'
	rt += global_attributes(**attributes)
	rt += event_attributes(**attributes)
	return rt + '>' + text + '</rt>'

# Define as anotações de Ruby
def ruby(text='', **attributes):
	ruby = '<ruby'
	ruby += global_attributes(**attributes)
	ruby += event_attributes(**attributes)
	return ruby + '>' + ''.join(text) + '</ruby>'

# Define um texto que não é mais correto
def s(text='', **attributes):
	s = '<s'
	s += global_attributes(**attributes)
	s += event_attributes(**attributes)
	return s + '>' + text + '</s>'

# Define um código de amostra
def samp(text='', **attributes):
	samp = '<samp'
	samp += global_attributes(**attributes)
	samp += event_attributes(**attributes)
	return samp + '>' + text + '</samp>'

# Define um script
def script(Async=False, charset=None, defer=False, src=None, text='', type=None, **attributes):
	script = '<script'
	script += (' async' if Async else '')
	script += (' charset="' + charset + '"' if charset else '')
	script += (' defer' if defer else '')
	script += (' src="' + src + '"' if src else '')
	script += (' type="' + type + '"' if type else '')
	script += global_attributes(**attributes)
	return script + '>' + ''.join(text) + '</script>'

# Define uma área ou seção
def section(text='', **attributes):
	section = '<section'
	section += global_attributes(**attributes)
	section += event_attributes(**attributes)
	return section + '>' + ''.join(text) + '</section>'

# Define uma lista selecionável
def select(autofocus=False, disabled=False, form=None, multiple=False, name=None, required=False, size=None, text='', **attributes):
	select = '<select'
	select += (' autofocus' if autofocus else '')
	select += (' disabled' if disabled else '')
	select += (' form="' + form + '"' if form else '')
	select += (' multiple' if multiple else '')
	select += (' name="' + name + '"' if name else '')
	select += (' required' if required else '')
	select += (' size="' + size + '"' if size else '')
	select += global_attributes(**attributes)
	select += event_attributes(**attributes)
	return select + '>' + ''.join(text) + '</select>'

# Define um pequeno texto
def small(text='', **attributes):
	small = '<small'
	small += global_attributes(**attributes)
	small += event_attributes(**attributes)
	return small + '>' + text + '</small>'

# Define recursos de mídia
def source(media=None, src='', srcset=None, text='', type=None, **attributes):
	source = '<source'
	source += (' media="' + media + '"' if media else '')
	source += ' src="' + src + '"'
	source += (' srcset="' + srcset + '"' if srcset else '')
	source += (' type="' + type + '"' if type else '')
	source += global_attributes(**attributes)
	source += event_attributes(**attributes)
	return source + '/>'

# Define uma seção no documento
def span(text='', **attributes):
	span = '<span'
	span += global_attributes(**attributes)
	span += event_attributes(**attributes)
	return span + '>' + text + '</span>'

# Define um texto forte (similar ao negrito)
def strong(text='', **attributes):
	strong = '<strong'
	strong += global_attributes(**attributes)
	strong += event_attributes(**attributes)
	return strong + '>' + text + '</strong>'

# Define um estilo
def style(media=None, text='', type=None, **attributes):
	style = '<style'
	style += (' media="' + media + '"' if media else '')
	style += (' type="' + type + '"' if type else '')
	style += global_attributes(**attributes)
	style += event_attributes(**attributes)
	return style + '>' + ''.join(text) + '</style>'

# Define um texto subscrito
def sub(text='', **attributes):
	sub = '<sub'
	sub += global_attributes(**attributes)
	sub += event_attributes(**attributes)
	return sub + '>' + text + '</sub>'

# Define o cabeçalho de dados “detalhe”
def summary(text='', **attributes):
	summary = '<summary'
	summary += global_attributes(**attributes)
	summary += event_attributes(**attributes)
	return summary + '>' + text + '</summary>'

# Define um texto sobrescrito
def sup(text='', **attributes):
	sup = '<sup'
	sup += global_attributes(**attributes)
	sup += event_attributes(**attributes)
	return sup + '>' + text + '</sup>'

# Define um contêiner para gráficos SVG
def svg(height=None, text='', width=None):
	svg = '<svg'
	svg += (' height="' + height + '"' if height else '')
	svg += (' width="' + width + '"' if width else '')
	return svg + '>' + ''.join(text) + '</svg>'

# Define uma tabela
def table(text='', **attributes):
	table = '<table'
	table += global_attributes(**attributes)
	table += event_attributes(**attributes)
	return table + '>' + ''.join(text) + '</table>'

# Define o corpo da tabela
def tbody(text='', **attributes):
	tbody = '<tbody'
	tbody += global_attributes(**attributes)
	tbody += event_attributes(**attributes)
	return tbody + '>' + ''.join(text) + '</tbody>'

# Define uma célula da tabela
def td(colspan=None, headers=None, rowspan=None, text='', **attributes):
	td = '<td'
	td += (' colspan="' + colspan + '"' if colspan else '')
	td += (' headers="' + headers + '"' if headers else '')
	td += (' rowspan="' + rowspan + '"' if rowspan else '')
	td += global_attributes(**attributes)
	td += event_attributes(**attributes)
	return td + '>' + text + '</td>'

# Define um conteúdo oculto
def template(text='', **attributes):
	template = '<template'
	template += global_attributes(**attributes)
	return template + '>' + ''.join(text) + '</template>'

# Define um área de texto
def textarea(autofocus=False, cols=None, dirname=None, disabled=False, form=None, maxlength=None, minlength=None, name=None, placeholder=None, readonly=False, required=False, rows=None, text='', wrap=None, **attributes):
	textarea = '<textarea'
	textarea += (' autofocus' if autofocus else '')
	textarea += (' cols="' + cols + '"' if cols else '')
	textarea += (' dirname="' + dirname + '"' if dirname else '')
	textarea += (' disabled' if disabled else '')
	textarea += (' form="' + form + '"' if form else '')
	textarea += (' maxlength="' + maxlength + '"' if maxlength else '')
	textarea += (' minlength="' + minlength + '"' if minlength else '')
	textarea += (' name="' + name + '"' if name else '')
	textarea += (' placeholder="' + placeholder + '"' if placeholder else '')
	textarea += (' readonly' if readonly else '')
	textarea += (' required' if required else '')
	textarea += (' rows="' + rows + '"' if rows else '')
	textarea += (' wrap="' + wrap + '"' if wrap else '')
	textarea += global_attributes(**attributes)
	textarea += event_attributes(**attributes)
	return textarea + '>' + text + '</textarea>'

# Define o rodapé da tabela
def tfoot(text='', **attributes):
	tfoot = '<tfoot'
	tfoot += global_attributes(**attributes)
	tfoot += event_attributes(**attributes)
	return tfoot + '>' + ''.join(text) + '</tfoot>'

# Define o cabeçalho da tabela
def th(abbr=None, colspan=None, headers=None, rowspan=None, scope=None, text='', **attributes):
	th = '<th'
	th += (' abbr="' + abbr + '"' if abbr else '')
	th += (' colspan="' + colspan + '"' if colspan else '')
	th += (' headers="' + headers + '"' if headers else '')
	th += (' rowspan="' + rowspan + '"' if rowspan else '')
	th += (' scope="' + scope + '"' if scope else '')
	th += global_attributes(**attributes)
	th += event_attributes(**attributes)
	return th + '>' + ''.join(text) + '</th>'

# Define o cabeçalho da tabela
def thead(text='', **attributes):
	thead = '<thead'
	thead += global_attributes(**attributes)
	thead += event_attributes(**attributes)
	return thead + '>' + ''.join(text) + '</thead>'

# Define uma data ou hora
def time(datetime=None, text='', **attributes):
	time = '<time'
	time += (' datetime="' + datetime + '"' if datetime else '')
	time += global_attributes(**attributes)
	time += event_attributes(**attributes)
	return time + '>' + text + '</time>'

# Define o título do documento
def title(text='', **attributes):
	title = '<title'
	title += global_attributes(**attributes)
	return title + '>' + text + '</title>'

# Define uma linha da tabela
def tr(text='', **attributes):
	tr = '<tr'
	tr += global_attributes(**attributes)
	tr += event_attributes(**attributes)
	return tr + '>' + ''.join(text) + '</tr>'

# Define faixas de texto para elementos de mídia
def track(default=False, kind=None, label=None, src='', srclang=None, **attributes):
	track = '<track'
	track += (' default' if default else '')
	track += (' kind="' + kind + '"' if kind else '')
	track += (' label="' + label + '"' if label else '')
	track += ' src="' + src + '"'
	track += (' srclang="' + srclang + '"' if srclang else '')
	track += global_attributes(**attributes)
	track += event_attributes(**attributes)
	return track + '/>'

# Define sublinhado
def u(text='', **attributes):
	u = '<u'
	u += global_attributes(**attributes)
	u += event_attributes(**attributes)
	return u + '>' + text + '</u>'

# Define uma lista desordenada
def ul(text='', **attributes):
	ul = '<ul'
	ul += global_attributes(**attributes)
	ul += event_attributes(**attributes)
	return ul + '>' + ''.join(text) + '</ul>'

# Define uma variável
def var(text='', **attributes):
	var = '<var'
	var += global_attributes(**attributes)
	var += event_attributes(**attributes)
	return var + '>' + text + '</var>'

# Define um vídeo
def video(autoplay=False, controls=False, height=None, loop=False, muted=False, poster=None, preload=None, src=None, text='', width=None, **attributes):
	video = '<video'
	video += (' autoplay' if autoplay else '')
	video += (' controls' if controls else '')
	video += (' height="' + height + '"' if height else '')
	video += (' loop' if loop else '')
	video += (' muted' if muted else '')
	video += (' poster="' + poster + '"' if poster else '')
	video += (' preload="' + preload + '"' if preload else '')
	video += (' src="' + src + '"' if src else '')
	video += (' width="' + width + '"' if width else '')
	video += global_attributes(**attributes)
	video += event_attributes(**attributes)
	return video + '>' + ''.join(text) + '</video>'

# Define uma possível quebra de linha
def wbr(**attributes):
	wbr = '<wbr'
	wbr += global_attributes(**attributes)
	wbr += event_attributes(**attributes)
	return wbr + '/>'
