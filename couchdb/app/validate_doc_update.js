function (newDoc, oldDoc, userCtx) {
  var author = (oldDoc || newDoc)['author'];
  var docid = (oldDoc || newDoc)['_id'];

  function forbidden(message) {    
    throw({forbidden : message});
  };
  
  function unauthorized(message) {
    throw({unauthorized : message});
  };

  function require(beTrue, message) {
    if (!beTrue) forbidden(message);
  };

  function requireString(str, message) {
    require(str, message);

    if(message.constructor != String) forbidden(message);
  };

  function requireArray(arr, message) {
    require(arr, message);

    if(arr.constructor != Array) forbidden(message);
  };

  function requireObject(obj, message) {
    require(obj, message);

    if(obj.constructor != Object) forbidden(message);
  };

  // Default Values

  if(!doc.clone)
    doc.clone = 'http://github.com/'+doc.owner+'/'+doc.name+'.git';

  if(!doc.repository)
    doc.repository = 'http://github.com/'+doc.owner+'/'+doc.name;

  // Required Items that Cannot Change Between Revisions

  requireString(doc.name, '"name" must be the name of your plugin, used in your file name.');

  if(oldDoc && doc.name != oldDoc.name)
    forbidden('You cannot change the plugin name.');

  // Required Items that May Change Between Revisions

  requireString(doc.label, '"label" must be a string, providing a decorative version of your plugin name.');

  requireString(doc.copyright, '"copyright" must be a string with your copyright information.');

  requireString(doc.defaultVersion, '"defaultVersion" must be a string referring to the most stable version of your plugin.');

  // Optional Items

  if(doc.licenses)
  {
    requireArray(doc.licenses, '"licenses" must be an array of strings.');

    for(var i in doc.licenses)
      requireString(doc.licenses[i], 'Each item in the "licenses" array needs to be a string.');
  }

  if(doc.docs)
    requireString(doc.docs, '"docs" should be a relative path to your plugin\'s documentation directory in GitHub (a string).');

  if(doc.description)
    requireString(doc.description, '"description" needs to be text describing your plugin.');

  if(doc.clone)
    requireString(doc.clone, '"clone" should be a URL to your git file on the Internet.');

  if(doc.repository)
    requireString(doc.repository, '"repository" should be a URL to your source code control\'s interface (ex., GitHub project page).');

  if(doc.screenshot)
    requireString(doc.screenshot, '"screenshot" should be a URL to an image for your plugin.');

  if(doc.issues)
    requireString(doc.issues, '"issues" should be a URL to your bug tracking software.');

  if(doc.dependencies)
  {

  }
}
