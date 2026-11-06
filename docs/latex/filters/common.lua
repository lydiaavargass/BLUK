local M = {}

function M.is_latex()
  return FORMAT:match('latex') ~= nil
end

function M.escape_latex(text)
  text = text or ''

  local replacements = {
    ['\\'] = '\\textbackslash{}',
    ['{'] = '\\{',
    ['}'] = '\\}',
    ['$'] = '\\$',
    ['&'] = '\\&',
    ['#'] = '\\#',
    ['_'] = '\\_',
    ['%'] = '\\%',
    ['~'] = '\\textasciitilde{}',
    ['^'] = '\\textasciicircum{}',
  }

  return (text:gsub('[\\{}$&#_%%~^]', function(char)
    return replacements[char] or char
  end))
end

function M.has_class(element, class_name)
  for _, class in ipairs(element.classes or {}) do
    if class == class_name then
      return true
    end
  end

  return false
end

function M.first_image_in_blocks(blocks)
  for _, block in ipairs(blocks or {}) do
    if block.t == 'Para' or block.t == 'Plain' then
      for _, inline in ipairs(block.content or {}) do
        if inline.t == 'Image' then
          return inline
        end
      end
    elseif block.t == 'Figure' then
      local image = M.first_image_in_blocks(block.content or block.c or {})
      if image then
        return image
      end
    end
  end

  return nil
end

return M
