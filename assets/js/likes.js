$(function() {
  $('.userLikesVideo').show();
  $('.userDoesNotLikeVideo').show();
  $('.noActionYet').show();

  $('.toggle-likes').on('click', function(e) {
    e.preventDefault();
    let $link = $(e.currentTarget);
    $.ajax({
      method: 'POST',
      url: $link.attr('href')
    }).done(function(data) {
      let number_of_likes_str = $('.number-of-likes-' + data.id)
      let number_of_likes = parseInt(number_of_likes_str.html().replace(/\D/g,''))
      let number_of_dislikes_str = $('.number-of-dislikes-' + data.id)
      let number_of_dislikes = parseInt(number_of_dislikes_str.html().replace(/\D/g,''))
      let $video_id_obj = $('.video-id-' + data.id)
      let $likes_video_id_obj = $('.likes-video-id-' + data.id)
      let $dislikes_video_id_obj = $('.dislikes-video-id-' + data.id)
      switch (data.action)
      {
        case 'liked':
          number_of_likes++
          number_of_likes_str.html('(' + number_of_likes + ')')
          $likes_video_id_obj.show();
          $video_id_obj.hide();
          break;
        case 'disliked':
          number_of_dislikes++
          number_of_dislikes_str.html('(' + number_of_dislikes + ')')
          $dislikes_video_id_obj.show();
          $video_id_obj.hide();
          break;
        case 'undo liked':
          number_of_likes--;
          number_of_likes_str.html('(' + number_of_dislikes + ')');
          $video_id_obj.show();
          $likes_video_id_obj.hide();
          $dislikes_video_id_obj.hide();
          break;
        case 'undo disliked':
          number_of_dislikes--;
          number_of_dislikes_str.html('(' + number_of_dislikes + ')');
          $video_id_obj.show();
          $likes_video_id_obj.hide();
          $dislikes_video_id_obj.hide();
          break;
      }
    })
  })
})
