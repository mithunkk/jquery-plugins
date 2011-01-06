function(doc)
{
  if(doc.docType == "plugin")
    emit([doc.pluginType, doc.name], doc); 
}
