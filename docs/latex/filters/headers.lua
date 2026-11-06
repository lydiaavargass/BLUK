local utils = require('common')

local NEEDSPACE_BY_LEVEL = {
  [1] = 12,
  [2] = 10,
  [3] = 7,
}

function Header(header)
  if not utils.is_latex() then
    return nil
  end

  local needspace = NEEDSPACE_BY_LEVEL[header.level]
  if not needspace then
    return nil
  end

  return {
    pandoc.RawBlock('latex', '\\Needspace{' .. needspace .. '\\baselineskip}'),
    header,
  }
end
