local utils = require('common')

local function format_header_cell(cell)
  local text = pandoc.utils.stringify(cell.contents or cell.content or {})
  local content = pandoc.Blocks({
    pandoc.Plain({
      pandoc.RawInline('latex', '\\cellcolor{foctableheader}\\color{white}\\bfseries\\sffamily\\small\\strut ' .. utils.escape_latex(text))
    })
  })

  cell.contents = content
  cell.content = content
  return cell
end

function Table(tbl)
  if not utils.is_latex() then
    return nil
  end

  local head_rows = tbl.head and (tbl.head.rows or tbl.head.content or tbl.head.c)
  if head_rows then
    for _, row in ipairs(head_rows) do
      local cells = row.cells or row.content or row.c or {}
      for _, cell in ipairs(cells) do
        format_header_cell(cell)
      end
    end
  end

  return tbl
end
