function(doc)
{
  if(doc.docType == "plugin")
  {
    var ret = new Document();

    // Required fields
    ret.add(doc.name);
    ret.add(doc.label);
    ret.add(doc.pluginType, {field: "pluginType"});

    // Optional fields
    if(doc.copyright)
      ret.add(doc.copyright);

    if(doc.authors)
      for(var i in doc.authors)
        for(var j in doc.authors[i])
          ret.add(doc.authors[i][j]);

    return ret; 
  }
}
