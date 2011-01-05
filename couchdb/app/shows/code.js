function(doc, req)
{
  if(!doc)
  {
    var resourceFields = req.path.pop().split('-'),
        user = req.path.pop();

    // Determine the file extension (for .min, etc.)
    var fileExt = resourceFields.pop().split('.');
    resourceFields.push(fileExt.shift());
    fileExt = fileExt.join('.');

    /* 
     * Reverse iter over the fields, figuring out the version. Version numbers
     * must start with an int.
     */
    var version;

    for(var field = resourceFields.pop(); 
        resourceFields.length > 0; 
        field = resourceFields.pop()
    )
    {
      version = (version) ? field+'-'+version : field;
      
      if(version.match(/^[0-9]/))
        break;
    }

    //TODO deal with getting latest if there is no version
      
    return {
      'code': '307',
      'headers': {
        'Location': '/'+user+'/'+resourceFields.join('-')+'-'+version+'.'+fileExt
      }
    };
  }

  // We never expect a valid doc id.
  return { 'code': '500' }; 
}
