<div class="text-center my-2">
  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#new-comment-modal"><i class="fa fa-comment"></i> New Comment</button>  
</div>

<div id="comments"></div>

<script type="text/javascript">
  $(document).ready(function () {
    comments.load();
  });
</script>