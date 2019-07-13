# frozen_string_literal: true

require 'sinatra'
require 'sinatra/activerecord'

set :database, adapter: 'sqlite3', database: 'blog.sqlite3'

# Router
get '/' do
  erb :index
end
