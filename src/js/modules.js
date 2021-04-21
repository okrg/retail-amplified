var comments = {
  load: function () {
    $.ajax({
      method: "POST",
      url: "/include/comment-ajax.php",
      data: {
        req: "show",
        project_id: $('#comment-project_id').val()
      }
    })
    .done(function (res) {
      $('#comments').html(res);
    });
  },

  add: function (el) {

    $('#comment-error-message').text('').fadeOut();
    var hasError = false;

    var comment_type = $('input[name="comment-type"]:checked').val();
    var message = $('#comment-message').val()
    
    if ( typeof(comment_type) === 'undefined' ) {    
      $('#comment-error-message').fadeIn();
      $('#comment-error-message').append('<div>You must select a comment type</div>');
      hasError = true;
    }
    
    if ( message == '' ) {      
      $('#comment-error-message').fadeIn();
      $('#comment-error-message').append('<div>You must enter a comment</div>');
      hasError = true;
    }

    if(hasError)
      return false;


    var reform = $(el),    
      data = {
        req: "add",
        project_id: $('#comment-project_id').val(),
        author_id: $('#comment-author_id').val(),
        type: $('input[name="comment-type"]:checked').val(),
        message: $('#comment-message').val()
      };
    
    $.ajax({
      method: "POST",
      url: "/include/comment-ajax.php",
      data: data
    })
    .done(function (res) {      
      if (res=="OK") { 
        comments.load();
        $('#new-comment-modal').modal('hide');        
        $('label.active').removeClass('active');
        $('input[name="comment-type"]').prop('checked', false);
        $('#comment-message').val('');
      } else { 
        console.log(res);
        //alert("ERROR"); 
      }
    });
    return false;
  }
};