
-- locals, prevent global scope lookup, grouped by module
local _ = _
local ngx, log, tools  = ngx, log, tools
local say, now, null = ngx.say, ngx.now, ngx.null
local tonumber, tostring = tonumber, tostring
local concat, insert, sort = table.concat, table.insert, table.sort
local check, parse_request, took = tools.check, tools.parse_request, tools.time.took
local LIMIT = 999999999
local API = {}

-- Connects to MySQL with default config
local function _mysql_connect()
  if not ngx.ctx.mysql then

    return require('lib.mysql').new()
  end
  return ngx.ctx.mysql
end

function API.quote(str)
  return "'" .. str:gsub("\\", "\\\\"):gsub("'", "\\'") .. "'"
end

local function htmlspecialchars(str)
    local html = {
        ["<"] = "&lt;",
        [">"] = "&gt;",
        ["&"] = "&amp;",
    }
    return string.gsub(tostring(str), "[<>&]", function(char)
        return html[char] or char
    end)
end

local function htmlspecialchars_decode(str)
    local html = {
        ["&lt;"] = "<",
        ["&gt;"] = ">",
        ["&amp;"] = "&",
    }
    return string.gsub(tostring(str), "%b&;", function(char)
        return html[char] or char
    end)
end 

function API.is_dir( sPath )
  if type( sPath ) ~= "string" then return false end

  local response = os.execute("cd ".. sPath)

  -- if response then

  -- ngx.say("cd ".. sPath)
  -- return ngx.say(cjson.encode(response))
  -- end

  if response == 0 then
    return true
  end
  return  false
end

-- Lua implementation of PHP scandir function
function API.scandir(dirname)
        callit = os.tmpname()
        os.execute("ls -a1 "..dirname .. " >"..callit)
        f = io.open(callit,"r")
        rv = f:read("*all")
        f:close()
        os.remove(callit)

        tabby = {}
        local from  = 1
        local delim_from, delim_to = string.find( rv, "\n", from  )
        while delim_from do
                table.insert( tabby, string.sub( rv, from , delim_from-1 ) )
                from  = delim_to + 1
                delim_from, delim_to = string.find( rv, "\n", from  )
                end
        -- table.insert( tabby, string.sub( rv, from  ) )
        -- Comment out eliminates blank line on end!
        return tabby
end

-- ############################################################### 

function API.get_Paths(path)
  local db = ngx.ctx.mysql or _mysql_connect()
  local result = {}
  local exclude = {".",".."}

  filetab = API.scandir(path)

  _.each(filetab,function (filetab_row)

    if not _.contains(exclude,filetab_row) then

      tmp_path=filetab_row
      tmp_path=tmp_path:gsub("% ", "\\ ")
      tmp_path=tmp_path:gsub("%(", "\\(")
      tmp_path=tmp_path:gsub("%)", "\\)")
      tmp_path=tmp_path:gsub("%-", "\\-")

  --   if filetab_row then
  -- return ngx.say(path.."/"..filetab_row)
  --   end
local un_tmp_path=""
      if(API.is_dir(path.."/"..tmp_path) )then

      result[filetab_row]=API.get_Paths(path.."/"..tmp_path)
  -- ngx.say(path.."/"..filetab_row)

-- ----------------------------------------------------------------
            un_tmp_path=path.."/"..tmp_path
            un_tmp_path=un_tmp_path:gsub("%\\", "")

      local qrySelect = string.format([[SELECT * FROM exp.cinepixi_pathFile WHERE name = "%s";]], un_tmp_path )

      local SelectCursor, errorString = db:send_query( qrySelect )
      local dataSelect, err, errno, sqlstate = db:read_result(SelectCursor)

      if err then
      return ngx.say(cjson.encode({["status"] =0,["error"] = "Error al seleccionar cinepixi_pathFile"}))
      end

      local recordFather
        if _.size(dataSelect) > 1 then
          -- Update
            -- local qryUpdate = string.format([[UPDATE exp.cinepixi_pathFile SET name= "%s" WHERE name = "%s";]],
            --  path.."/"..filetab_row, 
            --  path.."/"..filetab_row 
            --  )

            local qryUpdate = "UPDATE exp.cinepixi_pathFile SET name= \""..un_tmp_path.."\" WHERE name = \""..un_tmp_path.."\";"

            local SelectCursor, errorString = db:send_query( qryUpdate )
                  recordFather, err, errno, sqlstate = db:read_result(SelectCursor)

            if qryUpdate then
              return ngx.say(qryUpdate)
            -- return ngx.say(cjson.encode({["status"] =0,["error"] = "Error al actualizar cinepixi_pathFile"}))
            end

        else
          -- Insert
            local qryInsert = string.format([[INSERT exp.cinepixi_pathFile SET name= "%s";]],
             un_tmp_path, 
             un_tmp_path 
             )

            local SelectCursor, errorString = db:send_query( qryInsert )
                  recordFather, err, errno, sqlstate = db:read_result(SelectCursor)

            if err then
            return ngx.say(cjson.encode({["status"] =0,["error"] = "Error al insertar cinepixi_pathFile"}))
            end

        end
-- ----------------------------------------------------------------


      else

  -- ngx.say(cjson.encode(filetab))
  -- ngx.say(path.."/"..tmp_path)

      result[filetab_row]=filetab_row

      end

    end

  end)

return result;
end

function API.syn_Paths(params)
  local db = ngx.ctx.mysql or _mysql_connect()
  -- local art_dir ="/media/dell/67F18E800D673AB3/musica"
  local art_dir ="/mnt/DATA"

  filetab = API.get_Paths(art_dir)

   -- ngx.say(cjson.encode(filetab))
  -- return ngx.exit(ngx.HTTP_OK)  
end

-- function API.msg(m)
--   return reaper.ShowConsoleMsg(tostring(m) .. "\n")
-- end

-- function API.get_script_path()
--   local info = debug.getinfo(1,'S');
--   local script_path = info.source:match[[^@?(.*[\/])[^\/]-$]]
--   return script_path
-- end


-- -- Lua implementation of PHP scandir function
-- function API.scandir(directory)
--     local i, t, popen = 0, {}, io.popen
--     for filename in popen('dir "'..directory..'" /b'):lines() do
--         API.msg(filename)
--         i = i + 1
--         t[i] = filename
--     end
--     return t
-- end


--------------------------------------------------------------------------------
-- API - RESTful methods 
--------------------------------------------------------------------------------
--- process GET requests (to get an element or custom JSON)
function API._get(params)
  local result
  local filter = {}

  -- Sincronizar el SEO
  if params and params.get.action ~= nil and params.get.action == 'synPaths' then
    result = API.syn_Paths(filter)
    -- return log(result)
  end

  -- ngx.say(cjson.encode({error = "Verifique la documentación para usar esta funcion."}))
  return ngx.exit(ngx.HTTP_OK)  
end

-----------------------------------------------------------
-- Execute or creates a new instance
-----------------------------------------------------------
function API.new()
  -- requires per request
  local db = require('mysql').new()
  local r  = require 'router'.new()
  
  -- list synFiles
  r:match('GET', '/api/synFiles', function(params) API._get(params) end)

  -- get single produc details
  r:match('GET', '/api/synFiles/:id', function(params) API._get(params) end)

  -- create
  r:match('POST', '/api/synFiles', function(params) API._post(params) end)

  -- update
  r:match('PUT', '/api/synFiles/:id', function(params) API._put(params) end)

  -- delete
  r:match('DELETE', '/api/synFiles/:id', function(params) API._delete(params) end)

  -- Execute router
  local req = parse_request()
  r:execute(req.method, req.uri, req.args)

  -- close connection to client
  ngx.eof()

  -- release db connection back to pool
  db:set_keepalive(10000, 20);
end


-----------------------------------------------------------
-- return API
-------------
return API
