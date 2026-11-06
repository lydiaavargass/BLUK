local utils = require('common')

local function break_command(div)
  if div.attributes.kind == 'clearpage' or utils.has_class(div, 'clearpage') then
    return '\\clearpage'
  end

  return '\\newpage'
end

function Div(div)
  if not utils.is_latex() then
    return nil
  end

  if not (
    utils.has_class(div, 'pagebreak')
    or utils.has_class(div, 'pagebreak-before')
    or utils.has_class(div, 'newpage')
    or utils.has_class(div, 'clearpage')
  ) then
    return nil
  end

  local content = div.content or {}
  if #content > 0 then
    return nil
  end

  return { pandoc.RawBlock('latex', break_command(div)) }
end
