function(doc, req)
{
  if(!doc)
  {
    // Break out the user name and resource file name
    var resourceFields = req.path.pop().split('-'),
        user = req.path.pop();

    // Determine the file extension (for .min, etc.)
    var fileExtFields = resourceFields.pop().split('.');

    var fileExt = fileExtFields.pop(); //.js
    if(fileExtFields[fileExtFields.length - 1] == "min")
      fileExt = fileExtFields.pop() + fileExt;

    resourceFields.push(fileExtFields.join('.'));

    /* 
     * Reverse iter over the fields, figuring out the version. Version numbers
     * must start with an int.
     *
     * We're greedy and will eat up the entire file name if given the chance
     * (ex., no version is specified).
     */
    var version = '';

    for(var field; resourceFields.length > 0 && !version.match(/^[0-9]/);)
    {
      field = resourceFields.pop();
      version = (version) ? field+'-'+version : field;
    }

    // Determine plugin name - steal from version if it was too greedy
    var pluginName;

    if(resourceFields.length <= 0)
    {
      pluginName = version;
      version = null;
    }
    else
      pluginName = resourceFields.join('-');

    return {
      'code': '307',
      'headers': {
        'Location': '/'+user+'/jQuery.'+pluginName+'-'+version+'.'+fileExt
      }
    };
  }

  // We never expect a valid doc id.
  return { 'code': '500' }; 
}
