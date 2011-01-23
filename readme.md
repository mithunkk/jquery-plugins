# Couch System Design Documentation

## Example URLs to test the source paths:

* <http://plugins-v3.jquery.com/cowboy/bbq.js>
* <http://plugins-v3.jquery.com/cowboy/bbq-1.2.1.js>
* <http://plugins-v3.jquery.com/cowboy/bbq-1.2.1.min.js>

##Every plugin has a pluginType field with one of these values:
* "public",
* "jquery"
* "jquery-ui"

###To get all the plugins of a certain type, you go here:
<http://plugins-v3.jquery.com:5984/plugins/_design/app/_view/byType>

The keys are an array of ["TYPE", "plugin name"], which results in plugins of the same type also being grouped by their names. So, if you wanted all of the public plugins, you'd go to:

<http://plugins-v3.jquery.com:5984/plugins/_design/app/_view/byType?startkey=["PUBLIC"]&endkey=["PUBLIC",{}]>

Full text searching with couchdb-lucene (v0.6.0) can be a bit funky if you're not used to lucene. The [semi-poor] documentation is available at

<https://github.com/rnewson/couchdb-lucene/blob/v0.6.0/README.md>

### Some example lucene queries

#### Full text search for the string "bwah":
<http://plugins-v3.jquery.com:5984/plugins/_fti/_design/app/plugins?q=bwah>

Full text search for the string "bbq", and telling couchdb-lucene to include the full doc for each result:

<http://plugins-v3.jquery.com:5984/plugins/_fti/_design/app/plugins?q=bbq&include_docs=true>

There is also a special field in the index for pluginType, so you can choose to restrict search results by the type of plugin. For example (including docs):

<http://plugins-v3.jquery.com:5984/plugins/_fti/_design/app/plugins?q=cowboy%20pluginType:PUBLIC&include_docs=true>
