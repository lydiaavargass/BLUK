local utils = require('common')

function Div(div)
  if not utils.is_latex() or not utils.has_class(div, 'latex-figure') then
    return nil
  end

  local image = utils.first_image_in_blocks(div.content)
  if not image then
    return nil
  end

  local needspace = div.attributes.needspace or ''
  local width = div.attributes.width or '0.98\\linewidth'
  local height = div.attributes.height or ''
  local stretch = (div.attributes.stretch or ''):lower()
  local image_src = image.src
  local caption = pandoc.utils.stringify(image.caption or {})

  local lines = {}
  if needspace ~= '' then
    table.insert(lines, '\\Needspace{' .. needspace .. '\\baselineskip}')
  end

  table.insert(lines, '\\begin{figure}[!htbp]')
  table.insert(lines, '\\centering')

  local includegraphics = '\\includegraphics[width=' .. width
  if height ~= '' then
    includegraphics = includegraphics .. ',height=' .. height
    if stretch ~= 'true' and stretch ~= '1' then
      includegraphics = includegraphics .. ',keepaspectratio'
    end
  end
  includegraphics = includegraphics .. ']'
  includegraphics = includegraphics .. '{' .. utils.escape_latex(image_src) .. '}'

  table.insert(lines, includegraphics)
  if caption ~= '' then
    table.insert(lines, '\\caption{' .. utils.escape_latex(caption) .. '}')
  end
  table.insert(lines, '\\end{figure}')

  return { pandoc.RawBlock('latex', table.concat(lines, '\n')) }
end
