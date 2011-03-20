$(function() {
  var getTagsButton = $('#getTags');

  $(getTagsButton).click(function() {
    var user = $(getTagsButton).parent().children('input[name=username]').val(),
        repo = $(getTagsButton).parent().children('input[name=repo]').val();

    $.ajax({
      url: 'http://github.com/api/v2/json/repos/show/'+user+'/'+repo+'/tags',
      dataType: 'jsonp',
      success: function(data) {
        for(var name in data.tags)
          $('#tags').append('<li><input type="checkbox" name="hash[]" value="'+data.tags[name]+'"/> '+name);
      }
    });

    return false;
  });
});
