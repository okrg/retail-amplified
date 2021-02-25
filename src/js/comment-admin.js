var comments = {
  edit: function (id, name, msg) {
    $.ajax({
      method: "POST",
      url: "/include/comment-ajax.php",
      data: {
        req: "edit",
        comment_id: id,
        name : name,
        message : msg
      }
    }).done(function (res) {
      // DO SOMETHING
      if (res=="OK") {} else {}
    });
  },

  delete: function (id) {
    $.ajax({
      method: "POST",
      url: "/include/comment-ajax.php",
      data: {
        req: "del",
        comment_id: id
      }
    }).done(function (res) {
      // DO SOMETHING
      if (res=="OK") {} else {}
    });
  }
};